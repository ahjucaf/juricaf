#!/bin/bash

cd $(dirname $0)
source ../config/config.inc

mkdir -p export raw

for juridiction in cc ca ; do
	olddate=XXXX-XX-XX
	date=0001-01-01
	while ! test $date = 2023-12-31; do
		if ls export | grep "export_"$juridiction"__" > /dev/null ; then
			rm "export/export_"$juridiction"__"*
		fi
		if ls export | grep "export_"$juridiction > /dev/null ; then
			date=$(ls export/* | grep "export_"$juridiction | tail -n 11 | while read json ; do jq '.results[].decision_date' < $json | sed 's/"//g' ; done | tail -n 1 )
		fi
		if test "$olddate" = "$date"; then
			break;
		fi
		echo $juridiction $date;
		for i in 0 1 2 3 4 5 6 7 8 9 ; do
			curl -s -H "accept: application/json" -H "KeyId: "$JUDILIBRE_KEYID -X GET 'https://api.piste.gouv.fr/cassation/judilibre/v1.0/export?jurisdiction='$juridiction'&date_start='$date'&date_end=2023-12-31&resolve_references=true&batch_size=1000&order=asc&batch='$i > "export/export_"$juridiction"_"$date"_"$i".json" ;
		done
		if test $(cat "export/export_"$juridiction"_"$date"_"$i".json" | jq .results | wc -c) -lt 4; then
			break;
		fi
		olddate=$date
	done
done

find export/ -type f -size -1000 -delete

ls export/export_*json | while read file; do
	jq '.results[].id' < $file | sed 's/"//g'  | while read id ; do
		if ! test -s "raw/"$id".json" ;  then
			jq '.results[]|select(.id=="'$id'")' < $file > "raw/"$id".json" ;
		fi
	done
done
