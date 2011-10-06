#!/bin/bash

LIST=$1;
DIRDEST=$2;
TMPFILE="decision"

for doc in $(cat $LIST) ; do
    echo $doc
    FILENAME=$(echo $doc | sed 's/.*\///' | sed 's/.doc//');
    wvText $doc $TMPFILE.txt
    php ccBelgique_parser.php < $TMPFILE.txt > $TMPFILE.xml
    if test -s $TMPFILE.xml; then
	mv $TMPFILE.xml $DIRDEST/$FILENAME.xml
    fi
    rm $TMPFILE.txt $TMPFILE.xml 2> /dev/null
done