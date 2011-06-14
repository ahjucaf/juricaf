<div class="payscols">
<table><tr>
<?php
function replaceBlank($str) {
  return str_replace (' ', '_', $str);
}
$cpt = 0;
foreach ($pays as $p)
{
  $pays = preg_replace('/ /', '_', $p['key'][0]);
  if (++$cpt % 2)
    echo '</tr><tr>';
  echo '<td><img src="/images/drapeaux/'.ucfirst($pays).'.png"/>&nbsp;'.link_to($p['key'][0].' ('.$p['value'].')','recherche/search?query=pays:'.$pays).'</li></td>';
}?></tr><tr><td colspan=2 class="plus"><?php echo link_to('Plus de statistiques', '@stats'); ?></td></table>
</div>