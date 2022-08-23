#!/bin/bash
DATADIR=$1
find $DATADIR -name '*.xml' | while read fichier ;
do
  sed -f ansi2html < $fichier > temp.xml ;
  cat temp.xml > $fichier;
  php dila2juricaf.php $fichier;
done
