#!/bin/bash

cd $(dirname $0)
. ../config.inc
if [ -d "tmp" ];then
  rm -r tmp
fi
touch all_urls.txt
mkdir -p tmp/pages
mkdir -p tmp/home_pages
if [ -d "xmls" ];then
  rm -r xmls
fi

mkdir xmls
touch tmp/urls.txt all_urls.txt

nb_home_pages=1
php homes_downloader.php $nb_home_pages
php url_listing.php
php pages_downloader.php
nbr_pages=$(ls -A "tmp/pages/" | wc -l)
if [ $nbr_pages != '0' ];then
  for page in tmp/pages/* ; do
    php parser_htmltoxml.php $page
  done
fi
nbr_xml=$(ls -A "xmls" | wc -l)

if [ $nbr_xml != '0' ];then
  cp xmls/* $POOL
fi
