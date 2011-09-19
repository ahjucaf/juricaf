<?php
$log = 'log/to_detar_all.txt';
$to_detar_all = file($log, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($to_detar_all as $line) {
  $line = explode('#', $line);
  $to_process[$line[0]] = $line[1];
}

if (is_array($to_process)) {
  ksort($to_process);
  $to_update = '';
  foreach ($to_process as $value) {
    $to_update .= $value."\n";
  }
  $handler = fopen($log,"w");
  try {
    fputs($handler,$to_update);
  }
  catch (Exception $e) {
    echo "Erreur d'enregistrement de ".$log." contenant la liste des fichiers à décompresser\n";
    echo $e->getMessage()."\n";
    exit;
  }
}

?>
