<?php

$dossier='pages';
$urls=file('tmp/urls.txt');
$i=1;
$taburl=[];

foreach($urls as $k=>$v){
  // $v = rtrim($v);
  if ( preg_match('#^(http|https)://www#',$v))
   {
     preg_match('/(.+?)!/',$v,$v);
     $v=$v[1];
     // print_r($v);
     $cmd='curl "'.$v.'"'." > tmp/pages/arret$i.html";
  //     $taburl["arret$i.html"]=$v;
    // echo($cmd."\n");
     shell_exec($cmd);
     $i=$i+1;
 }
}
// if ($taburl!=[]){
//
// file_put_contents('tmp/urls.json', json_encode($taburl));
// }


?>
