#!/bin/bash

pdf=$1
xml=$(echo $pdf | sed 's/pdf$/xml/')

mkdir -p .tmp

rm -f .tmp/arrets.html
pdftohtml $pdf .tmp/arret > /dev/null 2>&1
cat .tmp/arrets.html | php parser_pdftohtml.php $xml
