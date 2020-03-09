#!/bin/bash

xml=$1

. config/config.inc

txt=$(echo $xml | sed 's/\.xml/_texte.txt/')
date=$(cat $xml | sed 's/.*<dec_date>//' | sed 's/<.*//' | sed 's/T.*//')
id=$(cat $xml   | sed 's/.*<dec_justel>//' | sed 's/<.*//')
role=$(cat $xml   | sed 's/.*<dec_role>//' | sed 's/<.*//')

if test -f $txt ; then
    res=$POOL"/"$id".xml"
	php ccBelgique_xmlgenerator.php $role $date "http://jure.juridat.just.fgov.be/pdfapp/download_blob?idpdf="$id < $txt > $res
	if ! test -s $res ; then
		rm -f $res
	fi
fi
