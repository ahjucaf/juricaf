<?php

$res = (array) json_decode(file_get_contents($argv[1]));
$update = (array) json_decode(file_get_contents($argv[2]));

foreach ($update as $k => $v) {
    $res[$k] = $v;
}

echo json_encode($res);
