<?php

$doc = $argv[1];
$doc = json_decode(@file_get_contents($doc));
if (! $doc) {
    fwrite(STDERR, "ERREUR: json non trouvé\n");
    exit(1);
}

$mois = [
    '01' => 'janvier',
    '02' => 'février',
    '03' => 'mars',
    '04' => 'avril',
    '05' => 'mai',
    '06' => 'juin',
    '07' => 'juillet',
    '08' => 'août',
    '09' => 'septembre',
    '10' => 'octobre',
    '11' => 'novembre',
    '12' => 'décembre',
];

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

$date_fr = substr($doc->decision_date, -2).' '.$mois[substr($doc->decision_date, -5, 2)].' '.strstr($doc->decision_date, '-', true);

$xml->addChild('TITRE', "France, $doc->jurisdiction, $doc->chamber, $date_fr, $doc->number");



echo $xml->asXML();
?>

