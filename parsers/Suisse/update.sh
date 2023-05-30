#!/bin/bash

cd $(dirname $0)
mkdir -p tmp

if ! test -d pool ; then
	echo "pool directory missing"
	exit 1
fi

perl neoget.pl

