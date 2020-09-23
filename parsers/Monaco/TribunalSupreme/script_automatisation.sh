#!/bin/bash

rm -r tmp
mkdir -p tmp/pages
mkdir -p tmp/home_pages
cd tmp/
touch urls.txt
cd ..
php homes_downloader.php
php url_listing.php
php pages_downloader.php

for page in tmp/pages/* ; do
  php parser_htmltoxml.php $page
done
