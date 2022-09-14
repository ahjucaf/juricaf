<?php

stream_context_set_default( [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ],
]);

function url_exist($url){
  $file=$url;
  $file_headers = get_headers($file);
  if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
      return False;
  }
  else {
      return True;
  }
}

$headurl='https://www.tribunal-supreme.mc/decisions/page/';
$dossier='tmp/home_pages';


$nb_pages=1;
if (isset($argv[1])) {
    $nb_pages = $argv[1];
}
$i=1;
while (url_exist($headurl.$i."/")==True  && $i<$nb_pages+1){
    $url=$headurl.$i."/";
    $cmd='curl -s -L -k '. $url.' > '.$dossier.'/page'.$i.'.html';
    shell_exec($cmd);
    $i=$i+1;
}

?>
