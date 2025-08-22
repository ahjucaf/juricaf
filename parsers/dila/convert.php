<?php
$xml = simplexml_load_file($argv[1]);
if ( ! $xml ) {
    fwrite(STDERR, "Error: Cannot read XML ".$argv[1] . PHP_EOL);
    exit(1);
 }

$fond = '';
foreach(['JURI','CETAT','CONSTIT'] as $o) {
    if (strpos($argv[1], substr($o, 0, 5)) !== false) {
        $fond = $o;
        break;
    }
}

$output = array();
$output['PAYS'] = 'France';
$output['FONDS_DOCUMENTAIRE'] = 'Légifrance';

foreach($xml as $k => $v) {
    if (in_array($k, ['META', 'TEXTE', 'LIENS'])) {
        continue;
    }
    if ($v) {
        $output[$k] = strval($v);
    }
}

function toIsoDate($date)
{
    $date = '';
    if(preg_match('/(\d{2})[\/.](\d{2})[\/.](\d{4})/', $date, $match)) {
        $date = $match[3]."-".$match[2]."-".$match[1];
    }
    if(preg_match('/(\d{4})-(\d{2})-(\d{2})/', $date, $match)) {
        if ($match[1] > date('Y') || $match[1] < 1000) {
            $date = '';
        }
    }
    return $date;
}

if (isset($xml->META)) {
    if (isset($xml->META->META_COMMUN->ORIGINE)) {
        $fond = $xml->META->META_COMMUN->ORIGINE;
        if ($fond == 'JURI') {
            $fond = 'JUDI';
        }
        if ($fond == 'CETAT') {
            $fond = 'ADMIN';
        }
    }
    if (isset($xml->META->META_SPEC->META_JURI)) {
        if (!isset($output['SENS_ARRET'])) {
            $output['SENS_ARRET'] = strval($xml->META->META_SPEC->META_JURI->SOLUTION);
        }
        if (!isset($output['NUM_ARRET'])) {
            $output['NUM_ARRET'] = str_replace(", ", ",", $xml->META->META_SPEC->META_JURI->NUMERO);
        }
        if (!isset($output['JURIDICTION'])) {
            $output['JURIDICTION'] = strval($xml->META->META_SPEC->META_JURI->JURIDICTION);
        }
        if (!isset($output['DATE_ARRET'])) {
            $output['DATE_ARRET'] = strval($xml->META->META_SPEC->META_JURI->DATE_DEC);
        }
        if (!isset($output['NUMEROS_AFFAIRES']) && isset($xml->META->META_SPEC->{"META_JURI_".$fond}->NUMEROS_AFFAIRES)) {
            $num_affaires = array();
            foreach ($xml->META->META_SPEC->{"META_JURI_".$fond}->NUMEROS_AFFAIRES as $num) {
                $num_affaires[] = strval($num->NUMERO_AFFAIRE);
            }
            if (count($num_affaires) > 1) {
                $output['NUMEROS_AFFAIRES'] = implode(', ', $num_affaires);
            }
            if (count($num_affaires) && !$output['NUM_ARRET']) {
                $output['NUM_ARRET'] = $num_affaires[0];
            }
        }
    }
    if (isset($xml->META->META_SPEC->{"META_JURI_".$fond})) {
        if (!isset($output['FORMATION']) && isset($xml->META->META_SPEC->{"META_JURI_".$fond})) {
            $output['FORMATION'] = str_replace("_", " ", $xml->META->META_SPEC->{"META_JURI_".$fond}->FORMATION);
        }
        if (!isset($output['NOR'])) {
            $nor = strval($xml->META->META_SPEC->{"META_JURI_".$fond}->NOR);
            $output['NOR'] = ($nor != 'SUPPRIME') ? $nor : null;
        }
        if (!isset($output['TYPE_RECOURS'])) {
            $output['TYPE_RECOURS'] = ucfirst(strval($xml->META->META_SPEC->{"META_JURI_".$fond}->TYPE_REC));
        }
        if (!isset($output['PRESIDENT'])) {
            $output['PRESIDENT'] = strval($xml->META->META_SPEC->{"META_JURI_".$fond}->PRESIDENT);
        }
        if (!isset($output['AVOCAT_GL'])) {
            $output['AVOCAT_GL'] = strval($xml->META->META_SPEC->{"META_JURI_".$fond}->AVOCAT_GL);
        }
        if (!isset($output['RAPPORTEUR'])) {
            $output['RAPPORTEUR'] = strval($xml->META->META_SPEC->{"META_JURI_".$fond}->RAPPORTEUR);
        }
        if (!isset($output['COMMISSAIRE_GVT'])) {
            $output['COMMISSAIRE_GVT'] = strval($xml->META->META_SPEC->{"META_JURI_".$fond}->COMMISSAIRE_GVT);
        }
        if (!isset($output['AVOCATS'])) {
            $output['AVOCATS'] = strval($xml->META->META_SPEC->{"META_JURI_".$fond}->AVOCATS);
        }
    }
    if (!isset($output['ID'])) {
        $output['ID'] = strval($xml->META->META_COMMUN->ID);
    }
}

