#!/bin/bash

# Répertoire de travail
cd $(dirname $0)

echo "=====================================================";
echo "|           Mise à jour des statistiques            |" ;
echo "=====================================================";
echo -e "\n* Statuts et licences des collections :";
php statsBase.php

echo -e "\n\n* Statistiques avancées des champs :";
php statsChamps.php
