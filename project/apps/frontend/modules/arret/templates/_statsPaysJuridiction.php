<div class="stats_pays_juridiction">
   <p>Juricaf publie <?php echo number_format($nb, 0, ',', ' '); ?> décisions de cours suprême provenant de <?php echo count($pays); ?> pays francophones.</p>
<ul class="juridcols">
<?php
function replaceBlank($str) {
  return str_replace (' ', '_', $str);
}

function pathToFlag($str) {
  return urlencode(str_replace("'", '_', replaceBlank($str)));
}

function sortInstitutions($a, $b) {
  return $b['value'] - $a['value'];
}

foreach ($pays as $p => $juridictions)
{
  $no_blank_p = replaceBlank($p);
  echo '<li style="list-style-image: url(/images/drapeaux/'.pathToFlag(ucfirst($no_blank_p)).'.png)"><strong>'.link_to($p,'recherche/search?query=+&facet_facets=pays:'.$no_blank_p).'</strong><ul class="subul">';
  uasort($juridictions, 'sortInstitutions');
  foreach ($juridictions as $j => $v) {
    $pluriel = '';
    if ($v['value'] > 1)
      $pluriel = 's';
    if($v['deb'] == $v['fin']) { $periode = "décision$pluriel de ".$v['deb']; }
    else { $periode = "décisions de ".$v['deb'].' à '.$v['fin']; }
    echo '<li>'.link_to($j.' ('.number_format($v['value'], 0, ',', ' ').' '.$periode.')', 'recherche/search?query=+&facets=facet_pays:'.$no_blank_p.',facet_juridiction:'.replaceBlank($j)).'</li>';
  }
  echo "</ul></li>";
}?></ul>
</div>
