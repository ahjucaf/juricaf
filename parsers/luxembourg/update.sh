#!/bin/bash

cd $(dirname $0)
. config.inc
mkdir -p $POOL_DIR

bash pdf_downloader.sh | while read pdf ; do
    echo -n $pdf";";
    bash parse_pdf.sh $pdf $(cat $pdf".url")
    echo
done | awk -F ';' '{print $8}' | grep 'xml$' | while read xml ; do
    if test -f $xml ; then
        cp $xml $POOL_DIR
    fi
done
