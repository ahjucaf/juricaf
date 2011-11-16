<?php
require("config.php");

function getSolrResults($pays, $juridiction, $sort) {
  $stream = fopen('http://localhost:8080/solr/select/?q=facet_pays:"'.urlencode($pays).'"+facet_juridiction:"'.urlencode($juridiction).'"&fq=type:arret&indent=on&sort=date_arret+'.$sort, 'r');
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
    $value = str_replace('ODBL', '<a href="http://www.juricaf.org/documentation/licence_odbl.php">ODBL</a>', $value);
  }
  if(strpos($value, 'AHJUCAF') !== false) {
    $value = str_replace('AHJUCAF', '<a href="http://www.juricaf.org/documentation/licence_ahjucaf.php">AHJUCAF</a>', $value);
  }
  if(strpos($value, 'Légifrance') !== false) {
    $value = str_replace('Légifrance', '<a href="http://www.legifrance.gouv.fr/Informations/Licences">Légifrance</a>', $value);
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

try { $bdd = new PDO('mysql:host='.$HOST.';dbname='.$DBNAME, $DBUSER, $DBPASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (Exception $error) { die('Erreur : '.$error->getMessage()); }

$req = $bdd->query('SELECT * FROM '.$DBTABLE);

$csv = '"Pays";"Institution";Nombre;"Etat";"Mise à jour";"Selection";"Traduction";"Plus ancien";"Plus récent";"Licence"';
$csv .= "\n";

$tableau = "<table class=\"statsbase\">\n";
$tableau .= "<tr><th>Pays</th><th>Institution</th><th>Nombre</th><th>Etat</th><th>Mise à jour</th><th>Selection</th><th>Traduction</th><th>Plus ancien</th><th>Plus récent</th><th>Licence</th></tr>\n";
$classe = "color2";

while ($donnees = $req->fetch())
{
  $results = array();
  $results = getSolrResults($donnees['pays'], $donnees['juridiction'], 'desc'); // Premier
  $results = array_merge($results, getSolrResults($donnees['pays'], $donnees['juridiction'], 'asc')); // Dernier

  $csv .= '"'.$donnees['pays'].'";"'.$donnees['juridiction'].'";'.$results['nb'].';"'.$donnees['etat'].'";"'.$donnees['maj'].'";"'.$donnees['selection'].'";"'.$donnees['traduction'].'";"'.$results['date_fin'].'";"'.$results['date_debut'].'";"'.$donnees['licence']."\"\n";

  if($classe == "color1") { $classe = "color2"; } else { $classe = "color1"; }
  $fpjlink = str_replace(' ', '_', 'http://www.juricaf.org/recherche/+/facet_pays:'.$donnees['pays'].',facet_juridiction:'.$donnees['juridiction']);
  $tableau .= '<tr class="'.$classe.'">
  <td><a href="http://www.juricaf.org/recherche/recherche/+/facet_pays:'.$donnees['pays'].'">'.$donnees['pays'].'</a></td>
  <td><a href="'.$fpjlink.'">'.$donnees['juridiction'].'</a></td>
  <td class="num">'.$results['nb'].'</td><td style="text-align: center;">'.addLegend($donnees['etat'], 'etat').'</td>
  <td style="text-align: center;">'.addLegend($donnees['maj'], 'maj').'</td>
  <td style="text-align: center;">'.addLegend($donnees['selection'], 'selection').'</td>
  <td>'.$donnees['traduction'].'</td><td style="text-align: center;">'.$results['date_fin'].'</td>
  <td style="text-align: center;">'.$results['date_debut'].'</td><td>'.returnLicenceLink($donnees['licence']).'</td>
  </tr>'."\n";
}

$tableau .= '</table>';

try {
  $handler = fopen("../project/web/documentation/stats/base.csv","w");
  fputs($handler,$csv);
  $handler = fopen("../project/web/documentation/stats/base.html","w");
  fputs($handler,$tableau);
  echo "Page mise à jour : http://www.juricaf.org/documentation/stats/statuts.php\n";
  echo "Tableur généré : http://www.juricaf.org/documentation/stats/base.csv";
}
catch (Exception $e) {
  echo "Erreur d'enregistrement\n";
  echo $e->getMessage()."\n";
  exit;
}
?>