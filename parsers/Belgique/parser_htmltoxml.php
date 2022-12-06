<?php

if( count($argv) != 3 || !file_exists($argv[1]) ){
  echo "MISSING HTML FILE OR SOURCE\n";
  exit(1);
}
$inputfile = $argv[1];
$source = $argv[2];

$content = file_get_contents($inputfile);
$content = str_replace("\n", '', $content); 

$name = basename($inputfile,".html");

if (preg_match('#<p class="champ-entete-table">No ECLI:</p></td> *<td><p class="description-entete-table">[^<]*\.(\d{4})(\d{2})(\d{2})\.[^<]*</p>#', $content, $m)) {
  $mois = ['01' => 'janvier', '02' => 'février', '03' => 'mars', '04' => 'avril', '05' => 'mai', '06' => 'juin', '07' => 'juillet', '08' => 'août', '09' => 'septembre', '10' => 'octobre', '11' => 'novembre', '12' => 'décembre'];
  $dateiso = "$m[1]-$m[2]-$m[3]";
  $datefr = $m[3]." ".$mois[$m[2]]." ".$m[1];
}

$juridictions = ["CASS" => "Cour de cassation",
                "GHCC" => "Cour constitutionnel",
                "CALIE" => "Cour d'appel du ressort de Liège",
                "CTLIE" => "Cour du travail de Liège et divisions Namur - Neufchâteau",
                "CABRL" => "Cour d'appel du ressort de Liège",
                "CAMON" => "Cour d'appel du ressort de Mons",
                "CTBRL" => "Cour du travail de Bruxelles",
                "PIBRL" => "Tribunal de première instance francophone de Bruxelles"];

if (preg_match("#ECLI:BE:(.+?):#",$name,$j)) {
  $juridiction = $j[1];
  if (array_key_exists($j[1], $juridictions)){
    $juridiction = $juridictions[$j[1]];
  }
}

if (preg_match('#<p class="champ-entete-table">No Rôle:</p></td> *<td><p class="description-entete-table">(.+?)</p>#',$content,$m)) {
  $numero = $m[1];
}

if (preg_match('#<div id="plaintext">(.+?)</div> *<p><a href="/JUPORTA#s',$content,$m)){
  $arret_text = $m[1];
  $arret_text = preg_replace("/\s*<p>\s*/", "", $arret_text);
  $arret_text = str_replace('&apos;', "'", $arret_text);
  $arret_text = str_replace("</p>", "\n", $arret_text);
  $arret_text = strip_tags($arret_text);
}

echo('<?xml version="1.0" encoding="UTF-8"?>'."\n");
echo("<DOCUMENT>\n");
echo("<DATE_ARRET>$dateiso</DATE_ARRET>\n");
echo("<JURIDICTION>$juridiction</JURIDICTION>\n");
echo("<NUM_ARRET>$numero</NUM_ARRET>\n");
echo("<PAYS>Belgique</PAYS>\n");
echo("<TEXTE_ARRET>$arret_text</TEXTE_ARRET>\n");
echo("<TITRE>Belgique, $juridiction, $datefr, $numero</TITRE>\n");
echo("<SOURCE>$source</SOURCE>\n");
echo("<TYPE>arret</TYPE>\n");
echo("<FONDS_DOCUMENTAIRE>juportal.be</FONDS_DOCUMENTAIRE>\n");
echo("</DOCUMENT>\n");