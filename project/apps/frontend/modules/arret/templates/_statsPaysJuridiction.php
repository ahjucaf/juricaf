<ul class="juridcols">
<?php
foreach ($pays as $p => $juridictions) 
{
  echo '<li>'.link_to($p,'recherche/search?query=pays:'.$p).'<ul class="subul">';
  foreach ($juridictions as $j => $v) {
    echo '<li>'.link_to($j.' ('.$v['value'].' décisions de '.$v['deb'].' à '.$v['fin'].')', 'recherche/search?query=pays:'.$p.'+juridiction:'.$j).'</li>';
  }
  echo "</ul></li>";
}?></ul>
