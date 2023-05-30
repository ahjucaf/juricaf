<?php 

$dossierArretsHTML = "./html";

if(!$argv[1]){
  echo "MISSING DATE\n";
  exit(1);
}
// arg 1 au format Y-m-d
$date = explode('-', $argv[1]);

$index=0;

while (true) {
  fwrite(STDERR, "Récupération du html des arrêts depuis ".$argv[1]." ($index)\n");

  $html = '';
  // Le HTML n'a pas de balise de fin, probable erreur réseau, on retente 3 fois
  for($i = 0 ; strpos($html, '</html>') === false && $i < 3 ; $i++) {
    if ($i) { sleep(1); }
    fwrite(STDERR, "Chargement du html de la page\n");
    // Les arrêts de la cour de cassation et constitutionnelle sur un an glissant (cas des traductions en FR qui peuvent arriver quelques mois après la publi en lang NL)
    $url = "https://e-justice.europa.eu/eclisearch/integrated/search.html?country-coded=BE&text-language=FR&lang=fr&issued=".$date[2]."%2F".$date[1]."%2F".$date[0]."&ascending=true&type-coded=02&court=BE-GHCC%2CBE-CASS&index=$index";
    fwrite(STDERR, "recherche des arrêts via $url\n");
    $html = file_get_contents($url);
  }
  if (! preg_match_all('#<a target="_blank" href="([^>]+)">https://juportal\.just\.fgov\.be#iU', $html, $links)) {
    fwrite(STDERR, "Arrêt ! Pas de juportal\.just\.fgov\.be\n");
    break;
  }

  foreach ($links[1] as $link) {
    fwrite(STDERR, "$link\n");

    if (! preg_match('#https:\/\/juportal\.just\.fgov\.be\/content\/ViewDecision\.php\?id=([^&]+)#i',$link, $jurimatch)) {
     fwrite(STDERR, "Arrêt ! Ce n'est pas une décision : on prend pas.\n");
     continue;
    }
    if (empty($jurimatch[1])) {
      fwrite(STDERR, "Arrêt ! Pas d'id.\n");
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
    // Le HTML n'a pas de balise de fin, probable erreur réseau, on retente 3 fois
    for($i = 0 ; strpos($content, '</html>') === false && $i < 3 ; $i++) {
      if ($i) { sleep(1); }
      $content = file_get_contents($output_url);
      if (preg_match('#<h2>(ECLI number .*? NOT FOUND)\s*</h2>#', $content, $errmatch)) {
        fwrite(STDERR, "Arrêt ! " . $errmatch[1]);
        continue 2;
      }
    }
    if (strpos($content, '<html lang="nl">')) {
      fwrite(STDERR, "Arrêt ! NL => ignoré \n");
      continue;
    }
    if (! strpos($content, '</html>')) {
      fwrite(STDERR, "Arrêt ! Pas de fin de HTML.\n");
      continue;
    }
    if (strpos($content, 'Cette publication est en préparation ou a été supprimée')) {
        fwrite(STDERR, "publication en préparation ou supprimée => ignore \n");
        continue;
    }
    file_put_contents($filename_html, $content);
    file_put_contents($filename_url, $output_url);
    echo "$filename_html $output_url\n";
  }

  $index +=25;
}
