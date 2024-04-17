<?php
require("config.php");

$criteres = array(
  'analyses',
  'sens_arret',
  'decisions_attaquees',
  'parties',
  'references',
  'nor',
  'ecli',
  'type_affaire',
  'type_recours',
  'president',
  'avocat_gl',
  'rapporteur',
  'commissaire_gvt',
  'avocats',
  'saisines'
);

function getSolrResults($pays, $juridiction, $critere = '') {
  global $SOLRHOST;
  if(!empty($critere)) { $critere = '+'.$critere.':*'; }
  $stream = fopen('http://'.$SOLRHOST.':8080/solr/select/?q=facet_pays:%22'.urlencode($pays).'%22+facet_juridiction:%22'.urlencode($juridiction).'%22'.$critere.'&fq=type:arret&indent=on', 'r');
  $xml = trim(stream_get_contents($stream));
  $response = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_COMPACT);
  fclose($stream);
  return $response->result["numFound"];
}

$csv = '"Pays";"Institution";"Nombre"';
$tableau = "<table class=\"statsbase\">\n";
$tableau .= '<tr><th>Pays</th><th>Institution</th><th>Nombre</th>';

foreach ($criteres as $value) {
  $csv .= ';'.$value;
  $tableau .= '<th>'.$value.'</th>';
}

$csv .= "\n";
$tableau .= "</tr>\n";
$classe = 'color2';

$line = -1;
$stream = fopen('http://'.$SOLRHOST.':8080/solr/select?indent=on&version=2.2&q=type:arret&rows=0&facet=true&facet.field=facet_pays_juridiction&facet.limit=-1', 'r');
$xml = trim(stream_get_contents($stream));
$response = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_COMPACT);
fclose($stream);
foreach($response->lst[1]->lst[1]->lst->int as $int) {
  $name = (string) $int['name'];
  $nb = $int[0];
  if (isset($pays_juridictions[$name]) || $nb == 0) {
      continue;
  }
  $pays_juridictions[$name] = explode(' | ', $name);
  $pays = $pays_juridictions[$name][0];
  $juridiction = $pays_juridictions[$name][1];
  $nb = getSolrResults($pays, $juridiction);
  foreach ($criteres as $critere) {
    $collection[$critere] = getSolrResults($pays, $juridiction, $critere);
  }

  $csv .= '"'.$pays.'";"'.$juridiction.'";'.$nb;

  $fpjlink = str_replace(' ', '_', 'http://www.juricaf.org/recherche/+/facet_pays:'.$pays.',facet_juridiction:'.$juridiction);
  if($classe == "color1") { $classe = "color2"; } else { $classe = "color1"; }
  $tableau .= '<tr class="'.$classe.'"><td><a href="http://www.juricaf.org/recherche/recherche/+/facet_pays:'.$pays.'">'.$pays.'</a></td><td><a href="'.$fpjlink.'">'.$juridiction.'</a></td><td class="num">'.$nb.'</td>';

  foreach ($criteres as $critere) {
    $csv .= ';'.$collection[$critere];
    $tableau .= '<td class="num">'.$collection[$critere].'</td>';
  }

  $csv .= "\n";
  $tableau .= "</tr>\n";
}

$tableau .= "\n</table>";

try {
  $handler = fopen("static/champs.csv","w");
  fputs($handler,$csv);
  $handler = fopen("static/champs.html","w");
  fputs($handler,$tableau);
  echo "Tableur généré : http://www.juricaf.org/documentation/stats/champs.csv";
}
catch (Exception $e) {
  echo "Erreur d'enregistrement\n";
  echo $e->getMessage()."\n";
  exit;
}
?>
