<?php
setlocale(LC_TIME, 'fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8');

global $errors;
$errors = '';

$mois = array(
        '01'=>'janvier',
        '02'=>'février',
        '03'=>'mars',
        '04'=>'avril',
        '05'=>'mai',
        '06'=>'juin',
        '07'=>'juillet',
        '08'=>'août',
        '09'=>'septembre',
        '10'=>'octobre',
        '11'=>'novembre',
        '12'=>'décembre',
        );

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

// Date pour _id couchdb
function date_id($d) {
  $d = explode('-', $d);
  $date = $d[0].$d[1].$d[2];
  return $date;
}

// Convert a string to juricaf ids
function ids($str) {
  $str = preg_replace('/[^a-z0-9\-]/i', '', replaceAccents($str));
  return strtoupper($str);
}

function cleanArray($array) {
  $array = array_filter($array);
  $array = array_change_key_case($array, CASE_LOWER);
  foreach ($array as $key => $value) {
    if  ($key === '@attributes') {
      unset($array[$key]);
      continue;
    }
    if (is_array($value) || is_object($value)) {
      if(is_object($value)) { $value = (array)$value; } ;
      $array[$key] = cleanArray($value);
    }
    else { $array[$key] = trim(str_replace(array('<<','>>','<','>'), array('«','»','',''), $value)); }
  }
  $array = array_filter($array);
  return $array;
}

// pour convertisseur V1 -> V2
function extractFormationDate($str, $type = "DECISION") {
  $str = ucfirst(trim(rtrim($str, ',.')));
  $value = array_reverse(explode(',', $str));
  if(count($value) >= 2) { // si formation, date
    if(preg_match('/^(\d{2})[\/|\.]{1}(\d{2})[\/|\.]{1}(\d{4})$/', trim($value[0]), $date_dec)) {
      $date = $date_dec[3]."-".$date_dec[2]."-".$date_dec[1];
      $formation = trim(rtrim(str_replace($value[0], '', $str), ','));
      $extracted = array('type' => $type, 'formation' => $formation, 'date' => $date);
    }
    else {
      $extracted = array('type' => $type, 'formation' => $str);
    }
  }
  else {
    $extracted = array('type' => $type, 'formation' => $str);
  }
  return $extracted;
}

function addError($str) {
  global $errors, $res;
  $errors[count($errors)] = $str;
  sort($errors);
  $res['type'] = 'error_arret';
  $res['on_error'] = implode(', ', $errors);
}

function toString($mixed) {
  if(is_array($mixed) || is_object($mixed)) {
    if(is_object($mixed)) { $mixed = (array)$mixed; }
    $mixed = trim(str_replace('Array', '', @implode('', $mixed)));
  }
  return $mixed;
}

// Pour sens_arret
function correctWrongSpelling($string) {
  $fautes = array(
    "anullation" => "annulation",
    " arrete de " => " arrêté de ",
    "attaquee" => "attaquée",
    "d'arret" => "d'arrêt",
    "l'arrete" => "l'arrêté",
    "l'arret" => "l'arrêt",
    "attaquee" => "attaquée",
    "casstion" => "cassation",
    "competence" => "compétence",
    "compérence" => "compétence",
    "condamantion" => "condamnation",
    "decheance" => "déchéance",
    "decision" => "décision",
    "declaration" => "déclaration",
    "defaut" => "défaut",
    "etait" => "était",
    "l'etat" => "l'état",
    "evocation" => "évocation",
    "execution" => "exécution",
    "executioin" => "exécution",
    "interprètation" => "interprétation",
    "interpretation" => "interprétation",
    "irrecevabilite" => "irrecevabilité",
    "irrecevebilite" => "irrecevabilité",
    "irrevabilité" => "irrecevabilité",
    "irecevabilité" => "irrecevabilité",
    "irrrecevabilité" => "irrecevabilité",
    "irrececevabilité" => "irrecevabilité",
    "irrecvabilité" => "irrecevabilité",
    "irrecevabilté" => "irrecevabilité",
    "irrecevabilit2" => "irrecevabilité",
    "levee" => "levée",
    "lévée" => "levée",
    "legale" => "légale",
    "prejudice" => "préjudice",
    "prevenu" => "prévenu",
    "ministere" => "ministère",
    "qualite" => "qualité",
    "rcevabilité" => "recevabilité",
    "recevabilite" => "recevabilité",
    "rexecevabilité" => "recevabilité",
    "refere " => "référé ",
    "reglement" => "règlement",
    "rejt" => "rejet",
    "reouverture" => "réouverture",
    "revision" => "révision",
    "requerant" => "requérant",
    "requete" => "requête",
    "startuer" => "statuer"
  );
  if(strlen($string) < 120) {
    $string = strtolower($string);
  }
  return rtrim(ucfirst(strtr($string, $fautes)), '.,;');
}

// Chargement du fichier xml
$obj = simplexml_load_file("data.xml", 'SimpleXMLElement', LIBXML_COMPACT | LIBXML_NOCDATA | LIBXML_NOENT | LIBXML_NOBLANKS);
$res = (array)$obj;
$res = cleanArray($res);

if (!count($res)) {
  fprintf(STDERR, 'id":"UNKNOWN","error":"XML parsing","reason":"Cannot parse '.$argv[1]."\"\n");
  exit(33);
}

$res['type'] = 'arret';

