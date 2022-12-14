<?php 

if( count($argv) != 4 || !file_exists($argv[1]) || !file_exists($argv[2]) ){  //il faut 3 argument
  fwrite(STDERR, "MISSING JSONs NAME OR SOURCE");
  exit(1);
}

$meta_json = $argv[1];
$content_json = $argv[2];
$source = $argv[3];

$meta_content = json_decode(file_get_contents($meta_json));
$date = $meta_content->raw->datejurisprudence;
$numero = $meta_content->raw->dossier;
$juridiction = "Cour Suprème";
$reference = $meta_content->raw->referenceneutre;

$mois = ['01' => 'janvier', '02' => 'février', '03' => 'mars', '04' => 'avril', '05' => 'mai', '06' => 'juin', '07' => 'juillet', '08' => 'août', '09' => 'septembre', '10' => 'octobre', '11' => 'novembre', '12' => 'décembre'];

$j = $date[8].$date[9];
$m = $date[5].$date[6];
$a = $date[0].$date[1].$date[2].$date[3];

$dateiso = $j."-".$m."-".$a;
$datefr = $j." ".$mois[$m]." ".$a;

$content = file_get_contents($content_json);


$characterestoescape = ["<" => "&lt;",
             ">" => "&gt;",
             "&" => "&amp;",
             '"' => "&quot;",
             "'" => "&apos;"];

foreach($characterestoescape as $k => $v){
  $content = str_replace($k,$v,$content);
  $source = str_replace($k,$v,$source);
}

$texte = $content;

preg_match("#COUR SUPRÊME DU CANADA(.+)Retour en haut#",$texte,$texte);
$texte = $texte[1];

$name = basename($meta_json, ".json");

echo('<?xml version="1.0" encoding="UTF-8"?>'."\n");
echo("<DOCUMENT>\n");
echo("<DATE_ARRET>$dateiso</DATE_ARRET>\n");
echo("<JURIDICTION>$juridiction</JURIDICTION>\n");
echo("<NUM_ARRET>$numero</NUM_ARRET>\n");
echo("<PAYS>Canada</PAYS>\n");
echo("<TEXTE_ARRET>$texte</TEXTE_ARRET>\n");
echo("<TITRE>Canada, $juridiction, $datefr, $numero</TITRE>\n");
echo("<SOURCE>$source</SOURCE>\n");
echo("<TYPE>arret</TYPE>\n");
echo("<FONDS_DOCUMENTAIRE>unik.caij.qc.ca</FONDS_DOCUMENTAIRE>\n");
echo("</DOCUMENT>\n");

