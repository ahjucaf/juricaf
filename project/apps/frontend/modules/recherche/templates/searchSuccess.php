<?php use_helper('Text'); ?>
<div>
<a href="<?php echo url_for('@recherche'); ?>" style="border: none;"><img src="/images/juricaf.png" alt="Juricaf" /></a>
</div>
<div>
<h2><?php echo $resultats->numFound; ?> résultats pour "<?php echo $query; ?>"</h2>
<?php
foreach ($resultats->docs as $resultat) {
  echo '<h3><a href="'.url_for('@arret?id='.$resultat->id).'">'.$resultat->titre.'</a></h3>';
  echo '<p>'.preg_replace ('/[^a-z0-9]*\.\.\.$/i', '...', truncate_text($resultat->texte_arret, 500, "...", true)).'</p>';
}
?>
</div>