if(!isset($res['pays']) && isset($argv[2])) { $res['pays'] = $argv[2]; }
if(isset($res['pays'])) { $res['pays'] = toString($res['pays']); }
if(empty($res['pays'])) { addError("pays manquant"); $res['pays'] = 'inconnu'; }
$res['pays'] = ucfirst(strtolower($res['pays']));

if(!isset($res['juridiction']) && isset($argv[3])) { $res['juridiction'] = $argv[3]; }
if(isset($res['juridiction'])) { $res['juridiction'] = toString($res['juridiction']); }
if(empty($res['juridiction'])) { addError("juridiction manquante"); $res['juridiction'] = 'inconnue'; }
$res['juridiction'] = ucfirst(strtolower($res['juridiction']));

// Gestion des numéros d'arrets
if (empty($res['num_arret']))
{
  if (isset($res['numeros_affaires']))
  {
    $res['num_arret'] = '';
    foreach ($res['numeros_affaires'] as $values) {
      $sep = '';
      if (is_array($values)) {
        foreach ($values as $vals) {
          if (!empty($res['num_arret'])) { $sep = ';'; }
          $res['num_arret'] .= $sep.$vals;
        }
      }
      else {
        if (!empty($res['num_arret'])) { $sep = ';'; }
        $res['num_arret'] .= $sep.$values;
      }
    }
  }
  elseif (isset($res['nor']))
  {
    $res['num_arret'] = $res['nor'];
  }
  else
  {
    $res['num_arret'] = $res['id'];
  }
  if(empty($res['num_arret']) || $res['num_arret'] == '.') {
    addError("numéro d'arrêt : nombre aléatoire généré");
    $res['num_arret'] = 'RANDOM'.mt_rand();
  }
}

//////// Cas particuliers ////////

// Général num_arret

$cas_num_arret = array(
    'Arrêt N° ' => '',
    'N° ' => '',
    'N °' => '',
    'n° ' => '',
    'n°' => '',
    'N°' => '',
    '° ' => '',
    ' - ' => '-',
    ' / ' => '/',
    '/ ' => '/',
    '  /' => '/',
    ' /' => '/',
    'BIS' => 'bis',
    'Bis' => 'bis',
    ' bis' => 'bis'
    );

$res['num_arret'] = trim(rtrim(strtr($res['num_arret'], $cas_num_arret), '-'));
$res['num_arret'] = preg_replace('/ +/', ' ', $res['num_arret']); // supprime les espaces redondants

$max_length_without_lf = 200; // si texte_arret ne comporte pas de retours à la ligne et excède cette limite de nombre de caractères = erreur

// Gestion spécifique par pays

if($res['pays'] == "Côte d-ivoire" || $res['pays'] == "Côte d'ivoire") { $res['pays'] = "Côte d'Ivoire"; }

if($res['pays'] == 'France') {

  $max_length_without_lf = 4000; // les arrêts anciens sont affichés tels quels sur légifrance

  // Majuscules juridictions
  $villes_fr = array(
    "amiens" => "Amiens",
    "angers" => "Angers",
    "auch" => "Auch",
    "basse-terre" => "Basse-Terre",
    "bastia" => "Bastia",
    "besançon" => "Besançon",
    "bordeaux" => "Bordeaux",
    "caen" => "Caen",
    "cayenne" => "Cayenne",
    "châlons-en-champagne" => "Châlons-en-Champagne",
    "châlons-sur-marne" => "Châlons-sur-Marne",// nommée ainsi jusqu'en 1995 (et une courte période en 1997) puis est devenue Châlons-en-champagne
    "clermont-ferrand" => "Clermont-Ferrand",
    "condom" => "Condom",
    "dijon" => "Dijon",
    "douai" => "Douai",
    "fort-de-france" => "Fort-de-France",
    "grenoble" => "Grenoble",
    "illkirch-graffenstaden" => "Illkirch-Graffenstaden",
    "lille" => "Lille",
    "limoges" => "Limoges",
    "lyon" => "Lyon",
    "marseille" => "Marseille",
    "montpellier" => "Montpellier",
    "nancy" => "Nancy",
    "nantes" => "Nantes",
    "nice" => "Nice",
    "nouméa" => "Nouméa",
    "orléans" => "Orléans",
    "papeete" => "Papeete",
    "paris" => "Paris",
    "pau" => "Pau",
    "poitiers" => "Poitiers",
    "rennes" => "Rennes",
    "rouen" => "Rouen",
    "saint-denis de la réunion" => "Saint-Denis-de-la-Réunion",
    "strasbourg" => "Strasbourg",
    "toulouse" => "Toulouse",
    "versailles" => "Versailles"
  );
  $res['juridiction'] = strtr($res['juridiction'], $villes_fr);

  // Numéros d'arrêts contenant un/des espaces
  if (preg_match('/ /', $res['num_arret'])) {
    $num_tmp = strtolower(replaceAccents($res['num_arret']));
    $to_replace = array(
      ' !' => '',
      'et ' => ''
    );
    $num_tmp = strtr($num_tmp, $to_replace);

    if(preg_match_all('/^([0-9]{5,7}(bis |bis a | a | ))+$/', $num_tmp.' ', $match)) { // 68521 à 68524 68527bis 68640 68645
      $res['num_arret'] = str_replace(array(' a ',' '), array('à',';'), $num_tmp);
      if(strlen($res['num_arret']) > 30) { $long_ok = true; }
    }

    elseif(preg_match_all('/^([0-9]{2}[\.-]{1}[0-9]{3,4} )+$/', $num_tmp.' ', $match)) { // 98-413 98-526 98-898 93.3070 93.2254
      $res['num_arret'] = str_replace(array('.', ' '), array('-', ';'), $num_tmp);
      if(strlen($res['num_arret']) > 30) { $long_ok = true; }
    }

    elseif(preg_match_all('/^([0-9]{2}[a-z]{2}[0-9]{5} )+$/', $num_tmp.' ', $match)) { // 89PA00700 89PA00741 89PA00746
      $res['num_arret'] = strtoupper(str_replace(' ', ';', $num_tmp));
      if(strlen($res['num_arret']) > 30) { $long_ok = true; }
    }

    elseif(preg_match_all('/^([0-9]{2}(-| )[0-9]{2} ?[0-9]{3}(bis |bis a | a |;| ))+$/', $num_tmp.' ', $match)) { // 60-40 457 60-40 458 60 40457 60 40 458 04-41362 à 04-44368
      $res['num_arret'] = preg_replace('/([0-9]{2})(-| )([0-9]{2}) ?([0-9]{3})(bis |bis a | a |;| )/', '\1-\3.\4\5', $num_tmp.' ');
      $res['num_arret'] = str_replace(array('bis a ',' a ',' '), array('bisà','à',';'), $res['num_arret']);
      if(strlen($res['num_arret']) > 30) { $long_ok = true; }
    }

    elseif(preg_match_all('/^([0-9]{4}-[0-9]{4}(bis |bis a | a | ))+$/', $num_tmp.' ', $match)) { // 2008-4509 à 2008-4514
      $res['num_arret'] = str_replace(array(' a ',' '), array('à',';'), $num_tmp);
      if(strlen($res['num_arret']) > 30) { $long_ok = true; }
    }
  }
}

