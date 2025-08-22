#!/bin/bash

cd $(dirname $0)
. ../config/config.inc

mkdir -p html xml
mkdir -p $POOL_DIR"/Belgique"

if [ $# -eq 0 ]; then
    date=$(curl -s "https://juricaf.org/recherche/+/facet_pays_juridiction%3ABelgique_%7C_Cour_de_cassation%2Cfacet_pays%3ABelgique?format=json&tri=DESC" | jq .docs[0].date_arret | sed 's/"//g' | sed 's/T.*//')
else
    date="${1}-12-31"
fi

bash pages_downloader_europa.sh "$date" > /tmp/juportal.$$.args
bash pages_downloader_juportal_rss.sh >> /tmp/juportal.$$.args

sort -u /tmp/juportal.$$.args | while read htmlfile source; do
    xmlfile=$(echo $htmlfile | sed 's/html/xml/g')

    # Si le xml a déja été généré on passe
    if [ -f $xmlfile ]; then
        echo "$xmlfile existe."
    else
        echo "Création du xml"
        php parser_htmltoxml.php $htmlfile $source > $xmlfile
        xmlsize=$(wc -c <"$xmlfile")

        # si le xml n'est pas vide (juportal.be peut renvoyer des html avec pas de données dedans => xml généré plus haut est vide)
        if [ $xmlsize -gt 0 ]; then
            echo "xml $xmlfile -> pool $POOL_DIR/Belgique"
            cp $xmlfile $POOL_DIR"/Belgique"
        fi
    fi
done

rm /tmp/juportal.$$.args
