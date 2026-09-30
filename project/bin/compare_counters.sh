#!/bin/bash

curl -s "https://juricaf.org/recherche/type%3Aarret" | grep option | sed 's/ *<[^>]*> */\n/g'  | grep nbsp | sed 's/^.. //'  | sed 's/^⚪ //' | head -n 50 | grep -v ^Cour | sed 's/&nbsp;/ /'  | sort -u > /tmp/pays_counters_solr.$$.list
echo >> /tmp/pays_counters_solr.$$.list
curl -s "https://juricaf.org/recherche/type%3Aarret" | grep "résultats trouvés"  | sed 's/ *<[^>]*> *//g' | sed 's/[^0-9]//g' >> /tmp/pays_counters_solr.$$.list
curl -s https://juricaf.org/ | grep list-group-item | sed 's/<[^>]*>//g'  | sed 's/ *.nbsp;//' | sort -u > /tmp/pays_counters_home.$$.list
echo >> /tmp/pays_counters_home.$$.list
curl -s https://juricaf.org | grep "décisions par pays" | sed 's/ *<[^>]*> *//g' | sed 's/[^0-9]//g' >> /tmp/pays_counters_home.$$.list
diff /tmp/pays_counters_home.$$.list /tmp/pays_counters_solr.$$.list && echo Compteurs des arrêts cohérents
