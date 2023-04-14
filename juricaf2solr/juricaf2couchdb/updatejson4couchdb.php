<?php

$file = $argv[1];
$id  = $argv[2];
$rev = $argv[3];
$json = json_decode(file_get_contents($file));
if (!$json || !isset($json->docs)) {
    error_log('ERROR: '.$file.' has no "docs" entity');
    exit(1);
}
foreach ($json->docs as $j) {
	if ($j->_id == $id) {
		$j->_rev = $rev;
		echo json_encode(array("docs" => array($j)));
		exit;
	}
}