$output['JURIDICTION'] = str_replace(["Caa", 'CAA', 'caa'], "Cour administrative d'appel", $output['JURIDICTION']);
$tribunaux = array(
    "Cour administrative d'appel",
    "Cour d'appel",
    "Tribunal administratif",
    "Tribunal d'instance",
    "Tribunal de commerce",
    "Tribunal de grande instance",
    "Tribunal des affaires de sécurité sociale",
    "Tribunal judiciaire",
    "Juridiction de proximité",
    "Conseil de prud'hommes"
);
foreach($tribunaux as $trib) {
    if (strpos($output['JURIDICTION'], $trib) !== false) {
        $output['TRIBUNAL'] = $output['JURIDICTION'];
        $output['JURIDICTION'] = $trib;
        break;
    }
}

if (isset($output['TEXTE_ARRET']) && $output['TEXTE_ARRET']) {
    $output['NO_ERROR'] = 'empty_text';
}
if(isset($output['NUM_ARRET'])) {
    $output['NUM_DECISION'] = $output['NUM_ARRET'];
}

$output['REFERENCES'] = [];
if ($fond == 'CONSTIT') {
    $saisine  = strval(implode(', ', $xml->xpath('/TEXTE_JURI_CONSTIT/TEXTE/SAISINES/SAISINE/@AUTEUR')));
    $saisine .= trim(implode("\n", $xml->xpath('/TEXTE_JURI_CONSTIT/TEXTE/SAISINES//*')));
    if(strlen($saisine) > 30) {
        $output['SAISINES'] = $saisine;
    }
    $output['TYPE_AFFAIRE'] = strval($xml->META->META_COMMUN->NATURE);
    $output['TITRE_SUPPLEMENTAIRE'] = strval($xml->META->META_SPEC->META_JURI->TITRE);

    $natureConstit = array(
        "AN" => "Élection à l'Assemblée nationale",
        "AR16" => "Pouvoir exceptionnel du Président de la République",
        "AUTR" => "Autres textes et décisions",
        "DC" => "Loi ordinaire, Loi organique, Traité ou Réglement des Assemblées",
        "D" => "Élection d'un parlementaire",
        "ELEC" => "Élection divers",
        "FNR" => "Proposition de loi ou Amendement",
        "I" => "Élection d'un parlementaire",
        "LOM" => "Répartitions des compétences entre l'État et certaines collectivités d'outre-mer",
        "LP" => "Loi du pays",
        "L" => "Texte législatif",
        "NOM" => "Nomination des membres du Conseil Constitutionnel",
        "OF" => "Pbligations fiscales",
        "ORGA" => "Décision intéressant le fonctionnement du Conseil constitutionnel",
        "PDR" => "Élection présidentielle",
        "QPC" => "Disposition législative",
        "RAPP" => "Nomination des rapporteurs-adjoints et des délégués auprès du Conseil constitutionnel",
        "REF" => "Référendum",
        "RIP" =>"Référendum d'initiative parlementaire",
        "SEN" => "Élection au Sénat",
    );

    if (strval($xml->META->META_SPEC->META_JURI_CONSTIT->LOI_DEF)) {
        $nor = implode(' ', $xml->xpath('/TEXTE_JURI_CONSTIT/META/META_SPEC/META_JURI_CONSTIT/LOI_DEF/@nor'));
        $output['DECISIONS_ATTAQUEES'] = array('DECISION_ATTAQUEE' => array(
                    'TYPE' => $natureConstit[$output['TYPE_AFFAIRE']],
                    'TITRE' => strval($xml->META->META_SPEC->META_JURI_CONSTIT->LOI_DEF),
                    'FORMATION' => '',
                    'DATE' => toIsoDate(implode(' ', $xml->xpath('/TEXTE_JURI_CONSTIT/META/META_SPEC/META_JURI_CONSTIT/LOI_DEF/@date'))),
                    'NUMERO' => implode(' ', $xml->xpath('/TEXTE_JURI_CONSTIT/META/META_SPEC/META_JURI_CONSTIT/LOI_DEF/@num')),
                    'NOR' => ($nor != 'SUPPRIME') ? $nor : null,
            )    );
        $output['REFERENCES']['REFERENCE id="'.count($output['REFERENCES']).'"'] = array(
                'TITRE' => strval($xml->META->META_SPEC->META_JURI_CONSTIT->LOI_DEF),
                'TYPE' => 'DECISION_ATTAQUEE',
                'NATURE' => $natureConstit[$output['TYPE_AFFAIRE']],
                'DATE' => implode(' ', $xml->xpath('/TEXTE_JURI_CONSTIT/META/META_SPEC/META_JURI_CONSTIT/LOI_DEF/@date')),
                'NUMERO' => implode(' ', $xml->xpath('/TEXTE_JURI_CONSTIT/META/META_SPEC/META_JURI_CONSTIT/LOI_DEF/@num')),
                'NOR' => ($nor != 'SUPPRIME') ? $nor : null,
                'URL' => ($nor && $nor != "SUPPRIME") ? 'http://www.legifrance.gouv.fr/WAspad/UnTexteDeJorf?numjo='.$nor : ''
        );
    } else {
            $output['DECISIONS_ATTAQUEES'] = array('DECISION_ATTAQUEE' => array('TYPE' => $natureConstit[$output['TYPE_AFFAIRE']]));
    }

    $i = 1;
    $output['ANALYSES'] = [];
    foreach ($xml->xpath('/TEXTE_JURI_CONSTIT/TEXTE/OBSERVATIONS/*') as $value) {
        $value = trim(strval($value));
        if ($value) {
            $output['ANALYSES']['SOMMAIRE id="'.$i.'"'] = $value; $i++;
        }
    }
    if (isset($xml->META->META_SPEC->META_JURI_CONSTIT->URL_CC)) {
        $nor = $xml->META->META_SPEC->META_JURI_CONSTIT->NOR;
        $output['REFERENCES']['REFERENCE id="'.count($output['REFERENCES']).'"'] = array(
            'TITRE' => 'site internet du Conseil constitutionnel',
            'TYPE' => 'SOURCE',
            'NATURE' => $output['TYPE_AFFAIRE'],
            'DATE' => strval($xml->META->META_SPEC->META_JURI->DATE_DEC),
            'NUMERO' => strval($xml->META->META_SPEC->META_JURI->NUMERO),
            'NOR' => ($nor != 'SUPPRIMER') ? $nor : null,
            'URL' => strval($xml->META->META_SPEC->META_JURI_CONSTIT->URL_CC)
        );
    }
    $nor = strval($xml->META->META_SPEC->META_JURI_CONSTIT->NOR);
    if ($nor) {
        $output['REFERENCES']['REFERENCE id="'.count($output['REFERENCES']).'"'] = array(
            'TITRE' => 'site internet Légifrance',
            'TYPE' => 'SOURCE',
            'NATURE' => $output['TYPE_AFFAIRE'],
            'DATE' => strval($xml->META->META_SPEC->META_JURI->DATE_DEC),
            'NUMERO' => strval($xml->META->META_SPEC->META_JURI->NUMERO),
            'NOR' => ($nor != 'SUPPRIME') ? $nor : null,
            'URL' => ($nor && $nor != "SUPPRIME") ? 'http://www.legifrance.gouv.fr/WAspad/UnTexteDeJorf?numjo='.$nor : ''
        );
    }
    if(isset($xml->META->META_SPEC->META_JURI_CONSTIT->TITRE_JO)) {
        if (strpos($xml->META->META_SPEC->META_JURI_CONSTIT->TITRE_JO, 'https') === 0) {
            $url = $xml->META->META_SPEC->META_JURI_CONSTIT->TITRE_JO;
            if (preg_match('/(JORFTEXT[0-9A-Z]+)/', $url, $m)) {
                $url = "https://www.legifrance.gouv.fr/jorf/id/".$m[1];
            }
            $output['REFERENCES']['REFERENCE id="'.count($output['REFERENCES']).'"'] = array(
                'TITRE' => 'Publication au JO',
                'TYPE' => 'SOURCE',
                'NOR' => ($nor != 'SUPPRIME') ? $nor : null,
                'URL' => $url,
            );
        }else {
          $output['REFERENCES']['REFERENCE id="'.count($output['REFERENCES']).'"'] = array(
            'TITRE' => $xml->META->META_SPEC->META_JURI_CONSTIT->TITRE_JO,
            'TYPE' => 'PUBLICATION',
          );
        }
    }

    if (!count($output['ANALYSES'])) {
        unset($output['ANALYSES']);
    }
} elseif ($fond == 'CETAT') {
    $meta_xpath = 'TEXTE_JURI_ADMIN';
    $output['TYPE_AFFAIRE'] = 'Administrative';
    foreach ($xml->xpath('/TEXTE_JURI_ADMIN/TEXTE/CITATION_JP/*') as $value) {
        $value = rtrim(strval($value));
        if ($value) {
            $output['REFERENCES']['REFERENCE id="'.count($output['REFERENCES']).'"'] = array(
                'TITRE' => $value,
                'TYPE' => 'CITATION_ANALYSE',
            );
        }
    }
    foreach ($xml->xpath('/TEXTE_JURI_ADMIN/LIENS/*') as $key => $value) {
        if($value) {
            $nor = $value['nortexte'];
            $output['REFERENCES']['REFERENCE id="'.count($output['REFERENCES']).'"'] = array(
                'TITRE' => strval($value),
                'TYPE' => 'CITATION_ARRET',
                'NATURE' => strval($value['naturetexte']),
                'DATE' => strval($value['datesignatexte']),
                'NUMERO' => strval($value['numtexte']),
                'NOR' => ($nor != 'SUPPRIME') ? $nor : null,
                'URL' => ($nor && $nor != 'SUPPRIME') ? 'http://www.legifrance.gouv.fr/WAspad/UnTexteDeJorf?numjo='.$nor : ''
            );

        }
    }

    $lettre2lebon = array(
        'A' => 'Publié au recueil Lebon',
        'B' => 'Mentionné aux tables du recueil Lebon',
        'C' => 'Inédit au recueil Lebon',
        'C+' => 'Inédit au recueil Lebon',
    );
    if(isset($xml->META->META_SPEC->META_JURI_ADMIN->PUBLI_RECUEIL) && $lettre2lebon[strval($xml->META->META_SPEC->META_JURI_ADMIN->PUBLI_RECUEIL)]) {
        $output['REFERENCES']['REFERENCE id="'.count($output['REFERENCES']).'"'] = array(
            'TITRE' => $lettre2lebon[strval($xml->META->META_SPEC->META_JURI_ADMIN->PUBLI_RECUEIL)],
            'TYPE' => 'PUBLICATION',
        );
    }

} elseif ($fond == 'JUDI') {
    foreach(array('civile','commerciale','criminelle','sociale', 'mixte', 'reunie', 'réunie') as $type) {
        if (isset($xml->META->META_COMMUN->URL) && strpos($xml->META->META_COMMUN->URL, $type) !== false) {
            $output['TYPE_AFFAIRE'] = $type;
        }elseif (isset($xml->META->META_SPEC->META_JURI_JUDI->FORMATION) && strpos($xml->META->META_SPEC->META_JURI_JUDI->FORMATION, $type) !== false ) {
            $output['TYPE_AFFAIRE'] = $type;
        }
        if (isset($output['TYPE_AFFAIRE']) && in_array($output['TYPE_AFFAIRE'], ['reunie', 'réunie'])) {
            $output['TYPE_AFFAIRE'] = 'mixte';
        }
        if (isset($output['TYPE_AFFAIRE'])) {
            $output['TYPE_AFFAIRE'] = "chambre ".$output['TYPE_AFFAIRE'];
        }
    }
    if (isset($xml->META->META_SPEC->META_JURI_JUDI)) {
        $output['DECISIONS_ATTAQUEES'] =
            array('DECISION_ATTAQUEE' =>
                array('TYPE' => 'DECISION',
                    'FORMATION' => rtrim(str_replace(strval($xml->META->META_SPEC->META_JURI_JUDI->DATE_DEC_ATT), '', strval($xml->META->META_SPEC->META_JURI_JUDI->FORM_DEC_ATT))),
                    'DATE' => strval($xml->META->META_SPEC->META_JURI_JUDI->DATE_DEC_ATT),
                    'SIEGE' => strval($xml->META->META_SPEC->META_JURI_JUDI->SIEGE_APPEL),
                    'JURI_PREM' => strval($xml->META->META_SPEC->META_JURI_JUDI->JURI_PREM),
                    'LIEU_PREM' => strval($xml->META->META_SPEC->META_JURI_JUDI->LIEU_PREM)
                )
            );
        foreach(['DATE', 'JURI_PREM', 'LIEU_PREM', 'SIEGE'] as $k) {
            if (!$output['DECISIONS_ATTAQUEES']['DECISION_ATTAQUEE'][$k]) {
                unset($output['DECISIONS_ATTAQUEES']['DECISION_ATTAQUEE'][$k]);
            }
        }
    }
    foreach ($xml->xpath('/TEXTE_JURI_JUDI/TEXTE/CITATION_JP/*') as $value) {
        $output['REFERENCES']['REFERENCE id="'.count($output['REFERENCES']).'"'] = array(
            'TITRE' => strval($value),
            'TYPE' => 'CITATION_ANALYSE',
        );
    }
    foreach ($xml->xpath('/TEXTE_JURI_JUDI/LIENS/*') as $key => $value) {
        if($value) {
            $nor = strval($value['nortexte']);
            $output['REFERENCES']['REFERENCE id="'.count($output['REFERENCES']).'"'] = array(
                'TITRE' => strval($value),
                'TYPE' => 'CITATION_ARRET',
                'NATURE' => strval($value['naturetexte']),
                'DATE' => strval($value['datesignatexte']),
                'NUMERO' => strval($value['numtexte']),
                'NOR' => ($nor != 'SUPPRIME') ? $nor : null,
                'URL' => ($nor && $nor != 'SUPPRIME') ? 'http://www.legifrance.gouv.fr/WAspad/UnTexteDeJorf?numjo='.$nor : ''
            );
        }
    }
    $publication = $xml->xpath('/TEXTE_JURI_JUDI/META/META_SPEC/META_JURI_JUDI/PUBLI_BULL');
    if(!empty($publication[0][0])) {
        $pub = $publication[0][0];
    } elseif (isset($publication[0]) && $publication[0]['publie'] == 'oui') {
        $pub = 'Publié au bulletin';
    } else {
        $pub = false;
    }
    if($pub !== false) {
        $output['REFERENCES']['REFERENCE id="'.count($output['REFERENCES']).'"'] = array(
            'TITRE' => strval($pub),
            'TYPE' => 'CITATION_ARRET'
        );
    }
}

