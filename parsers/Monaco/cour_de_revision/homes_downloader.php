<?php
$head="https://www.legimonaco.mc/305/legismc.nsf/ViewJurisCR!OpenView&Start=1&Count=100&Expand=";
$dossier='tmp/home_pages';

$nb_pages=$argv[1];
$i=1;
while($i<=$nb_pages){  //plus tard mettre 48
  $url=$head.$i."/";
  $cmd='curl -s "'. $url.'" >'.$dossier.'/page'.$i.'.html';
  shell_exec($cmd);
  $i=$i+1;
}
