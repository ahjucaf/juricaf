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

  $html = file_get_contents("https://e-justice.europa.eu/eclisearch/integrated/beta/search.html?issued=01%2F01%2F".$annee."%2C31%2F12%2F".$annee."&text-language=FR&ascending=false&country-coded=BE&lang=fr&index=$index");

  preg_match_all('#<a target="_blank" href="([^>]+)">https://juportal\.just\.fgov\.be#iU', $html, $links);

  if (empty($links[1])) {
    break;
  }

  foreach ($links[1] as $link) {
    fwrite(STDERR, "Récupère le HTML de l'arrêt à partir de $link\n");
    preg_match('#https:\/\/juportal\.just\.fgov\.be\/content\/ViewDecision\.php\?id=([^&]+)#i',$link, $jurimatch);

    if (!empty($jurimatch[1])) {
      $juriid = $jurimatch[1];
      $output_url = "https://juportal.be/content/$juriid/FR";
      $filename = $dossierArretsHTML."/".$juriid.".html";
      if (file_exists($filename)) {
        fwrite(STDERR, "arrêt déjà présent dans $dossierArretsHTML\n");
      }
      fwrite(STDERR, "Enregistre $output_url dans \n");
      $content = file_get_contents($output_url);
      if (strpos($content, '<html lang="nl">')) {
        fwrite(STDERR, "arrêt nl => ignore \n");
        continue;
      }
      file_put_contents($filename, $content);
      echo "$filename $output_url\n";
    }
  }

  $index +=25;
}

exit;