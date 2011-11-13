<?php
if(isset($argv[1])) {
  $id = $argv[1];
}
else {
  echo "Erreur getIdFromSolr : aucun identifiant dila spécifié en argument"; exit;
}

if ($stream = fopen('http://localhost:8080/solr/select/?q=id_source:'.$id.'&indent=on', 'r')) {
  $xml = trim(stream_get_contents($stream));
  $response = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_COMPACT);
  fclose($stream);
}
else { echo $id." : Erreur getIdFromSolr : solr n'est pas disponible"; exit; }

if(intval($response->result["numFound"]) !== 0) {
  if(intval($response->result["numFound"]) > 1) {
    echo $id." : Erreur getIdFromSolr : Plusieurs documents comportent cet identifiant\n";
    echo "http://www.juricaf.org/recherche/id_source:".$id."\n";
  }
  $lines = explode("\n", $xml);
  $i = 0; $id_juricaf = array();
  foreach ($lines as $value) {
    if(strpos($value, 'str name="id"') !== false) {
      $id_juricaf[$i] = trim(str_replace(array('<str name="id">', '</str>'), '', $value)); $i++;
    }
  }
  if(count($id_juricaf) > 1) {
    foreach($id_juricaf as $id_ju) {
      echo $id_ju."\n";
    }
  }
  else { echo $id_juricaf[0]; }
}
else {
  echo "Erreur getIdFromSolr : http://www.juricaf.org/recherche/id_source:".$id." non trouvé\n"; exit;
}
?>