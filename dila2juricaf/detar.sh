#!/bin/bash
# Usage
# ./detar.sh 1 pour tout extraire
# ./detar.sh pour extraire uniquement les nouveaux documents

DATA=../data/dila/temp/
ARCHIVE=../data/dila/archive/
FTP=../../ftp/dila/
TOPROCESS=log/to_detar_update.txt
TOERASE=log/to_erase.txt
# lock à revoir

if test "$1" ; then
  echo "Extraire TOUS les documents dila ? : veuillez confirmer (y/n)"
  read AA;
  TOPROCESS=log/to_detar_all.txt
  find $FTP -name "*.tar.gz" | xargs stat -c "%Y#%n" > $TOPROCESS
  php sort.php
fi

echo "Début : " ;
date '+%d-%m-%Y-%H:%M:%S' ;

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

echo "Fin : " ;
date '+%d-%m-%Y-%H:%M:%S' ;
