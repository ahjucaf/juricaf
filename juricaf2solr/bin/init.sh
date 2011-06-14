#!/bin/bash

TMPFILE=/tmp/$$.json

if ! test -e ../conf/juricaf.conf; then
    echo ../conf/juricaf.conf does not exist
    exit 1;
fi
. ../conf/juricaf.conf

if echo $0 | grep '/' > /dev/null; then
	cd $(echo $0 | sed 's/[^\/]*$//')
fi

if test "$1" ; then
echo "DELETING: please confirm "
read AA;
curl -X DELETE $COUCHDBURL
curl -X PUT $COUCHDBURL
cd ../couchdb2solr/
php deletesolr.php
cd -
fi

echo "Creating map/reduce stats";

curl -X DELETE $COUCHDBURL/_design/stats?rev=$(curl --stderr /dev/null $COUCHDBURL/_design/stats | sed 's/.*_rev":"//' | sed 's/",".*//' 2> /dev/null)

cat <<EOF > $TMPFILE
{
  "_id":"_design/stats",
  "language": "javascript",
  "views":
  {
    "pays_juridiction_date": {
      "map": "function(doc) { if (doc.type == 'arret' && doc.pays && doc.juridiction) { if (doc.date_arret.match(/-.*/)) date=doc.date_arret.replace(/-.*/, ''); emit([doc.pays,doc.juridiction,date], 1);}}",
      "reduce": "function(keys, values) { return sum(values) }"
    },
    "attributs": {
      "map": "function(doc) {if (doc.type == 'arret') for (var attr in doc) if (attr != 'unnamed') emit(attr, 1); }",
      "reduce": "function(keys, values) { return sum(values) }"
    }
  }
}
EOF

curl -X PUT -d "@$TMPFILE" $COUCHDBURL/_design/stats

echo "Creating map errors";

cat <<EOF > $TMPFILE
{
  "_id":"_design/errors",
  "language": "javascript",
  "views":
  {
    "errors": {
      "map": "function(doc) { if (doc.type == 'error_arret')  emit([doc.on_error,doc._id], 1); }",
      "reduce": "function(keys, values) { return sum(values) }"
    },
    "errors_date": {
      "map": "function(doc) { if (doc.type == 'error_arret') { year=doc.date_arret.substring(0,4); emit([doc.on_error,year,doc.date_arret], 1); } }",
      "reduce": "function(keys, values) { return sum(values) }"
    },
  }
}
EOF

curl -X DELETE $COUCHDBURL/_design/errors?rev=$(curl --stderr /dev/null $COUCHDBURL/_design/errors | sed 's/.*_rev":"//' | sed 's/",".*//')

curl -X PUT -d "@$TMPFILE" $COUCHDBURL/_design/errors

rm $TMPFILE
