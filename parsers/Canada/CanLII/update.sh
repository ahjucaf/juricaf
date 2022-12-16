#!/bin/bash

mkdir -p json xml

php pages_downloader.php | while read json_name json_content source; do
    xmlfile=$(echo $json_name | sed 's/json/xml/g')
    php parser_jsonandhtmltoxml.php $json_name $json_content $source > xml
done