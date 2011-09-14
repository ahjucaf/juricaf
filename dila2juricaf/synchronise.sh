#!/bin/bash
. ./config/conf.sh
LOCALCOPY=../../ftp/dila/
TO_UPDATE=log/to_detar_update.txt
OLDLOG=log/old.log
NEWLOG=log/new.log
DATE=$(date +%Y-%m-%d-%H-%M)
LOG=log/updates.log

# Anciens fichiers
find $LOCALCOPY -name "*.tar.gz" | xargs stat -c "%Y#%n" > $OLDLOG

# Synchronisation (non destructive)
lftp ftp://$USER:$PASS@$URL -e "mirror / $LOCALCOPY ; quit"

# Nouveaux fichiers
find $LOCALCOPY -name "*.tar.gz" | xargs stat -c "%Y#%n" > $NEWLOG

# Compare
php compare.php

# Lance l'importation
echo $DATE >> $LOG
if [ -e $TO_UPDATE ]
then
./detar.sh
mv $OLDLOG log/$DATE-old.log
mv $NEWLOG log/$DATE-new.log
echo $DATE >> $LOG
cat $TO_UPDATE >> $LOG
echo "--------------------------------------" >> $LOG
rm $TO_UPDATE
else
echo "pas de mise à jour" >> $LOG
echo "--------------------------------------" >> $LOG
fi