if($res['pays'] == 'Niger') { $res['num_arret'] = str_replace('--', '-', $res['num_arret']); }

if($res['pays'] == 'Luxembourg') {
  $to_replace = array(
    'pénal' => '',
    'Vac' => '',
    ' ' => ''
  );
  $num_tmp = trim(strtr($res['num_arret'], $to_replace));
  if(preg_match('/^[0-9]{2}\/[0-9]{2,4}$/', $num_tmp)) { // 10/2004
    $res['num_arret'] = $num_tmp;
  }
}

if($res['pays'] == 'Madagascar') {
  $to_replace = array(
    ' = et autres' => '',
    '=' => ';',
    '_' => '',
    ' ' => ''
  );
  $num_tmp = strtoupper(strtr($res['num_arret'], $to_replace));
  if(preg_match_all('/(([0-9]{1,4}\/[0-9]{1,4})-?ADM);?/', $num_tmp, $match, PREG_SET_ORDER)) { // 119/85-ADM;119/85-ADM
    $res['num_arret'] = '';
    foreach ($match as $num) {
      $res['num_arret'] .= $num[2].'-ADM;';
    }
    $res['num_arret'] = rtrim($res['num_arret'], ';');
    if(strlen($res['num_arret']) > 30) { $long_ok = true; }
  }
}

if($res['pays'] == 'Suisse' && $res['juridiction'] == 'Tribunal fédéral') { $res['juridiction'] = 'Tribunal fédéral suisse'; }

if($res['pays'] == 'Suisse') {
  if(preg_match('/^[a-zA-Z]{1} [0-9]+\/[0-9]+/', $res['num_arret'])) { // 5C.221/1996
    $res['num_arret'] = str_replace(' ', '.', $res['num_arret']);
  }
}

if($res['pays'] == 'Pologne') { // III_SPZP_3/05
  if(preg_match('/^[a-zA-Z]{1,5} [a-zA-Z]{1,5} [0-9]{1,5}\/[0-9]{1,5}/', $res['num_arret'])) {
    $res['num_arret'] = strtoupper(str_replace(' ', '_', $res['num_arret']));
  }
}

if(replaceAccents($res['pays']) == 'Benin') { // 43/CJ-CT
  if(preg_match('/^[0-9]{1,5} [a-zA-Z]{1,3}-[a-zA-Z]{1,3}$/', $res['num_arret'])) {
    $res['num_arret'] = strtoupper(str_replace(' ', '/', $res['num_arret']));
  }
}

if(replaceAccents($res['pays']) == 'Benelux') { // A 2009/5
  if(preg_match('/^[a-zA-Z]{1} [0-9]{2,4}\/[0-9]{1,}$/', $res['num_arret'])) {
    $res['num_arret'] = strtoupper(str_replace(' ', '_', $res['num_arret']));
  }
  $res['pays'] == 'Benelux';
}

if($res['pays'] == 'Burundi') { // R.C.C.10.322
  $to_replace = array(
    'RCC.' => 'R.C.C.',
    'RPC.' => 'R.P.C.',
    'RAA.' => 'R.A.A.',
    'RSC.' => 'R.S.C.'
  );
  $res['num_arret'] = rtrim(strtr(strtoupper($res['num_arret']), $to_replace), '.');
  if(preg_match('/^([A-Z]{1}.{1}){3} ([0-9.]{2,})$/', $res['num_arret'], $match)) {
    $res['num_arret'] = str_replace($match[2], number_format(str_replace('.', '', $match[2]), 0, ',', '.'), $res['num_arret']);
    $res['num_arret'] = str_replace(' ', '', $res['num_arret']);
  }
}

