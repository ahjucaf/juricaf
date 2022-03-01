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
	<h5  class="text-center pt-5">Rechercher parmi <?php echo number_format($nb, 0, '', ' '); ?> décisions provenant de <?php echo count($pays); ?> pays et institutions francophones</h5>
  <div class='col-12 container'>

    <ul  class="list-unstyled row">
      <?php
      $i = 0;
      foreach($pays as $p){
        $pays = preg_replace('/ /', '_', $p['key'][0]);
        $pays_nom = $p['key'][0].' ('.number_format($p['value'], 0, '', ' ').')';
        $link = link_to($pays_nom,'recherche/search?query=+&facets=facet_pays:'.$pays);
        if (strlen($pays_nom) > 45) {
          $pays_nom_min = substr($pays_nom, 0, 16) . '...';
          $link = link_to($pays_nom_min,'recherche/search?query=+&facets=facet_pays:'.$pays);
        }
        if($i > 7){
          $test = " hidden ";
        }
        echo('<li class="'.$test.'list-group-item col-lg-3"> <img src="/images/drapeaux/'.pathToFlag(ucfirst($pays)).'.png" alt ="'.$pays.'" />&nbsp;'.$link."</li>");
        $i++;
      }
      echo('<li class="show text-center list-group-item col-lg-3"><button id="btn-showmore" type="button" class="btn btn-link" onclick="showmore()">Voir plus</button></li>');
      ?>
    </ul>
    <div>

  <div class="text-center mt-3 p-3">
    <a href="/documentation/stats/statuts.php">Plus de statistiques</a>
  </div>
</div>


<script>
  function showmore(){
    if(document.getElementById("btn-showmore").innerHTML == "Voir plus"){
      document.getElementById("btn-showmore").innerHTML= "Voir moins";
      var toshow = document.getElementsByClassName("hidden");
      for (var i=0;i< toshow.length;i++){
        toshow[i].style.display = "block";
      }
    }
    else{
      document.getElementById("btn-showmore").innerHTML= "Voir plus";
      var toshow = document.getElementsByClassName("hidden");
      for (var i=0;i< toshow.length;i++){
        toshow[i].style.display = "none";
      }
    }
  }
</script>
