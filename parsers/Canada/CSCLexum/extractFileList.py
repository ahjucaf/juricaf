#! /usr/bin/env python
# -*- coding: utf8 -*-
import sys, re
from bs4 import BeautifulSoup

soup = BeautifulSoup(open(sys.argv[1], 'r'), "html.parser")

for link in soup.find_all(href=re.compile("/fr/[0-9]{4}/")):
  line = 'http://csc.lexum.org'+link.get('href')
  print(line.encode('utf8'))
