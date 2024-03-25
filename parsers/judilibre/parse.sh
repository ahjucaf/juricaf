#!/bin/bash

file="$1"
id=$(echo $file | sed 's/raw.//')

source ../config/config.inc

mkdir -p $POOL_DIR/cc $POOL_DIR/ca

juridiction=$(jq '.jurisdiction' < $file | sed "s/[^a-z]//ig")
if test "$juridiction" == "Courdecassation" ; then
	num=$( jq .number < $file | sed 's/["\.]//g');
	if ! curl 'https://juricaf.org/recherche/'$num'/facet_pays%3AFrance%2Cfacet_pays_juridiction%3AFrance_|_Cour_de_cassation?format=json' | grep 'https://juricaf.org/arret/' ; then
		php parse_jurilibre.php $file > $POOL_DIR/cc/$id".xml"
	fi
elif test "$juridiction" == "Courdappel" ; then
	php parse_courdappel.php $file > $POOL_DIR/ca/$id".xml"
else
	echo ERROR: $file : juridiction $juridiction non gérée 1>&2
fi
