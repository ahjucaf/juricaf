<?php $sf_response->setTitle($document->titre.'- Juricaf.org'); ?>
<div>
<a href="<?php echo url_for('@recherche'); ?>"><img src="/images/juricaf.png" alt="Juricaf" /></a>
</div>
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
  echo "<p><b>$f :</b>";
  print_r($document->{$f});
  echo "</p>";
} ?>
</div>