<?php $sf_response->setTitle('Bienvenue sur Juricaf.org'); ?>
<div>
<img src="/images/juricaf.png" alt="Juricaf" />
</div>
<div>
<h2><?php echo $document->titre; ?></h2>
<?php
echo "<pre>";
print_r($document->texte_arret);
echo "</pre>";
?>
</div>
