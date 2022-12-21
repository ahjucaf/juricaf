<?php

$fichiers=scandir('tmp/home_pages');

$lignes_all_urls=file('all_urls.txt');

$output = fopen('tmp/urls.txt', 'w');
foreach ($fichiers as $k => $v) {
  if($k!=0 && $k!=1){

    $tabhtml= file('./tmp/home_pages/'.$fichiers[$k], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $html='';
    foreach($tabhtml as $num=>$ligne){
      $ligne=trim($ligne);
      $html=$html.$ligne;
    }
    preg_match('/<div class="content">(.+)/',$html,$res);
    $content=$res[1];

    preg_match_all('/<a href="([^"]+)"/',$content,$lien);
    $lien=$lien[1];
    foreach($lien as $k=>$v){
          if ($v!='https://www.tribunal-supreme.mc/' && $v!="https://www.tribunal-supreme.mc/mentions-legales/" && $v!='#tarteaucitron' && stristr($v,'communique')!=True){
            if($k%2 == 0){
              if (count($lignes_all_urls)>0){
                if( in_array($v."\n",$lignes_all_urls)==false){
                  fwrite($output,$v."\n");
                }
              }
              else{
                fwrite($output,$v."\n");
              }

            }
        }
      }
  }
}
fclose($output);



$all_urls=fopen('all_urls.txt','a+');
$lignes=file('tmp/urls.txt');

foreach($lignes as $ligne){
      if (in_array($ligne,$lignes_all_urls)!=True){
        fwrite($all_urls,$ligne);
      }
}
fclose($all_urls);
