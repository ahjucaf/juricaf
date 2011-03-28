<?php use_helper('Text'); ?>
<div>
<a href="<?php echo url_for('@recherche'); ?>" style="border: none;"><img src="/images/juricaf.png" alt="Juricaf" /></a>
</div>
<div>
<h2>Résultats pour "<?php echo $query; ?>"</h2>
<?php
/*
echo "<pre>";
print_r($resultats->docs);
echo "</pre>";

'.url_for('@arret?id='.$resultat->_fields->id).'*/

foreach ($resultats->docs as $resultat) {
  echo '<h3><a href="'.url_for('@arret?id='.$resultat->id).'">'.$resultat->titre.'</a></h3>';
  echo '<p>'.truncate_text($resultat->texte_arret, 500, "...").'</p>';
}
?>
</div>
