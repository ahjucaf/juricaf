#!/bin/bash
cpt=0; 
pays=$1

rm test.json ; 
for i in ../../data/XML/$pays* ; do 
    for y in $i/*; do 
	cat "$y" | iconv -f ISO88591 -t UTF8 | sed 's/\r/\n/g' | sed 's/<BR *\/*>/\n/gi' >  data.xml ; 
	php juricaf2json.php >> test.json ; 
	echo -n ',' >> test.json ; cpt=$(expr $cpt + 1) ; 
	if test $cpt -eq 100 ; then 
	    sed 's/^/{"docs":[/' test.json | sed 's/,$/]}/' > test.json.tmp; 
	    mv test.json.tmp test.json ; 
	    curl -d @test.json  -X POST "http://127.0.0.1:5984/ahjucaf/_bulk_docs"; 
	    cpt=0; 
	    rm test.json ; 
	fi  ; 
    done ; 
done

