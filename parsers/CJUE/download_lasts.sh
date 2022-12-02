#!/bin/bash

curl -s "https://curia.europa.eu/juris/liste.jsf?oqp=&for=&mat=or&lgrec=fr&jge=&ordreTri=dateDesc&td=%3BALL&jur=C%2CT%2CF&etat=clot&page=1&dates=&pcs=Oor&lg=FR%252C%252Btrue%252Ctrue&pro=&nat=or&cit=none%252CC%252CCJ%252CR%252C2008E%252C%252C%252C%252C%252C%252C%252C%252C%252C%252Ctrue%252Cfalse%252Cfalse&language=fr&avg=" > /tmp/$$.html
jsessionid=$(grep jsessionid= /tmp/$$.html  | sed 's/.*jsessionid=//' | sed 's/[?"'"'"'].*//'  | head -n 1 )
mkdir -p data
for i in  {1..10000}; do
 	curl -s 'https://curia.europa.eu/juris/liste.jsf;jsessionid='$jsessionid -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:91.0) Gecko/20100101 Firefox/91.0' -H 'Accept: */*' -H 'Accept-Language: fr,en;q=0.7,en-US;q=0.3' --compressed -H 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8' -H 'Origin: https://curia.europa.eu' -H 'DNT: 1' -H 'Connection: keep-alive' -H 'Referer: https://curia.europa.eu/juris/liste.jsf?oqp=&for=&mat=or&lgrec=fr&jge=&ordreTri=dateDesc&td=%3BALL&jur=C%2CT%2CF&etat=clot&page='$i'&dates=&pcs=Oor&lg=FR%252C%252Btrue%252Ctrue&pro=&nat=or&cit=none%252CC%252CCJ%252CR%252C2008E%252C%252C%252C%252C%252C%252C%252C%252C%252C%252Ctrue%252Cfalse%252Cfalse&language=fr&avg=' -H 'Cookie: JSESSIONID='$jsessionid -H 'Sec-Fetch-Dest: empty' -H 'Sec-Fetch-Mode: cors' -H 'Sec-Fetch-Site: same-origin' --data-raw 'AJAXREQUEST=_viewRoot&mainForm=mainForm&mainForm%3Aj_id13=fr&lienImage=%2Fjuris&javax.faces.ViewState=j_id1&mainForm%3Aj_id451=mainForm%3Aj_id451&page='$i'&' > /tmp/$$.html
	url=$(grep Location /tmp/$$.html | sed 's/.*content="//' | sed 's/".*//' | sed 's|^|https://curia.europa.eu|')
	curl -s $url -H 'Cookie: JSESSIONID='$jsessionid'; critere=crt_redirectionBean%3Ddefcrt_triBean%3DdateDesccrt_affairesBean%3Dclotcrt_langueBean%3DlangProclangConclselectionFR' > /tmp/$$.html
	grep -i celex /tmp/$$.html | sed 's/.*CELEX/CELEX/' | sed 's/".*//'  | sort -u
done | grep CELEX | sed 's/CELEX://' | while read celex ; do
    if ! test -f data/$celex.xml ; then
        curl -s -L -H "Accept: application/xml;notice=branch" -H "Accept-language: fr" http://publications.europa.eu/resource/celex/$celex > data/$celex.xml
    fi
    if ! test -f data/$celex.html ; then
        curl -s -L 'http://publications.europa.eu/resource/celex/'$celex -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:91.0) Gecko/20100101 Firefox/91.0' -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8' -H 'Accept-Language: fr,en;q=0.7,en-US;q=0.3' > data/$celex.html
    fi
    if test -f data/$celex.html && ! test -f data/$celex.txt ; then
        links -dump -width 510 data/$celex.html > data/$celex.txt
    fi
    if grep ^Resource data/$celex.html data/$celex.xml > /dev/null ; then
        rm data/$celex.html data/$celex.xml
    fi
done
