#!/bin/bash

. ../config/config.inc

cd $(dirname $0)
mkdir -p tmp

if ! test -d pool ; then
	ln -s $POOL_DIR pool
fi

perl neoget.pl
