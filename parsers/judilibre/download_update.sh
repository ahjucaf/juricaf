#!/bin/bash

cd $(dirname $0)
source ../config/config.inc

mkdir -p raw

for (( i = 0 ; i < 20 ; i++ )) ; do
curl -s 'https://www.courdecassation.fr/recherche-judilibre?sort=date-desc&items_per_page=500&search_api_fulltext=&expression_exacte=&date_du=&date_au=&judilibre_chambre=&judilibre_type=&judilibre_publication=&judilibre_solution=&judilibre_juridiction=all&judilibre_formation=&judilibre_zonage=&judilibre_doctype=&judilibre_siege_ca=&judilibre_nature_du_contentieux=&judilibre_type_ca=&op=Trier&page='$i | grep /decision | awk -F '"' '{print $2}' | sed 's/.decision.//'  | while read decision ; do
	if ! test -s raw/$decision".json" ; then
	curl -s -H "accept: application/json" -H "KeyId: "$JUDILIBRE_KEYID -X GET 'https://api.piste.gouv.fr/cassation/judilibre/v1.0/decision?resolve_references=true&id='$decision > raw/$decision".json";
	if ! test -s raw/$decision".json" ; then
		rm -f raw/$decision".json"
	fi
	sed -i 's/query=<a[^>]*>[^<]*<\/a>//' raw/$decision".json"
	ls raw/$decision".json"
	fi
done
done
