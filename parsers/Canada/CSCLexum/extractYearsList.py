#! /usr/bin/env python
# -*- coding: utf8 -*-
import re
from bs4 import BeautifulSoup

html_doc = open('index.html', 'r')
soup = BeautifulSoup(html_doc)

for link in soup.find_all(href=re.compile("/fr/dn/")):
  try:
    int(link.string)
  except:
    pass
  else:
    print(link.string+' http://csc.lexum.org'+link.get('href'))
