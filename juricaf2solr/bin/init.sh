#!/bin/bash

TMPFILE=/tmp/$$.json

PREDIR=.
if echo $0 | grep '/' > /dev/null; then PREDIR=$(echo $0 | sed 's/\/[^\/]*$//'); fi
if ! test -e $PREDIR/../conf/juricaf.conf; then
    echo $PREDIR/../conf/juricaf.conf does not exist
    exit 1;
fi
. $PREDIR/../conf/juricaf.conf

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
      "map": "function(doc) { if (doc.type == 'arret') { date=doc.date_arret.substring(0,4); emit([doc.pays,doc.juridiction,date], 1);}}",
      "reduce": "function(keys, values) { return sum(values) }"
    },
    "pays_juridiction_date_all": {
      "map": "function(doc) { date=doc.date_arret.substring(0,4); emit([doc.pays,doc.juridiction,date], 1);}",
      "reduce": "function(keys, values) { return sum(values) }"
    },
    "Attributs": {
      "map": "function(doc) {if (doc.type == 'arret') for (var attr in doc) if (attr != 'unnamed') emit(attr, 1); }",
      "reduce": "function(keys, values) { return sum(values) }"
    },
    "Documents_mis_a_jour": {
      "map": "function(doc) { revision=doc._rev.substring(0,1); date=doc.date_arret.substring(0,4); if (revision !== '1') emit([revision,doc.pays,doc.juridiction,doc.type,date], 1); }",
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
      "map": "function(doc) { if (doc.type == 'error_arret')  emit([doc.pays,doc.on_error,doc._id], 1); }",
      "reduce": "function(keys, values) { return sum(values) }"
    },
    "errors_date": {
      "map": "function(doc) { if (doc.type == 'error_arret') { year=doc.date_arret.substring(0,4); emit([doc.pays,doc.on_error,year,doc.date_arret], 1); } }",
      "reduce": "function(keys, values) { return sum(values) }"
    },
  }
}
EOF

curl -X DELETE $COUCHDBURL/_design/errors?rev=$(curl --stderr /dev/null $COUCHDBURL/_design/errors | sed 's/.*_rev":"//' | sed 's/",".*//')

curl -X PUT -d "@$TMPFILE" $COUCHDBURL/_design/errors

rm $TMPFILE
