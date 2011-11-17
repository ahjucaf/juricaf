<?php
function replaceBlank($str) {
  return str_replace (' ', '_', $str);
}

function num($num) {
  return preg_replace('/(\d)(\d{3})$/', '\1&nbsp;\2', $num);
}

function pathToFlag($str) {
  return urlencode(str_replace("'", '_', replaceBlank($str)));
}

$cpt = 0;
?>
<div class="payscols">
  <p style='text-align: center;'>Rechercher parmi <?php echo num($nb); ?> décisions provenant de <?php echo count($pays); ?> pays francophones :</p>
<table><tr>
<?php
foreach ($pays as $p)
{
  $pays = preg_replace('/ /', '_', $p['key'][0]);
  if (++$cpt%2 && $cpt !== 1) { echo '</tr><tr>'; }
  echo '<td><img src="/images/drapeaux/'.pathToFlag(ucfirst($pays)).'.png" alt="'.$pays.'" />&nbsp;'.link_to($p['key'][0].' ('.num($p['value']).')','recherche/search?query=+&facets=facet_pays:'.$pays).'</td>';
}
?>
</tr><tr><td colspan="2" class="plus"><a href="/documentation/stats/statuts.php">Plus de statistiques</a></td></tr></table>
</div>
