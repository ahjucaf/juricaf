<?php



function url_exist($url){
  $file=$url;
  // $file = 'https://www.tribunal-supreme.mc/decisions/page/25/';
  $file_headers = @get_headers($file);
  if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
      // echo('False');
      return False;
  }
  else {
      // echo('True');
      return True;
  }
}

$headurl='https://www.tribunal-supreme.mc/decisions/page/';
$dossier='tmp/home_pages';

// si c'est une url on peut enregistrer les pages.

$i=1;
while (url_exist($headurl.$i."/")==True  && $i<6){
  if ($i==1){
    shell_exec('cd tmp/home_pages');
    shell_exec('curl '.'https://www.tribunal-supreme.mc/decisions/'.'>'.$dossier.'/page'.$i.'.html');
    shell_exec('cd ..');
    shell_exec('cd ..');
    $i=$i+1;
  }
  else{
    $url=$headurl.$i."/";
    $cmd='curl '. $url.' >'.$dossier.'/page'.$i.'.html';
    echo($cmd);
    shell_exec($cmd);
    $i=$i+1;
  }
}

?>
