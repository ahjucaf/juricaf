#!/bin/bash

cd $(dirname $0)

. config.inc

mkdir -p $DOC_DIR"/legilux"

orig_url="http://legilux.public.lu/search/A/?fulltext=&only_memorials=false&only_acts=false&thematique=arr%C3%AAt&type_memorial=A&statut=all&only_consolidated=false&annee_min=&annee_max=&date_sign_min=&date_sign_max=&date_pub_min=&date_pub_max=&sort_type=datePublication&sort_order=DESC&page_size=100"
url="$orig_url"
page=1
while test "$url" ; do
    curl -s "$url" > /tmp/$$.html
    if grep -i -B 2 'suivant' /tmp/$$.html | grep disable > /dev/null ; then
      url="";
    else
      ((page++))
      url="$orig_url&page="$page;
    fi
    cat /tmp/$$.html | grep '/jo"'  | grep 'class="consult"'  | awk -F '"' '{print "http://data.legilux.public.lu"$2"/fr/xml"}'
    rm /tmp/$$.html
done | awk -F ';' 'BEGIN{print "cd '$DOC_DIR'/legilux"} {output=$1 ; gsub(/.*etat./, "", output); gsub(/\//, "_", output); gsub(/.xml/, "", output); pdf=$1 ; gsub(/xml$/, "pdf", pdf); print "wget -nc -q -O "output".xml \""$1"\" || wget -nc -q -O "output".pdf \""pdf"\""}' | sh

find docs/ -size 0 -exec rm '{}' ';'

rm /tmp/$$.sh
