#!/bin/bash

# Répertoire de travail
cd $(dirname $0);

# Configuration
. ./config/conf.sh
LOCALCOPY=../../ftp/dila/
TO_UPDATE=log/to_detar_update.txt
OLDLOG=log/old.log
NEWLOG=log/new.log
DATE=$(date +%Y-%m-%d-%H-%M)
LOG=log/updates.log
TIMEMEMORY=log/synchro.time

# Anciens fichiers
find $LOCALCOPY -name "*.tar.gz" | xargs stat -c "%Y#%n" > $OLDLOG

touch $TIMEMEMORY
sleep 1
# Synchronisation (non destructive)
lftp ftp://$USER:$PASS@$URL -e "mirror / $LOCALCOPY ; quit"
mkdir -p $LOCALCOPY/../http_dila
wget -q -r --continue --no-parent -P $LOCALCOPY/../http_dila https://echanges.dila.gouv.fr/OPENDATA/CASS/
wget -q -r --continue --no-parent -P $LOCALCOPY/../http_dila https://echanges.dila.gouv.fr/OPENDATA/JADE/
rsync -c $LOCALCOPY/../http_dila/echanges.dila.gouv.fr/OPENDATA/CASS/CASS_* $LOCALCOPY
rsync -c $LOCALCOPY/../http_dila/echanges.dila.gouv.fr/OPENDATA/JADE/JADE_* $LOCALCOPY

# Nouveaux fichiers
find $LOCALCOPY -name "*.tar.gz"  -exec stat -c '%Y#%n' '{}' ';'

#Compare
php compare.php

#begin double patch du jour by habett
find $LOCALCOPY -name "*.tar.gz" -newer $TIMEMEMORY > $TO_UPDATE
#end habett

# Lance l'importation
echo $DATE >> $LOG
if [ -e $TO_UPDATE ]
then
echo -e "\n=====================================================";
echo "|                 Mise à jour Dila                  |" ;
echo "=====================================================";
bash ./detar.sh
mv $OLDLOG log/$DATE-old.log
mv $NEWLOG log/$DATE-new.log
cat $TO_UPDATE >> $LOG
echo "--------------------------------------" >> $LOG
rm $TO_UPDATE
else
echo "pas de mise à jour" >> $LOG
echo "--------------------------------------" >> $LOG
fi
