<?php
$host = "http://www.juricaf.tangui.eu.org";
$date = $argv[1];
$id = $argv[2];
//echo 'Date : '.$date.' Id : '.$id."\n";
if ($stream = fopen('http://localhost:8080/solr/select/?q=id_source:'.$id.'&indent=on', 'r')) {
  $xml = trim(stream_get_contents($stream));
  $response = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_COMPACT);
  fclose($stream);
}
if(intval($response->result["numFound"])) {
  if(strpos($id, "CONSTEXT") !== false) { $type = 'JuriConst'; }
  if(strpos($id, "JURITEXT") !== false) { $type = 'JuriJudi'; }
  if(strpos($id, "CETATEXT") !== false) { $type = 'JuriAdmin'; }
  $lines = explode("\n", $xml);
  foreach ($lines as $value) {
    if(strpos($value, 'str name="id"') !== false) {
      $id_juricaf = trim(str_replace(array('<str name="id">', '</str>'), '', $value));
      echo '<tr>
      <td>Date demande : '.$date.'</td>
      <td><a href="'.$host.'/arret/'.$id_juricaf.'">'.$id_juricaf.'</a></td>
      <td><a href="'.$host.'/couchdb/_utils/document.html?ahjucaf/'.$id_juricaf.'">CouchDB</a></td>
      <td><a href="http://www.legifrance.gouv.fr/affich'.$type.'.do?oldAction=rech'.$type.'&idTexte='.$id.'">'.$id.'</a></td>
      </tr>';
    }
  }
}
?>