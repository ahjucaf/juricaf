#!/bin/bash

cd $(dirname $0)

. ../config/config.inc

mkdir -p xml data
mkdir -p $POOL"/CJUE"

bash download_lasts.sh | while read id ; do
  if [ -e data/$id.xml -a -e data/$id.txt ]
  then
    php parse_arret.php data/$id.xml data/$id.txt > xml/$id.xml
    if ! test -s xml/$id.xml ; then
        rm xml/$id.xml
    else
        cp xml/$id.xml $POOL"/CJUE/"
    fi
  fi
done
