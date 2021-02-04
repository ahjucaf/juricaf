#!/bin/bash

cd $(dirname $0)
. config.inc
mkdir -p $POOL_DIR

bash retrieve_newdocs.sh | while read id ; do
    php parse_arret.php $id > $POOL_DIR"/"$id".xml"
    if ! test -s $POOL_DIR"/"$id".xml" ; then
        rm $POOL_DIR"/"$id".xml"
    fi
done
