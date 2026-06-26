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
datestart=$(date -d "-1 day" +%Y-%m-%d)
else
datestart=$1
fi

if ! test "$datestart"; then
	echo Usage: download_update.sh DATE_START DATE_END
	exit 1
fi

mkdir -p raw/transactional/$datestart
query="page_size=500&date="$datestart
i=0
d=0
while test "$query"; do
	let i++
	queryfile="raw/transactional/"$datestart"/"$(printf %06d $i)".json"
	if ! test -s "$queryfile" ; then
		curl -s -H "accept: application/json" -H "KeyId: "$JUDILIBRE_KEYID -X GET 'https://api.piste.gouv.fr/cassation/judilibre/v1.0/transactionalhistory?'"$query" > $queryfile
		if (( i % 5 == 0 )) ; then
			sleep 20 ;
		fi
	fi
	query=$(jq .next_page < "$queryfile" | sed 's/"//g' | sed 's/null//' )
	jq -c .transactions[] < "$queryfile" | awk -F '"' '{print $4" "$8" "$12}'
done | while read decision action date; do
	let d++
	decisiondir="raw/decisions/"${date:0:10}/${decision:0:4}
	mkdir -p "$decisiondir"
	decisionpath=$decisiondir"/"$decision"_"$action"_"$date".json"
	if test -f $decisionpath; then
		continue;
	fi
	if test $action = "delete"; then
		touch $decisionpath
		continue;
	fi
	curl -s -H "accept: application/json" -H "KeyId: "$JUDILIBRE_KEYID -X GET 'https://api.piste.gouv.fr/cassation/judilibre/v1.0/decision?resolve_references=true&id='$decision > "$decisionpath";
	sed -i 's/query=<a[^>]*>[^<]*<\/a>//' $decisionpath;
	if ! test -s $decisionpath; then
		rm -f $decisionpath;
	else
		ls $decisionpath;
	fi
	if (( d % 20 == 0 )) ; then
		sleep 30 ;
	fi
done

rm $LOCK
