#! /usr/bin/env python
# -*- coding: utf8 -*-
import os, sys, string, re
from bs4 import BeautifulSoup

def str_replace(to_replace, text):
  pattern = "|".join(map(re.escape, to_replace.keys()))
  return re.sub(pattern, lambda m: to_replace[m.group()], text)

mois = {'janvier' : '01', 'février' : '02', 'mars' : '03', 'avril' : '04', 'mai' : '05', 'juin' : '06', 'juillet' : '07', 'août' : '08', 'septembre' : '09', 'octobre' : '10', 'novembre' : '11', 'décembre' : '12'}

special_chars = { u"<" : u" ", u">" : u" ", u"\u2012" : u'—', u"\u2013" : u'—', u"\u2014" : u'—', u"\u2015" : u'—', u"\u0097" : u'—', u"‑‑" : u'—', u"—" : u' — ', u"\u0094" : u"\"", u"\u0093" : u"\"", u"\u0092" : u"'", u"\u00A0" : u" ", u"\u00C2" : u"Â", u"\u00C3" : "Â", u"\u0009" : u" ", u"\u000B" : u" " }

titre_complet = string.join(sys.argv[2:]).replace('\xc2\xa0', ' ')

titre = re.split(' , ', titre_complet)
temp = titre[1].strip()
titre = titre[0].strip()

reg_date = re.compile('\((.+)\)$')
date = reg_date.search(temp)
date_titre = date.group(1).strip()
date = date_titre.split()
date = date[0].rjust(2, '0')+'/'+mois[date[1]]+'/'+date[2]

reg_rcs = re.compile("((\[[0-9]{4}\])? ?[0-9]? R.C.S. [0-9]{1,4})")
reg_csc = re.compile('([0-9]{4} CSC [0-9]{1,3})')
if reg_csc.search(temp) is not None:
  req_num = reg_csc.search(temp)
  num_arret = req_num.group(1)
  num_arret = num_arret.replace(' ', '_')
elif reg_rcs.search(temp) is not None:
  req_num = reg_rcs.search(temp)
  num_arret = req_num.group(1).strip()
  num_arret = num_arret.replace(' ', '_')
else:
  num_arret = ''

if re.search(' c. ', titre) is not None:
  parties = re.split(' c. ', titre)
  i = 0
  for partie in parties:
    if partie == 'R.':
      parties[i] = 'Sa Majesté la Reine'
    elif partie == 'La Reine':
      parties[i] = 'Sa Majesté la Reine'
    i += 1
  demandeur = parties[0]
  defendeur = parties[1]
else:
  demandeur = ''
  defendeur = ''

docpath = sys.argv[1]

soup = BeautifulSoup(open('./download/'+os.path.basename(docpath), 'r'))

texte = ''
texte = soup.find(id="originalDocument")
texte = texte.get_text()
texte = str_replace(special_chars, texte)
texte = re.sub('( ){2,}', ' ', texte)

texte_temp = texte.split('\n')
texte = ''
analyses = ''
references = ''
is_analyse = 0
is_reference = 0
not_done = 1
reg_sens = re.compile(u"Arrêt *(\([^)]*\))? *:")
sens = ''
num_affaires = []
numeros_affaires = ''

