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
mkdir -p $LOCALCOPY
find $LOCALCOPY -name "*.tar.gz" | xargs stat -c "%Y#%n" > $OLDLOG

touch $TIMEMEMORY
sleep 1
# Synchronisation (non destructive)
# lftp ftp://$USER:$PASS@$URL -e "mirror / $LOCALCOPY ; quit"
mkdir -p $LOCALCOPY/../http_dila/echanges.dila.gouv.fr/OPENDATA/CASS/
wget -q -O /dev/stdout https://echanges.dila.gouv.fr/OPENDATA/CASS/ | grep tar.gz | sed 's|.*href="|https://echanges.dila.gouv.fr/OPENDATA/CASS/|'  | sed 's/".*//' > /tmp/CASS.url
wget -q -i /tmp/CASS.url -nc -P $LOCALCOPY/../http_dila/echanges.dila.gouv.fr/OPENDATA/CASS/
rm /tmp/CASS.url
mkdir -p $LOCALCOPY/../http_dila/echanges.dila.gouv.fr/OPENDATA/JADE/
wget -q -O /dev/stdout https://echanges.dila.gouv.fr/OPENDATA/JADE/ | grep tar.gz | sed 's|.*href="|https://echanges.dila.gouv.fr/OPENDATA/JADE/|'  | sed 's/".*//' > /tmp/JADE.url
wget -q -i /tmp/JADE.url -nc -P $LOCALCOPY/../http_dila/echanges.dila.gouv.fr/OPENDATA/JADE/
rm /tmp/JADE.url
mkdir -p $LOCALCOPY/../http_dila/echanges.dila.gouv.fr/OPENDATA/CONSTIT/
wget -q -O /dev/stdout https://echanges.dila.gouv.fr/OPENDATA/CONSTIT/ | grep tar.gz | sed 's|.*href="|https://echanges.dila.gouv.fr/OPENDATA/CONSTIT/|'  | sed 's/".*//' > /tmp/CONSTIT.url
wget -q -i /tmp/CONSTIT.url -nc -P $LOCALCOPY/../http_dila/echanges.dila.gouv.fr/OPENDATA/CONSTIT/
rm /tmp/CONSTIT.url
mkdir -p $LOCALCOPY/../http_dila/echanges.dila.gouv.fr/OPENDATA/CAPP/
wget -q -O /dev/stdout https://echanges.dila.gouv.fr/OPENDATA/CAPP/ | grep tar.gz | sed 's|.*href="|https://echanges.dila.gouv.fr/OPENDATA/CAPP/|'  | sed 's/".*//' > /tmp/CAPP.url
wget -q -i /tmp/CAPP.url -nc -P $LOCALCOPY/../http_dila/echanges.dila.gouv.fr/OPENDATA/CAPP/
rm /tmp/CAPP.url
rsync -c $LOCALCOPY/../http_dila/echanges.dila.gouv.fr/OPENDATA/CASS/CASS_* $LOCALCOPY
rsync -c $LOCALCOPY/../http_dila/echanges.dila.gouv.fr/OPENDATA/JADE/JADE_* $LOCALCOPY
rsync -c $LOCALCOPY/../http_dila/echanges.dila.gouv.fr/OPENDATA/CONSTIT/CONSTIT_* $LOCALCOPY
rsync -c $LOCALCOPY/../http_dila/echanges.dila.gouv.fr/OPENDATA/CAPP/CAPP_* $LOCALCOPY

# Nouveaux fichiers
find $LOCALCOPY -name "*.tar.gz"  -exec stat -c '%Y#%n' '{}' ';' > $NEWLOG

#Compare
php compare.php

#begin double patch du jour by habett
find $LOCALCOPY -name "*.tar.gz" -newer $TIMEMEMORY >> $TO_UPDATE
#end habett

# Lance l'importation
echo $DATE >> $LOG
if [ -e $TO_UPDATE ]
then
echo -e "\n=====================================================";
echo "|                 Mise à jour Dila                  |" ;
echo "=====================================================";
bash ./detar.sh $TO_UPDATE
mv $OLDLOG log/$DATE-old.log
mv $NEWLOG log/$DATE-new.log
cat $TO_UPDATE >> $LOG
echo "--------------------------------------" >> $LOG
rm $TO_UPDATE
else
echo "pas de mise à jour" >> $LOG
echo "--------------------------------------" >> $LOG
fi
