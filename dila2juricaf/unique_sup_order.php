<?php
$orders = file($argv[1], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$i = 0;
foreach ($orders as $order) {
  $lines[$i] = $order; $i++;
}
if(isset($lines)) {
  $lines = array_unique($lines);
  foreach($lines as $line) {
    echo $line."\n";
  }
}
?>