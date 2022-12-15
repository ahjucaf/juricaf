#!/bin/bash

mkdir -p json xml

php pages_downloader.php | while read json_name source; do
    xmlfile=$(echo $json_name | sed 's/json/xml/g')
    php parser_jsonstoxml.php json/$json_name-meta json/$json_name-content $source > xml/$xmlfile;
done