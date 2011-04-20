<?php if (isset($facets[$id]) && count($facets[$id]) > 0) : ?>
<p><?php echo $label; ?></p>
<ul><?php 
   if (count($facets[$id]) > 1)
     foreach($facets[$id] as $k => $v)
       echo "<li>".link_to($k."&nbsp;(".$v.")", '@recherche_resultats?query='.$query.'&facets='.$id.':'.preg_replace('/ /', '_', $k).$facetslink)."</li>";
   else {
     $k = $facets[$id]->key();
     echo "<li>".$k."&nbsp;(".$facets[$id][$k].")"."</li>";
   }
?>
</ul>
<?php endif; 
