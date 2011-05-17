<?php
$old = file('log/old.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$new = file('log/new.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$to_update = "";
$file_log = "log/to_detar_update.txt";
$prev_file_log = "log/prev_to_detar_update.txt";

foreach ($new as $line) {
  if(!in_array($line, $old)) {
    $line = explode('#', $line);
    $to_process[$line[0]] = $line[1];
  }
}

if (isset($to_process)) {
  asort($to_process);
  foreach ($to_process as $value) {
    $to_update .= $value."\n";
  }
  $handler = fopen($file_log,"w");
  try {
    fputs($handler,$to_update);
  }
  catch (Exception $e) {
    echo "Erreur d'enregistrement de ".$file_log." contenant la liste des nouveaux fichiers à décompresser\n";
    echo $e->getMessage()."\n";
    exit;
  }
}
else {
  if(is_file($file_log)) {
    if ($stream = fopen($file_log, 'r')) {
      $prev_update = stream_get_contents($stream);
      $handler = fopen($prev_file_log,"w");
      try {
        fputs($handler,$prev_update);
      }
      catch (Exception $e) {
        echo "Erreur d'enregistrement de ".$prev_file_log." contenant la liste des fichiers décompressés précédement\n";
        echo $e->getMessage()."\n";
        exit;
      }
      fclose($stream);
      unlink ($file_log);
    }
  }
}
?>
