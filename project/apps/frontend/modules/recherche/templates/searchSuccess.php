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
  $currentlink = array('module'=>'recherche', 'action'=>'search', 'query' => $query, 'facets'=>$myfacetslink);
?>


</div>
</div>
<hr>
<form method="get" action="<?php echo url_for('recherche')."/".$sf_request->getParameter('query')?>">
<div class="row g-3 align-items-center">
  <div class="col-auto m-2">
    <label>Tri : </label>
  </div>
  <div class="col-auto m-2">
    <select name="tri" class="form-select" aria-label="Default select example">
      <option value="antéchronologique"
      <?php
        if(!$GET['tri'] || ($_GET['tri'] && $_GET['tri'] == "antéchronologique"))
          echo('selected');
      ?>
      >Plus récent au plus ancien</option>
      <option value="chronologique"
      <?php
        if($_GET['tri'] && $_GET['tri']== "chronologique")
          echo('selected');
      ?>
      >Plus ancien au plus récent</option>
      <option value="pertinence"
      <?php
        if($_GET['tri'] && $_GET['tri']== "pertinence")
          echo('selected');
      ?>
      >Par pertinence</option>
    </select>
  </div>
<?php if(!preg_match("/facet_pays:/",$sf_request->getParameter('query'))){
?>
  <div class="col-auto m-2">
    <label>Pays : </label>
  </div>
<div class="col-auto m-2">
  <?php if($_GET['pays']){
    echo('<div class="form-inline input-group">
        <input id="pays_filter"class="form-control mx-auto" type="search" name="pays" value = '.$_GET['pays'].' readonly>
        </input>
        <a class="btn btn-light" onclick="deletePaysfilter()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
              <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
            </svg></a>
        </div>');
      }
  else{
  ?>
    <select name="pays" class="form-select" aria-label="Default select example">
      <option value="">Tous</option>
      <?php foreach($facets["facet_pays"] as $pays=>$num){
        echo("<option value=".preg_replace('/ /', '_', $pays).">".$pays.'('.$num.")</option>");
      } ?>
    </select>
  <?php } ?>
</div>
<?php } ?>
  <div class="col-auto m-2">
    <button type="submit"class="btn btn-primary">Filtrer</button>
  </div>
  </div>
</form>

<hr>
<div class="text-justify">
  <a href="<?php echo $sf_request->getUri().'?format=rss'; ?>"><img src="/images/rss_mini.png" alt="RSS" title="Flux RSS" /></a>
	<p class="text-center"><?php echo $nbResultats;?> résultats</p>

<?php
foreach ($resultats->response->docs as $resultat) {
 ?>
  <div class="card mb-3">
  <p class="card-header fs-5"> <?php echo('<img src="/images/drapeaux/'.pathToFlag($resultat->pays).'.png" alt="§" /> | <a class="a-unstyled " href="'.url_for('@arret?id='.$resultat->id).'">'.$resultat->juridiction.' '.$resultat->formation);?> </a></p>
  <div class="card-body" style="background-color:white">
    <p class="card-text"> <?php echo JuricafArret::getExcerpt($resultat, $resultats->highlighting->{$resultat->id});?></p>
    <a class="float-end" href="<?php echo(url_for('@arret?id='.$resultat->id));?>">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
      </svg>
  </a>
  </div>
  <small class="card-header text-muted"> <?php echo(date('d/m/Y', strtotime($resultat->date_arret)) . ' | '. strtoupper($resultat->pays) . ' | N°'.$resultat->num_arret);?> </small>
</div>
<?php
}
?>
	<div class="navigation">

	   <?php if ($resultats->response->numFound > 10) {
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

 foreach ($resultats->response->docs as $resultat) {
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
