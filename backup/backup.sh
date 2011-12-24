#!/bin/bash
cd $(echo $0 | sed 's|[^/]*$||') ;
DATA=$PWD ;
ANNEE=$(date '+%Y') ;
JOUR=$(date '+%A') ;
JOURMOIS=$(date '+%d') ;
NUMJOUR=$(date '+%u') ;
JOUR_SAUV_SEM=6 ;
DER_SEMAINE_TXT=semaine.txt;
DER_MOIS_TXT=mois.txt;
VCDB=$(couchdb -V | grep CouchDB | sed 's:[^0-9\.]::g');
. ../juricaf2solr/conf/juricaf.conf

echo "=====================================================";
echo "|                    Sauvegarde                     |";
echo "=====================================================";

echo "=====================================================";
echo "Désactivation des sites";
echo "=====================================================";
START=$(date '+%H:%M:%S') ;
echo $START;
echo "Version de développement :";
cd ../project/
php symfony project:disable prod
if [ -d ../../dev/project/ ] ; then
  echo "Version de production :";
  cd ../../dev/project/
  php symfony project:disable prod
fi

echo "=====================================================";
echo "Utilisation de l'espace disque avant sauvegarde";
echo "=====================================================";
df -h

if [ ! -d $DATA/data ] ; then
  mkdir -p $DATA/data ;
fi

cd "/var/lib/couchdb/$VCDB/"
echo "=====================================================";
echo "Sauvegarde de la base Couchdb";
echo "=====================================================";
rsync -avh --stats ahjucaf.couch $DATA/data/couchdb/ ;
echo "=====================================================";
echo "Sauvegarde des vues Couchdb";
echo "=====================================================";
rsync -avh --stats .ahjucaf_design/ $DATA/data/couchdb/design/ ;

cd "/var/lib/tomcat6/solr/"
echo "=====================================================";
echo "Sauvegarde de la base SolR";
echo "=====================================================";
if [ ! -d $DATA/data/solr ] ; then
  mkdir -p $DATA/data/solr ;
fi
rsync -avrh --stats data/ $DATA/data/solr/data/ ;

cd $DATA

echo "=====================================================";
echo "Réactivation des sites";
echo "=====================================================";

echo "Version de développement :";
cd ../project/
php symfony project:enable prod
if [ -d ../../dev/project/ ] ; then
  echo "Version de production :";
  cd ../../dev/project/
  php symfony project:enable prod
fi
DISPO=$(date '+%H:%M:%S') ;
echo $DISPO;

cd $DATA

if [ -d ../../svn/ahjucaf ] ; then
  echo "=====================================================";
  echo "Sauvegarde du SVN";
  echo "=====================================================";
  mkdir data/svn ;
  svnadmin -q dump ../../svn/ahjucaf > data/svn/ahjucaf.svndump
  if [ $? -eq 0 ] ; then
    echo "Ok : dump sauvegardé";
  fi
fi

if [ -d /var/lib/redmine/default/files ] ; then
  echo "=====================================================";
  echo "Sauvegarde de Redmine";
  echo "=====================================================";
  cd $DATA
  mkdir -p data/redmine/db ;
  /usr/bin/mysqldump -u root -p$MYSQLROOTPASS redmine_default | gzip > data/redmine/db/redmine_sql.gz ;
  if [ $? -eq 0 ] ; then
    echo "Ok : base mysql sauvegardé";
  fi
  cp -R -p /var/lib/redmine/default/files data/redmine/ ;
  if [ $? -eq 0 ] ; then
    echo "Ok : fichiers sauvegardés";
  fi
fi

echo "=====================================================";
echo "Compression et archivage";
echo "=====================================================";
if [ -e data.tar.gz ] ; then
 rm data.tar.gz ;
fi
tar -czf data.tar.gz data ;
if [ $? -eq 0 ] ; then
  echo "Ok : data.tar.gz créé";
  ls -lh data.tar.gz ;
fi

