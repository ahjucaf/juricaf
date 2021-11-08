#!/bin/bash

. config/config.sh

curl "$API_URL/export?batch=0&resolve_references=true" -H "KeyId: $API_TOKEN" | jq -rc '.results[]' > /tmp/batch.json

cat /tmp/batch.json | while read json; do
  _id=$(echo "$json" | cut -d'"' -f4)
  echo $_id
  touch /tmp/$_id.json
  echo "$json" > /tmp/$_id.json
done
