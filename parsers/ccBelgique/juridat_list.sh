#!/bin/bash

cd $(dirname $0) 2> /dev/null

fromdate=$1
if ! test "$fromdate"; then
    fromdate=$(date --date='TZ="Europe/Brussels" 22:00 last month' --iso)
fi
todate=$(date --date='TZ="Europe/Brussels" 22:00 today' --iso)

curl -s 'http://jure.juridat.just.fgov.be/JuridatSearchCombined/Juridat-Search' -H 'content-type: text/xml' --data '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><SOAP-ENV:Body><tns:decisionSearch xmlns:tns="http://search.juridat.bull.be/"><arg0><article><art_bleg>0</art_bleg><art_date xsi:nil="true"/><art_name></art_name><art_numero></art_numero><art_suite></art_suite></article><cassasionIdList></cassasionIdList><dec_juridiction>1</dec_juridiction><dec_justel></dec_justel><dec_location>0</dec_location><dec_role></dec_role><fromDate>'$fromdate'T22:00:00Z</fromDate><idxc_freekeyword></idxc_freekeyword><idxc_freekeywordsimple></idxc_freekeywordsimple><keywordMarkup></keywordMarkup><onConcl>false</onConcl><onDe>false</onDe><onFKey>false</onFKey><onFr>true</onFr><onNl>false</onNl><onNote>false</onNote><onSom>false</onSom><onText>false</onText><onThes>false</onThes><publication><pub_article></pub_article><pub_author></pub_author><pub_date></pub_date></publication><recentDate>true</recentDate><toDate>'$todate'T22:00:00Z</toDate><universalIdList></universalIdList></arg0></tns:decisionSearch></SOAP-ENV:Body></SOAP-ENV:Envelope>' |
    sed 's/dec_justel/dec_justel\n/g' | grep '/dec_justel' | sed 's/dec_justel//g' | sed 's/[<>\/]//g' |
    while read id ; do
        bash juridat_download_one.sh $id
    done
