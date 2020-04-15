#!/bin/bash
# Usage
# ./detar.sh 1 pour tout extraire
# ./detar.sh pour extraire uniquement les nouveaux documents

START=$(date '+%d-%m-%Y-%H:%M:%S') ;

# Répertoire de travail
if [ "$(echo $0 | sed 's|[^/]*$||')" != "./" ] ; then
  cd $(echo $0 | sed 's|[^/]*$||');
  echo "Positionnement dans le dossier de travail"
fi

. ../juricaf2solr/conf/juricaf.conf
FTP=../../ftp/dila
DATA=../data/dila/temp
CONVERTED=../data/dila/converted
ARCHIVE=../data/dila/archive
DELETED=../data/dila/deleted
POOL=../data/pool
TOPROCESS=log/to_detar_update.txt
LOCK=/tmp/import.sh.lock
DIRLOGSUPP=log/suppression
FULLIMPORT=log/full_import.lock
TORESUME=log/to_resume.txt
WAIT=60

if [ -e $LOCK ]
then
  if ! ps --pid $(cat $LOCK) > /dev/null ; then
    echo -e "\n!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!";
    echo "Erreur : juricaf2couchdb est vérouillé mais n'est pas en cours de fonctionnement : L'extraction a été annulée." ;
    echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!";
    exit 1
  fi
fi

if test "$1" ; then
  echo "Extraire TOUS les documents dila ? (les crons doivent être désactivés et les bdd vidées) : veuillez confirmer (y/n)"
  read AA;
  TOPROCESS=log/to_detar_all.txt
  > $DIRLOGSUPP/global.log
  > $FULLIMPORT
  find $FTP/ -name "*.tar.gz" | xargs stat -c "%Y#%n" > $TOPROCESS
  php sort.php
fi

if [ -e $TORESUME ]
then
  echo "Ok : Le fichier de reprise de la précédante opération est utilisé"
  TOPROCESS=$TORESUME ;
fi

