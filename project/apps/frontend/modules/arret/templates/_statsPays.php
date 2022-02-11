<?php
function replaceBlank($str) {
  return str_replace (' ', '_', $str);
}

function pathToFlag($str) {
  return urlencode(str_replace("'", '_', replaceBlank($str)));
}
$cpt = 0;
?>
<div class="pays">
	<h3 style="text-align: center;">Rechercher parmi <?php echo number_format($nb, 0, '', ' '); ?> décisions provenant de <?php echo count($pays); ?> pays et institutions francophones</h3>
	<div class="payscols">
    <div class="container accueil-div text-justify p-5">
      <div class="row">
    <?php
      $max_per_col = intval(count($pays) / 3) + 1;
      foreach($pays as $p){
        $pays = preg_replace('/ /', '_', $p['key'][0]);
        if($cpt % $max_per_col == 0 && $cpt){
          echo('</div><div class="row">');
        }
        $cpt++;
        $pays_nom = $p['key'][0].' ('.number_format($p['value'], 0, '', ' ').')';
        $link = link_to($pays_nom,'recherche/search?query=+&facets=facet_pays:'.$pays);
        if (strlen($pays_nom) > 45) {
          $pays_nom_min = substr($pays_nom, 0, 16) . '...';
          $link = link_to($pays_nom_min,'recherche/search?query=+&facets=facet_pays:'.$pays);
        }
        if (strlen($pays) > 0) {
          echo '<div class="col-lg-6"><img src="/images/drapeaux/'.pathToFlag(ucfirst($pays)).'.png" alt="'.$pays.'" />&nbsp;'.$link .'</div>';
        }
      }
      ?>
	</div>
  <div class="text-center mt-3 p-3">
    <img src="images/+.png" alt="+" /> <a href="/documentation/stats/statuts.php">Plus de statistiques</a>
  </div>
</div>
