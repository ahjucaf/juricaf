#!/bin/bash

lock=$(dirname $0)"/../couchdb2solr/lock/indexer.lock"
if test -f $lock; then
	exit 1;
fi
echo $$ > $lock

/usr/bin/php -q $(dirname $0)"/../couchdb2solr/couchdb2solr.php"

rm $lock
