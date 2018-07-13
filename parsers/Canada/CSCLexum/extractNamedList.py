#! /usr/bin/env python
# -*- coding: utf8 -*-
import sys, re
from bs4 import BeautifulSoup
reg_fr = re.compile("^/fr/[0-9]{4}/")

html_doc = open(sys.argv[1], 'r')
soup = BeautifulSoup(html_doc, "html.parser")

for section in soup.find_all("li", { "class" : ["even", "odd"] }):
  for number in section.find_all("span", "number"):
    number.decompose()
  line = section.a.get('href')+' '+section.get_text(" ", strip=True)
  fr = re.match(reg_fr, line)
  if fr is not None:
    print(line.encode('utf8'))
