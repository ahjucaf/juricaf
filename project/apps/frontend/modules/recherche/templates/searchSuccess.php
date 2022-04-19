<?php if ($json == false) {
use_helper('Text');

$nbResultats = number_format($resultats->response->numFound, 0, ',', ' ');

function replaceBlank($str) {
  return str_replace (' ', '_', $str);
}

function replaceUnderscore($str) {
  return str_replace ('_', ' ', $str);
}

function pathToFlag($str) {
  return urlencode(str_replace("'", '_', replaceBlank($str)));
}

function remplacequery($string) {
  $table = array(
   'analyses:' => '<br><span itemprop="title">Analyse: ',
   'type_recours:' => '<br><span itemprop="title">Type de recours: ',
   'references:' => '<br><span itemprop="title">Références: ',
   'president:' => '<br><span itemprop="title">Président: ',
   'rapporteur:' => '<br><span itemprop="title">Rapporteur ',
   'commissaire_gvt:' => '<br><span itemprop="title">Rapporteur public: ',
   'avocat_gl:' => '<br><span itemprop="title">Avocat général: ',
   'texte_arret:' => '<br><span itemprop="title">Recherche : ',
   'fonds_documentaire:' => '<br><span itemprop="title">Fonds documentaire: ',
   'avocats:' => '<br><span itemprop="title">Avocat: ',
   'decisions_attaquees:' => '<br><span itemprop="title">Juridiction attaquée: ',
   'sens_arret:' => '<br><span itemprop="title">Sens :',
   'saisines:' => '<br><span itemprop="title">Saisine: ',
   'ecli:' => '<span itemprop="title">ECLI: ',
   'nor:' => '<span itemprop="title">NOR: ',
   'type_affaire:' => 'Type d\'affaire: ',
   '(premier avocat general)' => '',
   '(president)' => '',
      '"' => ''
   );
  return strtr($string, $table);
}

function remplacequerytitre($string) {
  $table = array(
   'analyses:' => 'avec l\'analyse ',
   'type_recours:' => 'avec pour type de recours ',
   'references:' => 'avec les références ',
   'president:' => 'dont les audiences ont été présidées par ',
   'rapporteur:' => 'qui ont été rapportées par ',
   'commissaire_gvt:' => ' avec pour le commissaire du gouvernement ',
   'avocat_gl:' => 'avec pour l\'avocat général ',
   'fonds_documentaire:' => 'issues du fonds documentaire ',
   'avocats:' => 'avec pour avocat ',
   'decisions_attaquees:' => 'ayant fait l\'objet d\'un pouvoi en cassation ',
   'sens_arret:' => 'ayant pour sens ',
   'saisines:' => 'ayant été saisis par ',
   'ecli:' => 'ayant comme numéro ECLI ',
   'nor:' => 'ayant comme numéro NOR ',
   'type_affaire:' => 'ayant pour type d\'affaire ',
	'(president)' => '',
	'(premier avocat general)' => '',
	'"' => ''
   );
  return strtr($string, $table);
}

?>
<div class="recherche container mt-5">
<div class="row">
<div>
<?php
/* POUR LA PAGINATION */
  $myfacetslink = preg_replace('/^,/', '', $facetslink);

  $currentlink = array('module'=>'recherche', 'action'=>'search', 'query' => $query, 'facets'=>$myfacetslink,'tri'=>$sf_request->getParameter('tri'),'pays'=>$sf_request->getParameter('pays'), 'juridiction' => $sf_request->getParameter('juridiction'));
?>


</div>
</div>

<!-- BLOC FILTRES  -->

<p class="d-lg-none">Filtrés sur :
  <?php
  $tabFiltres = array($sf_request->getParameter('tri'),$sf_request->getParameter('pays'),$sf_request->getParameter('juridiction'));
  echo "<span class='fst-italic'>".implode(" / ",array_filter($tabFiltres))."<span>";
  ?>
  <span class="float-end"><a id="open-filters" class="btn btn-sm d-lg-none btn-outline-primary" data-bs-toggle="collapse" href="#bloc-filtres" role="button" aria-expanded="false" aria-controls="bloc-filtres" title="Filtrer"><i class="bi bi-filter"></i></a></span>
</p>

<form method="get" action="<?php echo url_for('recherche')."/".$sf_request->getParameter('query')?>">
<div id="bloc-filtres" class="row g-3 mt-5 align-items-center" >
  <div class="col-lg-auto col-md-2 d-none d-lg-block">
    <label class="col-form-label">Tri :</label>
  </div>
  <div class="col-lg-auto col-md-12 col-sm-12">
    <select name="tri" class="form-select form-control">
      <option value="DESC"
      <?php
        if(!$sf_request->getParameter('tri') || ($sf_request->getParameter('tri') && $sf_request->getParameter('tri') == "DESC"))
          echo('selected');
      ?>
      >Plus récent</option>
      <option value="ASD"
      <?php
        if($sf_request->getParameter('tri') && $sf_request->getParameter('tri')== "ASD")
          echo('selected');
      ?>
      >Plus ancien</option>
      <option value="pertinence"
      <?php
        if($sf_request->getParameter('tri') && $sf_request->getParameter('tri')== "pertinence")
          echo('selected');
      ?>
      >Par pertinence</option>
    </select>  </div>
  <?php if(!preg_match("/facet_pays:/",$sf_request->getParameter('query'))){
  ?>
  <div class="col-lg-auto col-md-2 d-none d-lg-block">
    <label class="col-form-label">Pays :</label>
  </div>
  <div class="col-lg-auto col-md-12 col-sm-12">

    <?php if($sf_request->getParameter('pays') || $sf_request->getParameter('juridiction')){
      echo('<div class="form-inline input-group">
          <input  class="form-control mx-auto" type="search" name="pays" value = ');
        echo $sf_request->getParameter('pays');
      echo(' readonly></input><a class="btn btn-light" onclick="deletePaysfilter()"><i class="bi bi-x-circle"></i></a></div>');
        }
    else{
    ?>
      <select id="pays_filter" name="pays" class="form-select">
        <option value="">Tous les pays</option>
        <?php foreach($facets["facet_pays"] as $pays=>$num){
          echo("<option value=".preg_replace('/ /', '_', $pays).">".ClientArret::TAB_DRAPEAU[$pays]." ".$pays.'('.$num.")</option>");
        } ?>
      </select>
    <?php } ?>
  </div>

<?php }?>
  <div class="col-lg-auto col-md-2 d-none d-lg-block">
    <label class="col-form-label">Juridiction :</label>
  </div>
  <div class="col-lg-auto col-md-12 col-sm-12">

    <?php if($sf_request->getParameter('juridiction')){
      echo('<div class="input-group">
          <input class="form-control g3" type="text" name="juridiction" size="45"
          value = "'.trim(preg_replace("/.+\|/",'',$sf_request->getParameter('juridiction'))).'"
          readonly>
          </input>
          <a class="btn btn-light" onclick="deleteJuridictionfilter()">
            <i class="bi bi-x-circle"></i>
          </a>
          </div>');
        }
    else{?>
    <select id="juridiction" name="juridiction" class="form-select">
      <option value="">Toutes les juridictions</option>
      <?php
        $tab = $facets["facet_pays_juridiction"];
        $pays = array();
        foreach($tab as $k=>$v){
          $pays[explode("|",$k)[0]][explode("|",$k)[1]] = $v;
        }
        foreach($pays as $p => $j){
          $py = preg_replace('/ /', '_',trim($p));
          echo('<optgroup label="'.ClientArret::TAB_DRAPEAU[$py]." ".$p.'">');
            foreach($j as $juridiction => $num){
            echo('<option data-pays='.$py.' value="'.trim($juridiction).'">'.$juridiction."(".$num.")</option>");
          }
          echo("</optgroup>");
        }
      ?>
    </select>
  <?php } ?>
  </div>
  <div class="col-lg-auto">
    <button  id="filtrer" type="submit"class="btn btn-outline-secondary">Filtrer</button>
  </div>
</div>
</form>
<br>
<hr>
<div class="text-justify">
    <div class="row justify-content-between">
      <div class="col-4">
        <a href="<?php echo $sf_request->getUri().'?format=rss'; ?>"><img src="/images/rss_mini.png" alt="RSS" title="Flux RSS" /></a>
      </div>
      <div class="col-4">
        <p class="text-end"><?php echo $nbResultats;?> résultats</p>
      </div>
    </div>
<?php
foreach ($resultats->response->docs as $resultat) {
 ?>
  <div class="card mb-3">

  <?php
  $pathToFlag = pathToFlag($resultat->pays);
  $urlForArret = url_for('@arret?id='.$resultat->id);
  $textArret = JuricafArret::getExcerpt($resultat, $resultats->highlighting->{$resultat->id});
  ?>

  <p class="card-header fs-5"><?php echo('<img src="/images/drapeaux/'.$pathToFlag .'.png" alt="§" /> | <a class="a-unstyled " href="'.$urlForArret.'">'.$resultat->titre);?></a></p>

  <div class="card-body" data-link=<?php echo($urlForArret);?>>
    <p class="card-text"> <?php echo($textArret); ?></p>
  </div>

  <?php
    $card_footer = $resultat->pays. " | ". date('d/m/Y', strtotime($resultat->date_arret));
    if($resultat->formation){
      $card_footer.=" | ".$resultat->formation;
    }
  ?>
  <small class="card-header text-muted"> <?php echo($card_footer);?> </small>
</div>
<?php
}
?>
	<div class="navigation">
	   <?php
      if ($resultats->response->numFound > 10) {
       echo include_partial('pager', array('pager' => $pager, 'currentlink' => $currentlink));
     }
      ?>
	</div>
</div>

<div style="clear:both;">&nbsp;</div>
</div>
</div>
<script type="text/javascript">
<!--
resultats = $('.resultats').css('height');
resultats = parseInt(resultats.substring(0,(resultats).length-2));
facets = $('.facets').css('height');
facets = parseInt(facets.substring(0,(facets).length-2));
if(facets > resultats) {
  $('.facets').css('height', resultats+'px');
  $('.facets').css('overflow', 'auto');
}
// -->
</script>
<?php }

else { // JSON

  $nbResultats = $resultats->response->numFound;

// Ouverture de l'objet JSON
 echo '{ ';

 echo "\"nb_resultat\" : $nbResultats, \"docs\" : [ ";

 // Gestion de la derniere boucle
 $total = count($resultats->response->docs);
 $i = 0;

 foreach ($resultats->response->docs as $resultat){
	echo '{ ';
		echo '"id" : "' . $resultat->id . '", ';
		echo '"pays" : "' . $resultat->pays . '", ';
		echo '"titre" : "' . $resultat->titre . '", ';
		echo '"formation" : "' . $resultat->formation . '", ';
		echo '"date_arret" : "' . date('d/m/Y', strtotime($resultat->date_arret)) . '", ';
		echo '"juridiction" : "' . $resultat->juridiction . '"';
		/*if (isset($resultats->highlighting))
			echo '"highlighting" : "' . JuricafArret::getExcerpt($resultat, $resultats->highlighting->{$resultat->id}) . '"';
		else
			echo '"highlighting" : "' . JuricafArret::getExcerpt($resultat) . '"';*/

	echo ' }';
	// Gestion de la derniere boucle
	$i++;
	if ($i < $total)
		echo ", ";
}

 // Fermeture de l'objet JSON
echo ' ] }';


} ?>
