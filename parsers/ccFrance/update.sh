#!/bin/bash

. config/config.sh

if [ -d "/tmp/ccFrance" ];then
  rm -r /tmp/ccFrance
fi

ANNEE=$(date +%Y)

mkdir -p /tmp/ccFrance/xmls
mkdir -p /tmp/ccFrance/jsons


curl "$API_URL/export?batch=0&resolve_references=true" -H "KeyId: $API_TOKEN" | jq -rc '.results[]' > /tmp/ccFrance/jsons/batch.json

cat /tmp/ccFrance/jsons/batch.json | while read -r json; do
  _id=$(echo "$json" | cut -d'"' -f4)
  touch /tmp/ccFrance/jsons/$_id.json
  echo "$json" > /tmp/ccFrance/jsons/$_id.json

  php json2xml.php /tmp/ccFrance/jsons/$_id.json > /tmp/ccFrance/xmls/$_id.xml

  cp /tmp/ccFrance/xmls/* $POOL_DIR/"$ANNEE"/courdecassation/

done