for line in texte_temp:
  line = line.strip()
  assert isinstance(line, unicode)
  if is_analyse :
    if re.match('Jurisprudence', line) is not None:
      references += '<REFERENCE><TITRE>'+line+'</TITRE><TYPE>CITATION_ARRET</TYPE></REFERENCE>'
      is_analyse = 0
      is_reference = 1
      not_done = 0
    elif re.match('APPEL', line) is not None: # Ajouter EN APPEL ????
      texte += line+'\n\n'
      is_analyse = 0
      is_reference = 0
      not_done = 0
    else :
      if line[0:250].count(u'—') >= 3 :
        analyses += '<ANALYSE><TITRE_PRINCIPAL>'+line+'</TITRE_PRINCIPAL></ANALYSE>'
      elif line != '' :
        analyses += '<ANALYSE><SOMMAIRE>'+line+'</SOMMAIRE></ANALYSE>'
        if re.search(reg_sens, line) is not None:
          sens = re.split(':', line)
          sens = sens[1].strip()
  elif is_reference :
    if re.match('POURVOI', line) is not None:
      texte += line+'\n\n'
      is_reference = 0
    elif re.search(';', line) is not None:
      multiref = line.split(';')
      for ref in multiref:
        references += '<REFERENCE><TITRE>'+ref.strip()+'</TITRE><TYPE>CITATION_ARRET</TYPE></REFERENCE>'
    elif line != '' :
      references += '<REFERENCE><TITRE>'+line+'</TITRE><TYPE>CITATION_ARRET</TYPE></REFERENCE>'
  elif line[0:250].count(u'—') >= 3 and not_done :
    analyses += '<ANALYSE><TITRE_PRINCIPAL>'+line+'</TITRE_PRINCIPAL></ANALYSE>'
    is_analyse = 1
  else :
    if re.match('(Dossier :)|(Nos? du greffe :)', line) is not None:
      if re.search(',', line) is not None:
        nums = re.split(',', line)
        for num in nums:
          num_affaires.append(re.sub('([^0-9])', '', num))
      else :
        num_affaires.append(re.sub('([^0-9])', '', line))
    texte += line+'\n\n'

num_affaires = list(set(num_affaires))

if len(num_affaires) > 0:
  for num_affaire in num_affaires:
    numeros_affaires += '<NUMERO_AFFAIRE>'+num_affaire+'</NUMERO_AFFAIRE>'

texte = re.sub('(\n){3,}', '\n\n', texte)
texte = texte.strip()

titre = titre+', '+re.sub('_', ' ', num_arret)+' ('+date_titre+')'

document = '<?xml version="1.0" encoding="UTF-8"?>\
<DOCUMENT>\
<PAYS>Canada</PAYS>\
<JURIDICTION>Cour suprême</JURIDICTION>\
<TITRE>'+titre+'</TITRE>\
<NUM_ARRET>'+num_arret+'</NUM_ARRET>\
<NUMEROS_AFFAIRES>'+numeros_affaires.encode('utf8')+'</NUMEROS_AFFAIRES>\
<DATE_ARRET>'+date+'</DATE_ARRET>\
<ANALYSES>'+analyses.encode('utf8')+'</ANALYSES>\
<TEXTE_ARRET><![CDATA['+texte.encode('utf8')+']]></TEXTE_ARRET>\
<DECISIONS_ATTAQUEES><DECISION_ATTAQUEE></DECISION_ATTAQUEE></DECISIONS_ATTAQUEES>\
<PARTIES>\
<DEMANDEURS><DEMANDEUR>'+demandeur+'</DEMANDEUR></DEMANDEURS>\
<DEFENDEURS><DEFENDEUR>'+defendeur+'</DEFENDEUR></DEFENDEURS>\
</PARTIES>\
<REFERENCES>\
'+references.encode('utf8')+'\
<REFERENCE>\
<TITRE>'+titre+' sur le site csc.lexum.org</TITRE>\
<TYPE>SOURCE_TOP</TYPE>\
<URL>http://csc.lexum.org'+docpath+'</URL>\
</REFERENCE>\
<REFERENCE>\
<TITRE>Télécharger le document PDF</TITRE>\
<TYPE>SOURCE_TOP</TYPE>\
<URL>http://csc.lexum.org'+docpath.replace('html', 'pdf')+'</URL>\
</REFERENCE>\
<REFERENCE>\
<TITRE>Télécharger le document DOCX</TITRE>\
<TYPE>SOURCE_TOP</TYPE>\
<URL>http://csc.lexum.org'+docpath.replace('html', 'docx')+'</URL>\
</REFERENCE>\
</REFERENCES>\
<SENS_ARRET>'+sens.encode('utf8')+'</SENS_ARRET>\
</DOCUMENT>'

document = document.replace('&', '&amp;')

print (document)