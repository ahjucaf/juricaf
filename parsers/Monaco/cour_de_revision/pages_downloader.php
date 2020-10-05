<?php

$dossier='pages';
$urls=file('tmp/urls.txt');
$i=1;
$taburl=[];

foreach($urls as $k=>$v){
  if ( preg_match('#^(http|https)://www#',$v))
   {
     preg_match('/(.+?)!/',$v,$v);
     $v=$v[1];
     $cmd='curl -s "'.$v.'"'." | iconv -f iso-8859-1 -t utf-8 > tmp/pages/arret$i.html";
     $taburl["arret$i.html"]=$v;
     shell_exec($cmd);
     $i=$i+1;
 }
}

if ($taburl!=[]){
  file_put_contents('tmp/urls.json', json_encode($taburl));
}

?>
