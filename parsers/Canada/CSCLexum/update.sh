#!/bin/bash
# Répertoire de travail
if [ "$(echo $0 | sed 's|[^/]*$||')" != "./" ] ; then cd $(echo $0 | sed 's|[^/]*$||') ; fi
. ./pyenv.conf
source $PYENV ;

THISYEAR=$(date '+%Y')
DEBUT=$(date '+%d/%m/%Y %H:%M:%S')

if [ -e wgetlog ] ; then rm wgetlog ; fi

# Si une année est spécifiée, c'est un ré-import forcé
if test "$1" ; then THISYEAR=$1 ; rm ./archive/$THISYEAR*.html ; fi
###########################################################################THISYEAR=2010

# Téléchargement de la page d'accueil de csc.lexum.org
if [ -e index.html ] ; then rm index.html ; fi
wget -nv http://csc.lexum.org/fr/index.html -o wgetlog;

# Supprime l'index de l'année souhaitée s'il existe
if [ -d ./indexes/$THISYEAR ] ; then rm -R ./indexes/$THISYEAR ; fi

# Selectionne l'url de l'index de l'année souhaitée
THISINDEX=$(python extractYearsList.py | grep ^$THISYEAR) ;

# Si pas d'index pour cette année on stoppe
if [ "$THISINDEX" = "" ] ; then exit 1 ; fi

# Sinon on le télécharge
wget -nv -P ./indexes/$THISINDEX -o wgetlog ;

# Suprimer les fichiers de l'update précédent
if [ -e old ] ; then rm old ; fi
if [ -e new ] ; then rm new ; rm newnamedlist ; fi

# Créer la liste des fichiers déjà présents
for fichier in $(find ./archive/ -name "$THISYEAR*.html");
  do
    echo $(basename $fichier) >> old
  done

# Créer la liste des fichiers présents dans le nouvel index
python extractFileList.py ./indexes/$THISYEAR/01.html >> new ;
python extractNamedList.py ./indexes/$THISYEAR/01.html >> newnamedlist ;

# Comparer new et old et retourner les fichiers non-présents
if [ -e maj ] ; then rm maj ; fi

if [ -e old ] ; then
  python findNew.py >> maj ;
else
  cat new >> maj ;
fi

# Si maj est vide on stoppe
if [ "$(wc -l maj)" = "0 maj" ] ; then exit 1 ; fi

echo "=====================================================";
echo "|         Mise à jour Cour suprême du Canada        |" ;
echo "=====================================================";
echo "Téléchargement des nouvelles décisions :"
wget -i maj -nv -P ./download/

# Nettoyage pour mise en forme
find ./download/ -name '*.html' | while read fichier ;
do
  cat $fichier | sed ':a;N;$!ba;s/\n/ /g' | sed s'/<\/p>/<\/p>\n\n\n/gi' > temp.html ;
  cat temp.html > $fichier ;
done

mkdir -p ./converted/Canada/CSC
if [ -d ../../../data/pool/Canada ] ; then rm -R ../../../data/pool/Canada ; fi

echo -e "\n=====================================================";
echo "Conversion au format Juricaf :"
cat maj | while read line ;
do
  to_convert=$(cat newnamedlist | grep $(basename $line))
  echo "$(basename $line) > $(basename $line | sed s':\.html::gi').xml"
  python createJuricaf.py $to_convert > ./converted/Canada/CSC/$(basename $line | sed s':\.html::gi').xml
done

echo -e "\n=====================================================";
echo "Archivage des fichiers originaux :" ;
mv ./download/* ./archive/
if [ $? -eq 0 ] ; then
  echo "Ok";
fi

echo -e "\n=====================================================";
echo "Transmission des fichiers convertis au pool" ;
mv ./converted/* ../../../data/pool/
if [ $? -eq 0 ] ; then
  echo "Ok";
fi

FIN=$(date '+%d/%m/%Y %H:%M:%S')
echo -e "\n=====================================================";
echo "Début : $DEBUT ; Fin : $FIN" ;

deactivate
