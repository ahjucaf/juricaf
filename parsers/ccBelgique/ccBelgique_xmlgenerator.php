<?php

$content = file_get_contents('php://stdin');

$content = preg_replace('/\&/', '&amp;', $content);

$mois = array('JANVIER' => '01', 'FEVRIER' => '02', 'MARS' => '03', 'AVRIL'=>'04', 'MAI'=>'05', 'JUIN'=>'06', 'JUILLET'=>'07', 'AOUT'=>'08', 'AOEUT' => '08', 'SEPTEMBRE'=>'09', 'OCTOBRE'=>'10', 'NOVEMBRE'=>'11', 'DECEMBRE'=>'12');

$num = '';
$date = '';
$source = '';
if (isset($argv[1])) {
    $num = $argv[1];
}
if (isset($argv[2])) {
    $date = $argv[2];
}
if (isset($argv[3])) {
    $source = $argv[3];
}

if (!preg_match('/arrêt/i', $content) && !preg_match('/arret/i', $content) && !preg_match('/demandeurs? en cassation/i', $content) && !preg_match('/défenderesses? en cassation/i', $content)) {
  error_log("ERROR: Arret non Francais (erreur arrêt) [$num, $date, $source]");
  exit(1) ;
 }

if (!preg_match('/cour de cassation/i', $content)) {
  error_log("ERROR: Arret non Francais (erreur cour) [$num, $date, $source]");
  exit(1) ;
 }

//Récupération du numéro d'arret
if (!$num && preg_match('/(NDEG\.?|N°[^A-Z]*|Nº[^A-Z]*)\s*([A-Z][^A-Z][^-\n]*)/', $content, $match)) {
  $num = preg_replace('/ /', '', $match[2]);
 }

if (!$num && preg_match('/([A-Z]\.[\d\.]+\.[A-Z])\/\d+\s+$/', $content, $match)) {
  $num = preg_replace('/ /', '', $match[1]);
 }

if (!$num) {
    $num = preg_replace('/[^A-Z\d]/i', '', $num);
}

if ($num && preg_match('/([A-Z])\.?(\d{2})\.?(\d+)\.?([A-Z])/i', $num, $match)) {
  $num = $match[1].'.'.sprintf('%02d', $match[2]).'.'.sprintf('%04d', $match[3]).'.'.$match[4];
 }else{
  error_log("ERROR: Numéro non trouvé $num [$num, $date, $source]");
  exit(1) ;
 }

//La date
if (!$date) {
    if (preg_match('/(\d+)\^?[eE]?[rR]?[\s\.\^]+([^\d\s\.]+)[\s\.]+(\d+)\s+(('.$num.'|[A-Z][\d\.]+\.[A-Z][\/\-])|\s*$)/i', $content, $match) ||
        preg_match('/à Bruxelles, le (\d+)\^?[eE]?[rR]?[\s\.]+([^\d\s\.]+)[\s\.]+(\d+)\s*\.\s*/', $content, $match)
       ) {
           $date = $match[3].'-'.$mois[strtoupper(preg_replace(array('/[éÉ]+/', '/[ûÛ]/'), array('e', 'u'), $match[2]))].'-'.sprintf('%02d', $match[1]);
       }
}

if (!$date || !preg_match('/\d{4}\-\d{2}\-\d{2}/', $date)) {
  error_log("ERROR: Date non trouvée ou mal formée $date [$num, $date, $source]");
  exit(1);
 }

//On récupère le texte de l'arret
$text = $content;

if (strlen($text) < 10) {
    error_log("ERROR: Arrêt sans texte [$num, $date, $source]");
    exit(1);
}

echo "<?xml version=\"1.0\"?>
<DOCUMENT>
  <PAYS>Belgique</PAYS>
  <JURIDICTION>Cour de cassation</JURIDICTION>
  <NUM_ARRET>$num</NUM_ARRET>
  <DATE_ARRET>$date</DATE_ARRET>\n";
if (isset($analyses) && count($analyses)) {
  echo "  <ANALYSES>\n";
  foreach ($analyses as $a) {
    echo "    <ANALYSE>
      <TITRE_PRINCIPAL>".$a."</TITRE_PRINCIPAL>
    </ANALYSE>\n";
  }
  echo "  </ANALYSES>\n";
}
if ($source) {
    echo "  <SOURCE>$source</SOURCE>\n";
}
echo "  <TEXTE_ARRET>$text</TEXTE_ARRET>
<FONDS_DOCUMENTAIRE>juridat.be</FONDS_DOCUMENTAIRE>
</DOCUMENT>
";
