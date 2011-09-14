#!/bin/bash
# Usage
# ./detar.sh 1 pour tout extraire
# ./detar.sh pour extraire uniquement les nouveaux documents

DATA=../data/dila/temp/
ARCHIVE=../data/dila/archive/
FTP=../../ftp/dila/
CONVERTED=../data/dila/converted/
POOL=../data/pool/
TOPROCESS=log/to_detar_update.txt
TOERASE=log/to_erase.txt
LOCK=/tmp/import.sh.lock

if test "$1" ; then
  echo "Extraire TOUS les documents dila ? : veuillez confirmer (y/n)"
  read AA;
  TOPROCESS=log/to_detar_all.txt
  find $FTP -name "*.tar.gz" | xargs stat -c "%Y#%n" > $TOPROCESS
  php sort.php
fi

START=$(date '+%d-%m-%Y-%H:%M:%S') ;

for fichier in $(cat $TOPROCESS);
  do
  echo "Décompression de $fichier" ;
  tar -zxvf "$FTP/$fichier" -C "$DATA" ;
  echo "Conversion des fichiers de $fichier" ;
  ./extract.sh ;
  echo "Log les fichiers à supprimer de $fichier" ;
  find $DATA -name "*.dat" | xargs cat >> $TOERASE ;
  echo "Archivage des fichiers originaux de $fichier" ;
  mv $DATA* $ARCHIVE ;
done

# Vérif indexation en cours
if [ -e $LOCK ]
then
  if ! ps --pid $(cat $LOCK) > /dev/null ; then
    if [ -e $POOL/France ] ; then
      rm -r $POOL/France
    fi
    mv $CONVERTED* $POOL
    echo "Les fichiers convertis ont été placés dans le pool, le lock a été supprimé" ;
    rm lock
  else
    echo "Import tiers en cours : les fichiers convertis restent dans $CONVERTED" ;
  fi
else
  if [ -e $POOL/France ] ; then
    rm -r $POOL/France
  fi
  echo "Les fichiers convertis ont été placés dans le pool" ;
  mv $CONVERTED* $POOL
fi

END=$(date '+%d-%m-%Y-%H:%M:%S') ;

echo "Début : $START , Fin : $END" ;
