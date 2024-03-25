#!/bin/bash

file="$1"
id=$(echo $file | sed 's/raw.//')

source ../config/config.inc

mkdir -p $POOL_DIR/cc $POOL_DIR/ca

juridiction=$(jq '.jurisdiction' < $file | sed "s/[^a-z]//ig")
if test "$juridiction" == "Courdecassation" ; then
	num=$( jq .number < $file | sed 's/["\.]//g');
	if ! curl 'https://juricaf.org/recherche/'$num'/facet_pays%3AFrance%2Cfacet_pays_juridiction%3AFrance_%7C_Cour_de_cassation?format=json' | grep 'https://juricaf.org/arret/' ; then
		php parse_jurilibre.php $file > $POOL_DIR/cc/$id".xml"
	fi
elif test "$juridiction" == "Courdappel" ; then
	if ! curl 'https://juricaf.org/recherche/'$num'+tribunal%3A%22'$(jq '.location' < $file | sed 's/ /+/g' | sed "s/'/%27/")'%22+/facet_pays%3AFrance%2Cfacet_pays_juridiction%3AFrance_%7C_Cour_d%27appel?format=json' | grep 'https://juricaf.org/arret/' ; then
		php parse_jurilibre.php $file > $POOL_DIR/ca/$id".xml"
	fi
else
	echo ERROR: $file : juridiction $juridiction non gérée 1>&2
fi
