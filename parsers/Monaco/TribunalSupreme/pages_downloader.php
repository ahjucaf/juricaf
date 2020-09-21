<?php

$dossier=' pages';
$urls=file('tmp/urls.txt');
$i=1;

foreach($urls as $k=>$v){
  $v = rtrim($v);
  if ( preg_match('#^(http|https)://www#',$urls[$k]))
    {
      $cmd="curl $v > tmp/pages/arret$i.html";
      echo($cmd);
      shell_exec($cmd);
      $i=$i+1;
  }
}

// echo($i)

?>
