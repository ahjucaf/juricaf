<?php

$urls=file('tmp/urls.txt');
$i=1;
$taburl=[];
foreach($urls as $v){
  $v = rtrim($v);
  if ( preg_match('#^(http|https)://www#',$v))
    {
      $cmd="curl -k -L -s $v > tmp/pages/arret$i.html";
      $taburl["arret$i.html"]=$v;
    echo "$cmd\n";
      shell_exec($cmd);
      $i=$i+1;
  }
}
if (count($taburl)){
    file_put_contents('tmp/urls.json', json_encode($taburl));
}
