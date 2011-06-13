<div class="stats_pays_juridiction">
<ul class="payscols">
<?php
function replaceBlank($str) {
  return str_replace (' ', '_', $str);
}

foreach ($pays as $p)
{
  $pays = preg_replace('/ /', '_', $p['key'][0]);
  echo '<li style="list-style-image: url(/images/drapeaux/'.ucfirst($pays).'.png)">'.link_to($p['key'][0].' ('.$p['value'].')','recherche/search?query=pays:'.$pays).'</li>';
}?></ul>
</div>