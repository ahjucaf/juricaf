#!/bin/bash
# Usage
# ./detar.sh 1 pour tout extraire
# ./detar.sh pour extraire uniquement les nouveaux documents

START=$(date '+%d-%m-%Y-%H:%M:%S') ;
TOPROCESS=$1

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
LOCK=/tmp/dila2juricaf.lock

if test -e $LOCK && ps --pid $(cat $LOCK) > /dev/null ; then
    echo -e "\n!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!";
    echo "Erreur : juricaf2couchdb est vérouillé et est en cours de fonctionnement : L'extraction a été annulée." ;
    echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!";
    exit 1
fi
echo $$ > $LOCK


sort -u $TOPROCESS | while read fichier; do
  echo -e "\n=====================================================";
  echo "Décompression de $fichier" ;
  echo "=====================================================";
  tar -zxvf "$FTP/$fichier" -C "$DATA" ;

  echo -e "\n=====================================================";
  echo "Conversion des fichiers" ;
  echo "=====================================================";
  find $DATA/ -name '*.xml' | while read fichier ; do
        sed -f ansi2html < $fichier >  $fichier".tmp" ;
        mv $fichier".tmp" $fichier;
        php dila2juricaf.php $fichier $CONVERTED;
  done
  find $DATA/ -name "*.dat" | while read deleteorder; do
    if ! test "$DELETEDJSONDIR"; then
        DELETEDJSONDIR=$DELETED"/"$(date +%Y-%m-%d);
        mkdir -p $DELETEDJSONDIR
        echo -e "\n=====================================================";
        echo "Traitement des ordres de suppression"
        echo "=====================================================";
        echo "* Gestion des ordres de suppression :" ;
    fi
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

  echo -e "\n=====================================================";
  echo "Archivage des fichiers originaux" ;
  echo "=====================================================";
  rsync -a $DATA/* $ARCHIVE/

  echo -e "\n=====================================================";
  echo "Transmission des fichiers convertis à juricaf2couchdb" ;
  echo "=====================================================";

  # Vérifie l'état de juricaf2couchdb avant de transmettre les fichiers, diffère la transmission ou génère un fichier de reprise suivant la situation
  rsync -a $CONVERTED/France $POOL/
done

END=$(date '+%d-%m-%Y-%H:%M:%S') ;

echo -e "\n=====================================================";
echo "Début : $START , Fin : $END" ;
rm $LOCK