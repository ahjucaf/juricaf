<?php if (count($facets) > 0) : ?>
<p><strong><?php echo $label; ?></strong></p>
<ul><?php
if (count($facets) > 1) {
  foreach($facets as $k => $v) {
    echo "<li>".link_to($k."&nbsp;(".$v['count'].")", '@recherche_resultats?query='.$query.'&facets='.$v['fid'].':'.preg_replace('/ /', '_', $v['fname']).$facetslink);
    if (count($v['sub'])) {
      echo '<ul>';
      foreach ($v['sub'] as $f => $o) {
	echo "<li>".link_to($f."&nbsp;(".$o['count'].")", '@recherche_resultats?query='.$query.'&facets='.$o['fid'].':'.preg_replace('/ /', '_', $o['fname']).$facetslink).'</li>';
      }
      echo '</ul>';
    }
    echo "</li>";
  }
} else {
     $k = array_keys($facets);
     echo "<li>".$k[0]."&nbsp;(".$facets[$k[0]]['count'].")"."</li>";
   }
?>
</ul>
<?php endif;
