#!/bin/bash

mydir=$(echo $0 | sed 's/[^\/]*$//')
if test $mydir ; then cd $mydir ; fi

. config/config.sh

php ccBelgique_mail.php $DOCDIR > $LISTFILE;

bash ccBelgique_parse_all.sh $LISTFILE $POOL

