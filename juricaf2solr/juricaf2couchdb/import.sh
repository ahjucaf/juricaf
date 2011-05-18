#!/bin/bash

LISTPOOL=files.list
DIRPOOL=../../data/pool
JSONFILE=test.json
LOG=/tmp/import.$$.log
DATE=$(date +%Y-%m-%d_%H:%M)
VERBOSE=$1;

if [ -e lock ]
then
echo "Importation lockée par un autre processus";
exit 1;
fi

if echo $0 | grep '/' > /dev/null ;
then
	cd $(echo $0 | sed 's|[^/]*$||');
fi

rm -f $LISTPOOL $JSONFILE 2> /dev/null

find $DIRPOOL -type f  | grep -v .svn > $LISTPOOL

#send json file to couchdb
function add2couch {
    if ! test -s $JSONFILE ; then
	return;
    fi
    sed 's/^/{"docs":[/' $JSONFILE | sed 's/,$/]}/' > $JSONFILE.tmp;
    mv $JSONFILE.tmp $JSONFILE ;
    curl -H"Content-Type: application/json" -s -d @$JSONFILE  -X POST "http://127.0.0.1:5984/ahjucaf/_bulk_docs" | sed 's/"},{"/\n/g' >> $LOG
    cpt=0;
    rm $JSONFILE ;
}

cpt=0;

while read y
do
    if test $VERBOSE ; then echo importing $y; fi
    if file -i "$y" | grep -v 'application/xml' > /dev/null;
    then
	echo "ERROR: $y ignored : it is not an XML doc (empty ?)";
	dest_error_dir=$(echo $y | sed "s/pool/archive\/$DATE\/error/" | sed 's/[^\/]*$//');
	mkdir -p "$dest_error_dir"
	mv "$y" "$dest_error_dir";
	continue;
    fi

    CAT='cat';
    if file -i "$y" | grep -v utf-8 > /dev/null;
    then
	CAT='iconv -f ISO8859-1 -t UTF8'
    fi
    $CAT "$y" | dos2unix | sed 's/\r/\n/g' | sed "s//'/g" | sed 's/\&\([^; ]* \)/\&amp;\1/g' | sed 's/<BR *\/*>/\n/gi' | sed 's/"iso-*8859[^"]*"/"utf8"/i' > data.xml ;

    if echo $y | grep pays_ > /dev/null ; then
	pays=$(echo $y | sed 's/.*pays_//' |  sed 's/\/.*//' | sed 's/_/ /g');
    fi
    if echo $y | grep juridiction_ > /dev/null; then
	juridiction=$(echo $y | sed 's/.*juridiction_//' |  sed 's/\/.*//' | sed 's/_/ /g');
    fi;

    while true ; do
	php juricaf2json.php "$y" "$pays" "$juridiction" > $JSONFILE.tmp 2> $JSONFILE.err
	cat $JSONFILE.err | grep 'id":"' >> $LOG
	cat $JSONFILE.err | grep -v 'id":"'
	RET=$?
	if test $RET = 0; then
		break;
	fi
	if test $RET = 33; then
		rm $JSONFILE.tmp
		break;
	fi
    done ;
    cat $JSONFILE.tmp >> $JSONFILE

    echo -n ',' >> $JSONFILE ;
    cpt=$(expr $cpt + 1) ;
    if test $cpt -eq 100 ; then
	add2couch ;
    fi  ;

    #
    # Move imported files to the archive directory
    #
    dest_dir=$(echo $y | sed "s/pool/archive\/$DATE/" | sed 's/[^\/]*$//');
    mkdir -p "$dest_dir"
    mv "$y" "$dest_dir";

done < $LISTPOOL

add2couch;

if test -e $LOG ; then

    echo
    echo "====================================================="
    echo

    sed 's/^\[."//' $LOG | grep 'id":"' | awk -F '"' '{ if ( $11 != "" ) print $3" not imported ("$11")" ; else print $3" imported" ; }'
fi
rm $LISTPOOL $LOG 2> /dev/null
cd - > /dev/null 2>&1

