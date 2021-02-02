#!/bin/bash

date_from=$( cat last_kpdate.txt | awk '{if ($3 > 2000-01-01) print $3 ; else print "2000-01-01" ;}' )
ARRET_LIST=/tmp/$$"_arrets.list"
rm -f last_kpdate.txt.new
mkdir -p arrets

for start in {0..10000..100} ; do

    curl -s -m 1 --ciphers 'DEFAULT:!DH' "https://hudoc.echr.coe.int/app/query/results?query=contentsitename%3AECHR%20AND%20(NOT%20(doctype%3DPR%20OR%20doctype%3DHFCOMOLD%20OR%20doctype%3DHECOMOLD))%20AND%20((languageisocode%3D%22FRE%22))%20AND%20((documentcollectionid%3D%22GRANDCHAMBER%22)%20OR%20(documentcollectionid%3D%22CHAMBER%22))&select=itemid,kpdate,kpdateAsText&sort=kpdate%20Descending&start="$start"&length=100&rankingModelId=11111111-0000-0000-0000-000000000000" |
        jq . | grep 'itemid\|kpdateAsText'  | tr -d '\n'  | sed 's/"itemid"/\n"itemid"/g'  |
        awk -F '"' '{print $4" "$8}' | sed 's/ 00:.*//' | sed 's| |/|' | awk -F '/' '{date = $4"-"$3"-"$2 ; if ( date  > "'$date_from'" ) print "'$start' "$1" "date}' > $ARRET_LIST".one"

    if ! test -s $ARRET_LIST".one"; then
        break;
    fi

    cat $ARRET_LIST".one" >> $ARRET_LIST
    rm $ARRET_LIST".one"

    if ! test -s last_kpdate.txt.new ; then
        head -n 1 $ARRET_LIST > last_kpdate.txt.new
    fi

done

cat $ARRET_LIST | while read i arretnum arretdate ; do
    curl -s -m 1 --ciphers 'DEFAULT:!DH' "https://hudoc.echr.coe.int/app/conversion/docx/html/body?library=ECHR&id="$arretnum > arrets/$arretnum".html"
    if test -s arrets/$arretnum".html"; then
        html2markdown -b 0 arrets/$arretnum".html" > arrets/$arretnum".txt"
    fi
    curl -s -m 1 --ciphers 'DEFAULT:!DH' "https://hudoc.echr.coe.int/app/query/results?query=(contentsitename=ECHR)%20AND%20"$arretnum"&select=advopidentifier,applicationnumber,advopstatus,applicability,appno,article,casecitation,conclusion,contentcategory,contentsitename,decisiondate,docname,doctype,documentcollectionid,documentcollectionid2,extractedappno,hudocdate,ecli,externalsources,importance,introductiondate,isplaceholder,issue,itemid,judgementdate,kpdate,kpdateAsText,kpthesaurus,meetingnumber,languageisocode,originatingbody,publishedby,referencedate,reportdate,representedby,resolutiondate,resolutionnumber,respondent,rulesofcourt,scl,sclappnos,separateopinion,typedescription&sort=&start=0&length=1" > arrets/$arretnum".json"
    echo $arretnum
done

mv last_kpdate.txt.new last_kpdate.txt
rm -f $ARRET_LIST".one" $ARRET_LIST

#https://hudoc.echr.coe.int/app/query/results?query=(contentsitename=ECHR)%20AND%20001-112422&select=itemid,applicability,appno,article,conclusion,decisiondate,docname,documentcollectionid,documentcollectionid2,doctype,externalsources,importance,introductiondate,issue,judgementdate,kpthesaurus,meetingnumber,originatingbody,publishedby,referencedate,kpdate,advopidentifier,advopstatus,reportdate,representedby,resolutiondate,resolutionnumber,respondent,rulesofcourt,separateopinion,scl,typedescription,ecli,casecitation,contentsitename&sort=&start=0&length=1

#https://hudoc.echr.coe.int/app/conversion/docx/html/body?library=ECHR&id=001-112422
