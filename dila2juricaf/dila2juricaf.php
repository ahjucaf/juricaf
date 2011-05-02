<?php
// Usage : $ php dila2juricaf.php fichier_dila.xml
if (file_exists($argv[1]) && filesize($argv[1]) != 0) {

// Fonctions

function multiple($nom, $array_vals) {
  $i = 1;
  $multiples = '';
  foreach ($array_vals as $value) {
    if(trim($value) !== '') {
      $multiples[$nom.' id="M'.$i.'"'] = cdata($value); $i++;
    }
  }
  return $multiples;
}

function cdata($str) {
  $str = toString($str);
  if(!empty($str)) { return '<![CDATA['.$str.']]>'; }
  else return false;
}

function typeAffaire($section, $chemin) {
  $type = array('civile','commerciale','criminelle','sociale', 'mixte', 'reunie', 'réunie');
  $type_affaire = '';
  $section = toString($section);
  foreach ($type as $value) {
    if(strpos(strtolower($section), $value)) { $type_affaire = $value; }
    elseif(strpos($chemin, $value)) { $type_affaire = $value; }
  }
  if($type_affaire == 'mixte' || $type_affaire == 'reunie' || $type_affaire == 'réunie') {
    $type_affaire = 'chambre mixte';
  }
  return ucfirst($type_affaire);
}

function pubLebon($pub) {
  switch ($pub) {
  case 'A': $txt = 'Publié au recueil Lebon';
    break;
  case 'B': $txt = 'Mentionné aux tables du recueil Lebon';
    break;
  case 'C': $txt = 'Inédit au recueil Lebon';
    break;
  default: $txt = null;
  }
  return $txt;
}

function urlNor($nor) {
  if(trim($nor) !== '') { return cdata('http://www.legifrance.gouv.fr/WAspad/UnTexteDeJorf?numjo='.$nor); }
  else { return false; }
}

function urlPdfJO($str) {
  if(trim($str) !== '') {
    $mois = array(
        'janvier'=>'01',
        'février'=>'02',
        'fevrier'=>'02',
        'mars'=>'03',
        'avril'=>'04',
        'mai'=>'05',
        'juin'=>'06',
        'juillet'=>'07',
        'août'=>'08',
        'aout'=>'08',
        'septembre'=>'09',
        'octobre'=>'10',
        'novembre'=>'11',
        'décembre'=>'12',
        'decembre'=>'12'
        );
    if (preg_match('/(\d{2}) (\w{3,}) (\d{4})(.)+p(\D{0,})?(\d{0,})/', $str, $match)) {
      if(isset($mois[$match[2]])) {
        return cdata('http://legifrance.gouv.fr/jopdf/common/jo_pdf.jsp?numJO=0&dateJO='.$match[3].$mois[$match[2]].$match[1].'&pageDebut='.$match[6]);
      }
    }
  }
}

function clean($str) {
  $ret = true;
  while ($ret) {
    $ret = strpos($str, "  ");
    $str = str_replace("  ", " ", $str);
    $ret = strpos($str, "\n\n\n");
    $str = str_replace("\n\n\n", "\n\n", $str);
    $str = str_replace("\n\n ", "\n\n", $str);
  }
  return $str;
}

function unsetEmptyVals($array) {
  if (is_array($array) || is_object($array)) {
    $array = array_filter($array);
    foreach ($array as $key => $value) {
      if (is_array($value) || is_object($value)) {
        if(is_object($value)) { $value = (array)$value; } ;
        $array[$key] = unsetEmptyVals($value);
      }
    }
  }
  return $array;
}

function parseAnalyse($dila, $meta_xpath) {
  if($dila->xpath('/'.$meta_xpath.'/TEXTE/SOMMAIRE/*/@ID')) {
  $analyses_ids = array_unique($dila->xpath('/'.$meta_xpath.'/TEXTE/SOMMAIRE/*/@ID'));
    foreach ($analyses_ids as $value) {
      $titre_principal = $dila->xpath('/'.$meta_xpath.'/TEXTE/SOMMAIRE/SCT[contains(@ID,"'.$value.'") and contains(@TYPE,"PRINCIPAL")]');
      $titre_secondaire = $dila->xpath('/'.$meta_xpath.'/TEXTE/SOMMAIRE/SCT[contains(@ID,"'.$value.'") and contains(@TYPE,"REFERENCE")]');
      $analyse = $dila->xpath('/'.$meta_xpath.'/TEXTE/SOMMAIRE/ANA[@ID="'.$value.'"]');
      $p = 1;
      foreach ($titre_principal as $values) {
        $analyse_array['ANALYSE id="A'.substr($value,1,1).'"']['TITRE_PRINCIPAL id="P'.$p.'"'] = cdata(toString($values)); $p++;
      }
      $s = 1;
      foreach ($titre_secondaire as $values) {
        $analyse_array['ANALYSE id="A'.substr($value,1,1).'"']['TITRE_SECONDAIRE id="S'.$s.'"'] = cdata(toString($values)); $s++;
      }
      $a = 1;
      foreach ($analyse as $values) {
        $analyse_array['ANALYSE id="A'.substr($value,1,1).'"']['SOMMAIRE id="A'.$a.'"'] = cdata(toString($values)); $a++;
      }
    }
  return $analyse_array;
  }
}

function addRef($references, $titre, $type, $nature, $date, $numero, $nor, $url) {
  if(is_array($references)) { $i = count($references)+1; } else { $i = 1; }
  $titre = toString($titre);
  if(!empty($titre)) {
    $references['REFERENCE id="'.$i.'"'] =
    array(
    'TYPE' => $type,
    'TITRE' => $titre,
    'NATURE' => toString($nature),
    'DATE' => toString($date),
    'NUMERO' => toString($numero),
    'NOR' => toString($nor),
    'URL' => $url
    );
  }
  return unsetEmptyVals($references);
}

function toString($mixed) {
  if(is_array($mixed) || is_object($mixed)) {
    if(is_object($mixed)) { $mixed = (array)$mixed; }
    $mixed = trim(str_replace('Array', '', @implode('', $mixed))); }
  return $mixed;
}

function findOrigine($filename) {
  $orig = array('JURI','CETAT','CONST');
  $origine = '';
  $filename = toString($filename);
  foreach ($orig as $value) {
    if(strpos($filename, $value) !== false) { $origine = $value; }
  }
  if($origine == 'CONST') {
    $origine = 'CONSTIT';
  }
  return $origine;
}

// Qualification : décision du conseil contitutionnel porte sur $
$natureConstit = array("QPC" => "Disposition législative",
                       "DC" => "Loi ordinaire, Loi organique, Traité ou Réglement des Assemblées",
                       "LP" => "Loi du pays",
                       "L" => "Texte législatif",
                       "FNR" => "Proposition de loi ou Amendement",
                       "LOM" => "Répartitions des compétences entre l'État et certaines collectivités d'outre-mer",
                       "AN" => "Élection à l'Assemblée nationale",
                       "SEN" => "Élection au Sénat",
                       "PDR" => "Élection présidentielle",
                       "REF" => "Référendum",
                       "ELECT" => "Élection divers",
                       "ELEC" => "Élection divers",
                       "D" => "Élection d'un parlementaire",
                       "I" => "Élection d'un parlementaire",
                       "AR16" => "Pouvoir exceptionnel du Président de la République",
                       "NOM" => "Nomination des membres du Conseil Constitutionnel",
                       "RAPP" => "Nomination des rapporteurs-adjoints et des délégués auprès du Conseil constitutionnel",
                       "ORGA" => "Décision intéressant le fonctionnement du Conseil constitutionnel",
                       "AUTR" => "Autres textes et décisions");

// Nom du fichier
$res = explode('/', $argv[1]);
$res = array_reverse($res);

try {
  $dila = simplexml_load_file($argv[1]);
}
catch (Exception $e) {
  echo $argv[1]." n'est pas un fichier xml valide\n";
  echo $e->getMessage()."\n";
}

// Identification de l'origine
if (isset($dila->META->META_COMMUN->ORIGINE)) {
  $origine = toString($dila->META->META_COMMUN->ORIGINE);
}
else {
  $origine = findOrigine($res[0]);
}

$references = '';

// Suivant la DTD
switch ($origine) {
  case 'JURI':
    $meta = 'META_JURI_JUDI';
    $meta_xpath = 'TEXTE_JURI_JUDI';
    $type_affaire = typeAffaire($dila->META->META_SPEC->$meta->FORMATION, $argv[1]);
    $numero_affaire = multiple('NUMERO_AFFAIRE', $dila->xpath('/'.$meta_xpath.'/META/META_SPEC/'.$meta.'/NUMEROS_AFFAIRES/*'));
    $titre_supplementaire = '';
    $decision_attaquee = array('DECISION_ATTAQUEE' =>
                              array('TYPE' => 'DECISION',
                                    'FORMATION' => cdata(str_replace(', '.$dila->META->META_SPEC->$meta->DATE_DEC_ATT, '', $dila->META->META_SPEC->$meta->FORM_DEC_ATT)),
                                    'DATE' => toString($dila->META->META_SPEC->$meta->DATE_DEC_ATT),
                                    'NUMERO' => '',
                                    'NOR' => '',
                                    'SIEGE' => cdata($dila->META->META_SPEC->$meta->SIEGE_APPEL),
                                    'JURI_PREM' => cdata($dila->META->META_SPEC->$meta->JURI_PREM),
                                    'LIEU_PREM' => cdata($dila->META->META_SPEC->$meta->LIEU_PREM)
                                   ));
    $parties = array('DEMANDEURS' => array('DEMANDEUR' => cdata(toString($dila->META->META_SPEC->$meta->DEMANDEUR))),
                     'DEFENDEURS' => array('DEFENDEUR' => cdata(toString($dila->META->META_SPEC->$meta->DEFENDEUR))));
    $analyses = parseAnalyse($dila, $meta_xpath);
    foreach ($dila->xpath('/'.$meta_xpath.'/TEXTE/CITATION_JP/*') as $value) {
      if(isset($value)) {
        $references = addRef($references, $value, 'CITATION_ANALYSE', '', '', '', '', '');
      }
    }
    $i = 0;
    foreach ($dila->xpath('/'.$meta_xpath.'/LIENS/*') as $key => $value) {
      $liens[$i] = $value; $i++;
    }
    if(isset($liens)) {
      foreach ($liens as $value) {
        if($value != '') { $references = addRef($references, $value, 'CITATION_ARRET', $value['naturetexte'], $value['datesignatexte'], $value['numtexte'], $value['nortexte'], urlNor($value['nortexte'])); }
      }
    }
    $publication = $dila->xpath('/'.$meta_xpath.'/META/META_SPEC/'.$meta.'/PUBLI_BULL');
    if(!empty($publication[0][0])) {
      $pub = $publication[0][0];
    }
    elseif ($publication[0]['publie'] == 'oui') {
      $pub = 'Publié au bulletin';
    }
    else { $pub = null; }
    $references = addRef($references, $pub, 'PUBLICATION', '', '', '', '', '');
    break;
  case 'CETAT':  // JADE
    $meta = 'META_JURI_ADMIN';
    $meta_xpath = 'TEXTE_JURI_ADMIN';
    $type_affaire = 'Administrative';
    $numero_affaire = '';
    $titre_supplementaire = '';
    $decision_attaquee = '';
    $parties = array('DEMANDEURS' => array('DEMANDEUR' => cdata(toString($dila->META->META_SPEC->$meta->DEMANDEUR))),
          'DEFENDEURS' => array('DEFENDEUR' => cdata(toString($dila->META->META_SPEC->$meta->DEFENDEUR))));
    $analyses = parseAnalyse($dila, $meta_xpath);
    foreach ($dila->xpath('/'.$meta_xpath.'/TEXTE/CITATION_JP/*') as $value) {
      $references = addRef($references, $value, 'CITATION_ANALYSE', '', '', '', '', '');
    }
    $i = 0;
    foreach ($dila->xpath('/'.$meta_xpath.'/LIENS/*') as $key => $value) {
      $liens[$i] = $value; $i++;
    }
    if(isset($liens)) {
      foreach ($liens as $value) {
        if($value != '') { $references = addRef($references, $value, 'CITATION_ARRET', $value['naturetexte'], $value['datesignatexte'], $value['numtexte'], $value['nortexte'], urlNor($value['nortexte'])); }
      }
    }
    if(pubLebon($dila->META->META_SPEC->$meta->PUBLI_RECUEIL)) { $references = addRef($references, pubLebon($dila->META->META_SPEC->$meta->PUBLI_RECUEIL), 'PUBLICATION', '', '', '', '', ''); }
    break;
  case 'CONSTIT':
    $meta = 'META_JURI_CONSTIT';
    $meta_xpath = 'TEXTE_JURI_CONSTIT';
    $type_affaire = toString($dila->META->META_COMMUN->NATURE);
    $numero_affaire = '';
    $titre_supplementaire = cdata(toString($dila->META->META_SPEC->META_JURI->TITRE));
    if (toString($dila->META->META_SPEC->$meta->LOI_DEF) !== '') {
      $decision_attaquee = array('DECISION_ATTAQUEE' =>
                                array('TITRE' => cdata(toString($dila->META->META_SPEC->$meta->LOI_DEF)),
                                      'TYPE' => $natureConstit[toString($dila->META->META_COMMUN->NATURE)],
                                      'FORMATION' => '',
                                      'DATE' => toString($dila->xpath('/'.$meta_xpath.'/META/META_SPEC/'.$meta.'/LOI_DEF/@date')),
                                      'NUMERO' => toString($dila->xpath('/'.$meta_xpath.'/META/META_SPEC/'.$meta.'/LOI_DEF/@num')),
                                      'NOR' => toString($dila->xpath('/'.$meta_xpath.'/META/META_SPEC/'.$meta.'/LOI_DEF/@nor')),
                                      'SIEGE' => '',
                                      'JURI_PREM' => '',
                                      'LIEU_PREM' => ''
                                     ));
      $references = addRef($references,
                           $dila->META->META_SPEC->$meta->LOI_DEF,
                           'DECISION_ATTAQUEE',
                           $natureConstit[toString($dila->META->META_COMMUN->NATURE)],
                           implode('', $dila->xpath('/'.$meta_xpath.'/META/META_SPEC/'.$meta.'/LOI_DEF/@date')),
                           implode('', $dila->xpath('/'.$meta_xpath.'/META/META_SPEC/'.$meta.'/LOI_DEF/@num')),
                           implode('', $dila->xpath('/'.$meta_xpath.'/META/META_SPEC/'.$meta.'/LOI_DEF/@nor')),
                           urlNor(implode('', $dila->xpath('/'.$meta_xpath.'/META/META_SPEC/'.$meta.'/LOI_DEF/@nor')))
                          );
    }
    else {
      $decision_attaquee = array('DECISION_ATTAQUEE' =>
                                array('TYPE' => $natureConstit[toString($dila->META->META_COMMUN->NATURE)]
                                     ));
    }
    $parties = '';
    $analyses = multiple('SOMMAIRE', $dila->xpath('/'.$meta_xpath.'/TEXTE/OBSERVATIONS/*'));
    if (isset($dila->META->META_SPEC->$meta->URL_CC)) {
      $references = addRef($references,
                           'conseil constitutionnel',
                           'SOURCE',
                           $dila->META->META_COMMUN->NATURE,
                           $dila->META->META_SPEC->META_JURI->DATE_DEC,
                           $dila->META->META_SPEC->META_JURI->NUMERO,
                           $dila->META->META_SPEC->$meta->NOR,
                           cdata($dila->META->META_SPEC->$meta->URL_CC)
                          );
    }
    if (urlNor($dila->META->META_SPEC->$meta->NOR)) {
      $references = addRef($references,
                           'légifrance',
                           'SOURCE',
                           $dila->META->META_COMMUN->NATURE,
                           $dila->META->META_SPEC->META_JURI->DATE_DEC,
                           $dila->META->META_SPEC->META_JURI->NUMERO,
                           $dila->META->META_SPEC->$meta->NOR,
                           urlNor($dila->META->META_SPEC->$meta->NOR)
                          );
    }
    if($dila->META->META_SPEC->$meta->TITRE_JO) { $references = addRef($references, $dila->META->META_SPEC->$meta->TITRE_JO, 'PUBLICATION', '', '', '', '', urlPdfJO($dila->META->META_SPEC->$meta->TITRE_JO)); }
    break;
}

// Le texte peut contenir des balises html
$texte_arret = clean(trim(implode("\n", $dila->xpath('/'.$meta_xpath.'/TEXTE/BLOC_TEXTUEL/CONTENU/*'))));

if (empty($texte_arret)) {
  $texte_arret = clean(trim(implode("\n", $dila->xpath('/'.$meta_xpath.'/TEXTE/BLOC_TEXTUEL/CONTENU'))));
}
if (!empty($texte_arret)) {
  $texte_arret = cdata(str_replace(array('<<','>>'), array('«','»'), $texte_arret));
}

// Construction du tableau avec les informations déduites et communes
$juricaf_array = array(
'PAYS' => 'France',
'JURIDICTION' => toString($dila->META->META_SPEC->META_JURI->JURIDICTION),
'FORMATION' => toString($dila->META->META_SPEC->META_JURI->JURIDICTION).' France',
'SECTION' => ucwords(str_replace("_", " ", strtolower($dila->META->META_SPEC->$meta->FORMATION))),
'NUM_ARRET' => toString($dila->META->META_SPEC->META_JURI->NUMERO),
'DATE_ARRET' => toString($dila->META->META_SPEC->META_JURI->DATE_DEC),
'SENS_ARRET' => toString($dila->META->META_SPEC->META_JURI->SOLUTION),
'NUMEROS_AFFAIRES' => $numero_affaire,
'NOR' => toString($dila->META->META_SPEC->$meta->NOR),
'ECLI' => '',
'TITRE_SUPPLEMENTAIRE' => $titre_supplementaire,
'TYPE_AFFAIRE' => $type_affaire,
'TYPE_RECOURS' => ucfirst(toString($dila->META->META_SPEC->$meta->TYPE_REC)),
'SAISINES' => multiple('SAISINE', $dila->xpath('/'.$meta_xpath.'/TEXTE/SAISINES/*')),
'DECISIONS_ATTAQUEES' => $decision_attaquee,
'PRESIDENT' => toString($dila->META->META_SPEC->$meta->PRESIDENT),
'AVOCAT_GL' => toString($dila->META->META_SPEC->$meta->AVOCAT_GL),
'RAPPORTEUR' => toString($dila->META->META_SPEC->$meta->RAPPORTEUR),
'COMMISSAIRE_GVT' => toString($dila->META->META_SPEC->$meta->COMMISSAIRE_GVT),
'AVOCATS' => cdata($dila->META->META_SPEC->$meta->AVOCATS),
'PARTIES' => $parties,
'ANALYSES' => $analyses,
'TEXTE_ARRET' => $texte_arret,
'REFERENCES' => $references,
'RESEAU' => '',
'ID' => toString($dila->META->META_COMMUN->ID)
);

// Suppression des valeurs vides
$juricaf_array = unsetEmptyVals($juricaf_array);
// Débug : var_dump($juricaf_array);

// Conversion du tableau en string xml balisé
$juricaf_str = '<DOCUMENT>';

foreach ($juricaf_array as $key => $value) {
  $tag_fin = explode(" ", $key);
  $juricaf_str .= '<'.$key.'>';
  if(is_array($value)) {
    foreach ($value as $sub1_key => $sub1_value) {
      $sub1_tag_fin = explode(" ", $sub1_key);
      $juricaf_str .= '<'.$sub1_key.'>';
      if(is_array($sub1_value)) {
        foreach ($sub1_value as $sub2_key => $sub2_value) {
          $sub2_tag_fin = explode(" ", $sub2_key);
          $juricaf_str .= '<'.$sub2_key.'>';
          if(is_array($sub2_value)) {
            foreach ($sub2_value as $sub3_key => $sub3_value) {
              $sub3_tag_fin = explode(" ", $sub3_key);
              $juricaf_str .= '<'.$sub3_key.'>'.$sub3_value.'</'.$sub3_tag_fin[0].'>';
            }
          }
          else { $juricaf_str .= $sub2_value.'</'.$sub2_tag_fin[0].'>'; }
        }
        $juricaf_str .= '</'.$sub1_tag_fin[0].'>';
      }
      else { $juricaf_str .= $sub1_value.'</'.$sub1_tag_fin[0].'>'; }
    }
    $juricaf_str .= '</'.$tag_fin[0].'>';
  }
  else { $juricaf_str .= $value.'</'.$tag_fin[0].'>'; }
}

$juricaf_str .= '</DOCUMENT>';

// Conversion du string xml en document xml
try {
$juricaf = simplexml_load_string($juricaf_str, 'SimpleXMLElement', LIBXML_COMPACT | LIBXML_NOCDATA | LIBXML_NOENT | LIBXML_NOBLANKS);
}
catch (Exception $e) {
  echo "Problème lors de la conversion de ".$argv[1]." en xml\n";
  echo $e->getMessage()."\n";
}

// Enregistrement
$file = "../data/pool/France/".$res[0];
$handler = fopen($file,"w");
try {
  fputs($handler,$juricaf->asXML());
  echo $argv[1]." : ok\n";
}
catch (Exception $e) {
  echo "Erreur d'enregistrement de ".$file." (".$argv[1].")\n";
  echo $e->getMessage()."\n";
}
}
else {
  $erreur = "Chemin incorrect : ";
  if(file_exists($argv[1])) { $erreur = "Le fichier est vide : "; }
  echo $erreur.$argv[1]."\n";
}
?>