if($res['pays'] == 'Hongrie') { // Kfv.III.35.215/1999
  if(preg_match('/^[a-zA-Z]{2,5}[.]{1} [a-zA-Z]{1,5}[.]{1} [0-9.\/]{2,}$/', $res['num_arret'])) {
    $res['num_arret'] = str_replace(' ', '', $res['num_arret']);
  }
}

if(replaceAccents($res['pays']) == 'Congo democratique') { // RP.1695
  $num_tmp = strtr($res['num_arret'], array('.' => '', ' ' => ''));
  if(preg_match('/^([a-zA-Z]{2})([0-9]{2,})$/', $num_tmp, $match)) {
    $res['num_arret'] = strtoupper($match[1].'.'.$match[2]);
  }
}

if($res['pays'] == 'Rwanda') { // RPA.A.0022/05/CS
  $num_tmp = strtr($res['num_arret'], array('.' => '', ' ' => ''));
  if(preg_match('/^([a-zA-Z]{3})([a-zA-Z]{1})([a-zA-Z0-9\/]+)$/', $num_tmp, $match)) {
    $res['num_arret'] = strtoupper($match[1].'.'.$match[2].'.'.$match[3]);
  }
}

if(replaceAccents($res['pays']) == 'Republique tcheque') {
  if(preg_match('/^([0-9]{1,3}) ([a-zA-Z]{2,4}) ([0-9\/]+)$/', $res['num_arret'], $match)) {
    $res['num_arret'] = $match[1].'_'.ucfirst($match[2]).'_'.$match[3]; // 29_Odo_1216/2005
  }
  $res['pays'] = 'République Tchèque';
}

if($res['pays'] == 'Canada') { // 30214;30729;30730
  $to_replace = array(
    ': ' => '',
    '; ' => ';',
    ', ' => ';',
    ',' => ';',
    ' ' => ';'
  );
  $num_tmp = rtrim(strtr($res['num_arret'], $to_replace), '.;').';';
  if(preg_match('/^([0-9]{5};)+$/', $num_tmp)) {
    $res['num_arret'] = rtrim($num_tmp, ';');
    if(strlen($res['num_arret']) > 30) { $long_ok = true; }
  }
}

if($res['pays'] == 'Belgique') { // P.05.0988.N ou énumération séparé par -
  if(preg_match('/^([a-zA-Z]{1}.[0-9]{2}.[0-9]{4}.[a-zA-Z]{1}-?)+$/', $res['num_arret'])) {
    $res['num_arret'] = rtrim($res['num_arret'], '-');
    if(strlen($res['num_arret']) > 30) { $long_ok = true; }
  }
}

if(replaceAccents($res['pays']) == 'Sao tome et principe') {
  $res['pays'] = 'Sao Tomé et Principe';
}

if($res['pays'] == 'Burkina faso') {
  $res['pays'] = 'Burkina Faso';
}

if($res['pays'] == "Conseil de l'europe") {
  $res['pays'] = "Conseil de l'Europe";
}

if($res['pays'] == 'Cedeao' || replaceAccents($res['pays']) == "Communaute economique des etats de l'afrique de l'ouest") {
  $res['pays'] = 'CEDEAO';
}

if($res['pays'] == 'Cemac' || replaceAccents($res['pays']) == "Communaute economique et monetaire de l'afrique centrale") {
  $res['pays'] = 'CEMAC';
}

if($res['pays'] == 'Nations-unies') {
  $res['pays'] = 'Nations Unies';
}

if($res['pays'] == 'Ohada' || $res['pays'] == "Organisation pour l'harmonisation en afrique du droit des affaires") {
  $res['pays'] = 'OHADA';
}

if($res['pays'] == 'Union africaine') {
  $res['pays'] = 'Union Africaine';
}

if(replaceAccents($res['pays']) == 'Union europeenne') {
  $res['pays'] = 'Union Européenne';
}

if($res['pays'] == 'Uemoa' || replaceAccents($res['pays']) == "Union economique et monetaire ouest africaine") {
  $res['pays'] = 'UEMOA';
}


if(isset($res['date_arret'])) {
  if(preg_match('/([0-9][0-9])[\/.]([0-9][0-9])[\/.]([0-9][0-9][0-9][0-9])/', $res['date_arret'], $match)) {
    $res['date_arret'] = $match[3].'-'.$match[2].'-'.$match[1];
  }
  if(preg_match('/(\d{4})-(\d{2})-(\d{2})/', $res['date_arret'], $match)) {
    if ($match[1] > date('Y') || $match[1] < 1000) {
      addError("date invalide");
    }
  }
}
else {
  $res['date_arret'] = date('Y-m-d');
  addError("date manquante");
}

if (isset($res['num_tros_gros'])) {
  $long_ok = true;
  unset($res['num_tros_gros']);
}
if (strlen($res['num_arret']) > 30 && !isset($long_ok))
{
  addError("num_arret trop gros");
}
if (preg_match('/ /', $res['num_arret']))
{
  addError("num_arret ne devrait pas contenir d'espace");
}

