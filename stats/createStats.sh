#!/bin/bash

# Répertoire de travail
cd $(dirname $0)

mkdir -p static

php statsBase.php

#echo -e "\n\n* Statistiques avancées des champs :";
#php statsChamps.php
