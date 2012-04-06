#!/bin/bash
# Répertoire de travail
if [ "$(echo $0 | sed 's|[^/]*$||')" != "./" ] ; then cd $(echo $0 | sed 's|[^/]*$||') ; fi
. ./pyenv.conf
source $PYENV ;

mkdir -p ./indexes
mkdir -p ./archive

# Extraction complete
if test "$1" ; then
  echo "Extraction complète"
  if [ -e index.html ]
  then
    rm index.html ;
    rm -R ./indexes/*
  fi
  wget -nv http://csc.lexum.org/fr/index.html ;
  python extractYearsList.py index.html > yearslist ;
  cat yearslist | while read years ;
  do
    wget -nv -P ./indexes/$years ;
  done
fi

if [ -e filelist ] ; then rm filelist ; rm namedlist ;fi

# Extrait les urls des années trouvées
find ./indexes/ -name "*.html" | while read yearindex ; # génère ./indexes/1950/01.html
do
  python extractFileList.py $yearindex >> filelist ;
  python extractNamedList.py $yearindex >> namedlist ;
done

mkdir -p ./download

# Télécharger les décisions html de lexum
if test "$1" ; then
  wget -i filelist -nv -P ./download/
  find ./download/ -name '*.html' | while read fichier ;
  do
    cat $fichier | sed ':a;N;$!ba;s/\n/ /g' | sed s'/<\/p>/<\/p>\n\n\n/gi' > temp.html ;
    cat temp.html > $fichier ;
  done
fi

if [ -e canada.log ] ; then rm canada.log ; fi

mkdir -p ./converted/Canada/CSC
if [ -d ../../../data/pool/Canada ] ; then rm -R ../../../data/pool/Canada ; fi

# Converti au format Juricaf
echo "Conversion au format juricaf"
cat namedlist | while read ligne ;
do
  nom=$(echo $ligne | sed 's|[^/]*$||')
  echo $(basename $nom)
  python createJuricaf.py $ligne > ./converted/Canada/CSC/$(basename $nom).xml
done

mv ./download/* ./archive/
mv ./converted/* ../../../data/pool/

deactivate