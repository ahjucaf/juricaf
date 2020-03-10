#!/bin/bash

cd $(dirname $0) 2> /dev/null
mkdir -p documents

id=$1

if test -f "documents/"$id".xml"; then
    exit 0;
fi

curl -s 'http://jure.juridat.just.fgov.be/JuridatSearchCombined/Juridat-Search' -H 'content-type: text/xml'  --data '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><SOAP-ENV:Body><tns:getDecision xmlns:tns="http://search.juridat.bull.be/"><arg0><justel>'$id'</justel><langue>FR</langue><markup></markup><showMarkup>true</showMarkup></arg0></tns:getDecision></SOAP-ENV:Body></SOAP-ENV:Envelope>' > "documents/"$id".xml"
cat "documents/"$id".xml" | sed 's/.*<dec_text>//' | sed 's/<.*//' | base64 -d | sed 's/<\/*p>/\n/gi'  | sed 's/<[^>]*>//g' > "documents/"$id"_texte.txt"
cat "documents/"$id".xml" | sed 's/.*<dec_conclusion>//' | sed 's/<.*//' | base64 -d | sed 's/<\/*p>/\n/gi'  | sed 's/<[^>]*>//g' > "documents/"$id"_conclusions.txt"

if ! test -s "documents/"$id".xml" ; then
    rm "documents/"$id".xml";
    exit 0
fi
if ! test -s "documents/"$id"_texte.txt" ; then
    rm "documents/"$id"_texte.txt";
    exit 0
fi
if ! test -s "documents/"$id"_conclusions.txt" ; then
    rm "documents/"$id"_conclusions.txt";
fi
echo "documents/"$id".xml" ;
