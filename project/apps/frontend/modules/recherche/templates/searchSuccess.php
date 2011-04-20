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
<?php 
//////////////////
//  Suppression des options
//////////////////
if (count($facetsset)) : ?>
<div class="options">
<?php   
$myfacetslink = preg_replace('/^,/', '', $facetslink);
$noorderlink = '@recherche_resultats?query='.$query.'&facets='.preg_replace('/^,/', '', preg_replace('/,$/', '', preg_replace('/order:[^:,]+,?/', '', $myfacetslink)));
foreach($facetsset as $f) : ?>
<div class="option"><?php
   if (!preg_match('/order:/', $f)) {
       $text = preg_replace('/_/', ' ', preg_replace('/[^:]+:/', '', $f));
       echo link_to('[X] Résultats filtrés sur <em>'.$text.'</em>', 
		    '@recherche_resultats?query='.$query.'&facets='.
		    preg_replace('/^,/', '', preg_replace('/,$/', '', preg_replace('/'.$f.',?/', '', $myfacetslink))));
     }else {
     echo link_to('[X] Résultats trié par pertinance', $noorderlink);
     }
?></div>
   <?php endforeach; ?>
</div>    
<?php endif;
//////////////////
//  Gestion des facettes
//////////////////
?>
<div class="facets">
<?php 
/////// TRI //////// 
?>
<p>Tri</p>
<ul>
<?php if (preg_match('/order:/', $facetslink)) :?>
<li><?php echo link_to('par date', $noorderlink); ?></li>
<li>par pertinance</li>
<?php else : ?>
<li>par date</li>
<li><?php echo link_to('par pertinance', '@recherche_resultats?query='.$query.'&facets=order:pertinance'.$facetslink); ?></li>
<?php endif; ?>
</ul>
<?php
  ////// FACETTE Pays //////////
include_partial('recherche/facets', array('label'=>'Pays', 'id'=>'pays', 'facets' => $facets, 'query'=>$query, 'facetslink'=>$facetslink));
  ////// FACETTE Juridiction //////////
include_partial('recherche/facets', array('label'=>'Juridiction', 'id'=>'juridiction', 'facets' => $facets, 'query'=>$query, 'facetslink'=>$facetslink));
?>
</div>
<?php
  //////////////////////////////////
  /// Affichage des résultats
  //////////////////////////////////
?><div class="resultats">
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
