<?php $sf_response->setTitle("Juricaf : la jurisprudence francophone des Cours suprêmes");?>
<div class="col-xl-10 offset-xl-1">
    <div class="text-center mt-4 container">
    <a href="/"><img class="align-self-center" height="100" width="100" id="logo" src="/images/juricaf.png" alt="Juricaf" /></a>
    <p><small class="text-secondary">La jurisprudence francophone des Cours suprêmes</small></p>
    <p class="pt-2 text-secondary">Rechercher parmi <?php echo number_format($nb, 0, '', ' '); ?> décisions provenant de <?php echo count($pays); ?> pays et institutions francophones</p>
    </div>
    <?php include_partial('recherche/barre', array('noentete' => true)); ?>
    <div class="stats">
        <?php
        function replaceBlank($str) {
          return str_replace (' ', '_', $str);
        }

        function pathToFlag($str) {
          return str_replace("'", '_', replaceBlank($str));
        }
        $cpt = 0;
        ?>
        <div class="pays container mt-2">
        	<p class="text-start pt-2 text-secondary d-none d-lg-block">Les <?php echo number_format($nb, 0, '', ' '); ?> décisions par pays :</p>
          <?php
            $allPays = $pays;
            $sliptPays = array_chunk($allPays,ceil(count($pays)/3));
          ?>
          <div class="row">
            <?php for($i=0 ; $i < 3; $i++): ?>
                <div class="col-lg-4"><ul class="list-unstyled list-group">

                <?php foreach($sliptPays[$i] as $p):
                  $pays = str_replace(' ', '_', $p['key'][0]);
                  $pays_nom = $p['key'][0].' ('.number_format($p['value'], 0, '', ' ').')';
                  $link = link_to($pays_nom,'recherche/search?query=+&facets=facet_pays:'.$pays);
                  if (strlen($pays_nom) > 45) {
                    $pays_nom_min = substr($pays_nom, 0, 16) . '...';
                    $link = link_to($pays_nom_min,'recherche/search?query=&facets=facet_pays:'.$pays);
                  }
                ?>
                  <li class="d-none d-lg-block list-group-item"> <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents(sfConfig::get('sf_web_dir').'/images/drapeaux/'.pathToFlag($pays).'.png')) ?>" alt ="<?php echo $pays ?>" />&nbsp;<?php echo $link ?></li>
                <?php endforeach ?>
                </ul></div>
            <?php endfor ?>
        </div>

              <div class="d-lg-none mt-3 p-3 pb-2 bg-light border rounded " style="opacity: 0.75">

                <select id="selected-pays"class="form-select text-muted" id="inputGroupSelect04" aria-label="Example select with button addon">
                  <option selected>Rechercher par pays</option>
                  <?php
                  foreach($allPays as $p){
                    $pays = preg_replace('/ /', '_', $p['key'][0]);
                    $pays_nom = $p['key'][0].' ('.number_format($p['value'], 0, '', ' ').')';
                    $link = 'recherche/+/facet_pays:'.$pays;
                    echo('<option id="'.$p['key'][0].'"data-test ="'.$link.'">'.ClientArret::TAB_DRAPEAU[$p['key'][0]]." ".$p['key'][0]." (".$p['value'].") ".'</option>');
                  }
                  ?>
                </select>
                <div class="clearfix mt-2">
                <a class="d-lg-none btn btn-link float-end" href="/recherche_avancee">Recherche avancée</a>
                </div>
              </div>
          <div class="text-center mt-3 p-3">
            <a class="d-none d-sm-none d-lg-block" href="/documentation/stats/statuts.php">Voir toutes les statistiques</a>
          </div>
        </div>
    </div>
    <hr class="d-none d-sm-none d-lg-block">
    <div class="text-center d-none d-lg-block">
        
    <?php include_partial('recherche/actualites'); ?>
    </div>
</div>


<script>
    (function() {
        document.getElementById('selected-pays').addEventListener('change', function(){
            link=$("#selected-pays :selected").data("test");
            window.location.replace(link);
        });
    })();
</script>