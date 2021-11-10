<?php

$doc = $argv[1];
$doc = json_decode(@file_get_contents($doc));
if (! $doc) {
    fwrite(STDERR, "ERREUR: json non trouvé\n");
    exit(1);
}

// https://stackoverflow.com/a/20511976
class SimpleXMLExtended extends SimpleXMLElement {
  public function addChildWithCDATA($name, $value = NULL) {
    $new_child = $this->addChild($name);

    if ($new_child !== NULL) {
      $node = dom_import_simplexml($new_child);
      $no   = $node->ownerDocument;
      $node->appendChild($no->createCDATASection($value));
    }

    return $new_child;
  }
}

$xml = new SimpleXMLExtended('<?xml version="1.0" encoding="utf8"?><DOCUMENT></DOCUMENT>');
$xml->addChild('DATE_ARRET', $doc->decision_date);
$xml->addChild('PAYS', 'France');
$xml->addChild('JURIDICTION', $doc->jurisdiction);
$xml->addChild('FORMATION', $doc->chamber);

$xml->addChildWithCDATA('TEXTE_ARRET', $doc->text);

$xml->addChild('TYPE', $doc->type);
$xml->addChild('NUM_ARRET', $doc->number);

$xml->addChild('TITRE', "France, $doc->jurisdiction, $doc->chamber, $doc->decision_date, $doc->number");



echo $xml->asXML();
?>

