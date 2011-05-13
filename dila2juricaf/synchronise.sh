#!/bin/bash
. ./config/conf.sh
LOCALCOPY=../../ftp/dila/
TO_UPDATE=log/to_detar_update.txt
OLDLOG=log/old.txt
NEWLOG=log/new.txt
DATE=$(date +%Y-%m-%d-%H-%M)

# Anciens fichiers
find $LOCALCOPY -name "*.tar.gz" | xargs stat -c "%Y#%n" > $OLDLOG

# Synchronisation (non destructive)
lftp ftp://$USER:$PASS@$URL -e "mirror / $LOCALCOPY ; quit"

# Nouveaux fichiers
find $LOCALCOPY -name "*.tar.gz" | xargs stat -c "%Y#%n" > $NEWLOG

# Compare
php compare.php

# Lance l'importation
if [ -e $TO_UPDATE ]
then
./detar.sh
mv $OLDLOG log/$DATE-old.log
mv $NEWLOG log/$DATE-new.log
else
echo "Il n'y a pas de mise à jour";
fi
