#!/bin/bash

mkdir -p html xml

php pages_downloader.php $(date '+%Y') | while read htmlfile source; do
    xmlfile=$(echo $htmlfile | sed 's/html/xml/g')
    php parser_htmltoxml.php $htmlfile $source > $xmlfile;
done