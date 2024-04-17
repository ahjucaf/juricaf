#!/bin/bash

cd $(dirname $0)

. ../juricaf2solr/conf/juricaf.conf

mkdir -p static

php statsBase.php

find static -name luke.xml -mtime +7 -delete
if ! test -s static/luke.xml ; then
	curl -s http://$SOLRHOST:8080/solr/admin/luke > static/luke.xml
	php statsChamps.php > static/champs.csv.new
	mv -f static/champs.csv.new static/champs.csv
fi
