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
  if(!empty($critere)) { $critere = '+'.$critere.':*'; }
  $stream = fopen('http://localhost:8080/solr/select/?q=facet_pays:%22'.urlencode($pays).'%22+facet_juridiction:%22'.urlencode($juridiction).'%22'.$critere.'&fq=type:arret&indent=on', 'r');
  $xml = trim(stream_get_contents($stream));
  $response = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_COMPACT);
  fclose($stream);
  return $response->result["numFound"];
}

try { $bdd = new PDO('mysql:host='.$HOST.';dbname='.$DBNAME, $DBUSER, $DBPASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (Exception $error) { die('Erreur : '.$error->getMessage()); }

$req = $bdd->query('SELECT * FROM '.$DBTABLE);

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

while ($donnees = $req->fetch())
{
  $nb = getSolrResults($donnees['pays'], $donnees['juridiction']);
  foreach ($criteres as $critere) {
    $collection[$critere] = getSolrResults($donnees['pays'], $donnees['juridiction'], $critere);
  }

  $csv .= '"'.$donnees['pays'].'";"'.$donnees['juridiction'].'";'.$nb;

  $fpjlink = str_replace(' ', '_', 'http://www.juricaf.org/recherche/+/facet_pays:'.$donnees['pays'].',facet_juridiction:'.$donnees['juridiction']);
  if($classe == "color1") { $classe = "color2"; } else { $classe = "color1"; }
  $tableau .= '<tr class="'.$classe.'"><td><a href="http://www.juricaf.org/recherche/recherche/+/facet_pays:'.$donnees['pays'].'">'.$donnees['pays'].'</a></td><td><a href="'.$fpjlink.'">'.$donnees['juridiction'].'</a></td><td class="num">'.$nb.'</td>';

  foreach ($criteres as $critere) {
    $csv .= ';'.$collection[$critere];
    $tableau .= '<td class="num">'.$collection[$critere].'</td>';
  }

  $csv .= "\n";
  $tableau .= "</tr>\n";
}

$tableau .= "\n</table>";

try {
  $handler = fopen("../project/web/documentation/stats/champs.csv","w");
  fputs($handler,$csv);
  $handler = fopen("../project/web/documentation/stats/champs.html","w");
  fputs($handler,$tableau);
  echo "Tableur généré : http://www.juricaf.org/documentation/stats/champs.csv";
}
catch (Exception $e) {
  echo "Erreur d'enregistrement\n";
  echo $e->getMessage()."\n";
  exit;
}
?>
