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

// Date pour _id couchdb (Ex : CETATEXT000007604769 et CETATEXT000007602727 : continuité d'une affaire : même numéro, même année, deux décisions)
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
    else { $array[$key] = str_replace(array('<<','>>','<','>'), array('«','»','',''), $value); }
  }
  $array = array_filter($array);
  return $array;
}

function addError($str) {
  global $errors;
  if(!empty($errors)) { $sep = ", "; } else { $sep = ''; }
  $errors .= $sep.$str;
}
// Chargement
$obj = simplexml_load_file("data.xml");
$res = (array)$obj;
$res = cleanArray($res);

if (!count($res)) {
	fprintf(STDERR, 'id":"UNKNOWN","error":"XML parsing","reason":"Cannot parse '.$argv[1]."\"\n");
	exit(33);
}

if (!isset($res['pays']) && isset($argv[2]))
  $res['pays'] = $argv[2];

if (!isset($res['juridiction']) && isset($argv[3]))
  $res['juridiction'] = $argv[3];

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
//clean them
unset($res['cat_pub']);
$res['pays'] = ucfirst(strtolower($res['pays']));
$res['juridiction'] = ucfirst(strtolower($res['juridiction']));
$res['formation'] = ucfirst(strtolower($res['formation']));
if ($res['juridiction'] == $res['formation'] || $res['formation'] == '-' ||
    strtolower($res['juridiction'].' '.$res['pays'])  == strtolower($res['formation']))
  unset($res['formation']);
if ($res['juridiction'] == 'Conseil d-etat')
  $res['juridiction'] = 'Conseil d\'état';
if ($res['juridiction'] == 'Cour d-arbitrage')
  $res['juridiction'] = 'Cour d\'arbitrage';
if (!isset($res['section']) || $res['section'] == '-')
  unset($res['section']);

//create extra fields
if (preg_match('/([0-9][0-9])[\/.]([0-9][0-9])[\/.]([0-9][0-9][0-9][0-9])/', $res['date_arret'], $match))
{
  $res['date_arret'] = $match[3].'-'.$match[2].'-'.$match[1];
}
if (!isset($res['titre']))
{
  $formation = '';
  if (isset($res['formation']))
    $formation = ', '.$res['formation'];
  $date = new DateTime($res['date_arret']);
  $res['titre'] = $res['pays'].', '.$res['juridiction'].$formation.', '.
				     $date->format('d').' '.$mois[$date->format('m')].' '.$date->format('Y').
				     ', décision n°'.$res['num_arret'];
}
$date = date_id($res['date_arret']);
$num_arret_id = preg_replace('/[^a-z0-9]/i', '', $res['num_arret']);
$num_arret_id = str_replace(';', '-', $res['num_arret']);
$res['_id'] = ids($res['pays'].'-'.$res['juridiction'].'-'.$date.'-'.$num_arret_id);
if (isset($res['id']))
    $res['juricaf_id'] = $res['id'];
$res['type'] = 'arret';
unset($res['id']);

//Handle errors
if (strlen($res['num_arret']) > 30)
{
  $res['type'] = 'error_arret';
  addError("num_arret trop gros");
}
if (preg_match('/ /', $res['num_arret']))
{
  $res['type'] = 'error_arret';
  addError("num_arret ne devrait pas contenir d'espace");
}
if (isset($res['texte_arret']) && $res['texte_arret'])
{
  if (!preg_match('/\n/', $res['texte_arret']))
  {
    $res['type'] = 'error_arret';
    addError("pas de saut de ligne dans l'arret");
  }
 } else if (isset($res['no_error']) && $res['no_error'] == 'empty_text')
  unset($res['no_error']);
 else {
   $res['type'] = 'error_arret';
   addError("texte de l'arret manquant");
 }

if(isset($res['on_error'])) {
  if(preg_match('/^Document/', $res['on_error'])) {
    $res['type'] = 'error_arret';
  }
  addError($res['on_error']);
}
if(!empty($errors)) {
  $res['on_error'] = $errors;
}
print json_encode($res);