if(!empty($res['texte_arret'])) {
  if(is_array($res['texte_arret'])) {
    $res['texte_arret'] = trim(implode("\n", $res['texte_arret']));
  }
}
if (isset($res['no_error'])) {
  if($res['no_error'] == 'empty_text') {
    unset($res['no_error']);
  }
}
elseif(!empty($res['texte_arret'])) {
  if (!preg_match('/\n/', $res['texte_arret'])) {
    if(strlen($res['texte_arret']) > $max_length_without_lf) {
      addError("pas de saut de ligne dans l'arret");
    }
    $log_texte_arret = true;
  }
}
else {
  if(!isset($res['analyses']) && !isset($res['references'])) {
    addError("texte de l'arret manquant");
  }
}

unset($res['no_error']);

unset($res['cat_pub']);


if(isset($res['formation'])) {
  $res['formation'] = ucfirst(strtolower($res['formation']));
  if ($res['juridiction'] == $res['formation'] || $res['formation'] == '-' || strtolower($res['juridiction'].' '.$res['pays'])  == strtolower($res['formation'])) {
    unset($res['formation']);
  }
}
if ($res['juridiction'] == "Conseil d-etat" || $res['juridiction'] == "Conseil d'état" || strtolower($res['juridiction']) == "conseil d'etat") {
  $res['juridiction'] = "Conseil d'État";
}
if ($res['juridiction'] == 'Cour d-arbitrage') {
  $res['juridiction'] = "Cour d'arbitrage";
}

if (!isset($res['section']) || $res['section'] == '-') {
  unset($res['section']);
}
else {
  $res['section'] = ucfirst(strtolower($res['section']));
}

if (isset($res['sens_arret'])) {
  $res['sens_arret'] = correctWrongSpelling($res['sens_arret']);
}

//create extra fields
if (!isset($res['titre']))
{
  $formation = '';
  if (isset($res['formation'])) { $formation = ', '.$res['formation']; }
  $date = new DateTime($res['date_arret']);

  if (preg_match('/;/', $res['num_arret'])) {
    $num_arret = explode(';', $res['num_arret']);
    $nb = count($num_arret);
    if($res['pays'] == 'France' && $res['juridiction'] == 'Cour de cassation') {
      $human_num_arret = $num_arret[0];
      $num_arret_id = $num_arret[0];
      if($nb > 2) { $human_num_arret .= ' et suivants'; }
      else { $human_num_arret .= ' et suivant'; }
    }
    else {
      $i = 1;
      $human_num_arret = '';
      $num_arret_id = $res['num_arret'];
      foreach ($num_arret as $num) {
        if($i == 1) { $sep = ''; } elseif($i == $nb) { $sep = ' et '; } else { $sep = ', '; }
        $human_num_arret .= $sep.$num; $i++;
      }
    }
  }
  else { $human_num_arret = $res['num_arret']; $num_arret_id = $res['num_arret']; }

  $human_num_arret = str_replace(array('_', 'à'), array(' ', ' à '), $human_num_arret);

  $res['titre'] = $res['pays'].', '.$res['juridiction'].$formation.', '.$date->format('d').' '.$mois[$date->format('m')].' '.$date->format('Y').', '.$human_num_arret;
}
else {
  $num_arret_id = $res['num_arret'];
}
$date = date_id($res['date_arret']);
$num_arret_id = preg_replace('/[^a-z0-9;à]/i', '', $num_arret_id);
$num_arret_id = str_replace(';', '-', $num_arret_id);
$res['_id'] = ids($res['pays'].'-'.str_replace('-', '', $res['juridiction']).'-'.$date.'-'.$num_arret_id);

if (isset($res['id'])) {
  $res['id_source'] = $res['id'];
  unset($res['id']);
}

$res['date_import'] = date('Y-m-d');

if (preg_match('/\-\-/', $res['_id'])) {
  fprintf(STDERR, 'id":"UNKNOWN","error":"wrong_id","reason":"Empty id is invalid '.preg_replace('/\n/', '', print_r($res, true))."\"\n");
  exit(32);
}

/////////// CONVERTISSEUR JURICAF V1 -> V2 ///////////

// REFERENCES
if(isset($res['references'])) {
  if(!is_array($res['references'])) {
    if(strtolower($res['references']) !== 'non') {
      $references = $res['references'];
      unset($res['references']);
      if(!preg_match('/\n/', $references)) {
        $res['references']['reference'][0] = array('type' => 'ARRET', 'titre' => $references);
      }
      else {
        $res['references']['reference'][0] = array('type' => 'ARRET', 'contenu' => $references);
      }
    }
    else {
      unset($res['references']);
    }
  }
}

// DECISIONS ATTAQUEES
//si decision attaquée contient une valeur de type string ou un tableau numérique qui contient des valeurs de type string, c'est un V1
if(isset($res['decisions_attaquees'])) {
  if(!is_array($res['decisions_attaquees']['decision_attaquee'])) {  // V1 Unique
    $v1tov2 = extractFormationDate($res['decisions_attaquees']['decision_attaquee']);
  }
  else {
    if(isset($res['decisions_attaquees']['decision_attaquee'][0]) || isset($res['decisions_attaquees']['decision_attaquee'][1])) {
      $i = 0;
      foreach ($res['decisions_attaquees']['decision_attaquee'] as $value)
      {
        if(!is_array($value)) { // V1 Multiple
          $v1tov2[$i] = extractFormationDate($value);
          $i++;
        }
      }
    }
  }
  if(isset($v1tov2)) {
    $res['decisions_attaquees']['decision_attaquee'] = $v1tov2;
  }
}

