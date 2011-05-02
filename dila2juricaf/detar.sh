#!/bin/bash
DILA=/home/ahjucaf/project/data/dila
cd /home/ahjucaf/ftp/dila
find -name '*.tar.gz' > to_detar.txt
for fichier in $(cat to_detar.txt); do tar -zxvf "$fichier" -C $DILA ; done
#rm to_detar.txt
