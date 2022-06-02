#!/bin/bash

. $(dirname $0)"/../conf/juricaf.conf"

if ! test  "$COUCHDISTANT" ; then
	echo "ERROR: $0: COUCHDISTANT non configué dans juricaf.conf" >&2
	exit 2;
fi

REV=$(curl -s "http://127.0.0.1:5984/_replicator/REPLICATION_PROD" | sed 's/.*_rev":"//' | sed 's/{*".*//')
if test "$REV" ; then
	curl -s -X DELETE  "http://127.0.0.1:5984/_replicator/REPLICATION_PROD?rev="$REV
fi

curl -s -X PUT -d '{"_id": "REPLICATION_PROD", "source": "'$COUCHDISTANT'", "target": "'$COUCHDBURL'", "continuous": true}' http://127.0.0.1:5984/_replicator/REPLICATION_PROD
