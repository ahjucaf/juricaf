<?php
setlocale(LC_TIME, 'fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8'); 

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

function parse($obj, $n = 0) 
{
  $res = array();
  $cpt = 0;
  if (is_a($obj, 'SimpleXMLElement'))
    $obj = get_object_vars($obj);
  if (is_array($obj))
  foreach($obj as $k => $v) {
    $k = strtolower($k);
    if (!is_a($v, 'SimpleXMLElement')) {
      if (is_array($v)) {
	$i = 0;
	foreach ($v as $a) {
	  $r = parse($a, $n + 1);
	  if ($r)
	    $res[$i++] = $r;
	}
	if ($i)
	  $cpt++;
      } else {
	$cpt++;
	$res[$k] = utf8_decode("$v");
      }
    } else {
      $r = parse($v, $n + 1);
      if ($r) 
	$res[$k] = $r;
    }
  }
  if (!$cpt)
    return ;
  return $res;
}


function ids($str) {
  $str = strtr($str,
	       array('è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','ç'=>'c','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ñ'=>'n','ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u','ý'=>'y','ÿ'=>'y','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Ç'=>'C','È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y'));
  $str = preg_replace('/[^a-z0-9\-]/i', '', $str);
  return strtoupper($str);
}

$obj = simplexml_load_file("data.xml");
$res = parse($obj);

$res['juridiction'] = ucfirst(strtolower($res['juridiction']));
$res['formation'] = ucfirst(strtolower($res['formation']));

if ($res['juridiction'] == $res['formation'] || $res['formation'] == '-' || strtolower($res['juridiction'].' '.$res['pays'])  == strtolower($res['formation']))
  unset($res['formation']);
if ($res['juridiction'] == 'Conseil d-etat') 
  $res['juridiction'] = 'Conseil d\'état'; 
if ($res['juridiction'] == 'Cour d-arbitrage') 
  $res['juridiction'] = 'Cour d\'arbitrage';

if (preg_match('/([0-9][0-9])\/([0-9][0-9])\/([0-9][0-9][0-9][0-9])/', $res['date_arret'], $match)) 
{
  $res['date_arret'] = $match[3].'-'.$match[2].'-'.$match[1];
}

if (!isset($res['titre'])) 
{
  $formation = '';
  if (isset($res['formation']))
    $formation = ', '.$res['formation'];
  $res['titre'] = $res['pays'].', '.$res['juridiction'].$formation.', '.
    date('d ', strtotime($res['date_arret'])).
    $mois[date('m', strtotime($res['date_arret']))].
    date(' Y', strtotime($res['date_arret'])).
    ', décision n°'.$res['num_arret'];
}

$year = preg_replace('/\-[0-9\-]*/', '', $res['date_arret']);
$num_arret_id = preg_replace('/[^a-z0-9]/i', '', $res['num_arret']);

$res['_id'] = ids($res['pays'].'-'.$res['juridiction'].'-'.$year.'-'.$num_arret_id);
$res['juricaf_id'] = $res['id'];
$res['type'] = 'arret';

unset($res['id']);

if (strlen($res['num_arret']) > 20 || preg_match('/ /', $res['num_arret'])) 
{
  $res['type'] = 'error_arret';
  $res['on_error'] = 'num_arret trop gros';
}
print json_encode($res);
