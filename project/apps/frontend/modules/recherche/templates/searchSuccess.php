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
<div class="recherche container">
<div class="row">
<div>
<?php
/* POUR LA PAGINATION */
  $myfacetslink = preg_replace('/^,/', '', $facetslink);

  $currentlink = array('module'=>'recherche', 'action'=>'search', 'query' => $query, 'facets'=>$myfacetslink,'tri'=>$filtre_tri,'pays'=>$filtre_pays, 'juridiction' => $filtre_juridiction);
?>


</div>
</div>

<!-- BLOC FILTRES  -->
<hr class="mt-3 d-none d-lg-block">
<hr class="d-lg-none">

<div class="d-lg-none">
<div class="float-end">
<span><a id="open-filters" class="btn btn-sm  btn-outline-secondary" data-bs-toggle="collapse" href="#bloc-filtres" role="button" aria-expanded="false" aria-controls="bloc-filtres" title="Filtrer"><i class="bi bi-filter"></i></a></span>
</div>
<p>
  <small>Résultats par
    <span>
    <?php
    if($filtre_tri == "ASD"){
      echo "plus ancien";
    }
    elseif ($filtre_tri == "DESC") {
      echo "plus récent";
    }
    else{
      echo "pertinence";
    }
    ?>
  </span>
</small>
</p>
  <?php
  if(!$filtre_pays && !$filtre_juridiction){
    echo "<p><small>Aucun filtre appliqué</small></p>";
  }
  else{
    echo "<p><small>Filtrés par : <span class='filtres'>".implode(" / ",array_filter(array($filtre_pays, $filtre_juridiction)))."</span></small></p>";
  }
  ?>
</p>
</div>
<form method="get" action="<?php echo url_for('@recherche_filtres?query='.$filtre_query); ?>">
<div id="bloc-filtres" class="row g-3 align-items-center">
  <div class="col-lg-auto col-md-2 d-none d-lg-block">
    <label class="col-form-label">Tri :</label>
  </div>
  <div class="col-lg-auto col-md-12 col-sm-12">
    <select name="tri" class="form-select form-control">
      <option value="DESC"
      <?php
        if(!$filtre_tri || ($filtre_tri && $filtre_tri == "DESC"))
          echo('selected');
      ?>
      >Plus récent</option>
      <option value="ASD"
      <?php
        if($filtre_tri && $filtre_tri == "ASD")
          echo('selected');
      ?>
      >Plus ancien</option>
      <option value="pertinence"
      <?php
        if($filtre_tri && $filtre_tri == "pertinence")
          echo('selected');
      ?>
      >Par pertinence</option>
    </select>  </div>
  <?php if(!preg_match("/facet_pays:/", $filtre_query)){
  ?>
  <div class="col-lg-auto col-md-2 d-none d-lg-block">
    <label class="col-form-label">Pays :</label>
  </div>
  <div class="col-lg-auto col-md-12 col-sm-12">
    <?php if($filtre_pays || $filtre_juridiction){
      echo('<div class="form-inline input-group">
          <input  class="form-control mx-auto" type="search" name="pays" value="');
        echo $filtre_pays;
        echo('" readonly></input><a class="btn btn-light" href="'.url_for('@recherche_resultats?query='.$query).'""><i class="bi bi-x-circle"></i></a></div>');
        }
    else{
    ?>
      <select id="pays_filter" name="pays" class="form-select">
        <option value="">Tous les pays</option>
        <?php foreach($facets["facet_pays"] as $pays=>$num){
          echo("<option value=".preg_replace('/ /', '_', $pays).">".ClientArret::TAB_DRAPEAU[$pays]." ".$pays.'&nbsp;('.number_format($num, 0, '', ' ').")</option>");
        } ?>
      </select>
    <?php } ?>
  </div>

<?php }?>
  <div class="col-lg-auto col-md-2 d-none d-lg-block">
    <label class="col-form-label">Juridiction :</label>
  </div>
  <div class="col-lg-auto col-md-12 col-sm-12">

    <?php if($filtre_juridiction){
      echo('<div class="input-group">
          <input class="form-control g3" type="text" name="juridiction" size="45"
          value = "'.trim(preg_replace("/.+\|/",'',$filtre_juridiction)).'"
          readonly>
          </input>
          <a class="btn btn-light" href="'.url_for('@recherche_resultats?query='.$query.'&facets=facet_pays:'.urlencode($filtre_pays)).'"">
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
            echo('<option data-pays='.$py.' value="'.trim($juridiction).'">'.$juridiction."&nbsp;(".number_format($num, 0, ',', ' ').")</option>");
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

<hr>
<div>
    <p class="text-center"><?php if($nbResultats > 0): ?><?php echo $nbResultats;?> résultats<?php else: ?>Aucun résultat trouvé<?php endif; ?> <a href="<?php echo $sf_request->getUri().'?format=rss'; ?>" class="text-muted float-end"><i class="bi bi-rss"></i></a></p>
<?php
foreach ($resultats->response->docs as $resultat) {
 ?>
  <div class="card mb-3">

  <?php
  $pathToFlag = pathToFlag($resultat->pays);
  $urlForArret = url_for('@arret?id='.$resultat->id);
  $textArret = JuricafArret::getExcerpt($resultat, $resultats->highlighting->{$resultat->id});
  ?>

  <p class="card-header fs-5"><img src="/images/drapeaux/<?php echo $pathToFlag ?>.png" alt="§" /> | <a class="a-unstyled" href="<?php echo $urlForArret ?>"><?php echo $resultat->titre ?></a></p>

  <div class="card-body" data-link=<?php echo($urlForArret);?>>
    <p class="card-text text-justify"> <?php echo($textArret); ?></p>
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
