#!/bin/bash

mydir=$(echo $0 | sed 's/[^\/]*$//')
if test $mydir ; then cd $mydir ; fi

. config/config.sh

bash juridat_list.sh 2010-01-01
grep -l 'dec_juridiction>1<' documents/*.xml  | while read xml ; do bash ccBelgique_parser.sh $xml || echo "ERROR $xml ^ "; done
