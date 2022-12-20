#!/bin/bash

mkdir -p html xml

if [ $# -eq 0 ]; then
    annee=$(date '+%Y')
else
    annee=$1
fi

php pages_downloader.php $annee | while read htmlfile source; do
    # On ne prend que les arrets de la cour de cass
    if [[ $htmlfile =~ .*ECLI:BE:CASS:$annee:ARR.* ]]; then
        xmlfile=$(echo $htmlfile | sed 's/html/xml/g')
        [ -f $xmlfile ] && echo "$xmlfile existe." || php parser_htmltoxml.php $htmlfile $source > $xmlfile;
        xmlsize=$(wc -c <"$xmlfile")
        if [ $xmlsize -le 0 ]; then
            # si vide il y a eu une erreur, on supprime le xml
            rm $xmlfile
        fi
    fi
done