curl -s https://juricaf.org/sitemap > web/sitemap.xml.tmp
cat web/sitemap.xml.tmp | grep loc | sed -r 's|<[^<>]*>||g' |sed 's/ //g' | while read url; do XMLFILENAME=$(echo -n $url | sed -r 's|.*/||'); curl $url > web/sitemap_$XMLFILENAME.xml.tmp; mv web/sitemap_$XMLFILENAME.xml{.tmp,}; done;
sed -ri 's|sitemap/([^\/]+)</loc>|sitemap_\1.xml</loc>|' web/sitemap.xml.tmp
mv web/sitemap.xml{.tmp,}
