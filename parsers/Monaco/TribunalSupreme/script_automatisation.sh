#!/bin/bash
mkdir -p tmp/pages
mkdir -p tmp/home_pages
cd tmp/
touch urls.txt
cd ..
php homes_downloader.php
php url_listing.php
php pages_downloader.php
php parser_htmltoxml.php
cd tmp/
rm -r home_pages
cd ..
rm -r tmp
