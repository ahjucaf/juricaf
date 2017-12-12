#!/bin/bash


. ../juricaf2solr/conf/juricaf.conf
DELETED=../data/dila/deleted

    DELETEDJSONDIR=$DELETED"/"$(date +%Y-%m-%d);
    mkdir -p $DELETEDJSONDIR
    find ../data/ -name "*.dat" -exec cat '{}' ';' | sort -u | while read linetobedeleted ; do
        ID_DILA=$(echo "$linetobedeleted" | sed 's:.*/::')
        ID_JURICAF=$(php getIdFromSolr.php $ID_DILA)
        # Si le résultat de l'interrogation solr contient un espace c'est une erreur donc on l'affiche
        if echo $ID_JURICAF | grep " "  > /dev/null; then
          echo "Erreur : ID CouchDB non conforme : $ID_JURICAF ("$ID_DILA" <= "$linetobedeleted")"
        else
          # Le document existe dans solr, on interroge couchdb
          DOC_COUCHDB=$(curl -s GET $COUCHDBURL/$ID_JURICAF) ;
          # Si le document n'existe pas dans couchdb
          if echo $DOC_COUCHDB | grep "not_found" > /dev/null; then
            echo "Erreur : $ID_JURICAF ($ID_DILA) existe dans solr mais pas dans couchdb"
          # S'il existe
          else
          # Suppression
            curl --stderr /dev/null $COUCHDBURL/$ID_JURICAF > $DELETEDJSONDIR"/"$ID_JURICAF
            REV=$(cat $DELETEDJSONDIR"/"$ID_JURICAF | sed 's/.*_rev":"//' | sed 's/",".*//' 2> /dev/null)
            if curl -X DELETE $COUCHDBURL/$ID_JURICAF?rev=$REV | grep -v $ID_JURICAF > /dev/null; then
              echo "Erreur : $ID_JURICAF ($ID_DILA) existe dans solr et couchdb mais n'a pas pu en être supprimé"
            else
              echo "Info : $ID_JURICAF ($ID_DILA) a été supprimé de couchdb avec succès"
            fi
          fi
        fi
    done

