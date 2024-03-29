#!/bin/bash

cd $(dirname $0)
. ../../config/config.inc

mkdir -p json xml $POOL_DIR"/Canada/CSC"

php pages_downloader.php | while read json_meta json_content ; do
    xmlfile=$(echo $json_meta | sed 's/json/xml/g'| sed 's/-meta//g')
    php parser_jsonstoxml.php $json_meta $json_content > $xmlfile
    if ! test -s $xmlfile;then
      rm $xmlfile;
    else
      cp $xmlfile $POOL_DIR"/Canada/CSC"
    fi
done
