#!/bin/bash

cd $(dirname $0)

source ../config/config.inc

bash download_new.sh | while read html ; do
	IDDEC=$(echo $html | sed 's/html.//' | sed 's/\.html//')
	bash parser.sh $html > "xml/canada_"$IDDEC".xml"
	mv "xml/canada_"$IDDEC".xml" $POOL_DIR"/canada_"$IDDEC".xml"
done
