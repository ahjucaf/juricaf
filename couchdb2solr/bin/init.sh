#!/bin/bash

TMPFILE=/tmp/$$.json
DB=http://localhost:5984/ahjucaf


curl -X DELETE $DB
curl -X PUT $DB

cat <<EOF > $TMPFILE
{
  "_id":"_design/stats",
  "language": "javascript",
  "views":
  {
    "pays_juridiction": {
      "map": "function(doc) { if (doc.type == 'arret' && doc.pays && doc.juridiction)  emit([doc.pays,doc.juridiction], 1);}",
      "reduce": "function(keys, values) { return sum(values) }"
    }
  }
}
EOF

curl -X PUT -d "@$TMPFILE" $DB/_design/stats 
