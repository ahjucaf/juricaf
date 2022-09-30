#!/bin/bash

ls *txt | sed 's/.txt//' | while read title ; do 
	echo $title | awk -F '_' '{print "<?xml version=\"1.0\" encoding=\"UTF-8\"?><DOCUMENT><PAYS>"$1"</PAYS><JURIDICTION>"$2"</JURIDICTION><FORMATION>"$3"</FORMATION><DATE_ARRET>"$4"</DATE_ARRET><NUM_ARRET>"$5"</NUM_ARRET><TEXTE_ARRET>"}' | tr -d '\n' > "$title"".xml"
	cat "$title"".txt" >> "$title"".xml"
	echo "</TEXTE_ARRET></DOCUMENT>" >> "$title"".xml"
done
