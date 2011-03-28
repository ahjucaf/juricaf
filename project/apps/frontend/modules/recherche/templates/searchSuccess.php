<?php use_helper('Text'); ?>
<div>
<img src="/images/juricaf.png" alt="Juricaf" />
</div>
<div>
<h2>Résultats pour "<?php echo $query; ?>"</h2>
<?php
echo "<pre>";
print_r($resultats->docs);
echo "</pre>";/*

'.url_for('@arret?id='.$resultat->_fields->id).'
foreach ($resultats->docs as $resultat) {
  echo '<h3><a href="">'.$resultat->_fields->titre.'</a></h3>';
  echo '<p>'.truncate_text($resultat->_fields->texte_arret, 200, "...").'</p>';
}
*/

?>
</div>
