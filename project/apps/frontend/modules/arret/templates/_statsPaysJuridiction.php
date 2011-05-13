<ul class="juridcols">
<?php
function replaceBlank($str) {
  return str_replace (' ', '_', $str);
}

foreach ($pays as $p => $juridictions)
{
  echo '<li>'.link_to($p,'recherche/search?query=pays:'.$p).'<ul class="subul">';
  foreach ($juridictions as $j => $v) {
    echo '<li>'.link_to($j.' ('.$v['value'].' décisions de '.$v['deb'].' à '.$v['fin'].')', 'recherche/search?query=pays:'.replaceBlank($p).'+juridiction:'.replaceBlank($j)).'</li>';
  }
  echo "</ul></li>";
}?></ul>
