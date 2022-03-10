<?php
function replaceBlank($str) {
  return str_replace (' ', '_', $str);
}

function pathToFlag($str) {
  return urlencode(str_replace("'", '_', replaceBlank($str)));
}
$cpt = 0;
?>
<div class="pays container w-75">
	<h5  class="text-center pt-5">Rechercher parmi <?php echo number_format($nb, 0, '', ' '); ?> décisions provenant de <?php echo count($pays); ?> pays et institutions francophones</h5>
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
              $link = link_to($pays_nom,'recherche/search?query=&facets=facet_pays:'.$pays);
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
                $link = 'recherche/facet_pays:'.$pays;
                echo('<option id="'.$p['key'][0].'"data-test ="'.$link.'">'.$p['key'][0].'</option>');
                echo($link);
              }
              ?>
            </select>
            <button class="btn btn-outline-secondary" type="button" onclick="goTo()">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
              </svg>
            </button>
          </div>
      </div>
  </div>
  <div class="text-center mt-3 p-3">
    <a href="/documentation/stats/statuts.php">Plus de statistiques</a>
  </div>
</div>
