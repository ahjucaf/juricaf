<?php 

if( count($argv) != 3 || !file_exists($argv[1]) || !file_exists($argv[2]) ){
  fwrite(STDERR, "MISSING JSONs NAME : ".implode(' ', $argv)."\n");
  exit(1);
}

$meta_json = $argv[1];
$content_json = $argv[2];

$meta_content = json_decode(file_get_contents($meta_json));
$date = $meta_content->raw->datejurisprudence;

if (!$meta_content->raw->juridiction){
  fwrite(STDERR, "MISSING JURIDICTION IN META: $meta_json\n");
  exit(1);
}

$juridictions = [
      'Cour supérieure-QC' => 'Cour supérieure du Québec',
      'Cour suprême du Canada-CA' => 'Cour suprême'
    ];

$juridiction = isset($juridictions[$meta_content->raw->tribunal]) ? $juridictions[$meta_content->raw->tribunal] : $meta_content->raw->tribunal;
$numero = $meta_content->raw->referenceneutre;

$mois = ['01' => 'janvier', '02' => 'février', '03' => 'mars', '04' => 'avril', '05' => 'mai', '06' => 'juin', '07' => 'juillet', '08' => 'août', '09' => 'septembre', '10' => 'octobre', '11' => 'novembre', '12' => 'décembre'];

$m = $date[5].$date[6];

$dateiso = $date;
$datefr = ($date[8]*10 + $date[9])." ".$mois[$m]." ".$date[0].$date[1].$date[2].$date[3];

$titre = $meta_content->title;
$titre = str_replace(' (CanLII)', '', $titre);
$demandeur =  "";
$defendeur = "";
if(preg_match("#(.+) c[ ,.](.+),#",$titre,$parties)){
  $demandeur = trim($parties[1]);
  $defendeur = trim($parties[2]);
}

$source = $meta_content->raw->sysuri;

$avocats = (isset($meta_content->raw->cabinet)) ? $meta_content->raw->cabinet : "";
$procureur = (isset($meta_content->raw->procureur)) ? $meta_content->raw->procureur : "";
$juge = (isset($meta_content->raw->juge)) ? $meta_content->raw->juge : "";

$analyses = (isset($meta_content->raw->sysconcepts)) ? $meta_content->raw->sysconcepts : "";

$content = file_get_contents($content_json);

$characterestoescape = ["<" => "&lt;",
             ">" => "&gt;",
             "&" => "&amp;"];


foreach($characterestoescape as $k => $v){
  $content = str_replace($k,$v,$content);
  $source = str_replace($k,$v,$source);
}

$tabregex = ["Cour suprême" => "#(COUR SUPRÊME DU CANADA.+)Retour en haut#",
             "Cour d'appel-QC" => "#(COUR D[’']APPEL.+)Retour en haut#i",
             "Cour supérieure du Québec" => "#(C *O *U *R[^A-Z]*S *U *P *É *R *I *E *U *R *E.+)Retour en haut#i"];


if(preg_match($tabregex[$juridiction],$content,$texte)){
  $texte = $texte[1];
}
else{
  fwrite(STDERR, "MISSING TEXTE CONTENT IN $content_json FROM $source\n");
  exit(1);
}

$texte = str_replace("\\n","\n",$texte);

echo('<?xml version="1.0" encoding="UTF-8"?>'."\n");
echo("<DOCUMENT>\n");
echo("<DATE_ARRET>$dateiso</DATE_ARRET>\n");
echo("<JURIDICTION>$juridiction</JURIDICTION>\n");
echo("<NUM_ARRET>".str_replace(" ","",$numero)."</NUM_ARRET>\n");
echo("<PAYS>Canada</PAYS>\n");
echo("<TEXTE_ARRET>$texte</TEXTE_ARRET>\n");
if ($demandeur || $defendeur) {
  echo("<PARTIES>\n");
  if ($demandeur) {
    echo("<DEMANDEURS>\n<DEMANDEUR>$demandeur</DEMANDEUR>\n</DEMANDEURS>\n");
  }
  if ($defendeur) {
    echo("<DEFENDEURS>\n<DEFENDEUR>$defendeur</DEFENDEUR>\n</DEFENDEURS>\n");
  }
  echo("</PARTIES>\n");
}
if($procureur){
  echo("<PROCUREUR>$procureur</PROCUREUR>\n");
}
if($juge){
  echo("<PRESIDENT>$juge</PRESIDENT>\n");
}
if($analyses){
  echo("<ANALYSES>\n");
  echo("<ANALYSE>\n");
  echo("<TITRE_PRINCIPAL>".str_replace(";","—",$analyses)."</TITRE_PRINCIPAL>\n");
  echo("</ANALYSE>\n");
  echo("</ANALYSES>\n");
}
echo("<TITRE>Canada, $juridiction, $datefr, $titre</TITRE>\n");
echo("<SOURCE>$source</SOURCE>\n");
echo("<TYPE>arret</TYPE>\n");
echo("<FONDS_DOCUMENTAIRE>CAIJ</FONDS_DOCUMENTAIRE>\n");
echo("</DOCUMENT>\n");

fwrite(STDERR, str_replace("-meta.json",".xml",basename($meta_json))." crée\n");