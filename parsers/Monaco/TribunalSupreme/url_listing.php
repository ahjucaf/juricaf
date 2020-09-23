
<?php

$fichiers=scandir('tmp/home_pages');

$lignes_all_urls=file('all_urls.txt');

$output = fopen('tmp/urls.txt', 'w');
foreach ($fichiers as $k => $v) {
  if($k!=0 && $k!=1){
    $html=file('./tmp/home_pages/'.$fichiers[$k]);
    $html=$html[0];
    preg_match('#<div class=\"content\">(.+)#',$html,$res);
    $content=$res[1];

    preg_match_all('/<a href="([^"]+)"/',$content,$lien);
    $lien=$lien[1];
    foreach($lien as $k=>$v){
          if ($v!='https://www.tribunal-supreme.mc/' && $v!="https://www.tribunal-supreme.mc/mentions-legales/" && $v!='#tarteaucitron' && stristr($v,'communique')!=True){
            if($k%2 == 0){
              if (count($lignes_all_urls)>0){
              // print_r(count($lignes_all_urls));
              if(in_array($v,$lignes_all_urls)==true){
                // echo('Je suis dans la liste');
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
        // echo($ligne);
      }
}
fclose($all_urls);


?>
