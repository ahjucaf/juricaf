<?php

include('config/analyses.php');

$content = file_get_contents('php://stdin');
$mois = array('JANVIER' => '01', 'FEVRIER' => '02', 'MARS' => '03', 'AVRIL'=>'04', 'MAI'=>'05', 'JUIN'=>'06', 'JUILLET'=>'07', 'AOUT'=>'08', 'SEPTEMBRE'=>'09', 'OCTOBRE'=>'10', 'NOVEMBRE'=>'11', 'DECEMBRE'=>'12');

if (!preg_match('/arret/', $content)) {
  error_log("ERROR: Arret non Francais");
  exit(1) ;
 }

if (!preg_match('/cour de cassation/i', $content)) {
  error_log("ERROR: Arret non Francais");
  exit(1) ;
 }

//Récupération du numéro d'arret
$num = '';
if (preg_match('/NDEG\.?\s*([A-Z][^A-Z][^-\n]*)/', $content, $match)) {
  $num = preg_replace('/ /', '', $match[1]);
 }

if (!$num && preg_match('/([A-Z]\.[\d\.]+\.[A-Z])\/\d+\s+$/', $content, $match)) {
  $num = preg_replace('/ /', '', $match[1]);
 }

$num = preg_replace('/[^A-Z\d]/i', '', $num);

if ($num && preg_match('/([A-Z])(\d{2})(\d+)([A-Z])/i', $num, $match)) {
  $num = $match[1].'.'.sprintf('%02d', $match[2]).'.'.sprintf('%04d', $match[3]).'.'.$match[4];
 }else{
  error_log("ERROR: Numéro non trouvé $num");
  exit(1) ;
 }

//La date
$date = '';
if (preg_match('/(\d+)[eE]?[rR]?[\s\.]+(\w+)[\s\.]+(\d+)\s+(('.$num.'|[A-Z][\d\.]+\.[A-Z][\/\-])|\s*$)/', $content, $match)) {
  $date = $match[3].'-'.$mois[strtoupper($match[2])].'-'.sprintf('%02d', $match[1]);
 }

if (!$date || !preg_match('/\d{4}\-\d{2}\-\d{2}/', $date)) {
  error_log("ERROR: Date non trouvée ou mal formée $date");
  exit(1);
 }

//les analyses
$analyses = array();
if (preg_match_all('/(\**\d+|Cour de cassation|Arret)/', $content, $match)) {
  foreach ($match[0] as $m) {
    if (!preg_match('/\d/', $m))
      break;
    if (!preg_match('/^\d+$/', $m) && !preg_match('/\d{4,}/', $m))
      continue;
    $analyses[] = $id2analyses[preg_replace('/\*/', '', $m)];
  }
 }

//On récupère le texte de l'arret
$text = "\n"; $header = 1;
foreach (split("\n", $content) as $ligne) {
  if ($header && !preg_match('/Cour de cassation/', $ligne))
    continue;
  $header = 0;
  $text .= preg_replace(array('/\`a/', '/<</', '/>>/', '/^ */'), array('à', '«', '»', ''), $ligne);
  if (preg_match('/(\||\+)\W*$/', $ligne))
    $text .= "\n";
  else if (!preg_match('/[a-z]/i', $ligne))
    $text .= "\n\n";
}

echo "<?xml version=\"1.0\"?>
<DOCUMENT>
  <PAYS>Belgique</PAYS>
  <JURIDICTION>Cour de cassation</JURIDICTION>
  <NUM_ARRET>$num</NUM_ARRET>
  <DATE_ARRET>$date</DATE_ARRET>\n";
if (count($analyses)) {
  echo "  <ANALYSES>\n";
  foreach ($analyses as $a) {
    echo "    <ANALYSE>
      <TITRE_PRINCIPAL>".$a."</TITRE_PRINCIPAL>
    </ANALYSE>\n";
  }
  echo "  </ANALYSES>\n";
}
echo "  <TEXTE_ARRET>$text</TEXTE_ARRET>
</DOCUMENT>
";
