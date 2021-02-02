#!/bin/bash

date_from=$( cat last_kpdate.txt | awk '{if ($3 > 2000-01-01) print $3 ; else print "2000-01-01" ;}' )
ARRET_LIST=/tmp/$$"_arrets.list"
rm -f last_kpdate.txt.new

for start in {0..10000..100} ; do

    curl -s --ciphers 'DEFAULT:!DH' "https://hudoc.echr.coe.int/app/query/results?query=contentsitename%3AECHR%20AND%20(NOT%20(doctype%3DPR%20OR%20doctype%3DHFCOMOLD%20OR%20doctype%3DHECOMOLD))%20AND%20((languageisocode%3D%22FRE%22))%20AND%20((documentcollectionid%3D%22GRANDCHAMBER%22)%20OR%20(documentcollectionid%3D%22CHAMBER%22))&select=sharepointid,Rank,ECHRRanking,languagenumber,itemid,docname,doctype,application,appno,conclusion,importance,originatingbody,typedescription,kpdate,kpdateAsText,documentcollectionid,documentcollectionid2,languageisocode,extractedappno,isplaceholder,doctypebranch,respondent,advopidentifier,advopstatus,ecli,appnoparts,sclappnos&sort=kpdate%20Descending&start="$start"&length=100&rankingModelId=11111111-0000-0000-0000-000000000000" |
        jq . | grep 'itemid\|kpdateAsText'  | tr -d '\n'  | sed 's/"itemid"/\n"itemid"/g'  |
        awk -F '"' '{print $4" "$8}' | sed 's/ 00:.*//' | sed 's| |/|' | awk -F '/' '{date = $4"-"$3"-"$2 ; if ( date  > "'$date_from'" ) print "'$start' "$1" "date}' > $ARRET_LIST

    if ! test -s $ARRET_LIST; then
        break;
    fi

    if ! test -s last_kpdate.txt.new; then
        tail -n 1 $ARRET_LIST > last_kpdate.txt.new
    fi

done

mv last_kpdate.txt.new last_kpdate.txt
rm /tmp/$$"_arrets.list"