// PUBLICATIONS
if(isset($res['publication'])) {
  if(strtolower($res['publication']) !== 'non') {
    if(isset($res['references'])) {
      $i = count($res['references']['reference']);
      $res['references']['reference'][$i] = array('type' => 'PUBLICATION', 'titre' => $res['publication']);
    }
    else {
      $res['references']['reference'][0] = array('type' => 'PUBLICATION', 'titre' => $res['publication']);
    }
    unset($res['publication']);
  }
  else {
    unset($res['publication']);
  }
}

/////////// Création d'identifiants ///////////

// ECLI et URN:LEX
$num_arret = $res['num_arret'];

// En cas de numéros multiples, le 1er cas doit être utilisé
if (preg_match('/;/', $num_arret)) {
  $num_arret = explode(';', $num_arret);
  $num_arret = $num_arret[0];
}
if (preg_match('/à/', $num_arret)) {
  $num_arret = explode('à', $num_arret);
  $num_arret = $num_arret[0];
}

// ECLI

$code_pays_euro = array(
      "Belgique" => "BE",
      "Bulgarie" => "BG",
      "République Tchèque" => "CZ",
      "Grèce" => "EL",
      "France" => "FR",
      "Lituanie" => "LT",
      "Luxembourg" => "LU",
      "Hongrie" => "HU",
      "Autriche" => "AT",
      "Pologne" => "PL",
      "Portugal" => "PT",
      "Roumanie" => "RO",
      "Slovaquie" => "SK",
      "Union Européenne" => "EU"
      );

// http://publications.europa.eu/code/fr/fr-370100.htm

$abbr_juridiction = array(
      "Haute cour de cassation et de justice" => "HCCJ", // Roumanie
      "Cour supérieure de justice" => "CSJ", // Luxembourg
      "Cour constitutionnelle" => "CC", // Luxembourg
      "Cour suprême" => "CS", // Hongrie
      "Tribunal des conflits" => "TC",
      "Cour de discipline budgétaire et financière" => "CDBF",
      "Cour de cassation" => "CCASS",
      "Conseil d'État" => "CE",
      "Conseil constitutionnel" => "CC",
      "Cour suprême de cassation" => "CSC", // Bulgarie
      "Cour d'arbitrage" => "CA", // Belgique
      "Cour de justice de l'union européenne" => "CJUE"
      );

$abbr_juridiction_test_fr = array(
    "Cour administrative d'appel" => "CAA",
    "Cour d'appel" => "CA",
    "Tribunal administratif" => "TA",
    "Tribunal d'instance" => "TI",
    "Tribunal de commerce" => "TCOM",
    "Tribunal de grande instance" => "TGI"
    );

// Formatage du numéro
$num_arret_ecli = preg_replace('/[^A-Z0-9]/', '.', strtoupper(replaceAccents($num_arret)));

// ajouter @original

// Identifiant
if(array_key_exists($res['pays'], $code_pays_euro) && array_key_exists($res['juridiction'], $abbr_juridiction)) { // Europe
  // France uniquement pour la base
  if($res['pays'] == 'France') {
    // num_arret spécifique aux juridictions lorsque ce n'est pas un identifiant dila (20 caractères)
    if(strpos($num_arret_ecli, "CONSTEXT") === false && strpos($num_arret_ecli, "JURITEXT") === false && strpos($num_arret_ecli, "CETATEXT") === false) {
      if($res['juridiction'] == "Conseil constitutionnel" && !empty($res['type_affaire'])) { $num_arret_ecli .= '.'.$res['type_affaire']; } // type d'affaire = 4 caractères
      if($res['juridiction'] == "Conseil d'État" && !empty($res['date_arret'])) { $num_arret_ecli .= '.'.str_replace('-', '', $res['date_arret']); } // date = 6 caractères
    }
    // Si le numéro d'arret n'excède pas 25 caractères
    if(strlen($num_arret_ecli) <= 25) {
      $res['ecli'] = 'ECLI:'.$code_pays_euro[$res['pays']].':'.$abbr_juridiction[$res['juridiction']].':'.substr($res['date_arret'], 0, 4).':'.$num_arret_ecli;
    }
  }
  // Logue les autres pays
  else {
    if(strlen($num_arret_ecli) <= 25) {
      $ecli = 'ECLI:'.$code_pays_euro[$res['pays']].':'.$abbr_juridiction[$res['juridiction']].':'.substr($res['date_arret'], 0, 4).':'.$num_arret_ecli;
    }
  }
}

// Logue les autres institutions françaises
if($res['pays'] == 'France' && !array_key_exists($res['juridiction'], $abbr_juridiction)) {
  foreach ($abbr_juridiction_test_fr as $key => $value) {
    if(strpos($res['juridiction'], $key) !== false) {
      $ville = str_replace(array($key, " de ", " d'"), '', $res['juridiction']);
      $ville = preg_replace('/[^A-Z]/', '.', strtoupper(replaceAccents($ville)));
      $abbr_ville = $value.'.'.$ville;
    }
  }
  $ecli = 'ECLI:FR:'.$abbr_ville.':'.substr($res['date_arret'], 0, 4).':'.$num_arret_ecli;
}

// URN:LEX

$urnlex_reserved = array(
      "%",
      "/",
      "?",
      "#",
      "@",
      "$",
      ":",
      ";",
      "+",
      ",",
      "~",
      "*",
      "!"
      );

