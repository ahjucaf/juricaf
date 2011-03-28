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
