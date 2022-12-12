<?php

if( count($argv) != 3 || !file_exists($argv[1]) ){
  fwrite(STDERR, "MISSING HTML FILE OR SOURCE");
  exit(1);
}
$inputfile = $argv[1];
$source = $argv[2];

$content = file_get_contents($inputfile);
$content = str_replace("\n", '', $content); 
$content = preg_replace('/  +/', ' ', $content);

$name = basename($inputfile, ".html");

if (preg_match('#<legend title="">.*?(\d{2}) ([^\s]+) (\d{4})\s*</legend>#', $content, $m)) {
  //if (preg_match('#<p class="champ-entete-table">No ECLI:</p></td> *<td><p class="description-entete-table">[^<]*\.(\d{4})(\d{2})(\d{2})\.[^<]*</p>#', $content, $m)) {
  $mois = ['janvier' => '01', 'février' => '02', 'mars' => '03', 'avril' => '04', 'mai' => '05', 'juin' => '06', 'juillet' => '07', 'août' => '08', 'septembre' => '09', 'octobre' => '10', 'novembre' => '11', 'décembre' => '12'];
  $moisfr = html_entity_decode($m[2]);
  $datefr = $m[1].' '.$moisfr.' '.$m[3];
  $dateiso = $m[3].'-'.$mois[$moisfr].'-'.$m[1];
}

$juridictions = ["CASS" => "Cour de cassation",
"GHCC" => "Cour constitutionnel",
"CALIE" => "Cour d'appel du ressort de Liège",
"CTLIE" => "Cour du travail de Liège et divisions Namur - Neufchâteau",
"CABRL" => "Cour d'appel du ressort de Liège",
"CAMON" => "Cour d'appel du ressort de Mons",
"CTBRL" => "Cour du travail de Bruxelles",
"PIBRL" => "Tribunal de première instance francophone de Bruxelles"];

if (preg_match("#ECLI:BE:(.+?):#", $name, $j)) {
  $juridiction = $j[1];
  if (array_key_exists($j[1], $juridictions)){
    $juridiction = $juridictions[$j[1]];
  }
}

if (preg_match('#<p class="champ-entete-table">(?:No Arrêt/)?No Rôle:</p></td> *<td><p class="description-entete-table">(.+?)</p>#', $content, $m)) {
  $numero = $m[1];
}

if (preg_match('#<fieldset\s*id="text">.*?<div\s*id="plaintext">(.+?)</div>#', $content, $m)){
  $arret_text = $m[1];
  $arret_text = preg_replace("/\s*<p>\s*/", "", $arret_text);
  $arret_text = str_replace('&apos;', "'", $arret_text);
  $arret_text = str_replace("</p>", "\n", $arret_text);
  $arret_text = str_replace("<br>", "\n", $arret_text);
  $arret_text = trim(strip_tags($arret_text));
}

