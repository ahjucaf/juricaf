<?php 

$dossierArretsHTML = "./html";

if(!$argv[1]){
  echo "MISSING YEAR\n";
  exit(1);
}
$annee = intval( $argv[1] );


$index=0;

while (true) {
  fwrite(STDERR, "Récupération du html de $annee ($index)\n");

  $html = '';
  for($i = 0 ; strpos($html, '</html>') === false && $i < 3 ; $i++) {
    if ($i) { sleep(1); }
    $html = file_get_contents("https://e-justice.europa.eu/eclisearch/integrated/beta/search.html?issued=01%2F01%2F".$annee."%2C31%2F12%2F".$annee."&text-language=FR&ascending=false&country-coded=BE&lang=fr&index=$index");
  }
  if (! preg_match_all('#<a target="_blank" href="([^>]+)">https://juportal\.just\.fgov\.be#iU', $html, $links)) {
    break;
  }

  foreach ($links[1] as $link) {
    if (! preg_match('#https:\/\/juportal\.just\.fgov\.be\/content\/ViewDecision\.php\?id=([^&]+)#i',$link, $jurimatch)) {
      continue;
    }
    if (empty($jurimatch[1])) {
      continue;
    }
    $juriid = $jurimatch[1];
    $output_url = "https://juportal.be/content/$juriid/FR";
    $filename_html = $dossierArretsHTML."/".$juriid.".html";
    $filename_url = $dossierArretsHTML."/".$juriid.".url";
    if (file_exists($filename_html)) {
      fwrite(STDERR, "Arrêt ! $juriid déjà présent dans $dossierArretsHTML\n");
      echo "$filename_html $output_url\n";
      continue;
    }
    if (!file_exists($filename_url)) {
        file_put_contents($filename_url, $output_url."\n");
    }
    fwrite(STDERR, "Enregistre $output_url dans $filename_html\n");
    $content = '';
    for($i = 0 ; strpos($content, '</html>') === null && $i < 3 ; $i++) {
      if ($i) { sleep(1); }
      $content = file_get_contents($output_url);
      if (preg_match('#<h2>(ECLI number .*? NOT FOUND)\s*</h2>#', $content, $errmatch)) {
        fwrite(STDERR, "Arrêt ! " . $errmatch[1]);
        continue 2;
      }
    }
    if (strpos($content, '<html lang="nl">')) {
      fwrite(STDERR, "Arrêt ! NL => ignore \n");
      continue;
    }
    if (! strpos($content, '</html>')) {
      fwrite(STDERR, "Arrêt ! Pas de fin de HTML.\n");
      continue;
    }
    file_put_contents($filename_html, $content);
    file_put_contents($filename_url, $output_url);
    echo "$filename_html $output_url\n";
  }

  $index +=25;
}

exit;