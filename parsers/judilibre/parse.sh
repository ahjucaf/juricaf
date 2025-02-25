#!/bin/bash

file="$1"
id=$(basename $file | sed 's/.json//')

source ../config/config.inc

mkdir -p $POOL_DIR/cc $POOL_DIR/ca

juridiction=$(jq '.jurisdiction' < $file | sed "s/[^a-z]//ig")
num=$( jq .number < $file | sed 's/["\.\+]//g' | sed 's|[/'"'"' ]|+|g');
if test "$juridiction" == "Courdecassation" ; then
	numres=$(curl -s 'https://juricaf.org/recherche/num_arret:%22'$num'%22/facet_pays%3AFrance%2Cfacet_pays_juridiction%3AFrance_%7C_Cour_de_cassation?format=json' | jq .nb_resultat)
	if test "$numres" -eq 0; then
		php parse_jurilibre.php $file > $POOL_DIR/cc/$id".xml"
	else
		echo "WARNING: duplicates/error $num (cour de cass)" 1>&2
	fi
elif test "$juridiction" == "Courdappel" || test "$juridiction" == "Tribunaljudiciaire"; then
	tribunal=$(jq '.location' < $file | sed 's/ /+/g')
	if test "$juridiction" == "Tribunaljudiciaire" ; then
		numres=$(curl -s 'https://juricaf.org/recherche/num_arret:%22'$num'%22+tribunal%3A%22'$(echo $tribunal | sed "s/'/%27/g")'%22/facet_pays%3AFrance%2Cfacet_pays_juridiction%3AFrance_%7C_Tribunal_judiciaire?format=json' | jq .nb_resultat)
	else
		numres=$(curl -s 'https://juricaf.org/recherche/num_arret:%22'$num'%22+tribunal%3A%22'$(echo $tribunal | sed "s/'/%27/g")'%22/facet_pays%3AFrance%2Cfacet_pays_juridiction%3AFrance_%7C_Cour_d%27appel?format=json' | jq .nb_resultat)
	fi
	if test "$numres" -eq 0; then
		php parse_jurilibre.php $file > $POOL_DIR/ca/$id".xml"
	else
		echo "WARNING: duplicates/error $tribunal $num" 1>&2
	fi
else
	echo ERROR: $file : juridiction $juridiction non gérée : 1>&2
	head -c 50 $file 1>&2
fi
