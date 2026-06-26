#!/bin/bash

source ../../project/bin/config.inc

decisionid=$1

curl -s "https://juricaf.org/recherche/id_source:"$decisionid"?format=json" | jq .docs[0].id | sed 's/"//g' | while read id ; do
	echo -n "deleting "$decisionid" "$id" : "
	curl -s "http://"$COUCHHOST":"$COUCHPORT"/"$COUCHBASE"/"$id > /tmp/$id.json
	sed -i 's/"type":"arret"/"type":"arret_deleted"/' /tmp/$id.json
	curl -s -X PUT -d @/tmp/$id.json "http://"$COUCHHOST":"$COUCHPORT"/"$COUCHBASE"/"$id
	rm /tmp/$id.json
done
