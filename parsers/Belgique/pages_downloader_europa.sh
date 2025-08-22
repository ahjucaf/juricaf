#!/bin/bash

date=$1

datefr=$( echo $date | sed 's/\([0-9][0-9][0-9][0-9]\)-\([0-9][0-9]\)-\([0-9][0-9]\)/\3\/\2\/\1/' );

js2api=$(curl -s "https://webgate.ec.europa.eu/ecli?lang=en" -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0' | grep main- | sed 's/.*src="main-/main-/'  | sed 's/".*//')
apikey=$(curl -s "https://webgate.ec.europa.eu/ecli/"$js2api -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0' | sed 's/apiKey/\napiKey/' | grep apiKey | awk -F '"' '{print $2}' )

for page in 1 2 3 4 5 6 7 8 9 10 ; do
    curl -s 'https://api.tech.ec.europa.eu/ecli/search' \
    -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0' \
    -H 'Accept: application/json, text/plain, */*' \
    -H 'Accept-Language: fr,fr-FR;q=0.8,en-US;q=0.5,en;q=0.3' \
    -H 'apikey: '"$apikey" \
    -H 'Content-Type: application/json' \
    -H 'Referer: https://webgate.ec.europa.eu/' \
    -H 'Origin: https://webgate.ec.europa.eu' \
    --data-raw  '{"interfaceLanguage":"fr","criteria":[{"name":"agreementCheckbox","value":"true","multipleValuesList":null},{"name":"organisations","value":null,"multipleValuesList":["BE"]},{"name":"referenceType","value":"Any","multipleValuesList":null},{"name":"referenceRelation","value":"Any","multipleValuesList":null},{"name":"languages","value":null,"multipleValuesList":["FR"]},{"name":"publicationFromDate","value":"'$datefr'","multipleValuesList":null}],"pageSize":100,"pageNumber":'$page',"searchType":  "ADVANCED_SEARCH","contentLanguage":"fr"}' \
    > /tmp/ecli.$$.json

    if ! grep searchResultItemList /tmp/ecli.$$.json > /dev/null ; then
        break;
    fi

    cat /tmp/ecli.$$.json | jq ".searchResultItemList[].ecliIdentifier" | sed 's/"//g' | while read ecli; do
        if ! test -s "html/"$ecli".html"; then
            echo "https://juportal.be/content/"$ecli"/FR" > "html/"$ecli".url"
#            curl -s "https://juportal.be/content/"$ecli"/FR" > "html/"$ecli".html"
            echo "html/"$ecli".html https://juportal.be/content/"$ecli"/FR"
        fi
    done
done
