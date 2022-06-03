<?php
require("config.php");

$pays_juridictions = array();
if (($handle = fopen("../stats/static/base.csv", "r")) !== FALSE) while (($donnees = fgetcsv($handle, 1000, ";")) !== FALSE) {
    if ($donnees[$HEADER2CSVID['maj']] || $donnees[$HEADER2CSVID['etat']] || $donnees[$HEADER2CSVID['licence']] ) {
        $pays_juridictions[$donnees[$HEADER2CSVID['pays']].' | '.$donnees[$HEADER2CSVID['juridiction']]] = $donnees;
    }
}

$stream = fopen('http://'.$SOLRHOST.':8080/solr/select?indent=on&version=2.2&q=type:arret&rows=0&facet=true&facet.field=facet_pays_juridiction&facet.limit=-1', 'r');
$xml = trim(stream_get_contents($stream));
$response = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_COMPACT);
fclose($stream);
foreach($response->lst[1]->lst[1]->lst->int as $int) {
    $name = (string) $int['name'];
    $nb = $int[0];
    if (!isset($pays_juridictions[$name]) && $nb > 0) {
        $pays_juridictions[$name] = explode(' | ', $name);
        $pays_juridictions[$name][] = '';
        $pays_juridictions[$name][] = '';
        $pays_juridictions[$name][] = '';
        $pays_juridictions[$name][] = '';
        $pays_juridictions[$name][] = '';
        $pays_juridictions[$name][] = '';
        $pays_juridictions[$name][] = '';
        $pays_juridictions[$name][] = '';
    }
}
$csv = '"Pays";"Institution";Nombre;"Etat";"Mise à jour";"Selection";"Traduction";"Plus ancien";"Plus récent";"Licence";';
$csv .= "\n";

$line = -1;
asort($pays_juridictions);
foreach($pays_juridictions as $donnees) {
    $line++;
    if (!$line) continue;
    $csv .= $donnees[$HEADER2CSVID['pays']].';'.$donnees[$HEADER2CSVID['juridiction']].';;'.$donnees[$HEADER2CSVID['etat']].';'.$donnees[$HEADER2CSVID['maj']].';';
    $csv .= $donnees[$HEADER2CSVID['selection']].';'.$donnees[$HEADER2CSVID['traduction']].';;;'.$donnees[$HEADER2CSVID['licence']]."\n";
}

$handler = fopen($ORIGINALCSV,"w");
fputs($handler,$csv);
