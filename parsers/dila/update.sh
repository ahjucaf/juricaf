#!/bin/bash

cd $(dirname $0)

. ../config/config.inc

date=$(date --iso=s)
mkdir -p xml/$date/

bash download_new.sh | while read file ; do
	if echo $file | grep 'xml$' > /dev/null ; then
		php convert.php $file > xml/$date/$(basename $file)
		if test -s xml/$date/$(basename $file) ;then
			echo '{"creation":"'$(basename $file)' CREATED"}'
		else
			rm xml/$date/$(basename $file)
		fi
	elif echo $file | grep 'dat$' > /dev/null ; then
		php delete_arrets.sh $file
	fi
done

mkdir -p $POOL_DIR/dila
mv xml/$date $POOL_DIR/dila/
