#!/bin/bash

LISTPOOL=files.list
DIRPOOL=../../data/pool
JSONFILE=test.json
LOG=/tmp/import.$$.log

rm -f $LISTPOOL $JSONFILE 2> /dev/null

find $DIRPOOL -type f | grep -v .svn > $LISTPOOL

#send json file to couchdb
function add2couch {
    if ! test -s $JSONFILE ; then
  return;
    fi
    sed 's/^/{"docs":[/' $JSONFILE | sed 's/,$/]}/' > $JSONFILE.tmp;
    mv $JSONFILE.tmp $JSONFILE ;
    curl -H"Content-Type: application/json" -s -d @$JSONFILE  -X POST "http://127.0.0.1:5984/ahjucaf/_bulk_docs" | sed 's/"},{"/\n/g' > $LOG
    cpt=0;
    rm $JSONFILE ;
}

cpt=0;

while read y
do
#    echo importing $y
    if file -i "$y" | grep -v 'application/xml' > /dev/null;
    then
  echo "ERROR: $y ignored : it is not an XML doc";
  rm $y;
  continue;
    fi
    if file -i "$y" | grep iso-8859 > /dev/null;
    then
    cat "$y" | dos2unix | sed 's/\r/\n/g' | sed 's/<BR *\/*>/\n/gi' >  data.xml ;
    else cat "$y" | sed 's/\r/\n/g' | sed 's/<BR *\/*>/\n/gi' >  data.xml ;
    fi
    php juricaf2json.php >> $JSONFILE ;
    echo -n ',' >> $JSONFILE ;
    cpt=$(expr $cpt + 1) ;
    if test $cpt -eq 2 ; then # 100
  add2couch ;
    fi  ;
    #
    # Move imported files to the archive directory
    #
    #dest_dir=$(echo $y | sed 's/pool/archive/' | sed 's/[^\/]*$//');
    #mkdir -p "$dest_dir"
    #mv "$y" "$dest_dir";
done < $LISTPOOL

add2couch;

if test -e $LOG ; then
    sed 's/^\[."//' $LOG | awk -F '"' '{ if ( $11 != "" ) print $3" not imported ("$11")" ; else print $3" imported" ; }'
fi
rm $LISTPOOL $LOG 2> /dev/null

