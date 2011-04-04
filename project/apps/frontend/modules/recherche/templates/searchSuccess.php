<?php $sf_response->setTitle('Résultats de votre recherche - Juricaf.org'); ?>
<?php use_helper('Text'); ?>
<div>
<a href="<?php echo url_for('@recherche'); ?>"><img src="/images/juricaf.png" alt="Juricaf" /></a>
<form method="get" action="<?php echo url_for('recherche_resultats'); ?>">
    <input type="text" name="q" value="<?php echo $query; ?>" tabindex="10" style="width: 300px;" />
    <input type="submit" value="Rechercher" tabindex="20" />
  </form>
</div>
<div>
<h2><?php echo $resultats->numFound; ?> résultats pour «&nbsp;<?php echo $query; ?>&nbsp;»</h2>
<?php
foreach ($resultats->response->docs as $resultat) {
  echo '<div class="resultat"><h3><a href="'.url_for('@arret?id='.$resultat->id).'">'.$resultat->titre.'</a></h3>';
  echo '<p>';
  $exerpt = '';
  if (isset($resultats->highlighting) && $resultats->highlighting->{$resultat->id}) {
    foreach ($resultats->highlighting->{$resultat->id}->content as $h)
      $exerpt .= '...'.html_entity_decode($h);
    $exerpt .= '...' ;
  }
  if ($resultat->analyses) 
    $exerpt .= $resultat->analyses.'...';
  echo preg_replace ('/[^a-z0-9]*\.\.\.$/i', '...', truncate_text($exerpt.$resultat->texte_arret, 500, "...", true));
  echo '</p>';
  echo '<div class="extra"><span class="pays '.preg_replace('/ /', '_', $resultat->pays).'">'.$resultat->pays.'</span> - <span class="date">'.date('d/m/Y', strtotime($resultat->date_arret)).'</span> - <span class="juridiction">'.$resultat->juridiction.', '.$resultat->formation.'</span> - <span class="num">'.$resultat->num_arret.'</span></div></div>';
}
?>
</div>
