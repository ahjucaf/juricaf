#!/bin/bash
DILA2JURICAF=/home/ahjucaf/project/dila2juricaf
DILA=/home/ahjucaf/project/data/dila
FTP=/home/ahjucaf/ftp/dila
cd $FTP
find -name '*.tar.gz' > $DILA/to_detar.txt
for fichier in $(cat $DILA/to_detar.txt); 
  do
cd $FTP 
echo "Décompression de $fichier" ; tar -zxvf "$fichier" -C "$DILA" ; 
cd $DILA2JURICAF
echo "Conversion des fichiers";
./extract.sh ; 
cd $DILA
rm -r * ;
done
#rm to_detar.txt
