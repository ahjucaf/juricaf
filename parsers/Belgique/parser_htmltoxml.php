<?php


$xmlFolder='/tmp/xmls/';

shell_exec("mkdir -p $xmlFolder");

if(!$argv[1]){
  echo "MISSING HTML FILE";
  exit;
}

$inputfile= $argv[1];
$content=file_get_contents($inputfile);

$content = str_replace("\n", '', $content); 

$name =basename($inputfile,".html");

$sources = file_get_contents('/tmp/links_Belgique',true);
$source = "https://juportal.be/content/".$name;

preg_match('#<p class="champ-entete-table">Date d\'introduction:</p></td>            <td><p class="description-entete-table">(.+?)</p>#',$content,$date);
$date = $date[1];

$juridictions = ["CASS" => "Cours de Cassation",
                "GHCC" => "Cour constitutionnel",
                "CALIE" => "Cour d'appel du ressort de Liège",
                "CTLIE" => "Cour du travail de Liège et divisions Namur - Neufchâteau",
                "CABRL" => "Cour d'appel du ressort de Liège",
                "CAMON" => "Cour d'appel du ressort de Mons",
                "CTBRL" => "Cour du travail de Bruxelles",
                "PIBRL" => "Tribunal de première instance francophone de Bruxelles"];

preg_match("#ECLI:BE:(.+?):#",$name,$j);
$juridiction = $j[1];

if (array_key_exists($j[1], $juridictions)){
  $juridiction = $juridictions[$j[1]];
}

preg_match('#<p class="champ-entete-table">No Rôle:</p></td>            <td><p class="description-entete-table">(.+?)</p>#',$content,$numero);
$numero = $numero[1];


preg_match('#<div id="plaintext">(.+?)</div>       <p><a href="/JUPORTA#s',$content,$content);
$content = $content[1];
$content = str_replace("<br>","\n",$content);
$content = strip_tags($content);


$mois = ['janvier'=>'01','février'=>'02','mars'=>'03','avril'=>'04','mai'=>'05','juin'=>'06','juillet'=>'07','août'=>'08','septembre'=>'09','octobre'=>'10','novembre'=>'11','décembre'=>'12'];
$j = substr($date, 8,2);
$m = substr($date, 5,2);
$m = array_keys($mois,$m);
$m = $m[0];
$a = substr($date, 0,4);
$datefr = $j." ".$m." ".$a;


$output = fopen($xmlFolder.$name.'.xml', 'w');

fwrite($output, '<?xml version="1.0" encoding="utf8"?>');
fwrite($output,"\n");
fwrite($output, "<DOCUMENT>\n");
fwrite($output, "<DATE_ARRET>$date</DATE_ARRET>\n");
fwrite($output, "<JURIDICTION>$juridiction</JURIDICTION>\n");
fwrite($output, "<NUM_ARRET>$numero</NUM_ARRET>\n");
fwrite($output, "<PAYS>Belgique</PAYS>\n");
fwrite($output, "<TEXTE_ARRET>$content</TEXTE_ARRET>\n");
fwrite($output,"<TITRE>Belgique, $juridiction, $datefr</TITRE>\n");
fwrite($output,"<SOURCE>$source</SOURCE>");
fwrite($output, "<TYPE>arret</TYPE>\n");
fwrite($output, "<FONDS_DOCUMENTAIRE>juportal.be</FONDS_DOCUMENTAIRE>\n");
fwrite($output, "</DOCUMENT>\n");
fclose($output);

?>
