#!/bin/bash
# Usage
# ./detar.sh 1 pour tout extraire
# ./detar.sh pour extraire uniquement les nouveaux documents

# Répertoire de travail
if [ "$(echo $0 | sed 's|[^/]*$||')" != "./" ] ; then
  cd $(echo $0 | sed 's|[^/]*$||');
  echo "Positionnement dans le dossier de travail"
fi

. ../juricaf2solr/conf/juricaf.conf
FTP=../../ftp/dila
DATA=../data/dila/temp
CONVERTED=../data/dila/converted
ARCHIVE=../data/dila/archive
DELETED=../data/dila/deleted
POOL=../data/pool
TOPROCESS=log/to_detar_update.txt
LOCK=/tmp/import.sh.lock
DIRLOGSUPP=log/suppression
GLOBALDELETELOG=log/suppression/global.log

if test "$1" ; then
  echo "Extraire TOUS les documents dila ? : veuillez confirmer (y/n)"
  read AA;
  TOPROCESS=log/to_detar_all.txt
  > $GLOBALDELETELOG
  find $FTP/ -name "*.tar.gz" | xargs stat -c "%Y#%n" > $TOPROCESS
  php sort.php
fi

START=$(date '+%d-%m-%Y-%H:%M:%S') ;

for fichier in $(cat $TOPROCESS);
  do
  echo "Décompression de $fichier" ;
  tar -zxvf "$FTP/$fichier" -C "$DATA" ;

  echo "Conversion des fichiers de $fichier" ;
  ./extract.sh ;

  if find $DATA/ -name "*.dat" > /dev/null; then
    echo "Sauvegarde des ordres de suppression de $fichier" ;
    NOTGZ=$(echo $fichier | sed "s/\(.*\)\(ahjucaf_.*\).tar.gz/\2/gi");
    find $DATA/ -name "*.dat" | xargs cat >> $DIRLOGSUPP/$NOTGZ.dat ;
    if test -s $DIRLOGSUPP/$NOTGZ.dat ; then
      echo "Application des ordres de suppression de $fichier" ;
      for fichier_suppr in $(cat $DIRLOGSUPP/$NOTGZ.dat);
      do
        if [ -e $ARCHIVE/$fichier_suppr.xml ] ; then
          DOC_ID=$(php dila2juricaf.php $ARCHIVE/$fichier_suppr.xml 1)
          # évolution : interroger solr directement
          if echo $DOC_ID | grep " "  > /dev/null; then
            echo "$NOTGZ Erreur : L'identifiant de $ARCHIVE/$fichier_suppr.xml n'a pas pu être déterminé correctement ($DOC_ID)" >> $GLOBALDELETELOG
          else
            if [ ! -d $DELETED/$NOTGZ ] ; then
              mkdir $DELETED/$NOTGZ ;
            fi
            cp -f $ARCHIVE/$fichier_suppr.xml $DELETED/$NOTGZ/
            rm $ARCHIVE/$fichier_suppr.xml
            echo "$DOC_ID a été sauvegardé dans $DELETED/$NOTGZ et supprimé des archives Dila avec succès ($ARCHIVE/$fichier_suppr.xml)" >> $GLOBALDELETELOG
            if echo $(curl -s GET $COUCHDBURL/$DOC_ID | grep "not_found") > /dev/null; then
              echo "$NOTGZ Erreur : $DOC_ID existait dans les archives mais non trouvé dans couchdb ($ARCHIVE/$fichier_suppr.xml)" >> $GLOBALDELETELOG
            else
              if echo $(curl -s DELETE $COUCHDBURL/$DOC_ID?rev=$(curl --stderr /dev/null $COUCHDBURL/$DOC_ID | sed 's/.*_rev":"//' | sed 's/",".*//' 2> /dev/null) | grep -v $DOC_ID) > /dev/null; then
                echo "$NOTGZ Erreur : $DOC_ID existe dans couchdb mais n'a pas pu en être supprimé" >> $GLOBALDELETELOG
              else
                echo "$DOC_ID a été supprimé de couchdb avec succès" >> $GLOBALDELETELOG
              fi
            fi
          fi
        else
          echo "$NOTGZ Erreur : $ARCHIVE/$fichier_suppr.xml n'existe pas" >> $GLOBALDELETELOG
        fi
      done
      if cat $GLOBALDELETELOG | grep "$NOTGZ Erreur" > /dev/null ; then
        echo "Des vérifications manuelles sont nécessaires :"
        cat $GLOBALDELETELOG | grep "$NOTGZ Erreur" ;
      else
        echo "Avec succès"
      fi
    else
      echo "Le fichier d'ordres de suppression de $fichier est vide"
    fi
  else
    echo "Aucun ordre de suppression dans $fichier"
  fi

  echo "Archivage des fichiers originaux de $fichier" ;
  EXTRACTEDDIR=$(echo $fichier | sed "s/\(.*\)ahjucaf_\(.*\).tar.gz/\2/g");
  if [ -e $DATA/$EXTRACTEDDIR ]
    then
    cp -R -f $DATA/$EXTRACTEDDIR/* $ARCHIVE/
    rm -R $DATA/*
  else
    cp -R -f $DATA/* $ARCHIVE/
    rm -R $DATA/*
  fi
done

# Vérif indexation en cours
if [ -e $LOCK ]
then
  if ! ps --pid $(cat $LOCK) > /dev/null ; then
    if [ -e $POOL/France ] ; then
      rm -r $POOL/France
    fi
    mv $CONVERTED* $POOL/
    echo "Les fichiers convertis ont été placés dans le pool, le lock a été supprimé" ;
    rm lock
  else
    echo "Import tiers en cours : les fichiers convertis restent dans $CONVERTED" ;
  fi
else
  if [ -e $POOL/France ] ; then
    rm -r $POOL/France
  fi
  mv $CONVERTED* $POOL/
  echo "Les fichiers convertis ont été placés dans le pool" ;
fi

END=$(date '+%d-%m-%Y-%H:%M:%S') ;

echo "Début : $START , Fin : $END" ;
