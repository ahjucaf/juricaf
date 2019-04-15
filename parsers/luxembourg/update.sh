#!/bin/bash

cd $(dirname $0)
. config.inc
mkdir -p $POOL_DIR

bash pdf_downloader.sh | while read pdf ; do
    bash parse_pdf.sh $pdf
done | awk -F ';' '{print $7}' | while read xml ; do
    if test -f $xml ; then
        cp $xml $POOL_DIR
    fi
done
