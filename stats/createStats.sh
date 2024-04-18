#!/bin/bash

cd $(dirname $0)

. ../juricaf2solr/conf/juricaf.conf

mkdir -p static

php statsBase.php

if find static -name luke.xml -mtime +7 | grep luke > /dev/null || ! test -s static/luke.xml; then
	curl -s http://$SOLRHOST:8080/solr/admin/luke > static/luke.xml.new
	#Pivot sur luke.xml car il est exploité dans la recherche avancée du site
	mv -f static/luke.xml.new static/luke.xml
	php statsChamps.php > static/champs.csv.new
	mv -f static/champs.csv.new static/champs.csv
fi

cp static/*.csv ../project/web/documentation/stats/
