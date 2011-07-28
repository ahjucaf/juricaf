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

// Date pour _id couchdb
function date_id($d) {
  $d = explode('-', $d);
  $date = $d[0].$d[1].$d[2];
  return $date;
}

// Convert a string to juricaf ids
function ids($str) {
  $str = strtr($str,
         array('è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','ç'=>'c','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ñ'=>'n','ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u','ý'=>'y','ÿ'=>'y','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Ç'=>'C','È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y'));
  $str = preg_replace('/[^a-z0-9\-]/i', '', $str);
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
  $str = ucfirst(trim($str));
  $value = explode(',', $str);
  if(count($value) == 2) { // si formation, date
    if(preg_match('/(\d{2})\/(\d{2})\/(\d{4})/', trim($value[1]), $date_dec)) {
      $date = $date_dec[3]."-".$date_dec[2]."-".$date_dec[1];
      $extracted = array('type' => $type, 'formation' => trim($value[0]), 'date' => $date);
    }
    else {
      $extracted = array('type' => $type, 'formation' => trim($str, ","));
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

// Chargement
$obj = simplexml_load_file("data.xml");
$res = (array)$obj;
$res = cleanArray($res);

if (!count($res)) {
  fprintf(STDERR, 'id":"UNKNOWN","error":"XML parsing","reason":"Cannot parse '.$argv[1]."\"\n");
  exit(33);
}

$res['type'] = 'arret';

if(!isset($res['pays']) && isset($argv[2])) { $res['pays'] = $argv[2]; }
if(!isset($res['pays'])) { addError("pays manquant"); $res['pays'] = 'inconnu'; }

if(!isset($res['juridiction']) && isset($argv[3])) { $res['juridiction'] = $argv[3]; }
if(!isset($res['juridiction'])) { addError("juridiction manquante"); $res['juridiction'] = 'inconnue'; }

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
    addError("ni numéro d'arret, ni numéro d'affaire, ni NOR");
    $res['num_arret'] = $res['id'];
  }
}

if(isset($res['date_arret'])) {
  if (preg_match('/([0-9][0-9])[\/.]([0-9][0-9])[\/.]([0-9][0-9][0-9][0-9])/', $res['date_arret'], $match))
  {
    $res['date_arret'] = $match[3].'-'.$match[2].'-'.$match[1];
    if ($match[3] > date('Y') || $match[3] < 1000) {
      addError("date invalide");
    }
  }
}
else {
  $res['date_arret'] = date('Y-m-d');
  addError("date manquante");
}

if (strlen($res['num_arret']) > 30)
{
  addError("num_arret trop gros");
}
if (preg_match('/ /', $res['num_arret']))
{
  addError("num_arret ne devrait pas contenir d'espace");
}
if (isset($res['texte_arret']) && $res['texte_arret'] && !is_array($res['texte_arret'])) {
  if (!preg_match('/\n/', $res['texte_arret'])) {
    addError("pas de saut de ligne dans l'arret");
  }
 } else if (isset($res['no_error']) && $res['no_error'] == 'empty_text') {
  unset($res['no_error']);
 } else {
  addError("texte de l'arret manquant");
 }
unset($res['no_error']);

//clean them
unset($res['cat_pub']);
$res['pays'] = ucfirst(strtolower($res['pays']));
$res['juridiction'] = ucfirst(strtolower($res['juridiction']));

// Cas particuliers et noms des villes en majuscules
if($res['pays'] == 'Suisse' && $res['juridiction'] == 'Tribunal fédéral') { $res['juridiction'] = 'Tribunal fédéral suisse'; }

if($res['pays'] == 'France') {
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
      "saint-denis de la réunion" => "Saint-Denis de la Réunion",
      "strasbourg" => "Strasbourg",
      "toulouse" => "Toulouse",
      "versailles" => "Versailles"
      );
  $res['juridiction'] = strtr($res['juridiction'], $villes_fr);
}

$res['formation'] = ucfirst(strtolower($res['formation']));
if ($res['juridiction'] == $res['formation'] || $res['formation'] == '-' ||
    strtolower($res['juridiction'].' '.$res['pays'])  == strtolower($res['formation']))
  unset($res['formation']);
if ($res['juridiction'] == 'Conseil d-etat')
  $res['juridiction'] = 'Conseil d\'État';
if ($res['juridiction'] == 'Cour d-arbitrage')
  $res['juridiction'] = 'Cour d\'arbitrage';
if (!isset($res['section']) || $res['section'] == '-')
  unset($res['section']);

//create extra fields
if (!isset($res['titre']))
{
  $formation = '';
  if (isset($res['formation'])) { $formation = ', '.$res['formation']; }
  $date = new DateTime($res['date_arret']);
  $res['titre'] = $res['pays'].', '.$res['juridiction'].$formation.', '.$date->format('d').' '.$mois[$date->format('m')].' '.$date->format('Y').', décision n°'.$res['num_arret'];
}
$date = date_id($res['date_arret']);
$num_arret_id = preg_replace('/[^a-z0-9]/i', '', $res['num_arret']);
$num_arret_id = str_replace(';', '-', $res['num_arret']);
$res['_id'] = ids($res['pays'].'-'.$res['juridiction'].'-'.$date.'-'.$num_arret_id);
if (isset($res['id']))
    $res['juricaf_id'] = $res['id'];
unset($res['id']);

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

ksort($res);
//debug :
//echo '<pre>';
//var_dump($res);
print json_encode($res);
//echo '</pre>';


