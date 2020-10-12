<?php

$fichiers=scandir('tmp/home_pages');

$lignes_all_urls=file('all_urls.txt');
// print_r($lignes_all_urls);
$output = fopen('tmp/urls.txt', 'w');
foreach ($fichiers as $k => $v) {
  if($k!=0 && $k!=1){
    $html=file_get_contents('./tmp/home_pages/'.$fichiers[$k]);
    // print_r($html);
    $html=preg_replace('/\n/',' ',$html);
    // $html=$html[0];
    // echo($html);
    preg_match('/<div class="content">(.+)/',$html,$res);
    // print_r($res);
    $content=$res[1];

    preg_match_all('/<a href="([^"]+)"/',$content,$lien);
    // print_r($lien);
    $lien=$lien[1];

    foreach($lien as $k=>$v){
          // echo($v);
          if ($v!='https://www.tribunal-supreme.mc/' && $v!="https://www.tribunal-supreme.mc/mentions-legales/" && $v!='#tarteaucitron' && stristr($v,'communique')!=True){
            // echo("1\n");
            if($k%2 == 0){
              // echo("2\n");
              if (count($lignes_all_urls)>0){
                // echo("3\n");
                if( in_array($v."\n",$lignes_all_urls)==false){
                  fwrite($output,$v."\n");
                  // echo("4\n");
                }
              }
              else{
                fwrite($output,$v."\n");
                // echo("5\n");
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
        // echo($ligne);
      }
}
fclose($all_urls);
