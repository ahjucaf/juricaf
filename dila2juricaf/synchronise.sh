#!/bin/bash

# Répertoire de travail
if [ "$(echo $0 | sed 's|[^/]*$||')" != "./" ] ; then
  cd $(echo $0 | sed 's|[^/]*$||');
fi

# Configuration
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

#Compare
php compare.php

#begin double patch du jour by habett
find $LOCALCOPY -name "*.tar.gz" | grep $(date -d "yesterday" +%Y%m%d) > $TO_UPDATE
#end habett

# Lance l'importation
echo $DATE >> $LOG
if [ -e $TO_UPDATE ]
then
echo -e "\n=====================================================";
echo "|                 Mise à jour Dila                  |" ;
echo "=====================================================";
./detar.sh
mv $OLDLOG log/$DATE-old.log
mv $NEWLOG log/$DATE-new.log
cat $TO_UPDATE >> $LOG
echo "--------------------------------------" >> $LOG
rm $TO_UPDATE
else
echo "pas de mise à jour" >> $LOG
echo "--------------------------------------" >> $LOG
fi
