#!/bin/bash
for curia in https://curia.europa.eu/jcms/jcms/P_106311/fr/ https://curia.europa.eu/jcms/jcms/P_106312/fr/ ; do
  curl -s https://curia.europa.eu/jcms/jcms/P_106311/fr/ | grep 'FR/TXT' | grep "CELEX:" | sed "s/[&_']/\n/g" | grep CELEX | sed 's/.*CELEX://' | grep -v img | sort -u | while read celex ; do
    ISNEW=""
    if ! test -f data/$celex.xml ; then
        curl -s -L -H "Accept: application/xml;notice=branch" -H "Accept-language: fr" http://publications.europa.eu/resource/celex/$celex > data/$celex.xml
        ISNEW="OK"
    fi
    if ! test -f data/$celex.html ; then
        curl -s -L 'http://publications.europa.eu/resource/celex/'$celex -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:91.0) Gecko/20100101 Firefox/91.0' -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8' -H 'Accept-Language: fr,en;q=0.7,en-US;q=0.3' > data/$celex.html
        ISNEW="OK"
    fi
    if test -f data/$celex.html && ! test -f data/$celex.txt ; then
        links -dump -width 510 data/$celex.html > data/$celex.txt
        ISNEW="OK"
    fi
    if grep ^Resource data/$celex.html data/$celex.xml > /dev/null ; then
        rm data/$celex.html data/$celex.xml
        ISNEW=""
    fi
    if test "$ISNEW"; then
        echo $celex;
    fi
  done
done
