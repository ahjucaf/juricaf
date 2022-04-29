<?php
require("config.php");

function getSolrResults($pays, $juridiction, $sort) {
  global $SOLRHOST;
  $stream = fopen('http://'.$SOLRHOST.':8080/solr/select/?q=facet_pays:%22'.urlencode($pays).'%22+facet_juridiction:%22'.urlencode($juridiction).'%22&fq=type:arret&indent=on&sort=date_arret+'.$sort, 'r');
  $xml = trim(stream_get_contents($stream));
  $response = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_COMPACT);
  fclose($stream);

  $results['nb'] = $response->result["numFound"];

  $lines = explode("\n", $xml);
  $i = 0;
  foreach ($lines as $value) {
    if(strpos($value, 'date name="date_arret"') !== false) {
      $date = explode('T', trim(str_replace(array('<date name="date_arret">', '</date>'), '', $value))); // date name="date_arret" 1995-11-07T12:00:00Z
      $date = explode('-', $date[0]);
      if($sort == 'desc') {
        $results['date_debut'][$i] = $date[2].'/'.$date[1].'/'.$date[0];
      }
      else {
        $results['date_fin'][$i] = $date[2].'/'.$date[1].'/'.$date[0];
      }
      $i++;
    }
  }
  if(isset($results['date_debut'][0])) { $results['date_debut'] = $results['date_debut'][0]; }
  if(isset($results['date_fin'][0])) { $results['date_fin'] = $results['date_fin'][0]; }
  return $results;
}

function returnLicenceLink($value) {
  if(strpos($value, 'ODBL') !== false) {
    $value = str_replace('ODBL', '<a href="http://www.juricaf.org/documentation/mentions-legales/article/licence-odbl">ODBL</a>', $value);
  }
  if(strpos($value, 'AHJUCAF') !== false) {
    $value = str_replace('AHJUCAF', '<a href="http://www.juricaf.org/documentation/mentions-legales/article/contact">AHJUCAF</a>', $value);
  }
  if(strpos($value, 'Légifrance') !== false) {
    $value = str_replace('Légifrance', '<a href="http://rip.journal-officiel.gouv.fr/">Légifrance</a>', $value);
  }
  return $value;
}

function addLegend($value, $type) {
  $legend['etat'] = array(
    'E' => 'En cours',
    'T' => 'Terminé'
  );
  $legend['maj'] = array(
    'Q' => 'Quotidienne',
    'M' => 'Mensuelle',
    'S' => 'Semestrielle',
    'A' => 'Annuelle',
    'I' => 'Interrompue',
    'P' => 'Plus de mise à jour'
  );
  $legend['selection'] = array(
    'R' => 'Recueil d\'arrêts essentiellement',
    'I' => 'Inédits',
    'P' => 'Pas de sélection'
  );

  foreach($legend[$type] as $key => $status) {
    if(strpos($value, $key) !== false) {
      $value = str_replace($key, '<span class="stat_legend" title="'.$status.'">'.$key.'</span>', $value);
    }
  }
  return $value;
}

$csv = '"Pays";"Institution";Nombre;"Etat";"Mise à jour";"Selection";"Traduction";"Plus ancien";"Plus récent";"Licence"';
$csv .= "\n";
$tableau = "<div class='table-responsive'><table class=\"table statsbase table-striped table-bordered\">\n";
$tableau .= "<tr><th>Pays</th><th>Institution</th><th>Nombre</th><th>Etat</th><th>Mise à jour</th><th>Selection</th><th>Traduction</th><th>Plus ancien</th><th>Plus récent</th><th>Licence</th></tr>\n";
$classe = "color2";
$line = -1;
if (($handle = fopen($ORIGINALCSV, "r")) !== FALSE) while (($donnees = fgetcsv($handle, 1000, ";")) !== FALSE) {
  $line++;
  if (!$line) {
      continue;
  }
  $results = array();
  $results = getSolrResults($donnees[$HEADER2CSVID['pays']], $donnees[$HEADER2CSVID['juridiction']], 'desc'); // Premier
  $results = array_merge($results, getSolrResults($donnees[$HEADER2CSVID['pays']], $donnees[$HEADER2CSVID['juridiction']], 'asc')); // Dernier

  if (!isset($results['date_debut'])) $results['date_debut'] = '1900-01-01';
  if (!isset($results['date_fin'])) $results['date_fin'] = '2999-01-01';


  $csv .= '"'.$donnees[$HEADER2CSVID['pays']].'";"'.$donnees[$HEADER2CSVID['juridiction']].'";'.$results['nb'].';"'.$donnees[$HEADER2CSVID['etat']].'";"'.$donnees[$HEADER2CSVID['maj']].'";"'.$donnees[$HEADER2CSVID['selection']];
  $csv .= '";"'.$donnees[$HEADER2CSVID['traduction']].'";"'.$results['date_fin'].'";"'.$results['date_debut'].'";"'.$donnees[$HEADER2CSVID['licence']]."\"\n";

  if($classe == "color1") { $classe = "color2"; } else { $classe = "color1"; }
  $fpjlink = str_replace(' ', '_', 'http://www.juricaf.org/recherche/+/facet_pays:'.$donnees[$HEADER2CSVID['pays']].',facet_juridiction:'.$donnees[$HEADER2CSVID['juridiction']]);
  $tableau .= '<tr class="'.$classe.'">
  <td><a href="http://www.juricaf.org/recherche/+/facet_pays:'.$donnees[$HEADER2CSVID['pays']].'">'.$donnees[$HEADER2CSVID['pays']].'</a></td>
  <td><a href="'.$fpjlink.'">'.$donnees[$HEADER2CSVID['juridiction']].'</a></td>
  <td class="num">'.$results['nb'].'</td><td style="text-align: center;">'.addLegend($donnees[$HEADER2CSVID['etat']], 'etat').'</td>
  <td style="text-align: center;">'.addLegend($donnees[$HEADER2CSVID['maj']], 'maj').'</td>
  <td style="text-align: center;">'.addLegend($donnees[$HEADER2CSVID['selection']], 'selection').'</td>
  <td>'.$donnees[$HEADER2CSVID['traduction']].'</td><td style="text-align: center;">'.$results['date_fin'].'</td>
  <td style="text-align: center;">'.$results['date_debut'].'</td><td>'.returnLicenceLink($donnees[$HEADER2CSVID['licence']]).'</td>
  </tr>'."\n";
}

$tableau .= '</table></div>';

try {
  $handler = fopen("../project/web/documentation/stats/base.csv","w");
  fputs($handler,$csv);
  $handler = fopen("../project/web/documentation/stats/base.html","w");
  fputs($handler,$tableau);
  echo "Page mise à jour : http://www.juricaf.org/documentation/stats/statuts.php\n";
  echo "Tableur généré : http://www.juricaf.org/documentation/stats/base.csv";
} catch (Exception $e) {
  echo "Erreur d'enregistrement\n";
  echo $e->getMessage()."\n";
  exit;
}
