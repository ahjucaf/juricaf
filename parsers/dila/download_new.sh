#!/bin/bash

cd $(dirname $0);

LOCALCOPY=../../../ftp/dila/
DETAR_DIR=../../data/dila/temp

mkdir -p $LOCALCOPY

touch /tmp/"dila_time_"$$.time

find $LOCALCOPY -name "*.tar.gz" | xargs stat -c "%Y#%n" > /tmp/"dila_files_"$$.old
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
mkdir -p $LOCALCOPY/../http_dila/echanges.dila.gouv.fr/OPENDATA/INCA/
wget -q -O /dev/stdout https://echanges.dila.gouv.fr/OPENDATA/INCA/ | grep tar.gz | sed 's|.*href="|https://echanges.dila.gouv.fr/OPENDATA/INCA/|'  | sed 's/".*//' > /tmp/INCA.url
wget -q -i /tmp/INCA.url -nc -P $LOCALCOPY/../http_dila/echanges.dila.gouv.fr/OPENDATA/INCA/
rm /tmp/INCA.url
rsync -ac $LOCALCOPY/../http_dila/echanges.dila.gouv.fr/OPENDATA/CASS/ $LOCALCOPY
rsync -ac $LOCALCOPY/../http_dila/echanges.dila.gouv.fr/OPENDATA/JADE/ $LOCALCOPY
rsync -ac $LOCALCOPY/../http_dila/echanges.dila.gouv.fr/OPENDATA/CONSTIT/ $LOCALCOPY
rsync -ac $LOCALCOPY/../http_dila/echanges.dila.gouv.fr/OPENDATA/CAPP/ $LOCALCOPY
rsync -ac $LOCALCOPY/../http_dila/echanges.dila.gouv.fr/OPENDATA/INCA/ $LOCALCOPY

find $LOCALCOPY -name "*.tar.gz"  -exec stat -c '%Y#%n' '{}' ';' > /tmp/"dila_files_"$$.new

diff /tmp/"dila_files_"$$.old /tmp/"dila_files_"$$.new | grep '^> ' | awk -F '#' '{print $2}'  > "/tmp/dila_toupdate_"$$.files
find $LOCALCOPY -name "*.tar.gz" -newer /tmp/"dila_time_"$$.time >> "/tmp/dila_toupdate_"$$.files

DETARD_DATE_DIR=$DETAR_DIR"/"$(date --iso=s)
mkdir -p $DETAR_DIR/../archive
mv $DETAR_DIR/* $DETAR_DIR/../archive
mkdir -p $DETARD_DATE_DIR
sort -u "/tmp/dila_toupdate_"$$.files | while read tarfile ; do
	tar -zxf $tarfile -C $DETARD_DATE_DIR
done

find $DETARD_DATE_DIR -type f

rm /tmp/"dila_files_"$$.old /tmp/"dila_files_"$$.new /tmp/"dila_time_"$$".time" "/tmp/dila_toupdate_"$$.files
