<h5><b>Limiter aux pays :</b></h5>
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
?>

<div class="d-none d-lg-block">
<div>
  <div class="row">
    <?php
    foreach ($pays as $p){
      $nom_pays = preg_replace('/ /', '_', $p['key'][0]);
      $nom_pays = preg_replace("/'/", '_', $nom_pays);
      echo('
      <div class="col-lg-3">
        <input id="'.$nom_pays.'" type="checkbox" name="pays['.$nom_pays.']" id="pays_'.$nom_pays.'" checked="checked"/>
        <label class="form-check-label" for="pays_'.$nom_pays.'"><img src="/images/drapeaux/'.pathToFlag(ucfirst($nom_pays)).'.png" alt="'.$nom_pays.'" />&nbsp;'.$p['key'][0].' ('.num($p['value']).')</label>
      </div>');

    }
    ?>
  </div>
</div>

<?php
echo '<input type="hidden" name="total" value="'.count($pays).'" />';
?>

<div style="clear: both; padding-top: 1em; padding-bottom: 0.7em;">
  <a id="checkall" href="#" onclick="javascript:$('input[name^=\'pays\']').prop('checked', 'checked');return false">Tout cocher</a> /
  <a id="uncheckall" href="#" onclick="javascript:$('input[name^=\'pays\']:checked').prop('checked', '');return false;">Tout décocher</a>
</div>
</div>


<div class="d-lg-none">
  <select id="selectpays" class="form-select" size="5" multiple="multiple" aria-label="multiple select example">
    <option id="selectall" selected>Tout sélectionner</option>
    <?php
    foreach ($pays as $p){
      $nom_pays = preg_replace('/ /', '_', $p['key'][0]);
      $nom_pays = preg_replace("/'/", '_', $nom_pays);
      echo '<option selected data-tocheck ="'.$nom_pays.'"value="'.$p['key'][0].'">'.ClientArret::TAB_DRAPEAU[$p['key'][0]]." ".$p['key'][0].'</option>';
    }
    ?>
  </select>
</div>
