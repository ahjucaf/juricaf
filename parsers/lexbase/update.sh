#!/bin/bash

cd $(dirname $0)
. ../config/config.inc

mirror_subdir=$(echo $1 | sed 's/\/*$//')
if ! test "$mirror_subdir" || echo $mirror_subdir | grep '://' > /dev/null ; then
	echo "subdir of lexbase source needed from $LEXBASE_SOURCEURL";
	exit 1;
fi

cd $LEXBASE_MIRRORDIR
wget -m $LEXBASE_WGET_AUTH $LEXBASE_SOURCEURL/$mirror_subdir
mkdir -p $POOL_DIR/$mirror_subdir
rsync -a $(echo $LEXBASE_SOURCEURL | sed 's/^[a-z]*:\/\///' )/$mirror_subdir/ $POOL_DIR/$mirror_subdir
