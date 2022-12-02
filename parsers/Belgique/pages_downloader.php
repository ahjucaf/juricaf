<?php 

$dossierArretsHTMl = "/tmp/pages";
$allLinksFile = "/tmp/links_Belgique";

$contentFile = "/tmp/contenu.html";

shell_exec("mkdir -p $dossierArretsHTMl");
shell_exec("rm -f $allLinksFile");

if(!$argv[1]){
  echo "MISSING YEAR";
  exit;
}

$annee = $argv[1];

$cmd = "curl -s 'https://e-justice.europa.eu/eclisearch/integrated/beta/search.html?year=$annee&text-language=FR&ascending=false&country-coded=BE&lang=fr&index=0' > $contentFile";
shell_exec($cmd);
preg_match('#var totalResults="([0-9]+)";#iU', file_get_contents($contentFile), $nbresultats);

$index=0;

while ($index <= $nbresultats[1]) {
    $cmd = "curl -s 'https://e-justice.europa.eu/eclisearch/integrated/beta/search.html?year=2021&text-language=FR&ascending=false&country-coded=BE&lang=fr&index=$index' > $contentFile";
    shell_exec($cmd);
    
    $c= file($contentFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $content='';
    foreach($c as $num=>$ligne){
      preg_match('#<a target="_blank" href="([^>]+)">https://juportal\.just\.fgov\.be#iU', $ligne, $link);
      if($link){
        $ligne=trim($link[1]);
        shell_exec("echo '$ligne' >> $allLinksFile");
      }
    }
    $index +=25;
}

shell_exec("rm -f $contentFile");

foreach(file($allLinksFile) as $url){
  $url = trim($url);
  preg_match('#https:\/\/juportal\.just\.fgov\.be\/content\/ViewDecision\.php\?id=([^&]+)#i',$url, $name);
  $name = $name[1];
  shell_exec("curl -s 'https://juportal.be/content/$name' > $dossierArretsHTMl/$name.html");
}

exit;

