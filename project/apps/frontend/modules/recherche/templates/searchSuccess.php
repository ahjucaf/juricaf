<?php $sf_response->setTitle('Résultats de votre recherche - Juricaf.org'); ?>
<?php use_helper('Text'); ?>
<div>
<form method="get" action="<?php echo url_for('recherche_resultats'); ?>">
    <input type="text" name="q" value="<?php echo $query; ?>" tabindex="10" style="width: 300px;" />
    <input type="submit" value="Rechercher" tabindex="20" />
  </form>
</div>
<div>
<h2><?php echo $resultats->response->numFound; ?> résultats pour «&nbsp;<?php echo $query; ?>&nbsp;»</h2>
<?php if (count($facetsset)) : ?>
<div class="options">
   <?php   foreach($facetsset as $f) : ?>
<div class="option"><?php
   $myfacetslink = preg_replace('/^,/', '', $facetslink);
   if (!preg_match('/order:/', $f)) {
       $text = preg_replace('/_/', ' ', preg_replace('/[^:]+:/', '', $f));
       echo link_to('[X] Résultats filtrés sur <em>'.$text.'</em>', 
		    '@recherche_resultats?query='.$query.'&facets='.
		    preg_replace('/^,/', '', preg_replace('/,$/', '', preg_replace('/'.$f.',?/', '', $myfacetslink))));
     }else {
     echo link_to('[X] Résultats trié par pertinance', 
		  '@recherche_resultats?query='.$query.'&facets='.
		  preg_replace('/^,/', '', preg_replace('/,$/', '', preg_replace('/order:[^:,]+,?/', '', $myfacetslink))));
     }
?></div>
   <?php endforeach; ?>
</div>    
<?php endif;  ?>
<div class="facets">
<?php if (isset($facets['juridiction']) && count($facets['juridiction']) > 1) : ?>
<p>Juridiction</p>
<ul><?php 
    foreach($facets['juridiction'] as $k => $v) {
      echo "<li>".link_to($k."&nbsp;(".$v.")", '@recherche_resultats?query='.$query.'&facets=juridiction:'.preg_replace('/ /', '_', $k).$facetslink)."</li>";
  }
?>
</ul>
<?php endif; ?>
<?php if (isset($facets['pays']) && count($facets['pays']) > 1) : ?>
<p>Pays</p>
<ul><?php 
   foreach($facets['pays'] as $k => $v) {
  echo "<li>".link_to($k."&nbsp;(".$v.")", '@recherche_resultats?query='.$query.'&facets=pays:'.preg_replace('/ /', '_', $k).$facetslink)."</li>";
 }
?>
</ul>
<?php endif; ?>
</div>
<div class="resultats">
<?php
foreach ($resultats->response->docs as $resultat) {
  echo '<div class="resultat"><h3><a href="'.url_for('@arret?id='.$resultat->id).'">'.$resultat->titre.'</a></h3>';
  echo '<p>';
  $exerpt = '';
  if (isset($resultats->highlighting) && $resultats->highlighting->{$resultat->id} && isset($resultats->highlighting->{$resultat->id}->content)) {
    foreach ($resultats->highlighting->{$resultat->id}->content as $h)
      $exerpt .= '...'.html_entity_decode($h);
    $exerpt .= '...' ;
  }
  if ($resultat->analyses) 
    $exerpt .= $resultat->analyses.'...';
  echo preg_replace ('/[^a-z0-9]*\.\.\.$/i', '...', truncate_text($exerpt.$resultat->texte_arret, 650, "...", true));
  echo '</p>';
  $formation = '';
  if ($resultat->formation)
    $formation = ', '.$resultat->formation;
  echo '<div class="extra"><span class="pays '.preg_replace('/ /', '_', $resultat->pays).'">'.$resultat->pays.'</span> - <span class="date">'.date('d/m/Y', strtotime($resultat->date_arret)).'</span> - <span class="juridiction">'.$resultat->juridiction.$formation.'</span> - <span class="num">'.$resultat->num_arret.'</span></div></div>';
}
?>
</div>
</div>
