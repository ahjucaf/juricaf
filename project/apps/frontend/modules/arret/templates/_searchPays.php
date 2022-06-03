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
 <!-- class="d-none d-lg-block" -->
<div>
<div>
  <div class="row">
    <?php
    foreach ($pays as $p){
      $nom_pays = preg_replace('/ /', '_', $p['key'][0]);
      $nom_pays = preg_replace("/'/", '_', $nom_pays);
      echo('
      <div class="col-lg-3">
        <input id="pays_'.$nom_pays.'" type="checkbox" name="pays['.$nom_pays.']" id="pays_'.$nom_pays.'" checked="checked"/>
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
  <span class="tout">
  <a  href="#" onclick="javascript:$('input[name^=\'pays\']').prop('checked', 'checked');tout_cocher_decocher();return false">Tous les pays</a>
  </span><span class="aucun tout">
  /
  </span><span class="aucun">
  <a  href="#" onclick="javascript:$('input[name^=\'pays\']:checked').prop('checked', '');tout_cocher_decocher();return false;">Décocher tous les pays</a>
  </span>
<script>
    (function () {
    nb_pays = $('input[name^=\'pays\']').length;
    function tout_cocher_decocher() {
        nb_pays_checked = $('input[name^=\'pays\']:checked').length;
        $('.tout').show();
        $('.aucun').show();
        if (nb_pays == nb_pays_checked) {
            $('.tout').hide();
        }else if (nb_pays_checked == 0) {
            $('.aucun').hide();
        }
    }
    $('input[name^=\'pays\']').change(tout_cocher_decocher);
    tout_cocher_decocher();
    })();
</script>
</div>
</div>



