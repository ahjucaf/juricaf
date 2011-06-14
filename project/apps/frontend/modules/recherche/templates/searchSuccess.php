<?php $sf_response->setTitle('Résultats de votre recherche - Juricaf.org'); ?>
<?php
use_helper('Text');

function replaceBlank($str) {
  return str_replace (' ', '_', $str);
}
?>
<div class="recherche">
  <h1><?php $nbResultats = $resultats->response->numFound; echo $nbResultats; ?> résultats
  <?php if (preg_match('/[a-z0-9]/i', $query)) : ?>
pour «&nbsp;<?php echo $query; ?>&nbsp;»
  <?php endif; ?>
</h1>
<?php
//////////////////
//  Suppression des options
//////////////////
$myfacetslink = preg_replace('/^,/', '', $facetslink);
$currentlink = array('module'=>'recherche', 'action'=>'search', 'query' => $query, 'facets'=>$myfacetslink);
if (count($facetsset)) : ?>
<div class="options">
<?php
$myfacetslink = preg_replace('/^,/', '', $facetslink);
$noorderlink = $currentlink;
$noorderlink['facets'] = preg_replace('/^,/', '', preg_replace('/,$/', '', preg_replace('/order:[^:,]+,?/', '', $myfacetslink)));

foreach($facetsset as $f) : ?>
<div class="option"><?php
   if (!preg_match('/order:/', $f)) {
       $text = preg_replace('/_/', ' ', preg_replace('/[^:]+:/', '', $f));
       $tmplink = $currentlink;
       $tmplink['facets'] = preg_replace('/^,/', '', preg_replace('/,$/', '', preg_replace('/'.preg_replace('/\|/', '\\\|', $f).',?/', '', $myfacetslink)));
       echo link_to('[X] Résultats filtrés sur <em>'.$text.'</em>', $tmplink);
     }else {
     echo link_to('[X] Résultats trié par pertinence', $noorderlink);
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
<p><strong>Tri</strong></p>
<ul>
<?php if (preg_match('/order:/', $facetslink)) :?>
<li><?php echo link_to('par date', $noorderlink); ?></li>
<li>par pertinence</li>
<?php else : ?>
<li>par date</li>
<li><?php
   $tmplink = $currentlink;
$tmplink['facets'] = 'order:pertinance'.$facetslink;
echo link_to('par pertinence', $tmplink); ?></li>
<?php endif; ?>
</ul>
<?php
  ////// FACETTE Pays //////////
  //include_component('recherche', 'facets', array('label'=>'Pays', 'id'=>'pays', 'facets' => $facets, 'query'=>$query, 'facetslink'=>$facetslink));
  ////// FACETTE Juridiction //////////
  //include_component('recherche', 'facets', array('label'=>'Juridiction', 'id'=>'juridiction', 'facets' => $facets, 'query'=>$query, 'facetslink'=>$facetslink));
include_component('recherche', 'facets', array('label'=>'Pays &amp; Juridiction', 'id'=>'facet_pays_juridiction', 'facets' => $facets, 'query'=>$query, 'facetslink'=>$facetslink, 'tree' => true, 'mainid' => 'pays'));
?>
</div>
<?php
  //////////////////////////////////
  /// Affichage des résultats
  //////////////////////////////////
?><div class="resultats">
<div class="pager">
<?php if ($nbResultats > 10) echo include_partial('pager', array('pager' => $pager, 'currentlink' => $currentlink)); ?>
</div>
<?php
foreach ($resultats->response->docs as $resultat) {
  echo '<div class="resultat"><h3><a href="'.url_for('@arret?id='.$resultat->id).'"><img style="height: 10px;" src="/images/drapeaux/'.replaceBlank($resultat->pays).'.png" alt="§" /> '.$resultat->titre.'</a></h3>';
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
<div class="pager">
<?php if ($nbResultats > 10) echo include_partial('pager', array('pager' => $pager, 'currentlink' => $currentlink)); ?>
</div>
</div>
