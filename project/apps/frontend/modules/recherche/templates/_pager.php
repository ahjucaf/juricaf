<?php
function echolinkcondition($titre, $link, $pagenum) 
{
  if (!$pagenum) {
    echo $titre;
    return;
  }
  $link['page'] = $pagenum;
  echo link_to($titre, $link);
}
?>
<span class="begin"><?php echo echolinkcondition('<< Debut', $currentlink, $pager['begin']); ?></span>
<span class="last"><?php echo echolinkcondition('< Précédent', $currentlink, $pager['last']); ?></span>
<span class="next"><?php echo echolinkcondition('Suivant >', $currentlink, $pager['next']); ?></span>
<span class="end"><?php echo echolinkcondition('Fin >>', $currentlink, $pager['end']); ?></span>