if (!isset($meta_xpath)) {
    $meta_xpath = 'TEXTE_JURI_'.$fond;
}
if($xml->xpath('/'.$meta_xpath.'/TEXTE/SOMMAIRE/*/@ID')) {
    $analyses_ids = array_unique($xml->xpath('/'.$meta_xpath.'/TEXTE/SOMMAIRE/*/@ID'));
    foreach ($analyses_ids as $value) {
        $p = 1;
        $titre_principal = $xml->xpath('/'.$meta_xpath.'/TEXTE/SOMMAIRE/SCT[contains(@ID,"'.$value.'") and contains(@TYPE,"PRINCIPAL")]');
        foreach ($titre_principal as $values) {
            $values = rtrim(strval($values));
            if ($values) {
                $output['ANALYSES']['ANALYSE id="A'.substr($value,1,1).'"']['TITRE_PRINCIPAL id="P'.$p.'"'] = $values; $p++;
            }
        }
        $s = 1;
        $titre_secondaire = $xml->xpath('/'.$meta_xpath.'/TEXTE/SOMMAIRE/SCT[contains(@ID,"'.$value.'") and contains(@TYPE,"REFERENCE")]');
        foreach ($titre_secondaire as $values) {
            $values = rtrim(strval($values));
            if ($values) {
                $output['ANALYSES']['ANALYSE id="A'.substr($value,1,1).'"']['TITRE_SECONDAIRE id="S'.$s.'"'] = $values; $s++;
            }
        }
        $a = 1;
        $analyses = $xml->xpath('/'.$meta_xpath.'/TEXTE/SOMMAIRE/ANA[@ID="'.$value.'"]');
        foreach ($analyses as $values) {
            $values = rtrim(strval($values));
            if ($values) {
                $output['ANALYSES']['ANALYSE id="A'.substr($value,1,1).'"']['SOMMAIRE id="A'.$a.'"'] = $values; $a++;
            }
        }
    }
    if (!count($output['ANALYSES'])) {
        unset($output['ANALYSES']);
    }
}

