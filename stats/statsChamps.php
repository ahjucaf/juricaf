<?php
require("config.php");

function getSolrResults($pays, $juridiction, $critere = '') {
  global $SOLRHOST;
  if(!empty($critere)) { $critere = '+'.$critere.':*'; }
  $stream = fopen('http://'.$SOLRHOST.':8080/solr/select/?q=facet_pays:%22'.urlencode($pays).'%22+facet_juridiction:%22'.urlencode($juridiction).'%22'.$critere.'&rows=0&indent=on', 'r');
  $xml = trim(stream_get_contents($stream));
  $response = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_COMPACT);
  fclose($stream);
  return $response->result["numFound"];
}

$criteres = [];
$stream = fopen('static/luke.xml', 'r');
$xml = trim(stream_get_contents($stream));
$response = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_COMPACT);
foreach($response->lst[2]->lst as $lst) {
    foreach ($lst->int as $int) {
        if ($int['name'] == 'docs' && intval($int) == 0) {
            continue 2;
        }
    }
    $criteres[] = strval($lst['name']);
}

$tableau = "<div class='table-responsive'><table class=\"table statsbase table-striped table-bordered\" id=\"statsbase\">\n";
$tableau .= '<tr><th>Pays</th><th>Institution</th><th>Nombre</th>';
foreach ($criteres as $value) {
  $tableau .= '<th>'.$value.'</th>';
}

$tableau .= "</tr>\n";
$classe = 'color2';

$stream = fopen('http://'.$SOLRHOST.':8080/solr/select?indent=on&version=2.2&q=type:arret&rows=0&facet=true&facet.field=facet_pays_juridiction&facet.limit=-1', 'r');
$xml = trim(stream_get_contents($stream));
$response = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_COMPACT);
fclose($stream);

echo '"Pays";"Institution";"Critère";"Nombre"';
echo "\n";

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
    if ($collection[$critere] > 0) {
        echo '"'.$pays.'";"'.$juridiction.'";'.$critere.';'.$collection[$critere]."\n";
    }
  }
  $fpjlink = str_replace(' ', '_', 'http://www.juricaf.org/recherche/+/facet_pays:'.$pays.',facet_juridiction:'.$juridiction);
  if($classe == "color1") { $classe = "color2"; } else { $classe = "color1"; }
  $tableau .= '<tr class="'.$classe.'"><td><a href="http://www.juricaf.org/recherche/recherche/+/facet_pays:'.$pays.'">'.$pays.'</a></td><td><a href="'.$fpjlink.'">'.$juridiction.'</a></td><td class="num">'.$nb.'</td>';

  foreach ($criteres as $critere) {
    $tableau .= '<td class="num">'.$collection[$critere].'</td>';
  }
  $tableau .= "</tr>\n";
}
$tableau .= "\n</table>";
$tableau .= '<p class="text-muted">généré le '.date("d/m/Y à H:i:s").'</p>';
$tableau .= '<script type="text/javascript" src="/js/jquery-3.6.0.slim.min.js?5ff8755abb8669f8185a89437b34389870241c92"></script>';
$tableau .= '<script type="text/javascript" src="/js/dataTables.js"></script>';
$tableau .= '<script>$("#statsbase").DataTable( {paging: false, searching: false, info: false} );</script>';

try {
  $handler = fopen("static/champs.html","w");
  fputs($handler,$tableau);
}
catch (Exception $e) {
  echo "Erreur d'enregistrement\n";
  echo $e->getMessage()."\n";
  exit;
}
