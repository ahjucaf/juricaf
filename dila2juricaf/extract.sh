#!/bin/bash
find ../data/dila/temp/ -name '*.xml' | while read fichier ;
do
  sed -f ansi2html < $fichier > temp.xml ;
  cat temp.xml > $fichier;
  php dila2juricaf.php $fichier;
done
