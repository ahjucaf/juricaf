#!/bin/bash

rm -r tmp
rm -r xmls
touch all_urls.txt
mkdir xmls
mkdir -p tmp/pages
mkdir -p tmp/home_pages
cd tmp/
touch urls.txt
cd ..
php homes_downloader.php
php url_listing.php
php pages_downloader.php
nbr_pages=$(ls -A "tmp/pages/" | wc -l)
echo $nbr_page
if [ $nbr_pages != '0' ];then

  for page in tmp/pages/* ; do
    php parser_htmltoxml.php $page
  done

fi
