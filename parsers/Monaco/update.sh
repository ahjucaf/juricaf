#!/bin/bash

cd $(dirname $0)
. ../config/config.inc

mkdir -p $POOL_DIR"/Monaco" html

bash download_new.sh | while read filename ; do
	perl parse_one.pl < "html/"$filename > $POOL_DIR"/Monaco/"$filename
done
