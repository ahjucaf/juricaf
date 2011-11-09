#!/bin/bash
TOPROCESS=erreurs.log
LOG=global.log

if [ ! -e $LOG ] ; then exit 1; fi

cat $LOG | grep "Erreur" > $TOPROCESS ;

if [ -e date_id.txt ] ; then rm date_id.txt ; fi

cat $TOPROCESS | while read line ;
do
  DATE=$(echo $line | sed 's: .*::' | sed 's:.*_::' | sed 's:-.*::') ;
  ID_DILA=$(echo $line | sed 's:.*/::' | sed 's:\..*::') ;
  echo "$DATE $ID_DILA" >> date_id.txt;
done

cat header.html ;

cat date_id.txt | while read line ;
do
  php checkNresume.php $line ;
done

cat footer.html ;