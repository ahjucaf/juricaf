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
<nav class="mt-3">
  <ul class="pagination justify-content-center">
    <li class="page-item"><?php echo echolinkcondition('<i class="bi bi-chevron-double-left"></i>', $currentlink, $pager['begin']); ?></li>
    <li class="page-item"><?php echo echolinkcondition('<i class="bi bi-chevron-left"></i> Précédent', $currentlink, $pager['last']); ?></li>
    <li class="page-item"><?php echo echolinkcondition('Suivant <i class="bi bi-chevron-right"></i>', $currentlink, $pager['next']); ?></li>
    <li class="page-item"><?php echo echolinkcondition('<i class="bi bi-chevron-double-right"></i>', $currentlink, $pager['end']); ?></li>
  </ul>
</nav>
