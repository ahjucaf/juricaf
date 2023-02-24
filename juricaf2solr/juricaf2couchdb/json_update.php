
<?php

$res = (array) json_decode(file_get_contents($argv[1]));
$update = (array) json_decode(file_get_contents($argv[2]));

if (isset($res['reason']) || (isset($res['error']) && $res['error'] == 'not_found') ) {
    unset($res['reason']);
    unset($res['error']);
}

foreach ($update as $k => $v) {
    $res[$k] = $v;
}


echo json_encode($res);
