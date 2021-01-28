#!/bin/bash

pdf=$1
xml=$(echo $pdf | sed 's/pdf$/xml/')
url=$2

mkdir -p .tmp

rm -f .tmp/arrets.html
pdftohtml $pdf .tmp/arret > /dev/null 2>&1
if test -s .tmp/arrets.html ; then
cat .tmp/arrets.html | php parser_pdftohtml.php $xml $url
else
echo "problème avec le pdf de $url";
fi
