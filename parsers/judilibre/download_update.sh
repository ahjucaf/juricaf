#!/bin/bash

cd $(dirname $0)
source ../config/config.inc

LOCK=/tmp/jurilibre.lock

if test -f $LOCK ; then
	exit 2
fi
touch $LOCK


mkdir -p raw
if ! test "$1"; then
datestart=$(date -d "-3 month" +%Y-%m-%d)
dateend=$(date +%Y-%m-%d)
else
datestart=$1
dateend=$2
fi

if ! test "$dateend"; then
	echo Usage: download_update.sh DATE_START DATE_END
	exit 1
fi

for mots in "pourvoi+OR+president+OR+cour+OR+tribunal" "peuple+OR+juge" ; do
for (( i = 0 ; i < 200 ; i++ )) ; do
	curl -s -H "accept: application/json" -H "KeyId: "$JUDILIBRE_KEYID -X GET 'https://api.piste.gouv.fr/cassation/judilibre/v1.0/search?date_start='$datestart'&date_end='$dateend'&sort=date&order=desc&page_size=50&page='$i'&query='$mots > /tmp/jurilibre.$$.json
	cat /tmp/jurilibre.$$.json | jq '.results[].id'  | sed 's/"//g' | while read decision ; do
		if ! test -s raw/$decision".json" ; then
			echo $decision;
		fi
	done
	if ! grep results /tmp/jurilibre.$$.json > /dev/null ; then
		echo "WARNING: no results in API $datestart -> $dateend / page $i / mots $mots"
		cat /tmp/jurilibre.$$.json
		i=999
	elif test "0"$( cat /tmp/jurilibre.$$.json | jq '.results|length' ) -eq 0 ; then
		i=999
	fi
done
sleep 30
done > /tmp/jurilibre.$$.decisions
rm /tmp/jurilibre.$$.json

sort -u /tmp/jurilibre.$$.decisions | while read decision ; do
	curl -s -H "accept: application/json" -H "KeyId: "$JUDILIBRE_KEYID -X GET 'https://api.piste.gouv.fr/cassation/judilibre/v1.0/decision?resolve_references=true&id='$decision > raw/$decision".json";
	sed -i 's/query=<a[^>]*>[^<]*<\/a>//' raw/$decision".json"
	if ! test -s raw/$decision".json" ; then
		rm -f raw/$decision".json"
	else
		ls raw/$decision".json"
	fi
done

rm /tmp/jurilibre.$$.decisions $LOCK
