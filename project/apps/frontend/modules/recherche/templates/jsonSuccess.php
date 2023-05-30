<?php

$nbResultats = $resultats->response->numFound;

// Ouverture de l'objet JSON
echo '{ ';
echo "\"nb_resultat\" : $nbResultats, \"docs\" : [ ";


// Gestion de la derniere boucle
$total = count($resultats->response->docs);
$i = 0;

foreach ($resultats->response->docs as $resultat) {
     if ($i) {
 		echo ", ";
    }
	echo '{ ';
	echo '"id" : "' . $resultat->id . '", ';
	echo '"pays" : "' . $resultat->pays . '", ';
	echo '"titre" : "' . $resultat->titre . '", ';
	echo '"formation" : "' . $resultat->formation . '", ';
	echo '"date_arret" : "' . $resultat->date_arret . '", ';
	echo '"juridiction" : "' . $resultat->juridiction . '"';
	echo ' }';
	$i++;
}

// Fermeture de l'objet JSON
echo ' ] }';