for fichier in $(sort -u $TOPROCESS);
  do
  echo -e "\n=====================================================";
  echo "Décompression de $fichier" ;
  echo "=====================================================";
  tar -zxvf "$FTP/$fichier" -C "$DATA" ;

  echo -e "\n=====================================================";
  echo "Conversion des fichiers" ;
  echo "=====================================================";
  ./extract.sh ;

  if find $DATA/ -name "*.dat" > /dev/null; then
    echo -e "\n=====================================================";
    echo "Traitement des ordres de suppression"
    echo "=====================================================";
    echo "* Gestion des ordres de suppression :" ;

    DELETEDJSONDIR=$DELETED"/"$(date +%Y-%m-%d);
    mkdir -p $DELETEDJSONDIR
    find $DATA/ -name "*.dat" -exec cat '{}' ';' | sort -u | while read deleteorder ; do
        ID_DILA=$(echo "$deleteorder" | sed 's:.*/::')
        ID_JURICAF=$(php getIdFromSolr.php $ID_DILA | grep -v "Erreur getIdFromSolr")
        # Si le résultat de l'interrogation solr contient un espace c'est une erreur donc on l'affiche
        if echo $ID_JURICAF | grep " "  > /dev/null; then
          echo "Erreur : ID CouchDB non conforme : $ID_JURICAF ("$ID_DILA" <= "$deleteorder")"
        elif test "$ID_JURICAF"; then
          # Le document existe dans solr, on interroge couchdb
          # Si le document n'existe pas dans couchdb
          if curl -s $COUCHDBURL/$ID_JURICAF | grep "not_found" > /dev/null; then
            echo "Erreur : $ID_JURICAF ($ID_DILA) existe dans solr mais pas dans couchdb"
          # S'il existe
          else
          # Suppression
            curl -s $COUCHDBURL/$ID_JURICAF > $DELETEDJSONDIR"/"$ID_JURICAF
            REV=$(cat $DELETEDJSONDIR"/"$ID_JURICAF | sed 's/.*_rev":"//' | sed 's/",".*//')
            if curl -s -X DELETE $COUCHDBURL/$ID_JURICAF?rev=$REV | grep -v $ID_JURICAF > /dev/null; then
              echo "Erreur : $ID_JURICAF ($ID_DILA) existe dans solr et couchdb mais n'a pas pu en être supprimé"
            else
              echo "Info : $ID_JURICAF ($ID_DILA) a été supprimé de couchdb avec succès"
            fi
          fi
        fi
    done
  else
    echo "Ok : Aucun ordre de suppression dans $fichier"
  fi

  echo -e "\n=====================================================";
  echo "Archivage des fichiers originaux" ;
  echo "=====================================================";
  EXTRACTEDDIR=$(echo $fichier | sed "s/\(.*\)ahjucaf_\(.*\).tar.gz/\2/g");
  if [ -e $DATA/$EXTRACTEDDIR ]
    then
    cp -R -f $DATA/$EXTRACTEDDIR/* $ARCHIVE/
    if [ $? -eq 0 ] ; then
      echo "Ok : Archivés";
    fi
    rm -R $DATA/*
  else
    cp -R -f $DATA/* $ARCHIVE/
    if [ $? -eq 0 ] ; then
      echo "Ok : Archivés";
    fi
    rm -R $DATA/*
  fi

  echo -e "\n=====================================================";
  echo "Transmission des fichiers convertis à juricaf2couchdb" ;
  echo "=====================================================";

  # Vérifie l'état de juricaf2couchdb avant de transmettre les fichiers, diffère la transmission ou génère un fichier de reprise suivant la situation
  function transmitFilesToImport()
  {
    if [ -e $LOCK ]
    then
      if ! ps --pid $(cat $LOCK) > /dev/null ; then
        echo -e "\n!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!";
        echo "Erreur : juricaf2couchdb est vérouillé mais n'est pas en cours de fonctionnement" ;
        echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!";
        echo "Les fichiers convertis sont restés dans $CONVERTED" ;
        echo "Tentative de création d'un fichier de reprise" ;
        RESUME=$(echo $fichier | sed 's:\/:\\/:g' | sed 's:\.:\\.:g') ;
        declare -i NUM_LIGNE=$(cat $TOPROCESS | grep -n "$fichier" | sed "s:\:$RESUME::") ;
        echo "Si la ligne suivante est égale à $fichier : le processus pourra reprendre automatiquement au lancement manuel de detar.sh"
        if (($NUM_LIGNE != 1)) && let $NUM_LIGNE 2>/dev/null ;
          then
            declare -i AV_DER_LIGNE=$NUM_LIGNE-1 ;
            cat $TOPROCESS | sed "1,$AV_DER_LIGNE d" > $TORESUME ;
            cat $TORESUME ;
          else
            cat $TOPROCESS
        fi
        exit 1
      else
        echo "Import tiers en cours attente de $WAIT secondes" ;
        sleep $WAIT ;
        transmitFilesToImport ;
      fi
    else
      if [ -e $CONVERTED/France ] ; then
        cp -R -f $CONVERTED/France $POOL/
        rm -R $CONVERTED/France
        echo "Ok : Les fichiers convertis ont été placés dans le pool" ;
        if [ -e $FULLIMPORT ] ; then
          echo "Lancement de l'import des fichiers de $fichier" ;
          ../juricaf2solr/juricaf2couchdb/import.sh ;
        fi
      else
        echo "Ok : La mise à jour ne contenait pas de fichiers à convertir" ;
      fi
    fi
  }
  transmitFilesToImport ;
done

if [ -e $TORESUME ] ; then rm $TORESUME ; fi
if [ -e $FULLIMPORT ] ; then rm $FULLIMPORT ; fi

END=$(date '+%d-%m-%Y-%H:%M:%S') ;

echo -e "\n=====================================================";
echo "Début : $START , Fin : $END" ;
