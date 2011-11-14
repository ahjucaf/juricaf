#!/bin/bash

# Répertoire de travail
if [ "$(echo $0 | sed 's|[^/]*$||')" != "./" ] ; then
  cd $(echo $0 | sed 's|[^/]*$||');
fi

echo "=====================================================";
echo "|           Mise à jour des statistiques            |" ;
echo "=====================================================";
echo -e "\n* Statuts et licences des collections :";
php statsBase.php

echo -e "\n\n* Statistiques avancées des champs :";
php statsChamps.php
