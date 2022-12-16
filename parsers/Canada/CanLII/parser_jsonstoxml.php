<?php 

if( count($argv) != 4 || !file_exists($argv[1]) || !file_exists($argv[2]) ){
  fwrite(STDERR, "MISSING JSONs NAME OR SOURCE");
  exit(1);
}

$meta_json = $argv[1];
$content_json = $argv[2];
$source = $argv[3];

$meta_content = json_decode(file_get_contents($meta_json));
$date = $meta_content->raw->datejurisprudence;

if (!$meta_content->raw->juridiction){
  fwrite(STDERR, "MISSING JURIDICTION IN META");
  exit(0);
}
$juridiction = $meta_content->raw->tribunal;
$numero = $meta_content->raw->referenceneutre;

$mois = ['01' => 'janvier', '02' => 'février', '03' => 'mars', '04' => 'avril', '05' => 'mai', '06' => 'juin', '07' => 'juillet', '08' => 'août', '09' => 'septembre', '10' => 'octobre', '11' => 'novembre', '12' => 'décembre'];

$j = $date[8].$date[9];
$m = $date[5].$date[6];
$a = $date[0].$date[1].$date[2].$date[3];

$dateiso = $j."-".$m."-".$a;
$datefr = $j." ".$mois[$m]." ".$a;


$titre = $meta_content->title;  
$demandeur =  "";
$defendeur = "";
if(preg_match("#(.+) c[ ,.](.+),#",$titre,$parties)){
  if($parties[1] == "R."){
    $parties[1] == 'Sa Majesté la Reine';
  }
  if($parties[2] == "R."){
    $parties[2] == 'Sa Majesté la Reine';
  }
  
  $demandeur = trim($parties[1]);
  $defendeur = trim($parties[2]);
}

$content = file_get_contents($content_json);

$characterestoescape = ["<" => "&lt;",
             ">" => "&gt;",
             "&" => "&amp;"];

foreach($characterestoescape as $k => $v){
  $content = str_replace($k,$v,$content);
  $source = str_replace($k,$v,$source);
}

$texte = $content;

$tabregex = ["Cour suprême du Canada-CA" => "#COUR SUPRÊME DU CANADA(.+)Retour en haut#",
             "Cour d'appel-QC" => "#COUR D'APPEL(.+)Retour en haut#",
             "Cour supérieure-QC" => "#COUR SUPÉRIEURE(.+)Retour en haut#"];

if(preg_match($tabregex[$juridiction],$texte,$texte)){
  $texte = $texte[1];
}
else{
  fwrite(STDERR, "MISSING TEXTE CONTENT FOR $source");
  exit(0);
}

$name = basename($meta_json, ".json");

echo('<?xml version="1.0" encoding="UTF-8"?>'."\n");
echo "string";("<DOCUMENT>\n");
echo("<DATE_ARRET>$dateiso</DATE_ARRET>\n");
echo("<JURIDICTION>$juridiction</JURIDICTION>\n");
echo("<NUM_ARRET>$numero</NUM_ARRET>\n");
echo("<PAYS>Canada</PAYS>\n");
echo("<TEXTE_ARRET>$texte</TEXTE_ARRET>\n");
echo("<PARTIES>\n<DEMANDEURS>\n<DEMANDEUR>$demandeur</DEMANDEUR>\n</DEMANDEURS>\n<DEFENDEURS>\n<DEFENDEUR>$defendeur</DEFENDEUR>\n</DEFENDEURS>\n</PARTIES>\n");
echo("<TITRE>Canada, $juridiction, $datefr, $numero</TITRE>\n");
echo("<SOURCE>$source</SOURCE>\n");
echo("<TYPE>arret</TYPE>\n");
echo("<FONDS_DOCUMENTAIRE>unik.caij.qc.ca</FONDS_DOCUMENTAIRE>\n");
echo("</DOCUMENT>\n");