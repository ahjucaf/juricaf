<?php if (count($facets) > 0) : ?>
<p><strong><?php echo $label; ?></strong></p>
<ul><?php
if (count($facets) > 1) {
  foreach($facets as $k => $v) {
    echo '<li style="margin-top: 5px;"><img style="height: 10px;" src="/images/drapeaux/'.urlencode(replaceBlank($v['fname'])).'.png" alt="" /> '.link_to($k."&nbsp;(".$v['count'].")", '@recherche_resultats?query='.$query.'&facets='.$v['fid'].':'.replaceBlank($v['fname']).$facetslink);
    if (count($v['sub'])) {
      echo '<ul>';
      foreach ($v['sub'] as $f => $o) {
  echo "<li class='sub'>".link_to($f."&nbsp;(".$o['count'].")", '@recherche_resultats?query='.$query.'&facets='.$o['fid'].':'.replaceBlank($o['fname']).$facetslink).'</li>';
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
