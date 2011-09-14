<?php
$old = file('log/old.log', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$new = file('log/new.log', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$to_update = "";
$file_log = "log/to_detar_update.txt";

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

?>
