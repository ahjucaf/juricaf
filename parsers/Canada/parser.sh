#!/bin/bash

source ../config/config.inc

basefile=$(echo $1 | sed 's/\.html//')
if ! grep scc-csc/scc-csc/fr $basefile".url" > /dev/null ; then
	exit 1
fi

lynx -width=100000 -dump $basefile".html" | grep -A 10000 'Contenu de la décision' | tail -n +4 | grep -B 10000 'BUTTON) Continuer' | head -n -2  > $basefile".txt"
grep -B 10000 'Contenu de la décision'  $basefile".html"  | tr -d '\n'  | sed 's/<tr/\n<tr/g' | sed 's/<h3/\n<h3/g' | grep '^<[th]'  | sed 's|</td> *<td class="metadata">  *|@|'  | sed 's/.*<td class="label">//' | sed 's|<h3 class="title">|Titre@|'  | sed 's/ *<br.> */;/g' | sed 's|</[th].*||'  | grep '@' | sed 's/ *; */|/g' | sed 's/@/;/' | sed 's/  *$//'  | sed 's/|$//g' > $basefile".meta"

NUMARRETBRUT=$(grep 'Référence neutre;'  $basefile".meta" | sed 's/^[^;]*;//')
NUMARRET=$(echo $NUMARRETBRUT | sed 's/ //g')
if ! test -s $basefile".canlii" && test "$CANLII_APIKEY" ; then
        ls $basefile".canlii"
	sleep 15;
	curl -s "https://api.canlii.org/v1/caseBrowse/fr/csc-scc/"$(echo $NUMARRET | tr '[:upper:]' '[:lower:]')"/?api_key="$CANLII_APIKEY > $basefile".canlii"
	if grep "error" $basefile".canlii" > /dev/null ; then
		rm $basefile".canlii"
	fi
fi

echo '<?xml version="1.0" encoding="UTF-8"?>'
echo "<DOCUMENT>"
DECISIONDATE=$(grep 'Date;'  $basefile".meta" | sed 's/^[^;]*;//')
echo "<DATE_ARRET>"$DECISIONDATE"</DATE_ARRET>"
echo "<JURIDICTION>Cour suprême</JURIDICTION>"
echo "<NUM_ARRET>"$NUMARRET"</NUM_ARRET>"
echo "<PAYS>Canada</PAYS>"
echo "<TEXTE_ARRET>"
cat $basefile".txt"
echo "</TEXTE_ARRET>"
DEMANDEUR=$(jq .title $basefile".canlii" | sed 's/"//g' | sed 's/ c\. .*//')
DEFENSEUR=$(jq .title $basefile".canlii" | sed 's/"//g' | sed 's/.* c\. //')
if test "$DEMANDEUR" || test "$DEFENSEUR"; then
    echo "<PARTIES>"
    if test "$DEMANDEUR"; then
    echo "<DEMANDEURS><DEMANDEUR>$DEMANDEUR</DEMANDEUR></DEMANDEURS>"
    fi
    if test "$DEFENSEUR"; then
    echo "<DEFENDEURS><DEFENDEUR>$DEFENSEUR</DEFENDEUR></DEFENDEURS>"
    fi
    echo "</PARTIES>"
fi
SUJETS=$(grep 'Sujets;'  $basefile".meta" | sed 's/^[^;]*;//' | sed 's/|/ — /g';)
CANLII=$(jq .topics $basefile".canlii" | sed 's/"//g')
if test "$CANLII"; then
    if test "$SUJETS"; then
        SUJETS=$SUJETS" — "$CANLII
    else
        SUJETS=$CANLII
    fi
fi
echo "<ANALYSES>"
echo "<ANALYSE>"
if test "$SUJETS"; then
  echo "<TITRE_PRINCIPAL>"$SUJETS"</TITRE_PRINCIPAL>"
fi
KEYWORDS=$(jq .keywords  $basefile".canlii" | sed 's/"//g')
if test "$KEYWORDS"; then
  echo "<KEYWORDS>"$KEYWORDS"</KEYWORDS>"
fi
echo "</ANALYSE>"
echo "</ANALYSES>"
echo "<JUGES>"
grep 'Juges;'  $basefile".meta" | sed 's/^[^;]*;//' | sed 's/|/\n/g' | awk -F ',' '{print "<JUGE>"$2" "$1"</JUGE>"}' | sed 's/> */>/'
echo "</JUGES>"
DATEFR=$(LC_ALL=fr_FR date --date=$DECISIONDATE "+%d %B %Y" | iconv -f iso88591)
TITRE=$(grep 'Titre;'  $basefile".meta" | sed 's/^[^;]*;//')
echo "<TITRE>Canada, Cour suprême, $DATEFR, $TITRE, $NUMARRETBRUT</TITRE>"
echo -n "<SOURCE>"
cat $basefile".url" | tr -d '\n'
echo "</SOURCE>";
echo "<TYPE>arret</TYPE>"
echo "<FONDS_DOCUMENTAIRE>CSCLexum</FONDS_DOCUMENTAIRE>"
echo "<ALIMENTATION_TYPE>parsers/Canada</ALIMENTATION_TYPE>"
echo "</DOCUMENT>"
