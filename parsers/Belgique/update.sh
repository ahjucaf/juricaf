#!/bin/bash

cd $(dirname $0)
. config/config.inc

mkdir -p html xml
mkdir -p $POOL_DIR

if [ $# -eq 0 ]; then
    annee=$(date '+%Y')
else
    annee=$1
fi

php pages_downloader.php $annee | while read htmlfile source; do
    # On ne prend que les arrets de la cour de cass et constitutionnelle 
    if [[ $htmlfile =~ .*ECLI:BE:(?:CASS|GHCC):$annee:ARR.* ]]; then
        xmlfile=$(echo $htmlfile | sed 's/html/xml/g')

        # Si le xml a déja été généré on passe
        if [ -f $xmlfile ]; then
            echo "$xmlfile existe."
        else
            echo "Création du xml"
            php parser_htmltoxml.php $htmlfile $source > $xmlfile
            xmlsize=$(wc -c <"$xmlfile")

            # si le xml n'est pas vide (juportal.be peut renvoyer des html avec pas de données dedans => xml généré plus ahut est vide)
            if [ $xmlsize -g 0 ]; then
                echo "xml -> pool"
                cp $xmlfile $POOL_DIR/
            fi
        fi
    fi
done