$text = $xml->xpath('/'.$meta_xpath.'/TEXTE/BLOC_TEXTUEL/CONTENU');
$output['TEXTE_ARRET'] = '';
foreach($text as $t) {
    $output['TEXTE_ARRET'] .= str_replace(['<CONTENU>', '</CONTENU>', '<p>', '</p>'], "\n", htmlspecialchars_decode(preg_replace('|<br[^>]*>|i', "\n", $t->asXML())));
}

if (isset($output['ID'])) {
    $output['ID_SOURCE'] = $output['ID'];
    unset($output['ID']);
}

foreach(array_keys($output) as $k) {
    if (!$output[$k]) {
        unset($output[$k]);
    }
}

function printXML($data) {
    foreach($data as $balise => $value) {
        echo "<$balise>";
        if (is_array($value)) {
            echo "\n";
            printXML($value);
        }else{
            echo preg_replace('/&amp;([a-z]*);/', '&$1;', preg_replace(['/([^ ;]+)(&[^ ;]+;)/', '/([^ ;]*)&([^ ]*)/'], ['$1$2', '$1&amp;$2'], $value));
        }
        //retire les attributs de l'ouverture de la balise
        $balise = preg_replace('/ .*/', '', $balise);
        echo "</$balise>\n";
    }
}
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo "\n<DOCUMENT>\n";
printXML($output);
echo("<ALIMENTATION_TYPE>parsers/dila</ALIMENTATION_TYPE>\n");
echo "</DOCUMENT>\n";
