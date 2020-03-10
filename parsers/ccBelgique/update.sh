#!/bin/bash

mydir=$(echo $0 | sed 's/[^\/]*$//')
if test $mydir ; then cd $mydir ; fi

. config/config.sh

bash juridat_list.sh | while read file ; do
    bash ccBelgique_parser.sh $file;
done
