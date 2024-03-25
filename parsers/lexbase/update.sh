#!/bin/bash

cd $(dirname $0)
. ../config/config.inc

mirror_subdir=$(echo $1 | sed 's/\/*$//')

if echo $LEXBASE_SOURCEURL | grep sftp > /dev/null && ! ssh-keygen -F $(echo $LEXBASE_SOURCEURL | sed 's/sftp:/[/' | sed 's|/||g' | sed 's/:/]:/') > /dev/null ; then
	echo "$LEXBASE_SOURCEURL not found in ssh's known_hosts file";
	exit 3;
fi

if ! test "$mirror_subdir" || echo $mirror_subdir | grep '://' > /dev/null ; then
	echo "subdir of lexbase source needed from $LEXBASE_SOURCEURL";
	echo "possible choices :"
	lftp $LEXBASE_WGET_AUTH  $LEXBASE_SOURCEURL"/" -e "ls; exit;"
	exit 1;
fi

if test "$2" ; then
	lftp $LEXBASE_WGET_AUTH $LEXBASE_SOURCEURL -e "ls $mirror_subdir ; exit;"
	exit 2;
fi

cd $LEXBASE_MIRRORDIR
wget -q -m $LEXBASE_WGET_AUTH $LEXBASE_SOURCEURL/$mirror_subdir
mkdir -p $POOL_DIR/$mirror_subdir
cd $POOL_DIR/$mirror_subdir
lftp $LEXBASE_WGET_AUTH $LEXBASE_SOURCEURL -e "cd $mirror_subdir ; mirror -r . . ; exit;"
cd - > /dev/null
