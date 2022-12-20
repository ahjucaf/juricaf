#!/bin/bash

mkdir -p json xml
. config/config.inc
php pages_downloader.php | while read json_meta json_content ; do
    xmlfile=$(echo $json_meta | sed 's/json/xml/g'| sed 's/-meta//g')
    if [ ! -f "$xmlfile" ]; then
      if ! php parser_jsonstoxml.php $json_meta $json_content > $xmlfile
      then
        rm $xmlfile;
      else
        cp $xmlfile $POOL_DIR/
      fi
    fi
done
