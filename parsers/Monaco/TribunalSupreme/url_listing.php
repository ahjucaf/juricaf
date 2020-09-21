
<?php
//Après les avoir enregistrer on les traite une par une:

$fichiers=scandir('tmp/home_pages');
// print_r($fichiers);
// Array
// (
//     [0] => .
//     [1] => ..
//     [2] => page1.html
//     [3] => page10.html
//     [4] => page11.html
//     [5] => page12.html
//     [6] => page13.html
//     [7] => page14.html
//     [8] => page15.html
//     [9] => page16.html
//     [10] => page17.html
//     [11] => page18.html
//     [12] => page19.html
//     [13] => page2.html
//     [14] => page20.html
//     [15] => page21.html
//     [16] => page22.html
//     [17] => page23.html
//     [18] => page24.html
//     [19] => page25.html
//     [20] => page3.html
//     [21] => page4.html
//     [22] => page5.html
//     [23] => page6.html
//     [24] => page7.html
//     [25] => page8.html
//     [26] => page9.html
// )
//
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
              echo($v."\n");
              if(in_array($v,$lignes_all_urls)!=True){
                fwrite($output,$v."\n");
              }
            }
        }
      }
  }
}
fclose($output);



$all_urls=fopen('all_urls.txt','a+');
// $lignes_all_urls=file('tmp/all_urls.txt');
$lignes=file('tmp/urls.txt');

foreach($lignes as $ligne){
      if (in_array($ligne,$lignes_all_urls)!=True){
        fwrite($all_urls,$ligne);
        echo($ligne);
      }
}
fclose($all_urls);


// fwrite($output,)

// parcourir les lignes du fichiers urls.txt

?>
