#!/bin/bash
LOCK=$0.lock 
if test -e $LOCK ; then
        exit 1;
fi

touch $LOCK
wget -q -O /dev/null http://localhost:5984/ahjucaf/_design/stats/_view/pays_juridiction_date
rm $LOCK
