<?php
if (!function_exists('echolinkcondition')) {
  function echolinkcondition($titre, $link, $pagenum)
  {
    if (!$pagenum) {
      echo $titre;
      return;
    }
    if ($pagenum > 1)
      $link['page'] = $pagenum;
    echo link_to($titre, $link);
  }
 }
?>
<div class="navigation">
<div style="float:left;">
  <?php echo echolinkcondition('<< Début', $currentlink, $pager['begin']); ?>
  <?php echo echolinkcondition('< Précédent', $currentlink, $pager['last']); ?>
</div>

<div style="float:right;">

	<?php echo echolinkcondition('Suivant >', $currentlink, $pager['next']); ?>
	<?php echo echolinkcondition('Fin >>', $currentlink, $pager['end']); ?>
</div>
</div>

