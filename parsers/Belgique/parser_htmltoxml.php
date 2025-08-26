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
  $mois = ['janvier' => '01', 'février' => '02', 'mars' => '03', 'avril' => '04', 'mai' => '05', 'juin' => '06', 'juillet' => '07', 'août' => '08', 'septembre' => '09', 'octobre' => '10', 'novembre' => '11', 'décembre' => '12'];
  $moisfr = html_entity_decode($m[2]);
  $datefr = $m[1].' '.$moisfr.' '.$m[3];
  $dateiso = $m[3].'-'.$mois[$moisfr].'-'.$m[1];
}

$juridictions = [
    "CASS" => "Cour de cassation",
    "GHCC" => "Cour constitutionnel",
    "CALIE" => "Cour d'appel",
    "CTLIE" => "Cour du travail",
    "CABRL" => "Cour d'appel",
    "CAMON" => "Cour d'appel",
    "CTBRL" => "Cour du travail",
    "PIBRL" => "Tribunal de première instance",
    "TTBRW" => "Tribunal du travail",
    "ARGNT" => "Tribunal du travail",
    "COHSAV" => "Commission pour l'aide financière aux victimes d'actes intentionnels de violence et aux sauveteurs occasionnels",
    "GBAPD" => "Autorité de protection des données",
    "RVSCE" => "Conseil d'État",
    "TTBRL" => "Tribunal du travail",
    "CTMON" => "Cour du travail",
];

$tribunaux = [
    "CALIE" => "Cour d'appel du ressort de Liège",
    "CTLIE" => "Cour du travail de Liège et divisions Namur - Neufchâteau",
    "CABRL" => "Cour d'appel du ressort de Liège",
    "CAMON" => "Cour d'appel du ressort de Mons",
    "CTBRL" => "Cour du travail de Bruxelles",
    "PIBRL" => "Tribunal de première instance francophone de Bruxelles",
    "TTBRW" => "Tribunal du travail du Brabant Wallon",
    "ARGNT" => "Tribunal du travail de Gand",
    "TTBRL" => "Tribunal du travail francophone de Bruxelles",
    "CTMON" => "Cour du travail de Mons",
];

$types = [
    "ARR" => "arret",
    "AVIS" => "avis",
    "CONC" => "conclusion",
    "DEC" => "decision",
    "JUG" => "jugement",
    "ORD" => "ordonnance",
];

$tribunal = '';
$arret_type = '';
if (preg_match("#ECLI:BE:(.+):[0-9]*:?([A-Z])\.#", $name, $j)) {
  if (array_key_exists($j[1], $juridictions)){
    $juridiction = $juridictions[$j[1]];
    if (isset($tribunaux[$j[1]])) {
        $trinal = $tribunaux[$j[1]]);
    }
  }else{
      fwrite(STDERR, "$inputfile: ERR: Juridiction ".$j[1]." non reconnue\n");
      exit(2);
  }
  if (isset($types[$j[2]])) {
      $arret_type = $types[$j[2]]
      if (in_array($j[2], ['CONC', 'AVIS']))  {
          exit(0);
      }
  }
}

if (preg_match('#<p class="[^"]*">(?:No Arrêt/)?No Rôle:</p></td> *<td><p class="[^"]*">([^<]+)</p>#', $content, $m)) {
  $numero = $m[1];
}
if (preg_match('/([CDFGNPS])([0-9][0-9])([0-9]{4})([FN]V?)/', $numero, $m)) {
  $numero = $m[1].'.'.$m[2].'.'.$m[3].'.'.$m[4];
}

if (preg_match('#<legend[^>]*>Texte (de la décision|des conclusions)[^<]*</legend> *<div[^>]*>(.+)</div>#', $content, $m)){
  $arret_text = $m[2];
  $arret_text = preg_replace("/\s*<p>\s*/", "", $arret_text);
  $arret_text = str_replace('&apos;', "'", $arret_text);
  $arret_text = str_replace("</p>", "\n\n", $arret_text);
  $arret_text = str_replace("<br>", "\n\n", $arret_text);
  $arret_text = trim(strip_tags($arret_text));
  $arret_text = htmlentities(html_entity_decode($arret_text), ENT_XML1);
  $arret_text = preg_replace('/(Imprimer cette page|Server Software|== Fluctuat nec|Document PDF ECLI).*/', '', $arret_text);
}

