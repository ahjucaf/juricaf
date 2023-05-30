#!/bin/bash

cd $(dirname $0)
. ../config/config.inc

mkdir -p $POOL_DIR"/cedh"

bash retrieve_newdocs.sh | while read id ; do
    php parse_arret.php $id > $POOL_DIR"/cedh/"$id".xml"
    if ! test -s $POOL_DIR"/cedh/"$id".xml" ; then
        rm $POOL_DIR"/cedh/"$id".xml"
    fi
done
