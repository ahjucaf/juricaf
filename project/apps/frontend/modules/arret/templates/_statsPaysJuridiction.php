<div class="stats_pays_juridiction">
<ul class="juridcols">
<?php
function replaceBlank($str) {
  return str_replace (' ', '_', $str);
}

foreach ($pays as $p => $juridictions)
{
  $no_blank_p = replaceBlank($p);
  echo '<li style="list-style-image: url(/images/drapeaux/'.ucfirst($no_blank_p).'.png)"><strong>'.link_to($p,'recherche/search?query=pays:'.$no_blank_p).'</strong><ul class="subul">';
  foreach ($juridictions as $j => $v) {
    $pluriel = '';
    if ($v['value'] > 1)
      $pluriel = 's';
    if($v['deb'] == $v['fin']) { $periode = "décision$pluriel de ".$v['deb']; }
    else { $periode = "décisions de ".$v['deb'].' à '.$v['fin']; }
    echo '<li>'.link_to($j.' ('.$v['value'].' '.$periode.')', 'recherche/search?query=pays:'.$no_blank_p.'+juridiction:'.replaceBlank($j)).'</li>';
  }
  echo "</ul></li>";
}?></ul>
</div>
