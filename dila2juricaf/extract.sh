#!/bin/bash
find ../data/dila/ -name '*.xml' > to_process.txt
for fichier in $(cat to_process.txt); do php dila2juricaf.php "$fichier"; done
rm to_process.txt
