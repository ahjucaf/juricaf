<?php
$fichiers=scandir('tmp/home_pages');
$head="https://www.legimonaco.mc/";
$output = fopen('tmp/urls.txt', 'w');
$lignes_all_urls=file('all_urls.txt');

foreach ($fichiers as $k => $v) {
    if ($k!=0 && $k!=1){
      $html=file_get_contents('tmp/home_pages/'.$v);
      preg_match_all('/<font size="2" face="Verdana"><a href="(.+?)">/',$html,$lien);
      $lien=$lien[1];
      foreach($lien as $k=>$v){
          fwrite($output,$head.$v."\n");
      }
    }
}


$all_urls=fopen('all_urls.txt','a+');
$lignes=file('tmp/urls.txt');

foreach($lignes as $ligne){
      if (in_array($ligne,$lignes_all_urls)!=True){
        fwrite($all_urls,$ligne);
      }
}
fclose($all_urls);

?>
