<?php

function parse($obj, $n = 0) 
{
  $res = array();
  $cpt = 0;
  if (is_a($obj, 'SimpleXMLElement'))
    $obj = get_object_vars($obj);
  if (is_array($obj))
  foreach($obj as $k => $v) {
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
	$r = parse($obj[$k], $n + 1);
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
if (!$res['TITRE']) 
{
  $res['TITRE'] = $res['PAYS'].' : Décision n°'.$res['NUM_ARRET'].' du '.$res['DATE_ARRET'].' ('.$res['JURIDICTION'].' - '.$res['FORMATION'].')';
}
$res['_id'] = ids($res['PAYS'].'-'.$res['JURIDICTION'].'-'.$res['ID']);
$res['JURICAF_ID'] = $res['ID'];
unset($res['ID']);
print json_encode($res);
