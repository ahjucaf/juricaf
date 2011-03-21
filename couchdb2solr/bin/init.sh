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

cat <<EOF > $TMPFILE
{
  "_id":"_design/errors",
  "language": "javascript",
  "views":
  {
    "pas_de_texte_arret": {
      "map": "function(doc) { if (doc.type == 'arret' && !doc.texte_arret.length)  emit(doc._id, 1); }"
    }
    "pas_de_texte_arret": {
      "map": "function(doc) { if (doc.type == 'arret' && doc.num_arret.length > 10)  emit(doc._id, 1); }"
    }
  }
}
EOF

curl -X PUT -d "@$TMPFILE" $DB/_design/errors

