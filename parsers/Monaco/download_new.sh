#!/bin/bash

mkdir -p html



curl -s 'https://legimonaco.mc/~~search/depot/_search' -X POST -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:102.0) Gecko/20100101 Firefox/102.0' -H 'Accept: */*' -H 'Accept-Language: fr,en;q=0.7,en-US;q=0.3'  -H 'Content-Type: application/json' -H 'Origin: https://legimonaco.mc' -H 'Referer: https://legimonaco.mc/~~write/explorer/type=case/dateDesc?showResults=true' -H 'Sec-Fetch-Dest: empty' -H 'Sec-Fetch-Mode: cors' -H 'Sec-Fetch-Site: same-origin' --data-raw '{"size":1000,"track_total_hits":true,"_source":["path","title","type","date","abrogated","focus","abstract"],"query":{"bool":{"must":[{"terms":{"type":["case"]}}],"must_not":[{"terms":{"processing":["home"]}},{"terms":{"trashed":["true"]}},{"terms":{"excludeFromSearch":["true"]}},{"range":{"startDate":{"gt":"now"}}},{"range":{"endDate":{"lte":"now"}}}]}},"sort":[{"date":{"order":"desc"}},{"number":{"order":"desc"}}],"from":0}' |
	jq . | grep path | awk -F '"' '{print $4}' | while read path ;
do
		filepath=$(echo $path | sed 's|/|%2F|g')
		if ! test -s "html/"$filepath ; then
			curl  -s "https://legimonaco.mc"$path"/" | sed 's/<\/div>/<\/div>\n/g' | sed 's/<div/\n<div/g' | sed 's/<section/\n<section/g'  | sed 's/<h/\n<h/g' > "html/"$filepath && echo $filepath
		fi
done
