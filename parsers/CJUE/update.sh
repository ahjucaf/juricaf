#!/bin/bash

mkdir -p xml
mkdir -p data

ls data|grep ".xml"|sed "s/.xml//"|sort|uniq | while read id ; do
  if [ -e data/$id.xml -a -e data/$id.txt ]
  then
    php parse_arret.php data/$id.xml data/$id.txt > xml/$id.xml
    if ! test -s xml/$id.xml ; then
        rm xml/$id.xml
    fi
  fi
done
