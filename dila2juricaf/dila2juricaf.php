<?php
// Usage : $ php dila2juricaf.php fichier_dila.xml

$instant = date("d-m-Y-H:i:s");

$INPUT_FILE=$argv[1];
$CONVERTED_DIR=$argv[2];
$DRYRUN=isset($argv[3]) && $argv[3];

if (!file_exists($INPUT_FILE) || filesize($INPUT_FILE) == 0) {
    $erreur = "chemin incorrect : ";
    if(file_exists($INPUT_FILE)) { $erreur = "le fichier est vide : "; }
    echo "Erreur : ".$erreur.$INPUT_FILE." : ".$instant."\n";
}

  global $mois;

  $mois = array('janvier'=>'01',
                'fevrier'=>'02',
                'mars'=>'03',
                'avril'=>'04',
                'mai'=>'05',
                'juin'=>'06',
                'juillet'=>'07',
                'aout'=>'08',
                'septembre'=>'09',
                'octobre'=>'10',
                'novembre'=>'11',
                'decembre'=>'12'
                );

  // Fonctions
  function replaceAccents($string) {
    $table = array(
        'Å' => 'A', 'Ä' => 'A', 'Ã' => 'A', 'Â' => 'A', 'å' => 'a', 'ä' => 'a', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'Á' => 'A', 'Æ' => 'A', 'æ' => 'a', 'À' => 'A',
        'Þ' => 'B', 'þ' => 'b',
        'ç' => 'c', 'Č' => 'C', 'č' => 'c', 'ć' => 'c', 'Ç' => 'C', 'Ć' => 'C', 'đ' => 'dj', 'Đ' => 'Dj',
        'ê' => 'e', 'É' => 'E', 'ë' => 'e', 'é' => 'e', 'è' => 'e', 'Ë' => 'E', 'È' => 'E', 'Ê' => 'E',
        'í' => 'i', 'ì' => 'i', 'Î' => 'I', 'Ì' => 'I', 'î' => 'i', 'Í' => 'I', 'ï' => 'i', 'Ï' => 'I',
        'ñ' => 'n', 'Ñ' => 'N',
        'ö' => 'o', 'ø' => 'o', 'õ' => 'o', 'ô' => 'o', 'ð' => 'o', 'ò' => 'o', 'ó' => 'o', 'Ö' => 'O', 'Ô' => 'O', 'Ó' => 'O', 'Ò' => 'O', 'Õ' => 'O', 'Ø' => 'O',
        'ŕ' => 'r', 'Ŕ' => 'R',
        'š' => 's', 'Š' => 'S', 'ß' => 'Ss',
        'ü' => 'u', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'Ü' => 'U', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U',
        'ý' => 'y', 'ÿ' => 'y', 'Ý' => 'Y',
        'Ž' => 'Z', 'ž' => 'z'
    );
    return strtr($string, $table);
  }

  function ids($str) {
    $str = preg_replace('/[^a-z0-9\-]/i', '', replaceAccents($str));
    return strtolower($str);
  }

  function multiple($nom, $array_vals) {
    $i = 1;
    $multiples = [];
    foreach ($array_vals as $value) {
      if(trim($value) !== '') {
        $multiples[$nom.' id="'.$i.'"'] = cdata($value); $i++;
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
    $chemin = toString($chemin);
    foreach ($type as $value) {
      if(strpos(strtolower($section), $value)) { $type_affaire = $value; }
      elseif(strpos(strtolower($chemin), $value)) { $type_affaire = $value; }
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
      global $mois;
      $str = replaceAccents($str);
      if (preg_match('/(\d{2}) (\w{3,}) (\d{4}), p. (\d{0,})/', $str, $match)) {
        if(isset($mois[$match[2]]) && $match[3] >= 1947) { // Fac similé du JO depuis 1947
          return cdata('http://legifrance.gouv.fr/jopdf/common/jo_pdf.jsp?numJO=0&amp;dateJO='.$match[3].$mois[$match[2]].$match[1].'&amp;pageDebut='.sprintf('%05d', $match[4]));
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
    if(is_array($references)) { $i = count($references)+1; } else { $references = [] ; $i = 1; }
    $titre = toString($titre);
    if(!empty($titre)) {
      $references['REFERENCE id="'.$i.'"'] =
      array(
      'TYPE' => $type,
      'TITRE' => cdata($titre),
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
      $mixed = trim(str_replace('Array', '', @implode('', $mixed)));
    }
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

  function is_date($date) {
    if(preg_match('/(\d{2})[\/.](\d{2})[\/.](\d{4})/', $date, $match)) {
      $date = $match[3]."-".$match[2]."-".$match[1];
    }
    if(preg_match('/(\d{4})-(\d{2})-(\d{2})/', $date, $match)) {
      if ($match[1] > date('Y') || $match[1] < 1000) { // date dans le futur ou trop ancienne
        $date = '';
      }
    }
    else { $date = ''; }
    return $date;
  }

  function normalizeJurid($juri) {
    $tribunaux = array(
    "Cour administrative d'appel",
    "Cour d'appel",
    "Tribunal administratif",
    "Tribunal d'instance",
    "Tribunal de commerce",
    "Tribunal de grande instance",
    "Tribunal des affaires de sécurité sociale",
    "Juridiction de proximité",
    "Conseil de prud'hommes"
    );

    $voyelles = array('a', 'e', 'i', 'o', 'u', 'y');
    $tribunal = '';
    foreach ($tribunaux as $t) {
      if(strpos($juri, $t) !== false) {
        $ville = trim(str_replace($t, '', $juri));
        if(!preg_match("/^de|d'/", $ville)) {
          if(in_array(substr($ville, 0, 1), $voyelles)) {
            $ville = " d'".$ville;
          }
          else {
            $ville = " de ".$ville;
          }
          $juri = $t;
          $tribunal = $t.$ville;
          break;
        }
      }
    }
    return array($juri, $tribunal);
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
                         "ELEC" => "Élection divers",
                         "D" => "Élection d'un parlementaire",
                         "I" => "Élection d'un parlementaire",
                         "AR16" => "Pouvoir exceptionnel du Président de la République",
                         "NOM" => "Nomination des membres du Conseil Constitutionnel",
                         "RAPP" => "Nomination des rapporteurs-adjoints et des délégués auprès du Conseil constitutionnel",
                         "ORGA" => "Décision intéressant le fonctionnement du Conseil constitutionnel",
                         "AUTR" => "Autres textes et décisions");

  // Nom du fichier
  $res = explode('/', $INPUT_FILE);
  $res = array_reverse($res);

  try {
    $dila = simplexml_load_file($INPUT_FILE, 'SimpleXMLElement', LIBXML_COMPACT);
  }
  catch (Exception $e) {
    echo "Erreur : ".$INPUT_FILE." n'est pas un fichier xml valide : ".$e->getMessage()."\n";
  }

  // Identification de l'origine
  if (isset($dila->META->META_COMMUN->ORIGINE)) {
    $origine = toString($dila->META->META_COMMUN->ORIGINE);
  }
  else {
    $origine = findOrigine($res[0]);
  }

  $references = '';
  $saisine = '';

  // Suivant la DTD
  switch ($origine) {
    case 'JURI':
      $meta = 'META_JURI_JUDI';
      $meta_xpath = 'TEXTE_JURI_JUDI';
      $type_affaire = typeAffaire($dila->META->META_SPEC->$meta->FORMATION, $dila->META->META_COMMUN->URL);
      $numero_affaire = multiple('NUMERO_AFFAIRE', $dila->xpath('/'.$meta_xpath.'/META/META_SPEC/'.$meta.'/NUMEROS_AFFAIRES/*'));
      $titre_supplementaire = '';
      $decision_attaquee = array('DECISION_ATTAQUEE' =>
                                array('TYPE' => 'DECISION',
                                      'FORMATION' => cdata(ucfirst(rtrim(trim(str_replace($dila->META->META_SPEC->$meta->DATE_DEC_ATT, '', $dila->META->META_SPEC->$meta->FORM_DEC_ATT)), ','))),
                                      'DATE' => is_date(toString($dila->META->META_SPEC->$meta->DATE_DEC_ATT)),
                                      'NUMERO' => '',
                                      'NOR' => '',
                                      'SIEGE' => cdata($dila->META->META_SPEC->$meta->SIEGE_APPEL),
                                      'JURI_PREM' => cdata($dila->META->META_SPEC->$meta->JURI_PREM),
                                      'LIEU_PREM' => cdata($dila->META->META_SPEC->$meta->LIEU_PREM)
                                     ));
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
      else { $pub = false; }
      if($pub !== false) {
        $references = addRef($references, $pub, 'PUBLICATION', '', '', '', '', '');
      }
      break;
    case 'CETAT':  // JADE
      $meta = 'META_JURI_ADMIN';
      $meta_xpath = 'TEXTE_JURI_ADMIN';
      $type_affaire = 'Administrative';
      $numero_affaire = '';
      $titre_supplementaire = '';
      $decision_attaquee = '';
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
          if($value !== '') { $references = addRef($references, $value, 'CITATION_ARRET', $value['naturetexte'], $value['datesignatexte'], $value['numtexte'], $value['nortexte'], urlNor($value['nortexte'])); }
        }
      }
      if(pubLebon($dila->META->META_SPEC->$meta->PUBLI_RECUEIL)) { $references = addRef($references, pubLebon($dila->META->META_SPEC->$meta->PUBLI_RECUEIL), 'PUBLICATION', '', '', '', '', ''); }
      break;
    case 'CONSTIT':
      $meta = 'META_JURI_CONSTIT';
      $meta_xpath = 'TEXTE_JURI_CONSTIT';
      $type_affaire = toString($dila->META->META_COMMUN->NATURE);
      if($type_affaire == 'ELECT') { $type_affaire = 'ELEC'; }
      $numero_affaire = '';
      $titre_supplementaire = cdata(toString($dila->META->META_SPEC->META_JURI->TITRE));
      if (toString($dila->META->META_SPEC->$meta->LOI_DEF) !== '') {
        $decision_attaquee = array('DECISION_ATTAQUEE' =>
                                  array('TITRE' => cdata(toString($dila->META->META_SPEC->$meta->LOI_DEF)),
                                        'TYPE' => $natureConstit[$type_affaire],
                                        'FORMATION' => '',
                                        'DATE' => is_date(toString($dila->xpath('/'.$meta_xpath.'/META/META_SPEC/'.$meta.'/LOI_DEF/@date'))),
                                        'NUMERO' => toString($dila->xpath('/'.$meta_xpath.'/META/META_SPEC/'.$meta.'/LOI_DEF/@num')),
                                        'NOR' => toString($dila->xpath('/'.$meta_xpath.'/META/META_SPEC/'.$meta.'/LOI_DEF/@nor'))
                                       ));
        $references = addRef($references,
                             $dila->META->META_SPEC->$meta->LOI_DEF,
                             'DECISION_ATTAQUEE',
                             $natureConstit[$type_affaire],
                             toString($dila->xpath('/'.$meta_xpath.'/META/META_SPEC/'.$meta.'/LOI_DEF/@date')),
                             toString($dila->xpath('/'.$meta_xpath.'/META/META_SPEC/'.$meta.'/LOI_DEF/@num')),
                             toString($dila->xpath('/'.$meta_xpath.'/META/META_SPEC/'.$meta.'/LOI_DEF/@nor')),
                             urlNor(toString($dila->xpath('/'.$meta_xpath.'/META/META_SPEC/'.$meta.'/LOI_DEF/@nor')))
                            );
      }
      else {
        $decision_attaquee = array('DECISION_ATTAQUEE' =>
                                  array('TYPE' => $natureConstit[$type_affaire]
                                       ));
      }
      $analyses = multiple('SOMMAIRE', $dila->xpath('/'.$meta_xpath.'/TEXTE/OBSERVATIONS/*'));
      if (isset($dila->META->META_SPEC->$meta->URL_CC)) {
        $references = addRef($references,
                             'site internet du Conseil constitutionnel',
                             'SOURCE',
                             $type_affaire,
                             $dila->META->META_SPEC->META_JURI->DATE_DEC,
                             $dila->META->META_SPEC->META_JURI->NUMERO,
                             $dila->META->META_SPEC->$meta->NOR,
                             cdata($dila->META->META_SPEC->$meta->URL_CC)
                            );
      }
      if (urlNor($dila->META->META_SPEC->$meta->NOR)) {
        $references = addRef($references,
                             'site internet Légifrance',
                             'SOURCE',
                             $type_affaire,
                             $dila->META->META_SPEC->META_JURI->DATE_DEC,
                             $dila->META->META_SPEC->META_JURI->NUMERO,
                             $dila->META->META_SPEC->$meta->NOR,
                             urlNor($dila->META->META_SPEC->$meta->NOR)
                            );
      }
      if(isset($dila->META->META_SPEC->$meta->TITRE_JO)) { $references = addRef($references, $dila->META->META_SPEC->$meta->TITRE_JO, 'PUBLICATION', '', '', '', '', ''); }

      if($saisine_auteur = toString($dila->xpath('/'.$meta_xpath.'/TEXTE/SAISINES/SAISINE/@AUTEUR'))) { $saisine .= $saisine_auteur."\n\n" ;}
      $saisine .= clean(trim(implode("\n", $dila->xpath('/'.$meta_xpath.'/TEXTE/SAISINES//*'))));
      if(strlen($saisine) < 30) { $saisine = ''; }

      break;
  }

  // Supprimer les balises html du texte de l'arrêt
  $texte_arret = clean(trim(implode("\n", $dila->xpath('/'.$meta_xpath.'/TEXTE/BLOC_TEXTUEL/CONTENU//*'))));
  if (empty($texte_arret)) {
    $texte_arret = clean(trim(implode("\n", $dila->xpath('/'.$meta_xpath.'/TEXTE/BLOC_TEXTUEL/CONTENU'))));
  }

  if (!empty($texte_arret)) {
    $texte_arret = cdata($texte_arret);
  }

  // Cas particuliers : Juridiction
  $juridiction = ucfirst(strtolower(toString($dila->META->META_SPEC->META_JURI->JURIDICTION)));
  $juridiction = str_replace("Caa", "Cour administrative d'appel", $juridiction);
  $r = normalizeJurid($juridiction); // ajoute "de" ou "d'" à la ville si manquant
  $juridiction = $r[0];
  $tribunal = '';
  if ($r[1]) {
      $tribunal = $r[1];
  }

  // Cas particuliers : Numero d'arrêt
  if(!preg_match('/\n/', toString($dila->META->META_SPEC->META_JURI->NUMERO))) {
    $num_arret = str_replace(", ", ",", toString($dila->META->META_SPEC->META_JURI->NUMERO));
  }
  else {
    $num_arret = toString($dila->META->META_SPEC->META_JURI->NUMERO);
  }

  // Construction du tableau avec les informations déduites et communes
  $juricaf_array = array(
  'PAYS' => 'France',
  'JURIDICTION' => $juridiction,
  'FORMATION' => ucfirst(strtolower(str_replace("_", " ", toString($dila->META->META_SPEC->$meta->FORMATION)))),
  'NUM_ARRET' => $num_arret,
  'DATE_ARRET' => is_date(toString($dila->META->META_SPEC->META_JURI->DATE_DEC)),
  'SENS_ARRET' => ucfirst(strtolower(toString($dila->META->META_SPEC->META_JURI->SOLUTION))),
  'NUMEROS_AFFAIRES' => $numero_affaire,
  'NOR' => toString($dila->META->META_SPEC->$meta->NOR),
  'ECLI' => '',
  'TITRE_SUPPLEMENTAIRE' => $titre_supplementaire,
  'TYPE_AFFAIRE' => $type_affaire,
  'TYPE_RECOURS' => ucfirst(toString($dila->META->META_SPEC->$meta->TYPE_REC)),
  'SAISINES' => $saisine,
  'DECISIONS_ATTAQUEES' => $decision_attaquee,
  'PRESIDENT' => toString($dila->META->META_SPEC->$meta->PRESIDENT),
  'AVOCAT_GL' => toString($dila->META->META_SPEC->$meta->AVOCAT_GL),
  'RAPPORTEUR' => toString($dila->META->META_SPEC->$meta->RAPPORTEUR),
  'COMMISSAIRE_GVT' => toString($dila->META->META_SPEC->$meta->COMMISSAIRE_GVT),
  'AVOCATS' => cdata($dila->META->META_SPEC->$meta->AVOCATS),
  'ANALYSES' => $analyses,
  'TEXTE_ARRET' => $texte_arret,
  'REFERENCES' => $references,
  'FONDS_DOCUMENTAIRE' => 'Légifrance',
  'RESEAU' => '',
  'ID' => toString($dila->META->META_COMMUN->ID)
  );
  if ($tribunal) {
      $juricaf_array['TRIBUNAL'] = $tribunal;
  }

  if(empty($texte_arret)) {
    $juricaf_array['NO_ERROR'] = 'empty_text';
  }
  // Suppression des valeurs vides
  $juricaf_array = unsetEmptyVals($juricaf_array);
  // Débug : var_dump($juricaf_array);

  // Numéros d'affaires en tant que num_arret en priorité pour la cour de cassation
  if($juricaf_array['JURIDICTION'] == 'Cour de cassation' && isset($juricaf_array['NUMEROS_AFFAIRES'])) {
    $num_affaire = '';
    foreach ($juricaf_array['NUMEROS_AFFAIRES'] as $values) {
      $sep = '';
      if (!empty($num_affaire)) { $sep = ';'; }
      $num_affaire .= $sep.str_replace(array("<![CDATA[", "]]>"), '', $values);
    }
    if(!empty($juricaf_array['NUM_ARRET'])) {
      $juricaf_array['NUM_DECISION'] = $juricaf_array['NUM_ARRET'];
    }
    // num_arret predictible
    $num_predictible = explode(';', $num_affaire);
    $num_predictible = $num_predictible[0];
    $juricaf_array['NUM_ARRET'] = '<![CDATA['.$num_affaire.']]>';
    if(strlen($num_affaire) > 30) { $juricaf_array['NUM_TROS_GROS'] = 'ok'; }
  }

  // Conversion du tableau en string xml balisé
  $juricaf_str = '<?xml version="1.0" encoding="UTF-8"?><DOCUMENT>';

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
    $juricaf = simplexml_load_string($juricaf_str, 'SimpleXMLElement', LIBXML_COMPACT);
  }
  catch (Exception $e) {
    echo "Erreur : problème lors de la conversion de ".$INPUT_FILE." en xml : ".$instant."\n";
    echo $e->getMessage()."\n";
  }

  // Sous dossiers année/institution
  if(empty($juricaf_array['DATE_ARRET'])) { $date_rec = "date_manquante"; } else { $date_rec = substr($juricaf_array['DATE_ARRET'], 0, 4); }
  $dir = $CONVERTED_DIR."/France/".$date_rec."/".ids($juricaf_array['JURIDICTION']) ;
  if (!is_dir($dir)) {
    try {
      mkdir($dir, 0777, true);
    }
    catch (Exception $e) {
      echo "Erreur : échec de la création du dossier ".$dir." : ".$instant."\n";
      echo $e->getMessage()."\n";
    }
  }

  // Id prédictible
  $juri_rec = str_replace('-', '', strtoupper(ids($juricaf_array['JURIDICTION'])));

  if($date_rec == 'date_manquante') { $date_rec = date('Ymd'); }
  else { $date_rec = str_replace('-', '', $juricaf_array['DATE_ARRET']); }

  if (empty($juricaf_array['NUM_ARRET']))
  {
    if (isset($juricaf_array['NUMEROS_AFFAIRES']))
    {
      $num_rec = '';
      foreach ($juricaf_array['NUMEROS_AFFAIRES'] as $values) {
        $sep = '';
        if (is_array($values)) {
          foreach ($values as $vals) {
            if (!empty($num_rec)) { $sep = ';'; }
            $num_rec .= $sep.$vals;
          }
        }
        else {
          if (!empty($num_rec)) { $sep = ';'; }
          $num_rec .= $sep.$values;
        }
      }
      $num_rec = str_replace(';', '-', $num_rec);
    }
    elseif (isset($juricaf_array['NOR']))
    {
      $num_rec = $juricaf_array['NOR'];
    }
    else
    {
      $num_rec = $juricaf_array['ID'];
    }
    if(empty($num_rec)) {
      $num_rec = 'RANDOM'.mt_rand();
    }
  }
  else { $num_rec = $juricaf_array['NUM_ARRET']; }

  $num_rec = str_replace(array("<![CDATA[", "]]>"), '', $num_rec);
  if(!empty($num_predictible)) { $num_rec = $num_predictible; }
  $num_rec = preg_replace('/[^a-z0-9;à]/i', '', $num_rec);
  $num_arret_id = str_replace(';', '-', $num_rec);

  $id_predictible = "FRANCE-".$juri_rec."-".$date_rec."-".$num_rec;
  if(!$DRYRUN) {
    // Enregistrement
    try {
      $file = $dir."/".$res[0];
      $handler = fopen($file,"w");
      fputs($handler,$juricaf->asXML());
      echo $id_predictible." : ".$res[0]." : ".$instant."\n";
    }
    catch (Exception $e) {
      echo "Erreur : enregistrement de ".$file." a échoué (".$INPUT_FILE.") : ".$instant."\n";
      echo $e->getMessage()."\n";
    }
  }
  // Ordre de suppression Dila
  else { echo "$id_predictible\n"; }