$urnlex_unauthorized = array(
      " de la ",
      " et de ",
      " de l'",
      " des ",
      " de ",
      " d'",
      " et "
      );

$pays_iso3166 = array(
      "Albanie" => "AL",
      "Algérie" => "DZ",
      "Andorre" => "AD",
      "Autriche" => "AT",
      "Belgique" => "BE",
      "Bénin" => "BJ",
      "Bulgarie" => "BG",
      "Burkina Faso" => "BF",
      "Burundi" => "BI",
      "Cambodge" => "KH",
      "Cameroun" => "CM",
      "Canada" => "CA",
      "Cap-vert" => "CV",
      "Centrafrique" => "CF",
      "Comores" => "KM",
      "Congo" => "CG",
      "Congo démocratique" => "CD",
      "Côte d'Ivoire" => "CI",
      "Croatie" => "HR",
      "Djibouti" => "DJ",
      //"dominicaine, république" => "DO",
      "Dominique" => "DM",
      "Égypte" => "EG",
      "Estonie" => "EE",
      "États-unis" => "US",
      "France" => "FR",
      "Gabon" => "GA",
      "Grèce" => "GR",
      "Guinée" => "GN",
      "Guinée-bissau" => "GW",
      "Guinée équatoriale" => "GQ",
      "Haïti" => "HT",
      "Hongrie" => "HU",
      //"Lao, république démocratique populaire" => "LA",
      "Liban" => "LB",
      "Lituanie" => "LT",
      "Luxembourg" => "LU",
      "Macédoine" => "MK",
      "Madagascar" => "MG",
      "Mali" => "ML",
      "Maroc" => "MA",
      "Maurice" => "MU",
      "Mauritanie" => "MR",
      "Monaco" => "MC",
      "Mozambique" => "MZ",
      "Niger" => "NE",
      "Nouvelle-Zélande" => "NZ",
      "Pologne" => "PL",
      "Roumanie" => "RO",
      "Royaume-uni" => "GB",
      "Rwanda" => "RW",
      "Sainte-lucie" => "LC",
      "Sao Tomé et Principe" => "ST",
      "Sénégal" => "SN",
      "Serbie" => "RS",
      "Seychelles" => "SC",
      "Slovaquie" => "SK",
      "Suisse" => "CH",
      "Tchad" => "TD",
      "République Tchèque" => "CZ",
      "Togo" => "TG",
      "Tunisie" => "TN",
      "Ukraine" => "UA",
      "Vanuatu" => "VU",
      "Vietnam" => "VN"
      );
// ISO 3166-1 : http://www.iso.org/iso/fr/country_codes/iso_3166_code_lists.htm

$organisations = array(
      "UEMOA" => "UEMOA",
      "CEDEAO" => "CEDEAO",
      "Union Africaine" => "UA",
      "Union Européenne" => "EU",
      "CEMAC" => "CEMAC",
      "Conseil de l'Europe" => "COE",
      "Nations Unies" => "UN",
      "OHADA" => "OHADA"
);

$codes_pays_orgas = array_merge($pays_iso3166, $organisations);

if (array_key_exists($res['pays'], $codes_pays_orgas)) {

  $juridiction = str_replace($urnlex_unauthorized, " ", $res['juridiction']);
  $juridiction = str_replace(" ", ".", $juridiction);
  $juridiction = replaceAccents($juridiction);

  if($res['type'] !== 'error_arret') { $type = $res['type']; } else { $type = 'arret'; }

  if(isset($res['type_affaire'])) {
    if($res['pays'] == 'France' && $res['juridiction'] == 'Conseil constitutionnel') {
      $type = $res['type_affaire'];
    }
  }
  $num_arret_urnlex = preg_replace('/[^a-z0-9]/', '.', strtolower(replaceAccents($num_arret)));

  $res['urnlex'] = strtolower('urn:lex;'.$codes_pays_orgas[$res['pays']].';'.$juridiction.';'.$type.';'.$res['date_arret'].';'.$num_arret_urnlex);
}

/////////// MYSQL ///////////

