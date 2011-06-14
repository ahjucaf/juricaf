#!/bin/bash

PREDIR=.
if echo $0 | grep '/' > /dev/null; then PREDIR=$(echo $0 | sed 's/\/[^\/]*$//'); fi
if ! test -e $PREDIR/../conf/juricaf.conf; then
    echo $PREDIR/../conf/juricaf.conf does not exist
    exit 1;
fi
. $PREDIR/../conf/juricaf.conf

LOCK=$0.lock 
if test -e $LOCK ; then
        exit 1;
fi

touch $LOCK
wget -q -O /dev/null $COUCHDBURL/_design/stats/_view/pays_juridiction_date
rm $LOCK
