#!/bin/bash

cd $(dirname $0)
cd cour_de_revision/
bash update.sh
cd ..
cd TribunalSupreme/
bash update.sh
cd ..
