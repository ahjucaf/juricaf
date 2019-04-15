#!/bin/bash
find docs/justice.public.lu -name '*pdf' | while read pdf ; do
    echo -n $pdf";"
    bash parse_pdf.sh $pdf
    echo
done | grep pdf
rm -rf .tmp
