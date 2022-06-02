<?php

$xmlfile = $argv[1];
$source = $argv[2];
$arguments =  explode('_', preg_replace('/.*\//', '', preg_replace('/\.[^\.]+$/', '', $xmlfile)));

$lesmois = array("janver"=>"01", "janvier" => "01", "fevrier" => "02", "février" => "02", "mars" => "03", "avril" => "04", "mai" => "05", "juni" => "06", "juin" => "06",
 "juillet" => "07", "aout" => "08", "août" => "08", "septembre" => "09", "ocotbre" => "10", "octobre" => "10",  "novembre" => "11", "decembre" => "12", "décembre" => "12");
$lesnombres = array (
  "un" => "01", "premier" => "02", "deux" => "02", "trois" => "03", "quatre"=>"04", "cinq"=>"05", "six"=>"06", "sept"=>"07", "huit"=>"08","neuf"=>"09",
  "dix"=>"10", "onze"=>"11", "douze"=>"12", "treize"=>"13", "quatorze"=>"14", "quinze"=>"15", "seize"=>"16", "dix-sept"=>"17", "dix-huit"=>"18", "dix-neuf"=>"19",
  "vingt"=>"20", "vingt-et-un" => "21", "vingt et un" => "21", "vingt-deux" => "22", "vingt-trois" => "23", "vingt-quatre" => "24", "vingt-cinq" => "25", "vingt-six" => "26","vingt-sept" => "27", "vingt-huit" => "28", "vingt-neuf" => "29",
  "trente"=>"30", "trente et un" => "31",
  "deux mille" => "2000", "deux mille un" => "2001", "deux mille deux" => "2002", "deux mille trois" => "2003", "deux mille quatre" => "2004",
  "deux mille cinq" => "2005", "deux mille six" => "2006", "deux mille sept" => "2007", "deux mille huit" => "2008", "deux mille neuf" => "2009",
  "deux mille dix" => "2010", "deux mille onze" => "2011", "deux mille douze" => "2012", "deux mille treize" => "2013", "deux mille quatorze" => "2014",
  "deux mille quinze" => "2015", "deux mille seize" => "2016", "deux mille dix-sept" => "2017", "deux mille dix-huit" => "2018", "deux mille dix-neuf" => "2019",
  "deux mille vingt" => "2020", "deux mille vingt-et-un" => "2021", "deux mille vingt-deux" => "2022", "deux mille vingt-trois" => "2023", "deux mille vingt-quatre" => "2024"
);
$lesmois_reverse = array();
foreach ($lesmois as $key => $value) {
    $lesmois_reverse[$value] = $key;
}


$content = file_get_contents('php://stdin');
$content = preg_replace("/&(#160|nbsp);/", " ", $content);
$content = html_entity_decode($content);
$content = preg_replace("/ <br.>\n[0-9]* <br.>\n<hr.>\n<a name=[0-9]*><.a>/", '', $content);
$content = preg_replace('/<\/?b>/', '', $content);
$content = preg_replace('/<br\/?>(janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre|mille)/', '\1', $content);

$header = substr($content, 0, 1000);

$juridiction = $arguments[1];
$numero = $arguments[3];
$date = preg_replace('/(....)(..)(..)/', '\1-\2-\3', $arguments[0]);
$partie1 = "";
$partie2 = "";

if (preg_match('/(COUR [^<]*[^ :]|TRIBUNAL[^<]*[^ :])[ :]*</i', $header, $match)) {
  $juridiction = $match[1];
}
$formation = null   ;
if (preg_match('/tribunal +administratif([^\.;]+) statuant/i', $content, $match)) {
    $test_formation = preg_replace('/[ ,]+$/', '', preg_replace('/^[ ,]+/', '', $match[1]));
    if (preg_match('/chambre/', $test_formation)) {
        $formation = $test_formation;
    }
    if ($juridiction) {
        $juridiction = 'Tribunal administratif';
    }
}elseif (preg_match('/la +cour[^\.]+ statuant/i', $content)) {
    $juridiction = 'Cour administrative';
}
$found_numero = $numero;
if (preg_match('/(Numéros?|N°|Nos?) *([A-Z0-9][A-Z0-9\-, et\+]+) *du(<br.?>|\n| )*(rôle)/i', $header, $match) || preg_match('/(Numéros?|N°|Nos?) +du +rôle[ : <b>]+([A-Z0-9][A-Z0-9 ]+[A-Z0-9])/', $header, $match)) {
    $found_numero = $match[2];
}elseif (preg_match('/(Numéros?|N°|Nos?) *([0-9]+ *\/ *([21][0-9]{3}|[0-9]{2}))/i', $header, $match)) {
    $found_numero = $match[2];
}
$found_numero = str_replace(' et ', ',', $found_numero);
$found_numero = str_replace(' ', '', $found_numero);
$found_numero = preg_replace('/\/20(\d\d)/', '/\1', $found_numero);

if (preg_match('/[0-9]/', $found_numero)) {
    $numero = $found_numero;
}



