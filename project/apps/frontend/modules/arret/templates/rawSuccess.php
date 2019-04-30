<?php
if ($txt == true) {
    echo $document->texte_arret;
    return;
}
if ($json == true) {

function printJson($field, $balise)
{
  if (!is_array($field)) {
	if ($balise == "texte_arret") {
		$texte_html = str_replace("\n", "<br/>", $field);
		echo json_encode($texte_html, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
	}
	else
		echo json_encode($field, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    return ;
  }
  echo '{';
  if (array_keys($field))
    foreach ($field as $key => $value) {
      if (!is_int($key)) echo '"'.$key.'" : ';
      printJson($value, $key);
      if (!is_int($key)) echo ',';
    }
  else
    foreach($field as $value)
      printJson($value, $balise);
  echo '}';
}

// Ouverture de l'objet JSON
echo '{';

// Gestion de la derniere boucle
$total = count($document->getFields());
$i = 0;

// Boucle principale
foreach ($document->getFields() as $field) :

if ($field == "_id")
	echo '"id" : ';
else
	echo '"'.$field.'" : ';
printJson($document->{$field}, $field);

// Gestion de la derniere boucle
$i++;
if ($i < $total)
echo ', ';

endforeach;

// Fermeture de l'objet JSON
echo '}';

}

else { // XML


echo '<?xml version="1.0" encoding="utf8"?>'; ?>

<DOCUMENT>
<?php

function printBalise($field, $balise)
{
  if (!is_array($field)) {
    echo $field;
    return ;
  }
  if (array_keys($field))
    foreach ($field as $k => $v) {
      if (!is_int($k)) echo '<'.strtoupper($k).'>';
      printBalise($v, $k);
      if (!is_int($k)) echo '</'.strtoupper($k).'>';
    }
  else
    foreach($field as $v)
      printBalise($v, $balise);
}

foreach ($document->getFields() as $f) :
if (preg_match('/^_/', $f))
  continue;
echo '<'.strtoupper($f).'>';
printBalise($document->{$f}, $f);
echo '</'.strtoupper($f).'>';
endforeach; ?>
</DOCUMENT>
<?php } ?>
