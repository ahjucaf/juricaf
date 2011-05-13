#!/bin/bash
# Usage
# ./detar.sh 1 pour tout extraire
# ./detar.sh pour extraire uniquement les nouveaux documents

DATA=../data/dila/
FTP=../../ftp/dila/
TOPROCESS=log/to_detar_update.txt
TOERASE=log/to_erase.txt
LOCK=../juricaf2solr/juricaf2couchdb/lock

0 > $LOCK

if test "$1" ; then
echo "Extraire TOUS les documents dila ? : veuillez confirmer (y/n)"
read AA;
TOPROCESS=log/to_detar_all.txt
find $FTP -name "*.tar.gz" | xargs stat -c "%Y#%n" > $TOPROCESS
php sort.php
fi

for fichier in $(cat $TOPROCESS);
  do
echo "Décompression de $fichier" ;
tar -zxvf "$FTP/$fichier" -C "$DATA" ;
echo "Conversion des fichiers";
./extract.sh ;
done

find $DATA -name "*.dat" | xargs cat > $TOERASE

rm $LOCK