echo "=====================================================";
echo "Archivage journalier";
echo "=====================================================";
if [ ! -d archive/journaliere/$JOUR ] ; then
  mkdir -p archive/journaliere/$JOUR ;
fi
cp -p data.tar.gz archive/journaliere/$JOUR/ ;
if [ $? -eq 0 ] ; then
  echo "Ok : Sauvegardé dans archive/journaliere/$JOUR";
fi

if [ $NUMJOUR -eq $JOUR_SAUV_SEM ] ; then
  echo "=====================================================";
  echo "Archivage hebdomadaire";
  echo "=====================================================";

  if [ -e $DER_SEMAINE_TXT ] ; then
    declare -i DER_SEMAINE=$(cat $DER_SEMAINE_TXT | sed 's:[^0-9]::g');
    if [ $DER_SEMAINE -eq 4 ] ; then
      declare -i DER_SEMAINE=1;
      echo "$DER_SEMAINE" > $DER_SEMAINE_TXT;
    else
      declare -i DER_SEMAINE=$DER_SEMAINE+1;
      echo "$DER_SEMAINE" > $DER_SEMAINE_TXT;
    fi
  else
    declare -i DER_SEMAINE=1;
    echo "$DER_SEMAINE" > $DER_SEMAINE_TXT;
  fi

  if [ ! -d archive/semaine/$DER_SEMAINE ] ; then
    mkdir -p archive/semaine/$DER_SEMAINE ;
  fi
  cp -p data.tar.gz archive/semaine/$DER_SEMAINE/ ;
  if [ $? -eq 0 ] ; then
    echo "Ok : Sauvegardé dans archive/semaine/$DER_SEMAINE";
  fi
fi

if [ $JOURMOIS -eq 01 ] ; then
  echo "=====================================================";
  echo "Archivage mensuel";
  echo "=====================================================";

  if [ -e $DER_MOIS_TXT ] ; then
    declare -i DER_MOIS=$(cat $DER_MOIS_TXT | sed 's:[^0-9]::g');
    if [ $DER_MOIS -eq 3 ] ; then
      declare -i DER_MOIS=1;
      echo "$DER_MOIS" > $DER_MOIS_TXT;
    else
      declare -i DER_MOIS=$DER_MOIS+1;
      echo "$DER_MOIS" > $DER_MOIS_TXT;
    fi
  else
    declare -i DER_MOIS=1;
    echo "$DER_MOIS" > $DER_MOIS_TXT;
  fi

  if [ ! -d archive/mensuelle/$DER_MOIS ] ; then
    mkdir -p archive/mensuelle/$DER_MOIS ;
  fi
  cp -p data.tar.gz archive/mensuelle/$DER_MOIS/ ;
  if [ $? -eq 0 ] ; then
    echo "Ok : Sauvegardé dans archive/mensuelle/$DER_MOIS";
  fi
fi

if [ ! -d archive/annuelle/$ANNEE ] ; then
  echo "=====================================================";
  echo "Archivage annuel";
  echo "=====================================================";
  mkdir -p archive/annuelle/$ANNEE ;
  cp -p data.tar.gz archive/annuelle/$ANNEE/ ;
  if [ $? -eq 0 ] ; then
    echo "Ok : Sauvegardé dans archive/$ANNEE";
  fi
fi

echo "=====================================================";
echo "Suppression des fichiers temporaires";
echo "=====================================================";
rm data.tar.gz ;
if [ $? -eq 0 ] ; then
  echo "Ok : data.tar.gz supprimé";
fi
rm -R data/* ;
if [ $? -eq 0 ] ; then
  echo "Ok : Contenu de data/ supprimé";
fi

echo "=====================================================";
echo "Utilisation de l'espace disque après sauvegarde";
echo "=====================================================";
df -h
echo "=====================================================";
echo "Espace disque utilisé par les sauvegardes :";
echo "=====================================================";
du -sh
END=$(date '+%H:%M:%S') ;
echo "=====================================================";
echo "Début : $START > Réactivation des sites : $DISPO > Terminé : $END";
echo "=====================================================";



