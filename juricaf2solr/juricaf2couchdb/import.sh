#!/bin/bash

#Default configuration options
LISTPOOL=files.list
JSONFILE=test.json
LOG=/tmp/import.$(date '+%Y%m%d').$$.log
DATE=$(date +%Y-%m-%d_%H:%M)
LOCK=/tmp/$0.lock
VERBOSE=$1;

PREDIR=.
if echo $0 | grep '/' > /dev/null; then
  PREDIR=$(echo $0 | sed 's/\/[^\/]*$//');
  LOCK=/tmp/$(echo $0 | sed "s|$PREDIR||").lock
fi

#Configuration file juricaf2couchdb.conf
if ! test -e $PREDIR/../conf/juricaf.conf; then
  echo Configuration file $PREDIR/../conf/juricaf.conf does not exist
  exit 1;
fi
. $PREDIR/../conf/juricaf.conf

#Si d'un autre chemin que le repertoire local, on se déplace dans le répertoire local
if echo $0 | grep '/' > /dev/null ;
then
  cd $(echo $0 | sed 's|[^/]*$||');
fi

if [ -e $LOCK ]
then
  if ! ps --pid $(cat $LOCK) > /dev/null ; then
    echo $(cat $LOCK) not running, destroy the lock
    rm lock
  fi
  exit 1;
fi
echo $$ > $LOCK

rm -f $JSONFILE 2> /dev/null
rm -f $LOG 2> /dev/null

find $DIRPOOL -type f  | grep -v .svn > $LISTPOOL

function update2couch {
  REV=$(curl -H"Content-Type: application/json" -s "$COUCHDBURL/$CONFLICTID"  | sed 's/.*"_rev":"//' | sed 's/".*//')
  php updatejson4couchdb.php $JSONFILE $CONFLICTID $REV | curl -H"Content-Type: application/json" -s -d @/dev/stdin  -X POST "$COUCHDBURL/_bulk_docs" | sed 's/$/"updated"/' >> $LOG
}

#send json file to couchdb
function add2couch {
  if ! test -s $JSONFILE ; then
  return;
  fi
  sed 's/^/{"docs":[/' $JSONFILE | sed 's/,$/]}/' > $JSONFILE.tmp;
  mv $JSONFILE.tmp $JSONFILE ;
  curl -H"Content-Type: application/json" -s -d @$JSONFILE  -X POST "$COUCHDBURL/_bulk_docs" | sed 's/"},{"/\n/g' >> $LOG.tmp
  grep -v '"conflict"' $LOG.tmp >> $LOG
  for CONFLICTID in $(grep '"conflict"' $LOG.tmp | sed 's/.*id":"//' | sed 's/".*//') ; do
  update2couch
  done
  cpt=0;
  rm $JSONFILE $LOG.tmp;
}

cpt=0;

#if test -s $LISTPOOL ; then
#  php create_mysql_log.php
#fi

cat  $LISTPOOL | while read y
do
    if test $VERBOSE ; then echo importing $y; fi
    if file -i "$y" | grep -v '/xml' > /dev/null;
    then
        echo "ERROR: $0: $y ignored : it is not an XML doc (empty ?)";
        dest_error_dir=$(echo $y | sed "s/pool/archive\/$DATE\/error/" | sed 's/[^\/]*$//');
        mkdir -p "$dest_error_dir"
        mv "$y" "$dest_error_dir";
        continue;
    fi

    if file -i "$y" | grep -v utf-8 > /dev/null;
    then
      if file -i "$y" | grep -v iso-8859-1 > /dev/null;
      then
        iconv -c -f windows-1252 -t iso-8859-1//TRANSLIT "$y" | iconv -f ISO8859-1 -t UTF8 | sed 's/^M$//' | sed 's/\&\#x1;//g' | sed 's/\&\([^; ]* \)/\&amp;\1/g' | sed 's/<BR *\/*>/\n/gi' | sed 's/"iso-*8859[^"]*"/"utf8"/i' > data.xml ;
      else
        iconv -f ISO8859-1 -t UTF8 "$y" | sed 's/^M$//' | sed 's/\&\#x1;//g' | sed 's/\&\([^; ]* \)/\&amp;\1/g' | sed 's/<BR *\/*>/\n/gi' | sed 's/"iso-*8859[^"]*"/"utf8"/i' > data.xml ;
      fi
    else
      cat "$y" | sed 's/^M$//' | sed 's/\&\#x1;//g' | sed 's/\&\([^; ]* \)/\&amp;\1/g' | sed 's/<BR *\/*>/\n/gi' > data.xml ;
    fi

    if echo $y | grep pays_ > /dev/null ; then
    pays=$(echo $y | sed 's/.*pays_//' |  sed 's/\/.*//' | sed 's/_/ /g');
    fi
    if echo $y | grep juridiction_ > /dev/null; then
    juridiction=$(echo $y | sed 's/.*juridiction_//' |  sed 's/\/.*//' | sed 's/_/ /g');
    fi;

    if ! php juricaf2json.php "$y" "$pays" "$juridiction" > $JSONFILE.tmp 2> $JSONFILE.err ; then
      rm $JSONFILE.tmp
      echo "ERROR $y:"
      cat $JSONFILE.err | grep -v 'id":"'
      echo
    fi
    cat $JSONFILE.err | grep 'id":"' >> $LOG
    if test -e $JSONFILE.tmp; then
      DOCID=$(cat $JSONFILE.tmp | sed 's/.*_id":"//'  | sed 's/".*//')
      curl -s $COUCHDBURL"/"$DOCID > $JSONFILE.orig
      if grep not_found $JSONFILE.orig > /dev/null ; then
        rm $JSONFILE.orig
      fi
      if test -n $JSONFILE.orig ; then
        php json_update.php $JSONFILE.orig $JSONFILE.tmp >> $JSONFILE
      else
        cat $JSONFILE.tmp >> $JSONFILE
      fi
      echo -n ',' >> $JSONFILE ;
    fi

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

done

add2couch;

if test -e $LOG ; then

    echo
    echo "====================================================="
    echo

    sed 's/^\[."//' $LOG | sed 's/ok":true,"//' | grep 'id":"' | awk -F '"' '{ if ( $11 != "" ) print $3" not imported ("$11")" ; else if ( $9 == "updated" ) print $3" updated"; else print $3" imported" ; }'
fi
#rm $LISTPOOL $LOG $LOCK 2> /dev/null
rm $LOCK 2> /dev/null
cd - > /dev/null 2>&1
