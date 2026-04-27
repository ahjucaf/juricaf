#!/bin/bash

mkdir -p json

curl -s -H 'Referer: https://infocuria.curia.europa.eu/' -H 'Content-Type: application/json; charset=utf-8' -H 'Origin: https://infocuria.curia.europa.eu' 'https://infocuriaws.curia.europa.eu/elastic-connector/search' -X POST --data-raw '{"searchTerm":"","multiSearchTerms":[],"sortTermList":[{"sortDirection":"DESC","sortTerm":"SCORE"}],"pagination":{"pageNumber":0,"pageSize":1000,"from":0,"to":1000},"language":"FR","tabName":"affair","isAllTabsRequest":false,"ecli":"","publishedId":"","usualName":"","logicDocId":"","filtersValue":[{"field":"affairState","values":["CLOTPUB"],"valuesWithFullHierarchy":["CLOTPUB"]},{"field":"jurisdiction","values":["C"],"valuesWithFullHierarchy":["C"]}],"isSearchExact":true,"searchSources":["document","metadata"]}' > /tmp/cjue.$$.json

cat /tmp/cjue.$$.json | jq . | grep affId | awk -F '"' '{print $4}' | while read affid; do
    affidfile="json/"$(echo $affid | sed 's|/|_|g')".json"
    if ! test -s $affidfile; then
        if ! curl -s  'https://infocuriaws.curia.europa.eu/elastic-connector/affairId/procedures' -X POST -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:140.0) Gecko/20100101 Firefox/140.0'   -H 'Accept: application/json'   -H 'Accept-Language: fr,fr-FR;q=0.8,en-US;q=0.5,en;q=0.3'   -H 'Accept-Encoding: gzip, deflate, br, zstd'   -H 'Referer: https://infocuria.curia.europa.eu/'   -H 'Content-Type: application/json; charset=utf-8'   -H 'Origin: https://infocuria.curia.europa.eu'   -H 'Connection: keep-alive'   -H 'Sec-Fetch-Dest: empty'   -H 'Sec-Fetch-Mode: cors'   -H 'Sec-Fetch-Site: same-site'   -H 'Priority: u=0'   -H 'TE: trailers'  --data-raw '{"affId":"'$affid'","searchTerm":"","tabName":"affair","language":"FR"}' > $affidfile ; then
            rm -f $affidfile;
        fi
    fi
    if test -s $affidfile; then
        echo $affidfile;
    fi
done

rm /tmp/cjue.$$.json
