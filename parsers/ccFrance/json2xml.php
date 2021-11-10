<?php

$doc = $argv[1];
$doc = json_decode(@file_get_contents($doc));
if (! $doc) {
    fwrite(STDERR, "ERREUR: json non trouvé\n");
    exit(1);
}

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf8"?><DOCUMENT></DOCUMENT>');
$xml->addChild('DATE_ARRET', $doc->decision_date);
$xml->addChild('PAYS', 'France');
$xml->addChild('JURIDICTION', $doc->jurisdiction);
$xml->addChild('FORMATION', $doc->chamber);
$xml->addChild('TEXTE_ARRET', '<![CDATA['.$doc->text.']]');


echo $xml->asXML();
?>

