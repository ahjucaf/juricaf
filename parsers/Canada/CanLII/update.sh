#!/bin/bash

mkdir -p json html xml

php pages_downloader.php | while read json_name html_name source; do
    xmlfile=$(echo $json_name | sed 's/json/xml/g')
    php parser_jsonstoxml.php $json_name $html_name $source > xml/$xmlfile;  #json html > xml
done