#!/bin/bash

cd $(dirname $0)
. ../config/config.inc

mirror_subdir=$1

if ! test "$mirror_subdir"; then
	echo "subdir of lexbase source needed"
	exit 1;
fi

cd $LEXBASE_MIRRORDIR
wget -m $LEXBASE_WGET_AUTH $LEXBASE_SOURCEURL/$mirror_subdir
rsync -a $(echo $LEXBASE_SOURCEURL | sed 's/^[a-z]*:\/\///' )/$mirror_subdir $POOL_DIR/$mirror_subdir
