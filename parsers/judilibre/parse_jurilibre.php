<?php

$rawfile = $argv[1];
$json = json_decode(file_get_contents($rawfile));

if (isset($json->chamber) && strlen($json->chamber) < 4) {
	switch ($json->chamber) {
		case 'cr':
			$json->chamber = "chambre criminelle";
			break;
		case 'civ3':
			$json->chamber = 'troisième chambre civile';
			break;
		case 'civ2':
			$json->chamber = 'deuxieme chambre civile';
			break;
		case 'civ1':
			$json->chamber = 'première chambre civile';
			break;
	}

}
if (isset($json->jurisdiction) && strlen($json->jurisdiction) < 4) {
	switch ($json->jurisdiction) {
		case 'cc':
			$json->jurisdiction = "Cour de cassation";
			break;
	}

}
if (isset($json->formation) && strlen($json->formation) < 5) {
	switch ($json->formation) {
		case 'frh':
			$json->formation = 'formation restreinte';
			break;
		case 'fs':
			$json->formation = 'formation de section';
			break;
	}
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo "\n<DOCUMENT>\n";
echo "<PAYS>FRANCE</PAYS>\n";
echo "<SOURCE>".$json->source."</SOURCE>\n";
echo "<ID_SOURCE>".$json->id."</ID_SOURCE>\n";
echo "<JURIDICTION>".$json->jurisdiction."</JURIDICTION>\n";
if (isset($json->location)) {
	echo "<TRIBUNAL>".$json->location."</TRIBUNAL>\n";
}
if (isset($json->formation) || isset($json->chamber)) {
	echo "<FORMATION>";
	if (isset($json->chamber)) {
		echo $json->chamber;
	}
	if (isset($json->formation) && isset($json->chamber)) {
		echo " - ";
	}
	if (isset($json->formation)) {
		echo $json->formation;
	}
	"</FORMATION>\n";
}
echo "<TYPE>".strtolower(transliterator_transliterate('NFKC; [:Nonspacing Mark:] Remove; NFKC; Any-Latin; Latin-ASCII', $json->type))."</TYPE>\n";
echo "<DATE_ARRET>".$json->decision_date."</DATE_ARRET>\n";
echo "<NUM_ARRET>".$json->number."</NUM_ARRET>\n";
if (count($json->numbers) > 1) {
	echo "<NUMEROS_AFFAIRES>\n";
	foreach($json->numbers as $num)  {
		echo "<NUMERO_AFFAIRE>".$num."</NUMERO_AFFAIRE>\n";
	}
	echo "</NUMEROS_AFFAIRES>\n";
}
if (isset($json->publication) || isset($json->rapprochements) || isset($json->visa)) {
echo "<REFERENCES>\n";
	foreach($json->publication as $pub) {
		switch (strtolower($pub)) {
			case 'b':
				$pub = "bulletin";
				break;
			case 'a':
				$pub = 'recueil Lebon';
				break;
			case 'c':
			case 'c+':
			 	$pub = 'Inédit';
				break;
		}
		echo "<REFERENCE>\n";
		echo "<TYPE>PUBLICATION</TYPE>\n";
		echo "<TITRE>".$pub."</TITRE>\n";
		echo "</REFERENCE>\n";
	}
	foreach ($json->rapprochements as $rappro) {
		echo "<REFERENCE>\n";
		echo "<TYPE>SIMILAIRE</TYPE>\n";
		echo "<TITRE>".$rappro->title."</TITRE>\n";
		echo "</REFERENCE>\n";
	}
	foreach ($json->visa as $v) {
		echo "<REFERENCE>\n";
		echo "<TYPE>VISA</TYPE>\n";
		echo "<TITRE>".$v->title."</TITRE>\n";
		echo "</REFERENCE>\n";
	}
	if (isset($json->timeline)) {
		foreach($json->timeline as $t) {
			if ($t->number != $json->number) {
				continue;
			}
			echo "<REFERENCE>\n";
			echo "<TYPE>CITATION_ARRET</TYPE>\n";
			echo "<NATURE>".$t->jurisdiction."</NATURE>\n";
			echo "<DATE>".$t->date."</DATE>\n";
			echo "<TITRE>";
			echo str_replace('\\n', ' ', $t->title);
			if (isset($t->number)) {
				echo ", arrêt n°".$t->number;
			}
			if (isset($t->solution)) {
				echo " : ".$t->solution;
			}
			echo "</TITRE>\n";
			echo "</REFERENCE>\n";
		}
	}
echo "</REFERENCES>\n";
}
echo "<TEXTE_ARRET>\n".$json->text."\n</TEXTE_ARRET>\n";
if (isset($json->ecli)) {
	echo "<ECLI>".$json->ecli."</ECLI>\n";
}
if (isset($json->contested)) {
	echo "<DECISIONS_ATTAQUEES>\n";
	echo "<DECISION_ATTAQUEE>\n";
	echo "<TYPE>DECISION</TYPE>\n";
	echo "<DATE>".$json->contested->date."</DATE>\n";
	echo "<TITRE>".str_replace("\n", " ", $json->contested->title)."</TITRE>\n";
	echo "<FORMATION>".str_replace("\n", " ", $json->contested->jurisdiction)."</FORMATION>\n";
	echo "</DECISION_ATTAQUEE>\n";
	echo "</DECISIONS_ATTAQUEES>\n";
}
if (isset($json->summary)) {
	echo "<ANALYSES>\n";
	if (isset($json->summary)) {
		echo "<ANALYSE><SOMMAIRE>";
		echo $json->summary;
		echo "</SOMMAIRE></ANALYSE>";
	}
	if (isset($json->themes)) {
		echo "<ANALYSE><TITRE_PRINCIPAL>";
		echo implode(' - ', $json->themes);
		echo "</TITRE_PRINCIPAL></ANALYSE>";
	}
	echo "</ANALYSES>\n";
}
echo "<SENS_ARRET>".$json->solution."</SENS_ARRET>\n";
echo "</DOCUMENT>\n";
