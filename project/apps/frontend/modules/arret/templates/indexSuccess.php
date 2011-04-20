<?php $sf_response->setTitle($document->titre.'- Juricaf.org'); 

function printDocument($d) {
  if (!is_object($d) || get_class($d) != 'sfOutputEscaperArrayDecorator') {
    return print_r($d);
  }
  echo "<ul>";
  $d->rewind();
  while ($sd = $d->current()) {
    echo "<li>";
    if (!is_int($d->key()))
      echo "<b>".$d->key()."</b>&nbsp;: ";
    printDocument($sd);
    echo "</li>";
    $d->next();
  }
  echo "</ul>";
}

?>
<div>
<h2><?php echo $document->titre; ?></h2>
<?php
echo "<p>";
echo preg_replace ('/\n/', '</p><p>', $document->texte_arret);
echo "</p>";
?>
</div>
<div class="extra">
<h2>Extras</h2>
<?php 
  foreach ($document->getFields() as $f) {
  if (in_array($f, array('titre', 'texte_arret')))
    continue;
  echo "<p><b>$f : </b>";
  printDocument($document->{$f});
  echo "</p>";
} ?>
</div>
<div class="download">
<?php echo link_to('Télécharger au format juricaf', '@arretxml?id='.$document->_id); ?>
</div>