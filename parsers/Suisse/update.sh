#!/bin/bash

cd $(dirname $0)

. ../config/config.inc

mkdir -p tmp

if ! test -d pool ; then
	ln -s $POOL_DIR pool
fi

perl neoget.pl