$audience = ['formation' => false, 'president' => false, 'assesseurs' => false, 'ministere_public' => false, 'greffier' => false ];
if (preg_match('#<p class="champ-entete-table">Audience:</p></td> *<td><p class="description-entete-table">\s*(\S.*?\S)\s*</p>#', $content, $m)) {
  $meta_audience = explode('<br>', $m[1]);
  $audience['formation'] = trim(array_shift($meta_audience));
  foreach($meta_audience as $meta) {
    if (preg_match('/ *(\S.*\S), Présidente?/', $meta, $m)) {
      $audience['president'] = $m[1];
    }
    if (preg_match('/ *(\S.*\S), Assesseurs?/', $meta, $m)) {
      $audience['assesseurs'] = $m[1];
    }
    if (preg_match('/ *(\S.*\S), Ministère public/', $meta, $m)) {
      $audience['ministere_public'] = $m[1];
    }
    if (preg_match('/ *(\S.*\S), Greffi[eè]re?/', $meta, $m)) {
      $audience['greffier'] = $m[1];
    }
  }
}
if (preg_match('#<p class="champ-entete-table">Domaine juridique:</p></td> *<td><p class="description-entete-table">\s*(\S.*?\S)\s*</p>#', $content, $m)) {
  $type_affaire = $m[1];
}
$analyses = [];
if (preg_match_all('#<fieldset id="(notice\d+)" >.*?<div class="plaintext"> *<p>\s*(\S.*?\S)\s*</p>.*?</fieldset>#', $content, $m, PREG_SET_ORDER)) { 
  foreach ($m as $fieldset_match) {
    $index = $fieldset_match[1];
    $analyses[$index] = ['titre_principal' => '', 'reference' => [], 'sommaire' => []];
    $analyses[$index]['titre_principal'] = $fieldset_match[2];
    if (preg_match_all('#<p class="champ-notice-table">(Thésaurus[^:]*|Mots libres|Bases légales):</p></td> *<td> *<p class="description-notice-table">\s*(\S.*?\S)\s*</p>#', $fieldset_match[0], $m3, PREG_SET_ORDER)) {
      foreach ($m3 as $tr) {
        $tr[2] = str_replace('<br>', ' ; ', $tr[2]);
        $tr[2] = str_replace('<a ', ' / <a ', $tr[2]);
        $tr[2] = str_replace('Lien ELI ', '', $tr[2]);
        $tr[2] = strip_tags($tr[2]);
        if ($tr[1] == 'Bases légales') {
          $analyses[$index]['reference'][$tr[2]] = $tr[2];
        } else{
          $analyses[$index]['sommaire'][$tr[2]] = $tr[2];
        }
      }
    }
  }
}

if (!isset($dateiso) || !isset($juridiction) || !isset($numero) || !isset($arret_text)) {
  fwrite(STDERR, "\n\nDONNEES MANQUANTE " . print_r($argv, true));
  fwrite(STDERR, print_r([$arret_text,$dateiso,$juridiction,$numero], true));
  exit(2);
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
if ($formation = $audience['formation']) {
  echo("<FORMATION>$formation</FORMATION>\n");
}
if ($president = $audience['president']) {
  echo("<PRESIDENT>$president</PRESIDENT>\n");
}
if ($assesseurs = $audience['assesseurs']) {
  echo("<ASSESSEURS>$assesseurs</ASSESSEURS>\n");
}
if ($ministere_public = $audience['ministere_public']) {
  echo("<MINISTERE_PUBLIC>$ministere_public</MINISTERE_PUBLIC>\n");
}
if ($greffier = $audience['greffier']) {
  echo("<GREFFIER>$greffier</GREFFIER>\n");
}
if (count($analyses)) {
  $has_ref = false;
  echo("<ANALYSES>\n");
  foreach($analyses as $notice_id => $analyse)  {
    echo("<ANALYSE>\n");
    echo("<TITRE_PRINCIPAL>".$analyse['titre_principal']."</TITRE_PRINCIPAL>\n");
    echo("<SOMMAIRE>");
    echo(implode(' - ', $analyse['sommaire']));
    if (!empty($analyse['reference'])) {
      $has_ref = true;
      echo(" [$notice_id]");
    }
    echo("</SOMMAIRE>\n");
    echo("</ANALYSE>\n");
  }
  echo("</ANALYSES>\n");
  if ($has_ref) {
    echo("<REFERENCES>\n");
    foreach($analyses as $notice_id => $analyse)  {
      if (!empty($analyse['reference'])) {
        echo("<REFERENCE>\n");
        echo("<TYPE>CITATION_ANALYSE</TYPE>");
        echo("<TITRE>");
        echo("[$notice_id] " . implode(" ; ", $analyse['reference']));
        echo("</TITRE>");
        echo("</REFERENCE>\n");
      }
    }
    echo("</REFERENCES>\n");
  }
}
if(isset($type_affaire)) {
  echo("<TYPE_AFFAIRE>$type_affaire</TYPE_AFFAIRE>\n");
}
echo("<FONDS_DOCUMENTAIRE>juportal.be</FONDS_DOCUMENTAIRE>\n");
echo("</DOCUMENT>\n");