if (preg_match('/Audience publique( extraordinaire de vacation | extraordinaire | de vacation | )(du)? *([^<]*[0-9]) *</i', $header, $match)){
  $date = $match[3];
}elseif (preg_match('/(Luxembourg|publique)( |<br.?>|<b>)+du( |<br.?>|<b>)+(lundi.....[^<]*|mardi......[^<]*|mercredi.......[^<]*|jeudi......[^<]*|vendredi....[^<]*|samedi......[^<]*|dimanche......[^<]*) *</i', $header, $match)){
  $date = $match[4];
}elseif (preg_match('/du ([0-9]{1,2}[.\/][0-9]{1,2}[.\/][0-9]{4})./', $header, $match)){
  $date = $match[1];
}
if (preg_match('/Recours formé par ([^<]*) *<.*\ncontre ([^<]*) */', $header, $match)) {
  $partie1 = $match[1];
  $partie2 = $match[2];
}

if ($date) {
  $date = preg_replace('/<[^>]*>/', '', $date);
  $date = preg_replace('/  +/', ' ', $date);
  $date = strtolower($date);
  $date = str_replace(array('Û', 'É'), array('û', 'é'), $date);
  $date = preg_replace('(\.\.\.|…)', '01', $date);
  $date = preg_replace('/\. /', ' ', $date);
  $jour = null;
  $mois = null;
  $annee = null;

  if (preg_match('/([0-9]{1,2})[\.\/]([0-9]{1,2})[\.\/]([0-9]{4})/', $date, $m)) {
    $jour = sprintf("%02d", $m[1]);
    $mois = sprintf("%02d", $m[2]);
    $annee = $m[3];
  } elseif (preg_match('/ *([^,\.]*[^,\. ]) ?('.join('|', array_keys($lesmois)).') ?([^,\. ][^,\.]*[^,\. ])/i', $date, $m)) {
    $m = preg_replace('/  +/', ' ', $m);
    $mois = $lesmois[$m[2]];
    if (preg_match('/[0-9]/', $m[1])) {
      $jour = sprintf("%02d", $m[1]);
    }else{
      $m[1] = preg_replace('/jeudi /', '', $m[1]);
      $jour = $lesnombres[$m[1]];
    }
    if (preg_match('/[0-9]/', $m[3])) {
      $annee = $m[3];
    }else{
      $annee = $lesnombres[$m[3]];
    }
  }
  if ($annee && $mois && $jour) {
    $date = $annee.'-'.$mois.'-'.$jour;
  }elseif(!preg_match('/^....-..-..$/', $date)){
    error_log("d: $date\n");
  }
}

if (preg_match('/Cour +de +cassation/i', $juridiction)) {
    $juridiction = 'Cour de cassation';
}elseif (preg_match('/Cour +administrative/i', $juridiction)) {
    $juridiction = 'Cour administrative';
}elseif (preg_match('/Tribunal +administratif/i', $juridiction)) {
    $juridiction = 'Tribunal administratif';
}

$numero = preg_replace('/[+,] */', ',', $numero);

$ligne = "$juridiction;$formation;$numero;$date;$partie1;$partie2;$xmlfile";
$ligne = preg_replace('/<[^>]*>/', '', $ligne);
$ligne = preg_replace('/\n/', ' ', $ligne);
$ligne = preg_replace('/  */', ' ', $ligne);
$ligne = preg_replace('/; */', ';', $ligne);
$ligne = preg_replace('/ *;/', ';', $ligne);

print "$ligne;\n";

$content = preg_replace('/\.\.\./', '…', $content);
$content = preg_replace('/<br[^>]*>/', "\n", $content);
$content = preg_replace('/<[^>]*>/', '', $content);
$content = preg_replace('/\n/', 'ø', $content);
$content = preg_replace('/ *ø */', 'ø', $content);
$content = preg_replace('/([\.;_:=-])ø+ */', '\1'."\n\n", $content);
$content = preg_replace('/([^=\n])===/', '\1'."\n\n===", $content);
$content = preg_replace('/([^-\n])---/', '\1'."\n\n---", $content);
$content = preg_replace('/([^_\n])___/', '\1'."\n\n___", $content);
$content = preg_replace('/ø+/', ' ', $content);
$content = preg_replace('/\n +/', "\n", $content);
$content = preg_replace('/  +/', " ", $content);
$content = preg_replace('/[\n ](JUGEMENT|ORDONNANCE) /',"\n\n".'\1'."\n\n", $content);
$content = preg_replace('/ (_+)\n/',"\n".'$1'."\n", $content);
$content = preg_replace('/^ +/', '', $content);
$content = preg_replace('/\n\d+ *$/', '', $content);

$output = fopen($xmlfile, 'w');
fwrite($output, '<?xml version="1.0" encoding="utf8"?>'."\n");
fwrite($output, "<DOCUMENT>\n");
fwrite($output, "<NUM_ARRET>$numero</NUM_ARRET>\n");
fwrite($output, "<DATE_ARRET>$date</DATE_ARRET>\n");
fwrite($output, "<JURIDICTION>$juridiction</JURIDICTION>\n");
fwrite($output, "<PAYS>Luxembourg</PAYS>\n");
fwrite($output, "<TYPE>arret</TYPE>\n");
$dates = explode('-', $date);
$datefr = $dates[2].' '.$lesmois_reverse[$dates[1]].' '.$dates[0];
if ($source) {
    fwrite($output, "<SOURCE>$source</SOURCE>\n");
}
fwrite($output, "<TITRE>Luxembourg, $juridiction, $datefr, $numero</TITRE>\n");
if ($formation) {
    fwrite($output, "<FORMATION>$formation</FORMATION>\n");
}
fwrite($output, "<TEXTE_ARRET>$content</TEXTE_ARRET>\n");
fwrite($output, "</DOCUMENT>\n");
fclose($output);
