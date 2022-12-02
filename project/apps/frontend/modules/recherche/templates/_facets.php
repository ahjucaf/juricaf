<?php
function facet_link_to($t, $l) {
  return link_to($t, preg_replace("/\'/", '’', $l));
}

if (count($facets) > 0) : ?>
<li>
	<p class="font-weight-bold"><?php echo $label; ?> :</p>
	<ul  class="ul-sans-point"><?php
foreach($facets as $k => $v) {
    echo '<li style="margin-top: 5px;">';
    if($mainid === 'facet_pays')
  echo '<img style="height: 10px;" src="/images/drapeaux/'.pathToFlag($v['fname']).'.png" alt="'.$v['fname'].'" width="17" height="12" />&nbsp;';
    $facet = $v['fid'].':'.preg_replace('/ /', '_', $v['fname']);
    if (preg_match('/'.$facet.'/', $facetslink))
      echo $k."&nbsp;(".number_format($v['count'], 0, ',', ' ').")";
    else
      echo facet_link_to($k."&nbsp;(".number_format($v['count'], 0, ',', ' ').")", '@recherche_resultats?query='.$query.'&facets='.$facet.$facetslink);
    if (count($v['sub'])) {
      echo '<ul class="ul-sans-point">';
      foreach ($v['sub'] as $f => $o) {
  echo "<li class='sub'>";
  $facet = $o['fid'].':'.preg_replace('/ /', '_', $o['fname']);
  if (preg_match('/'.$facet.'/', $facetslink) || $o['count'] == $v['count'])
    echo $f."&nbsp;(".number_format($o['count'], 0, ',', ' ').")";
  else
    echo facet_link_to($f."&nbsp;(".number_format($o['count'], 0, ',', ' ').")", '@recherche_resultats?query='.$query.'&facets='.$facet.$facetslink);
  echo '</li>';
      }
      echo '</ul>';
    }
    echo "</li>";
  }
?>
	</ul>
</li>
<?php endif;
