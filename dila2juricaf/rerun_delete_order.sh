#!/bin/bash


. ../juricaf2solr/conf/juricaf.conf
DELETED=../data/dila/deleted

    DELETEDJSONDIR=$DELETED"/"$(date +%Y-%m-%d);
    mkdir -p $DELETEDJSONDIR
    find ../data/ -name "*.dat" -exec cat '{}' ';' | sort -u | while read deleteorder ; do
        ID_DILA=$(echo "$deleteorder" | sed 's:.*/::')
        ID_JURICAF=$(php getIdFromSolr.php $ID_DILA)
        # Si le résultat de l'interrogation solr contient un espace c'est une erreur donc on l'affiche
        if echo $ID_JURICAF | grep " "  > /dev/null; then
          echo "Erreur : ID CouchDB non conforme : $ID_JURICAF ("$ID_DILA" <= "$deleteorder")"
        else
          # Le document existe dans solr, on interroge couchdb
          # Si le document n'existe pas dans couchdb
          if curl -s $COUCHDBURL/$ID_JURICAF | grep "not_found" > /dev/null; then
            echo "Erreur : $ID_JURICAF ($ID_DILA) existe dans solr mais pas dans couchdb"
          # S'il existe
          else
          # Suppression
            curl -s $COUCHDBURL/$ID_JURICAF > $DELETEDJSONDIR"/"$ID_JURICAF
            REV=$(cat $DELETEDJSONDIR"/"$ID_JURICAF | sed 's/.*_rev":"//' | sed 's/",".*//')
            if curl -s -X DELETE $COUCHDBURL/$ID_JURICAF?rev=$REV | grep -v $ID_JURICAF > /dev/null; then
              echo "Erreur : $ID_JURICAF ($ID_DILA) existe dans solr et couchdb mais n'a pas pu en être supprimé"
            else
              echo "Info : $ID_JURICAF ($ID_DILA) a été supprimé de couchdb avec succès"
            fi
          fi
        fi
    done

