#!/bin/bash

cd $(dirname $0)
. ../config/config.inc
. ../../project/bin/config.inc

cat $1 | while read xmlpath; do
	xmlid=$(basename $xmlpath)
	curl -s $PROJETURL/recherche/$xmlid"/facet_pays%3AFrance?format=json" | jq '.docs[].id' | sed 's/"//g' | while read docid ; do
		rev=$(curl -s http://$COUCHHOST:$COUCHPORT/$COUCHBASE/$docid | jq ._rev | sed 's/"//g' | grep -v null)
		if test "$rev" && test "$docid" ; then
			curl -s -X DELETE http://$COUCHHOST:$COUCHPORT/$COUCHBASE/$docid?rev=$rev | sed 's|:true,|:"deletion query : '$xmlpath' - '$1'",|'
		else
			echo "error with deletion query $xmlpath $docid ($1)"
		fi
	done
done