if(isset($res['_id'])) { $id_base = $res['_id']; } else { $id_base = 0; }
if(isset($res['on_error'])) { $erreurs = $res['on_error']; } else { $erreurs = 0; }
if(isset($res['pays'])) { $pays = $res['pays']; } else { $pays = 0; }
if(isset($res['juridiction'])) { $juridiction = $res['juridiction']; } else { $juridiction = 0; }
if(isset($res['formation'])) { $formation = $res['formation']; } else { $formation = 0; }
if(isset($res['section'])) { $section = $res['section']; } else { $section = 0; }
if(isset($res['num_arret'])) { $num_arret = $res['num_arret']; } else { $num_arret = 0; }
if(isset($res['num_decision'])) { $num_decision = $res['num_decision']; } else { $num_decision = 0; }
if(isset($res['date_arret'])) { $date_arret = $res['date_arret']; } else { $date_arret = 0; }
if(isset($res['sens_arret'])) { $sens_arret = $res['sens_arret']; } else { $sens_arret = 0; }
if(isset($res['numeros_affaires'])) { $numeros_affaires = count($res['numeros_affaires']); } else { $numeros_affaires = 0; }
if(isset($res['nor'])) { $nor = $res['nor']; } else { $nor = 0; }
if(isset($res['urnlex'])) { $urnlex = $res['urnlex']; } else { $urnlex = 0; }
if(isset($res['ecli'])) { $ecli = $res['ecli']; } elseif(!isset($ecli)) { $ecli = 0; }
if(isset($res['titre'])) { $titre = 1; } else { $titre = 0; }
if(isset($res['titre_supplementaire'])) { $titre_supplementaire = $res['titre_supplementaire']; } else { $titre_supplementaire = 0; }
if(isset($res['type_affaire'])) { $type_affaire = $res['type_affaire']; } else { $type_affaire = 0; }
if(isset($res['type_recours'])) { $type_recours = $res['type_recours']; } else { $type_recours = 0; }
if(isset($res['decisions_attaquees'])) { $decisions_attaquees = count($res['decisions_attaquees']); } else { $decisions_attaquees = 0; }
if(isset($res['president'])) { $president = $res['president']; } else { $president = 0; }
if(isset($res['avocat_gl'])) { $avocat_gl = $res['avocat_gl']; } else { $avocat_gl = 0; }
if(isset($res['rapporteur'])) { $rapporteur = $res['rapporteur']; } else { $rapporteur = 0; }
if(isset($res['commissaire_gvt'])) { $commissaire_gvt = $res['commissaire_gvt']; } else { $commissaire_gvt = 0; }
if(isset($res['avocats'])) { $avocats = $res['avocats']; } else { $avocats = 0; }
if(isset($res['parties'])) { $parties = count($res['parties']); } else { $parties = 0; }
if(isset($res['analyses'])) { $analyses = count($res['analyses']); } else { $analyses = 0; }
if(isset($res['saisines'])) { $saisines = 1; } else { $saisines = 0; }
if(isset($res['texte_arret'])) { if(isset($log_texte_arret)) { $texte_arret = $res['texte_arret']; } else { $texte_arret = 1; } } else { $texte_arret = 0; }
if(isset($res['references'])) { $references = count($res['references']); } else { $references = 0; }
if(isset($res['fonds_documentaire'])) { $fonds_documentaire = $res['fonds_documentaire']; } else { $fonds_documentaire = 0; }
if(isset($res['reseau'])) { $reseau = $res['reseau']; } else { $reseau = 0; }
if(isset($res['id_source'])) { $id_source = $res['id_source']; } else { $id_source = 0; }

// Charge les paramêtres de configuration
try {
  require_once('../conf/mysql_conf.php');
}
catch (Exception $error) {
  fprintf(STDERR, 'id":"'.$id_base.'","error":"MYSQL","reason":"Loading configuration failed : '.$error->getMessage()."\"\n");
  $no_connexion = true;
}

// Connexion
if(!isset($no_connexion)) {
  try {
    $bdd = new PDO('mysql:host='.$HOST.';dbname='.$DBNAME, $DBUSER, $DBPASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  catch (Exception $error) {
    fprintf(STDERR, 'id":"'.$id_base.'","error":"MYSQL","reason":"No connexion : '.$error->getMessage()."\"\n");
    $no_connexion = true;
  }
}

// Log l'arrêt
if(!isset($no_connexion)) {
  try {
    $insert = 'INSERT INTO `'.$DBTABLE.'` VALUES("", :id_base, :erreurs, :pays, :juridiction, :formation, :section, :num_arret, :num_decision, :date_arret, :sens_arret, :numeros_affaires, :nor, :urnlex, :ecli, :titre, :titre_supplementaire, :type_affaire, :type_recours, :decisions_attaquees, :president, :avocat_gl, :rapporteur, :commissaire_gvt, :avocats, :parties, :analyses, :saisines, :texte_arret, :references, :fonds_documentaire, :reseau, :id_source, :type, NOW())';

    $req = $bdd->prepare($insert);

    $req->execute(array(
      'id_base' => $id_base,
      'erreurs' => $erreurs,
      'pays' => $pays,
      'juridiction' => $juridiction,
      'formation' => $formation,
      'section' => $section,
      'num_arret' => $num_arret,
      'num_decision' => $num_decision,
      'date_arret' => $date_arret,
      'sens_arret' => $sens_arret,
      'numeros_affaires' => $numeros_affaires,
      'nor' => $nor,
      'urnlex' => $urnlex,
      'ecli' => $ecli,
      'titre' => $titre,
      'titre_supplementaire' => $titre_supplementaire,
      'type_affaire' => $type_affaire,
      'type_recours' => $type_recours,
      'decisions_attaquees' => $decisions_attaquees,
      'president' => $president,
      'avocat_gl' => $avocat_gl,
      'rapporteur' => $rapporteur,
      'commissaire_gvt' => $commissaire_gvt,
      'avocats' => $avocats,
      'parties' => $parties,
      'analyses' => $analyses,
      'saisines' => $saisines,
      'texte_arret' => $texte_arret,
      'references' => $references,
      'fonds_documentaire' => $fonds_documentaire,
      'reseau' => $reseau,
      'id_source' => $id_source,
      'type' => $res['type']
      ));

    $req->closeCursor();
  }
  catch (Exception $error) {
    fprintf(STDERR, 'id":"'.$id_base.'","error":"MYSQL","reason":"'.$error->getMessage()."\"\n");
  }
}

ksort($res);
//debug :
//echo '<pre>';
//var_dump($res);
print json_encode($res);
//echo '</pre>';


