#!/bin/bash

cd $(dirname $0)

for rss in $(cat ../config/judiportal.rss.list | sed 's/ *#.*//'); do
    curl -s "$rss" | grep '<guid' | sed 's/<[^>]*>//g' | while read ecli; do
        if ! test -s "html/"$ecli".html"; then
            echo "https://juportal.be/content/"$ecli"/FR" > "html/"$ecli".url"
            curl -s "https://juportal.be/content/"$ecli"/FR" > "html/"$ecli".html"
            echo "html/"$ecli".html https://juportal.be/content/"$ecli"/FR"
        fi
    done
done
