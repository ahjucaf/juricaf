#!/bin/bash

mkdir -p html xml

if [ $# -eq 0 ]; then
    annee=$(date '+%Y')
else
    annee=$1
fi

php pages_downloader.php $annee | while read htmlfile source; do
    # On ne prend que les arrets de la cour de cass
    if [[ "$htmlfile" =~ ^ECLI:BE:CASS:\d{4}:ARR.* ]]; then
        xmlfile=$(echo $htmlfile | sed 's/html/xml/g')
        [ -f $xmlfile ] && echo "$xmlfile existe." || php parser_htmltoxml.php $htmlfile $source > $xmlfile;
    fi
done