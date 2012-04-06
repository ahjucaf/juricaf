#! /usr/bin/env python
# -*- coding: utf8 -*-
import os

old = open('old','r')
oldlist = old.readlines()
old.close()

newfilelist = open('new','r')
newlist = newfilelist.readlines()
newfilelist.close()

for newfile in newlist:
  if os.path.basename(newfile) not in oldlist:
    print(newfile.strip())
