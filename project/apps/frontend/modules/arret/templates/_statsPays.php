<?php
function replaceBlank($str) {
  return str_replace (' ', '_', $str);
}

function pathToFlag($str) {
  return urlencode(str_replace("'", '_', replaceBlank($str)));
}
$cpt = 0;
?>
<div class="pays container">
	<p  class="text-center pt-5">Rechercher parmi <?php echo number_format($nb, 0, '', ' '); ?> décisions provenant de <?php echo count($pays); ?> pays et institutions francophones</p>
  <?php
    $allPays = $pays;
    $sliptPays = array_chunk($allPays,ceil(count($pays)/3));
  ?>


<div >
      <div class="row">
        <?php
          for($i=0 ; $i < 3; $i++){
            echo('<div class="col-lg-4"><ul class="list-unstyled list-group">');
            foreach($sliptPays[$i] as $p){
              $pays = preg_replace('/ /', '_', $p['key'][0]);
              $pays_nom = $p['key'][0].' ('.number_format($p['value'], 0, '', ' ').')';
              $link = link_to($pays_nom,'recherche/search?query=+&facets=facet_pays:'.$pays);
              if (strlen($pays_nom) > 45) {
                $pays_nom_min = substr($pays_nom, 0, 16) . '...';
                $link = link_to($pays_nom_min,'recherche/search?query=&facets=facet_pays:'.$pays);
              }
               echo('<li class="d-none d-lg-block list-group-item"> <img src="/images/drapeaux/'.pathToFlag(ucfirst($pays)).'.png" alt ="'.$pays.'" />&nbsp;'.$link."</li>");
            }
            echo('</ul></div>');
          }
        ?>

          <div class="input-group d-lg-none w-90 m-auto">

            <select id="selected-pays"class="form-select" id="inputGroupSelect04" aria-label="Example select with button addon">
              <option selected>Rechercher par pays</option>
              <?php
              foreach($allPays as $p){
                $pays = preg_replace('/ /', '_', $p['key'][0]);
                $pays_nom = $p['key'][0].' ('.number_format($p['value'], 0, '', ' ').')';
                $link = 'recherche/+/facet_pays:'.$pays;
                echo('<option id="'.$p['key'][0].'"data-test ="'.$link.'">'.ClientArret::TAB_DRAPEAU[$p['key'][0]]." ".$p['key'][0]." (".$p['value'].") ".'</option>');
                echo($link);
              }
              ?>
            </select>
          </div>
      </div>
  </div>
  <div class="text-center mt-3 p-3">
    <a href="/documentation/stats/statuts.php">Plus de statistiques</a>
  </div>
</div>

<script>
  $(document.getElementById('selected-pays')).change(function(){
    link=$("#selected-pays :selected").data("test");
    window.location.replace(link);
  });
</script>
