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
        <label class="form-check-label" for="pays_'.$nom_pays.'"><img src="/images/drapeaux/'.pathToFlag(ucfirst($nom_pays)).'.png" alt="'.$nom_pays.'" width="17" height="12" />&nbsp;'.$p['key'][0].' ('.num($p['value']).')</label>
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
  <button type="button" class="btn btn-link" onclick="javascript:$('input[name^=\'pays\']').prop('checked', 'checked');tout_cocher_decocher();">Tous les pays</button>
  </span><span class="aucun tout">
  /
  </span><span class="aucun">
  <button type="button" class="btn btn-link" onclick="javascript:$('input[name^=\'pays\']:checked').prop('checked', '');tout_cocher_decocher();">Décocher tous les pays</button>
  </span>
<script>
        function tout_cocher_decocher() {
        let nb_pays = document.querySelectorAll('input[name^=\'pays\']').length;
        let nb_pays_checked = document.querySelectorAll('input[name^=\'pays\']:checked').length;

        document.querySelectorAll('.tout').forEach(function(item) { item.classList.remove('d-none') });
        document.querySelectorAll('.aucun').forEach(function(item) { item.classList.remove('d-none') });
        if (nb_pays == nb_pays_checked) {
            document.querySelectorAll('.tout').forEach(function(item) { item.classList.add('d-none') });
        }else if (nb_pays_checked == 0) {
            document.querySelectorAll('.aucun').forEach(function(item) { item.classList.add('d-none') });
        }
    }

    (function () {
    document.querySelectorAll('input[name^=\'pays\']').forEach(function(item) {
        item.addEventListener('change', function() {tout_cocher_decocher();});
    });
    tout_cocher_decocher();
    })();
</script>
</div>
</div>



