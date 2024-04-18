<?php
require("config.php");

function getSolrResults(&$results, $pays, $juridiction, $sort, $champ_date = 'date_arret') {
  global $SOLRHOST;
  $stream = fopen('http://'.$SOLRHOST.':8080/solr/select/?indent=on&q=facet_pays:%22'.urlencode($pays).'%22+facet_juridiction:%22'.urlencode($juridiction).'%22&fq=type:arret&indent=on&sort='.$champ_date.'+'.$sort.'&rows=1&fl=alimentation_type,facet_pays_juridiction,id,'.$champ_date, 'r');
  $xml = stream_get_contents($stream);
  fclose($stream);
  $lines = explode("\n", $xml);
  $i = 0;
  if($sort == 'asc') {
      $champs_date_name = $champ_date.'_debut';
  }else{
      $champs_date_name = $champ_date.'_fin';
  }
  foreach ($lines as $value) {
      if (!isset($results[$champs_date_name]) && strpos($value, 'date name="'.$champ_date.'"') !== false) {
          $date = explode('T', trim(str_replace(array('<date name="'.$champ_date.'">', '</date>'), '', $value))); // date name="date_arret" 1995-11-07T12:00:00Z
          if ($date[0]) {
              $date = explode('-', $date[0]);
              $results[$champs_date_name] = $date[0].'-'.$date[1].'-'.$date[2];
          }
      }
      if (!isset($results['alimentation_type']) && strpos($value, 'name="alimentation_type') !== false) {
          $s = str_replace('  <str name="alimentation_type">', '', str_replace('</str>', '', $value));
          if ($s) {
              $results['alimentation_type'] = $s;
          }
      }
      if (!isset($results['nb']) && strpos($value, 'numFound') !== false) {
          $s = str_replace('<result name="response" numFound="', '', str_replace('" start="0">', '', $value));
          if ($s) {
              $results['nb'] = $s;
          }
      }
      if (isset($results['alimentation_type']) && isset($results[$champs_date_name]) && isset($results['nb'])) {
          break;
      }
  }
}

$csv = '"Pays";"Institution";Nombre;"Mode d\'intégration";"Mise à jour";"Plus ancien";"Plus récent"';
$csv .= "\n";
$tableau = "<div class='table-responsive'><table class=\"table statsbase table-striped table-bordered\" id=\"statsbase\">\n";
$tableau .= "<thead><tr><th>Pays</th><th>Institution</th><th>Nombre</th><th>Mode&nbsp;d'intégration</th><th>Mise à jour</th><th>Date&nbsp;arrêt le&nbsp;plus&nbsp;ancien</th><th>Date&nbsp;arrêt le&nbsp;plus&nbsp;récent</th></tr><thead>\n<tbody>\n";

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
  $results = array();
  getSolrResults($results, $pays, $juridiction, 'desc', 'date_import'); // Dernier
  getSolrResults($results, $pays, $juridiction, 'desc'); // Premier
  getSolrResults($results, $pays, $juridiction, 'asc'); // Dernier

  if (!isset($results['date_arret_debut'])) $results['date_arret_debut'] = '1900-01-01';
  if (!isset($results['date_arret_fin'])) $results['date_arret_fin'] = '2999-01-01';
  if(!isset($results['alimentation_type'])) {$results['alimentation_type'] = '';}
  if(!isset($results['date_import_fin'])) {$results['date_import_fin'] = '';}

  $csv .= '"'.$pays.'";"'.$juridiction.'";'.$results['nb'].';"'.$results['alimentation_type'].'";"'.$results['date_import_fin'].'";"';
  $csv .= $results['date_arret_fin'].'";"'.$results['date_arret_debut'].'";'."\n";

  $fpjlink = str_replace(' ', '_', '/recherche/+/facet_pays:'.$pays.',facet_juridiction:'.$juridiction);
  $tableau .= '<tr>';
  $tableau .= '<td><a href="https://juricaf.org/recherche/+/facet_pays:'.$pays.'">'.$pays.'</a></td>';
  $tableau .= '<td><a href="https://juricaf.org'.$fpjlink.'">'.$juridiction.'</a></td>';
  $tableau .= '<td style="text-align: right;" class="num">'.$results['nb'].'</td>';
  $tableau .= '<td style="text-align: center;">';
  $tableau .= ($results['alimentation_type']) ? 'Quotidienne <a href="https://github.com/ahjucaf/juricaf/tree/master/'.$results['alimentation_type'].'">+</a>' : 'Ponctuelle';
  $tableau .= '</td>';
  $tableau .= '<td style="text-align: center;" data-order="'.$results['date_import_fin'].'">'.preg_replace('/(....)-(..)-(..)/', '\3/\2/\1', $results['date_import_fin']).'</td>';
  $tableau .= '<td style="text-align: center;" data-order="'.$results['date_arret_debut'].'">'.preg_replace('/(....)-(..)-(..)/', '\3/\2/\1', $results['date_arret_debut']).'</td>';
  $tableau .= '<td style="text-align: center;" data-order="'.$results['date_arret_fin'].'">'.preg_replace('/(....)-(..)-(..)/', '\3/\2/\1', $results['date_arret_fin']).'</td>';
  $tableau .= "</tr>\n";
}

$tableau .= '</tbody></table></div>';
$tableau .= '<p class="text-muted">généré le '.date("d/m/Y à H:i:s").'</p>';
$tableau .= '<script type="text/javascript" src="/js/jquery-3.6.0.slim.min.js?5ff8755abb8669f8185a89437b34389870241c92"></script>';
$tableau .= '<script type="text/javascript" src="/js/dataTables.js"></script>';
$tableau .= '<script>$("#statsbase").DataTable( {paging: false, searching: false, info: false} );</script>';

try {
  $handler = fopen("static/base.csv","w");
  fputs($handler,$csv);
  $handler = fopen("static/base.html","w");
  fputs($handler,$tableau);
  if (isset($_ENV['VERBOSE'])) {
      echo "Page mise à jour : https://juricaf.org/documentation/stats/statuts.php\n";
      echo "Tableur généré : https://juricaf.org/documentation/stats/base.csv\n";
  }
} catch (Exception $e) {
  echo "Erreur d'enregistrement\n";
  echo $e->getMessage()."\n";
  exit;
}
