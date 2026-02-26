curl -s https://juricaf.org/plandusite > web/sitemapsecret/sitemap.xml.tmp
cat web/sitemapsecret/sitemap.xml.tmp | grep loc | sed -r 's|<[^<>]*>||g' |sed 's/ //g' | while read url; do XMLFILENAME=$(echo -n $url | sed -r 's|.*/||'); curl -s $url > web/sitemapsecret/sitemap_$XMLFILENAME.xml.tmp; mv web/sitemapsecret/sitemap_$XMLFILENAME.xml{.tmp,}; done;
sed -ri 's|plandusite/([^\/]+)</loc>|sitemapsecret/sitemap_\1.xml</loc>|' web/sitemapsecret/sitemap.xml.tmp
mv web/sitemapsecret/sitemap.xml{.tmp,}
