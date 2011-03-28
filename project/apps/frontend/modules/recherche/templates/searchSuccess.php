<?php use_helper('Text'); ?>
<div>
<a href="<?php echo url_for('@recherche'); ?>"><img src="/images/juricaf.png" alt="Juricaf" /></a>
<form method="get" action="<?php echo url_for('recherche_resultats'); ?>">
    <input type="text" name="q" value="<?php echo $query; ?>" tabindex="10" style="width: 300px;" />
    <input type="submit" value="Rechercher" tabindex="20" />
  </form>
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
