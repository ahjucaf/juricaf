<h2>Limiter aux pays</h2>
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

foreach ($pays as $p)
{
  $nom_pays = preg_replace('/ /', '_', $p['key'][0]);
  echo '<div style="float: left;width: 24%;"><input type="checkbox" name="pays['.$nom_pays.']" id="pays_'.$nom_pays.'" checked="checked" /> <label for="pays_'.$nom_pays.'"><img src="/images/drapeaux/'.pathToFlag(ucfirst($nom_pays)).'.png" alt="'.$nom_pays.'" />&nbsp;'.$p['key'][0].' ('.num($p['value']).')</label></div>'."\n";
}
echo '<input type="hidden" value="pays[total]['.count($pays).']" />';
?>
<div style="clear: both; padding-top: 1em; padding-bottom: 0.7em;">
  <input type="button" onclick="javascript:$('input[name^=\'pays\']:checked').prop('checked', '');" value="Tout décocher" />
  <input type="button" onclick="javascript:$('input[name^=\'pays\']').prop('checked', 'checked');" value="Tout cocher" />
</div>