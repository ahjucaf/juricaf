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
<nav>
  <ul class="pagination justify-content-center">
    <li class="page-item"><?php echo echolinkcondition('Début', $currentlink, $pager['begin']); ?></li>
    <li class="page-item"><?php echo echolinkcondition('Précédent', $currentlink, $pager['last']); ?></li>
    <li class="page-item"><?php echo echolinkcondition('Suivant', $currentlink, $pager['next']); ?></li>
    <li class="page-item"><?php echo echolinkcondition('Fin', $currentlink, $pager['end']); ?></li>
  </ul>
</nav>
