#!/bin/bash

SOLRURL=$(grep solr_url_db conf/config.php | grep -v global | sed 's/.*= "//' | sed 's/".*//';)
curl -H "content-type: text/xml" -d '<optimize/>' $SOLRURL/update  > /tmp/optimize.log
