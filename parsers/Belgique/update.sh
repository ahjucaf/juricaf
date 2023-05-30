#!/bin/bash

cd $(dirname $0)
. config/config.inc

mkdir -p html xml
mkdir -p $POOL_DIR

if [ $# -eq 0 ]; then
    date=$(curl -s "https://juricaf.org/recherche/+/facet_pays%3ABelgique?format=json" | jq .docs[0].date_arret | sed 's/"//g' | sed 's/T.*//')
else
    date="${1}-12-31"
fi

php pages_downloader.php "$date" | while read htmlfile source; do
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
            echo "xml -> pool"
            cp $xmlfile $POOL_DIR/
        fi
    fi
done
