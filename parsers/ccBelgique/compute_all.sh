#!/bin/bash

mydir=$(echo $0 | sed 's/[^\/]*$//')
if test $mydir ; then cd $mydir ; fi

. config/config.sh

php ccBelgique_mail.php $DOCDIR all | sed 's/$/ : mail downloaded/';

find $DOCDIR -name '*doc' > $LISTFILE

bash ccBelgique_parse_all.sh $LISTFILE $POOL

