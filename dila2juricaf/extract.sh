#!/bin/bash
find ../data/dila/ -name '*.xml' | while read fichier ;
do 
	php dila2juricaf.php "$fichier"; 
done
