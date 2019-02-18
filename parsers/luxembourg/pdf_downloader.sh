#!/bin/bash

cd $(dirname $0)

. config.inc

mkdir -p $DOC_DIR

for nexturl in https://justice.public.lu/fr/jurisprudence/cour-cassation.html https://justice.public.lu/fr/jurisprudence/juridictions-administratives.html; do
	url=$nexturl
	while test "$url" ; do
		curl -s $url  > /tmp/$$.html
		type=""
		if echo $url | grep cassation > /dev/null; then
			type="courdecassation"
		elif echo $url | grep admin > /dev/null; then
			type="juridictionsadministratives";
		fi
		url="";
		cat /tmp/$$.html| tr '\r' ' ' | tr '\n' ' ' | sed 's/<article/\n<article/g' | sed 's|<header |<div class="number">N°arrêt</div><header |' | sed 's|</article>|</article>\n|g'  | grep article  | sed 's/href=./>/g' | sed 's/. target/</g'  | sed 's/<[^>]*>/;/g' | sed 's/; */;/g'   | sed 's/^;;N°arrêt;;;;/;;N°arrêt;;N°arrêt;;;;/' | sed "s/^/$type;/"
		url=$(cat /tmp/$$.html | grep 'title="Page suivante' | awk -F '"' '{print "https://justice.public.lu"$4}')
	done
done | awk -F ';' '{gsub(/\//, ";", $17); print $10 ";" $1 ";" $17}' | grep '[0-9]' | sed 's|^/|https://justice.public.lu/|' | sed 's/\r//g' |
awk -F ';' 'BEGIN{print "cd '$DOC_DIR'"} {url=$1 ; nom=url ; gsub(/.*\//, "", nom); gsub(/[()]/, "", nom); if ( url ~ /http/ ) print "wget -q -nc -O "$5 $4 $3 "_" $2 "_$(echo \""url"\" | sha256sum | cut -d \" \" -f 1)_"nom" \""url"\""}' | sh

rm /tmp/$$.html
