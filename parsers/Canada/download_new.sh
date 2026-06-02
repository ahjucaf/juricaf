#!/bin/bash

cd $(dirname $0)/

mkdir -p html

i=1
nbdecisions_new=0
nbdecisions_old=1
while test "$nbdecisions_new" -ne "$nbdecisions_old" ; do
	nbdecisions_old=$nbdecisions_new
	curl -s "https://decisions.scc-csc.ca/scc-csc/fr/a/s/"$i"/infiniteScroll.do?cont=&ref=&d1=&d2=&or=&iframe=tr" > /tmp/infiniteScroll.html
	if cat /tmp/infiniteScroll.html | grep robocop/captcha > /dev/null ; then
		exit 1
	fi
	cat /tmp/infiniteScroll.html | sed 's/.*href=.//'  | grep scc-csc/scc | sed 's/".*//' | grep scc-csc/scc-csc/fr/item | while read uri ; do
		#/scc-csc/scc-l-csc-a/fr/item/21459/index.do
		iddecision=$(echo $uri | awk -F '/' '{print $6}');
		if ! test -s "html/"$iddecision".html" ; then
			curl -s "https://decisions.scc-csc.ca"$uri"?iframe=true" > "html/"$iddecision".html"
			if test -s "html/"$iddecision".html" && cat "html/"$iddecision".html" | grep -v robocop/captcha > /dev/null ; then
				echo "html/"$iddecision".html"
				echo "https://decisions.scc-csc.ca"$uri > "html/"$iddecision".url"
			else
				rm -f "html/"$iddecision".html"
			fi
			if cat "html/"$iddecision".html" | grep robocop/captcha > /dev/null ; then
				exit 1
			fi
			sleep 30;
		fi
	done
	nbdecisions_new=$(ls html | wc -l)
	((i++))
	sleep 30;
done
