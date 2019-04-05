#!/bin/bash
mkdir -p tmp
find docs/justice.public.lu -name '*pdf' | while read pdf ; do
    pdftohtml $pdf tmp/arret > /dev/null 2>&1
    xmlfile=$(echo $pdf | sed 's/\.pdf/.xml/')
    echo -n $pdf";"
    cat tmp/arrets.html | php parser_pdftohtml.php $xmlfile
    echo
done | grep pdf
rm -rf tmp
