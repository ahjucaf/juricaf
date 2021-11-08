<?php

$doc = $argv[1];
$doc = json_decode(@file_get_contents($doc));
if (! $doc) {
    fwrite(STDERR, "ERREUR: json non trouvé\n");
}

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf8"?><DOCUMENT></DOCUMENT>');
$date_arret = $xml->addChild('DATE_ARRET');

echo $xml->asXML();
?>

