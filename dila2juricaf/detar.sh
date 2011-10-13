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
GLOBALDELETELOG=log/suppression/global.log
FULLIMPORT=log/full_import.lock
TORESUME=log/to_resume.txt

if [ -e $LOCK ]
then
  if ! ps --pid $(cat $LOCK) > /dev/null ; then
    echo "L'import est locké mais n'est pas en cours" ;
  else
    echo "Import tiers en cours" ;
  fi
  echo "Extraction annulée : aucune opération effectuée" ;
  exit 1
fi

if test "$1" ; then
  echo "Extraire TOUS les documents dila ? (les crons doivent être désactivés et les bdd vidées) : veuillez confirmer (y/n)"
  read AA;
  TOPROCESS=log/to_detar_all.txt
  > $GLOBALDELETELOG
  > $FULLIMPORT
  find $FTP/ -name "*.tar.gz" | xargs stat -c "%Y#%n" > $TOPROCESS
  php sort.php
fi

if [ -e $TORESUME ]
then
  echo "Le fichier de reprise de la précédante opération est utilisé"
  TOPROCESS=$TORESUME ;
fi

for fichier in $(cat $TOPROCESS);
  do
  echo "Décompression de $fichier" ;
  tar -zxvf "$FTP/$fichier" -C "$DATA" ;

  echo "Conversion des fichiers de $fichier" ;
  ./extract.sh ;

  if find $DATA/ -name "*.dat" > /dev/null; then
    echo "Sauvegarde des ordres de suppression de $fichier" ;
    NOTGZ=$(echo $fichier | sed "s/\(.*\)\(ahjucaf_.*\).tar.gz/\2/gi");
    find $DATA/ -name "*.dat" | xargs cat >> $DIRLOGSUPP/$NOTGZ.dat ;
    if test -s $DIRLOGSUPP/$NOTGZ.dat ; then
      echo "Application des ordres de suppression de $fichier" ;
      for fichier_suppr in $(cat $DIRLOGSUPP/$NOTGZ.dat);
      do
        if [ -e $ARCHIVE/$fichier_suppr.xml ] ; then
          DOC_ID=$(php dila2juricaf.php $ARCHIVE/$fichier_suppr.xml 1)
          # évolution : interroger solr directement
          if echo $DOC_ID | grep " "  > /dev/null; then
            echo "$NOTGZ Erreur : L'identifiant de $ARCHIVE/$fichier_suppr.xml n'a pas pu être déterminé correctement ($DOC_ID)" >> $GLOBALDELETELOG
          else
            if [ ! -d $DELETED/$NOTGZ ] ; then
              mkdir $DELETED/$NOTGZ ;
            fi
            cp -f $ARCHIVE/$fichier_suppr.xml $DELETED/$NOTGZ/
            rm $ARCHIVE/$fichier_suppr.xml
            echo "$DOC_ID a été sauvegardé dans $DELETED/$NOTGZ et supprimé des archives Dila avec succès ($ARCHIVE/$fichier_suppr.xml)" >> $GLOBALDELETELOG
            if echo $(curl -s GET $COUCHDBURL/$DOC_ID | grep "not_found") > /dev/null; then
              echo "$NOTGZ Erreur : $DOC_ID existait dans les archives mais non trouvé dans couchdb ($ARCHIVE/$fichier_suppr.xml)" >> $GLOBALDELETELOG
            else
              if echo $(curl -s DELETE $COUCHDBURL/$DOC_ID?rev=$(curl --stderr /dev/null $COUCHDBURL/$DOC_ID | sed 's/.*_rev":"//' | sed 's/",".*//' 2> /dev/null) | grep -v $DOC_ID) > /dev/null; then
                echo "$NOTGZ Erreur : $DOC_ID existe dans couchdb mais n'a pas pu en être supprimé" >> $GLOBALDELETELOG
              else
                echo "$DOC_ID a été supprimé de couchdb avec succès" >> $GLOBALDELETELOG
              fi
            fi
          fi
        else
          echo "$NOTGZ Erreur : $ARCHIVE/$fichier_suppr.xml n'existe pas" >> $GLOBALDELETELOG
        fi
      done
      if cat $GLOBALDELETELOG | grep "$NOTGZ Erreur" > /dev/null ; then
        echo "Certains ordres de suppression requièrent des vérifications manuelles :"
        cat $GLOBALDELETELOG | grep "$NOTGZ Erreur" ;
      else
        echo "Avec succès"
      fi
    else
      echo "Le fichier d'ordres de suppression de $fichier est vide"
    fi
  else
    echo "Aucun ordre de suppression dans $fichier"
  fi

  echo "Archivage des fichiers originaux de $fichier" ;
  EXTRACTEDDIR=$(echo $fichier | sed "s/\(.*\)ahjucaf_\(.*\).tar.gz/\2/g");
  if [ -e $DATA/$EXTRACTEDDIR ]
    then
    cp -R -f $DATA/$EXTRACTEDDIR/* $ARCHIVE/
    rm -R $DATA/*
  else
    cp -R -f $DATA/* $ARCHIVE/
    rm -R $DATA/*
  fi

  # Vérif indexation en cours
  if [ -e $LOCK ]
  then
    if ! ps --pid $(cat $LOCK) > /dev/null ; then
      echo "Erreur : L'import est locké mais ne semble pas être en cours" ;
    else
      echo "Erreur : Import tiers en cours" ;
    fi
    echo "Les fichiers de $fichier sont restés dans $CONVERTED" ;
    echo "Tentative de création d'un fichier de reprise" ;
    RESUME=$(echo $fichier | sed 's:\/:\\/:g' | sed 's:\.:\\.:g') ;
    declare -i NUM_LIGNE=$(cat $TOPROCESS | grep -n "$fichier" | sed "s:\:$RESUME::") ;
    echo "Si la prochaine ligne est $fichier : le processus reprendra automatiquement au prochain lancement"
    if (($NUM_LIGNE != 1)) && let $NUM_LIGNE 2>/dev/null ;
      then
        declare -i AV_DER_LIGNE=$NUM_LIGNE-1 ;
        cat $TOPROCESS | sed "1,$AV_DER_LIGNE d" > $TORESUME ;
        cat $TORESUME ;
      else
        cat $TOPROCESS
    fi
    if [ -e $DIRLOGSUPP/$NOTGZ.dat ]
      then
      echo "Sauvegarde du log de suppression en $DIRLOGSUPP/$NOTGZ.$START.bak à comparer à $DIRLOGSUPP/$NOTGZ.dat lors de la reprise"
      cp $GLOBALDELETELOG $GLOBALDELETELOG.$START.bak
    fi
    echo "Extraction stoppée suite à l'erreur précédante" ;
    exit 1
  else
    cp -R -f $CONVERTED/* $POOL/
    rm -R $CONVERTED/*
    echo "Les fichiers convertis ont été placés dans le pool" ;
    if [ -e $FULLIMPORT ] ; then
      echo "Lancement de l'import des fichiers de $fichier" ;
      ../juricaf2solr/juricaf2couchdb/import.sh ;
    fi
  fi
done

cp -f $GLOBALDELETELOG $GLOBALDELETELOG.$START.log

if [ -e $FULLIMPORT ] ; then
  rm $FULLIMPORT;
fi

END=$(date '+%d-%m-%Y-%H:%M:%S') ;

echo "Début : $START , Fin : $END" ;
