<?php if (isset($facets[$id]) && count($facets[$id]) > 0) : ?>
<p><strong><?php echo $label; ?></strong></p>
<ul><?php
   if (count($facets[$id]) > 1)
     foreach($facets[$id] as $k => $v)
       echo "<li>".link_to($k."&nbsp;(".$v.")", '@recherche_resultats?query='.$query.'&facets='.$id.':'.preg_replace('/ /', '_', $k).$facetslink)."</li>";
   else {
     $k = array_keys($facets[$id]);
     echo "<li>".$k[0]."&nbsp;(".$facets[$id][$k[0]].")"."</li>";
   }
?>
</ul>
<?php endif;