$audience = ['formation' => false, 'president' => false, 'assesseurs' => false, 'ministere_public' => false, 'greffier' => false ];
if (preg_match('#<p class="[^"]*">Audience:</p></td> *<td><p class="[^"]*">\s*(\S[^<]*\S)\s*</p>#', $content, $m)) {
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

if (preg_match('#<p class="[^"]*">Domaine juridique:</p></td> *<td><p class="[^"]*">\s*(\S[^<]*?\S)\s*</p>#', $content, $m)) {
  $type_affaire = $m[1];
}

// Est-ce qu'il y a une REFERENCE à mettre dans le XML(peut être dans analyse ou dans un fieldset "publications liées")
$has_ref = false;

$analyses = [];
if (preg_match_all('#<fieldset id="[^"]*" >.*?<div class="plaintext"> *<p>\s*(\S[^<]*?\S)\s*</p>.*?</fieldset>#', $content, $m, PREG_SET_ORDER)) {
  foreach ($m as $fieldset_match) {
    $index = $fieldset_match[1];
    $analyses[$index] = ['titre_principal' => '', 'reference' => [], 'sommaire' => []];
    $analyses[$index]['titre_principal'] = $fieldset_match[2];
    if (preg_match_all('#<p class="[^"]*">(Thésaurus[^:]*|Mots libres|Bases légales):</p></td> *<td> *<p class="[^"]*">\s*(\S[^<]*?\S)\s*</p>#', $fieldset_match[0], $m3, PREG_SET_ORDER)) {
      foreach ($m3 as $tr) {
        $tr[2] = str_replace('<br>', ' ; ', $tr[2]);
        $tr[2] = str_replace('<a ', ' / <a ', $tr[2]);
        $tr[2] = str_replace('Lien ELI ', '', $tr[2]);
        $tr[2] = strip_tags($tr[2]);
        if ($tr[1] == 'Bases légales') {
          $has_ref = true;
          $analyses[$index]['reference'][$tr[2]] = $tr[2];
        } else{
          $analyses[$index]['sommaire'][$tr[2]] = $tr[2];
        }
      }
    }
  }
}

$extra = '';
if (preg_match('/<fieldset> *<legend title="">([^<]*)<\/legend/', $content, $m)) {
    $extra = ', '.$m[1];
    if (preg_match('/arrêt/', $extra)) {
        $extra = '';
        $arret_type = 'arret';
    }else{
        $t = explode(' ', $m[1]);
        $arret_type = strtolower($t[0]);
    }
}

if (preg_match('#<legend title="">(Publication\(s\) liée\(s\))\s*</legend>\s*<div class="show-lien">\s*<div class="champ-entete">\s*<p>\s*([^:]+):\s*</p>\s*</div>\s*<div class="description-entete">\s*<p>\s*(\S.*?\S)\s*</p>\s*</div>#', $content, $m)) {
  $has_ref = true;
  $doc_lie = $m[1] . ': ' . $m[2] . ' ' . str_replace('href="/', 'href="https://juportal.be/', str_replace('target="_self"', 'target="_blank"', $m[3]));
}

if (!$dateiso || !$juridiction || !$numero || !$arret_text) {
  fwrite(STDERR, "\nDONNEES MANQUANTE : " . implode(' ',$argv));
  fwrite(STDERR, " [$arret_text,$dateiso,$juridiction,$numero]");
  fwrite(STDERR, "\n");
  exit(2);
}

$titre = "Belgique, $juridiction, $datefr, $type $numero$extra";

echo('<?xml version="1.0" encoding="UTF-8"?>'."\n");
echo("<DOCUMENT>\n");
echo("<PAYS>Belgique</PAYS>\n");
echo("<JURIDICTION>$juridiction</JURIDICTION>\n");
if ($tribunal) {
    echo("<TRIBUNAL>$tribunal</TRIBUNAL>\n");
    $titre = "Belgique, $tribunal, $datefr, $type $numero$extra";
}
echo("<NUM_ARRET>$numero</NUM_ARRET>\n");
echo("<DATE_ARRET>$dateiso</DATE_ARRET>\n");
echo("<SOURCE>$source</SOURCE>\n");
echo("<TEXTE_ARRET>$arret_text</TEXTE_ARRET>\n");
echo("<TITRE>$titre</TITRE>\n");
echo("<TYPE>$arret_type</TYPE>\n");
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
  echo("<ANALYSES>\n");
  foreach($analyses as $notice_id => $analyse)  {
    echo("<ANALYSE>\n");
    echo("<TITRE_PRINCIPAL>".$analyse['titre_principal']."</TITRE_PRINCIPAL>\n");
    echo("<SOMMAIRE>");
    echo(implode(' - ', $analyse['sommaire']));
    if (!empty($analyse['reference'])) {
      echo(" [$notice_id]");
    }
    echo("</SOMMAIRE>\n");
    echo("</ANALYSE>\n");
  }
  echo("</ANALYSES>\n");
}
if ($has_ref) {
  echo("<REFERENCES>\n");
  if(isset($doc_lie)) {
    echo("<REFERENCE id=\"doc-liee\">\n");
    echo("<TYPE>PUBLICATIONS_LIEES</TYPE>");
    echo("<TITRE>");
    echo($doc_lie);
    echo("</TITRE>");
    echo("</REFERENCE>\n");
  }
  foreach($analyses as $notice_id => $analyse)  {
    if (!empty($analyse['reference'])) {
      echo("<REFERENCE id=\"$notice_id\">\n");
      echo("<TYPE>CITATION_ANALYSE</TYPE>");
      echo("<TITRE>");
      echo("[$notice_id] " . implode(" ; ", $analyse['reference']));
      echo("</TITRE>");
      echo("</REFERENCE>\n");
    }
  }
  echo("</REFERENCES>\n");
}
if(isset($type_affaire)) {
  echo("<TYPE_AFFAIRE>$type_affaire</TYPE_AFFAIRE>\n");
}
echo("<FONDS_DOCUMENTAIRE>juportal.be</FONDS_DOCUMENTAIRE>\n");
echo("<ALIMENTATION_TYPE>parsers/Belgique</ALIMENTATION_TYPE>\n");
echo("</DOCUMENT>\n");
