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
<div class="col-sm-3">
  <a href="http://www.juricaf.org">Accueil</a> > <a href="<?php echo $sf_request->getUri() ?>">Recherche</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="<?php echo $sf_request->getUri().'?format=rss'; ?>"><img src="/images/rss_mini.png" alt="RSS" title="Flux RSS" /></a>
	<div class="affinercols ">
		<ul class="list-unstyled">
			<li>
				<p class="font-weight-bold">Termes de la recherche :</p>
				<p class="recherche_terme"><?php echo remplacequery($query); ?></p>
			</li>
			<li>
				<p class="font-weight-bold">Tri :</p>
				<ul class="ul-sans-point">
<?php
//  Suppression des options
$myfacetslink = preg_replace('/^,/', '', $facetslink);
$currentlink = array('module'=>'recherche', 'action'=>'search', 'query' => $query, 'facets'=>$myfacetslink);
if (count($facetsset)) {
  $myfacetslink = preg_replace('/^,/', '', $facetslink);
  $noorderlink = $currentlink;
  $noorderlink['facets'] = preg_replace('/^,/', '', preg_replace('/,$/', '', preg_replace('/order:[^:,]+,?/', '', $myfacetslink)));

  foreach($facetsset as $f) {
    if (!preg_match('/order:/', $f)) {
      $text = preg_replace('/_/', ' ', preg_replace('/[^:]+:/', '', $f));
      $tmplink = $currentlink;
      $tmplink['facets'] = preg_replace('/^,/', '', preg_replace('/,$/', '', preg_replace('/'.preg_replace('/\|/', '\\\|', $f).',?/', '', $myfacetslink)));
      echo link_to('<li><img src="/images/annuler.png" alt="Annuler" title="Annuler" />Résultats filtrés sur <em>'.$text.'</em></li>', $tmplink);
    }
    else {
      if (preg_match('/order:perti/', $f)) {
        echo link_to('<li><img src="/images/annuler.png" alt="Annuler" title="Annuler" />Résultats trié par pertinence</li>', $noorderlink);
      }
	  if (preg_match('/order:antéchronologique/', $f)) {
        echo link_to('<li><img src="/images/annuler.png" alt="Annuler" title="Annuler" />Résultats trié dans l\'ordre antechronologique</li>', $noorderlink);
      }
      else if (preg_match('/order:chrono/', $f)) {
        echo link_to('<li><img src="/images/annuler.png" alt="Annuler" title="Annuler" />Résultats trié dans l\'ordre chronologique</li>', $noorderlink);
      }
    }
?>
</ul>
<ul  class="ul-sans-point">
<?php
}
// Metadonnées
foreach($facetsset as $facet) {
  if (preg_match('/^facet_pays_juridiction:/', $facet)) {
    $title_facet['pays_juri'] = replaceUnderscore(str_replace('facet_pays_juridiction:', '', $facet));
  }
  if (preg_match('/^facet_pays:/', $facet)) {
    $title_facet['pays'] = replaceUnderscore(str_replace('facet_pays:', '', $facet));
  }
  if(preg_match('/^facet_juridiction:/', $facet)) {
    $title_facet['juri'] = replaceUnderscore(str_replace('facet_juridiction:', '', $facet));
  }
}

if(isset($title_facet['pays_juri'])) {
  $title_facet = $title_facet['pays_juri'];
}
elseif(isset($title_facet['pays']) && isset($title_facet['juri'])) {
  $title_facet = $title_facet['pays'].' | '.$title_facet['juri'];
}
else {
  if(isset($title_facet['juri'])) { $title_facet = $title_facet['juri']; }
  elseif(isset($title_facet['pays'])) { $title_facet = $title_facet['pays']; }
}

}

if (trim($query) !== '' || isset($title_facet)) {
  $pays_noindex = array(Guinée
    // "Guinée", Pays non indexés
  );

  if(isset($title_facet) && trim($query) == '') {
    $title = 'Jurisprudences '.$title_facet.'';
    $description = $resultats->response->numFound.' arrêts publiés dans la base de données';
      }
  if(isset($title_facet) && trim($query) !== '') {
    $title = 'Jurisprudences '.remplacequerytitre($query).' - '.$title_facet.'';
    $description = $resultats->response->numFound.' arrêts publiés dans la base de données';
      }
  if(isset($title_facet)) {
    foreach ($pays_noindex as $noindex) {
      if(strpos($title_facet, $noindex) !== true) { $sf_response->addMeta('robots', 'noarchive', false, false, false); }
    }
  }
  if(!isset($title_facet) && trim($query) !== '') {
    $title = 'Jurisprudences '.remplacequerytitre($query).'';
    $description = $resultats->response->numFound.' arrêts publiés dans la base de données';
      }
  slot("metadata");
  include_partial("metadata", array('url_flux' => $sf_request->getUri().'?format=rss', 'titre_flux' => "S'abonner à cette recherche"));
  end_slot();
  $sf_response->setTitle($title);
  $sf_response->addMeta('description', $description);
  $sf_response->addMeta('keywords', $keywords);
}

//  Gestion des facettes

if ($resultats->response->numFound !== 0) {
  if (!preg_match('/order:/', $facetslink)) {
    echo '';
  }
  else {
    echo '<li>'.link_to('antéchronologique', $noorderlink).'</li>';
  }

  if (preg_match('/order:chrono/', $facetslink)) {
    echo '';
  }
  else {
    $tmplink = $currentlink;
    $tmplink['facets'] = 'order:chrono'.preg_replace('/,?order:[a-z]*,?/', '', $facetslink);
    echo '<li>'.link_to('chronologique', $tmplink, array('rel'  => 'nofollow')).'</li>';
  }

  if (preg_match('/order:pertinence/', $facetslink)) {
    echo '';
  }
  else {
    $tmplink = $currentlink;
    $tmplink['facets'] = 'order:pertinence'.preg_replace('/,?order:[a-z]*,?/', '', $facetslink);
    echo '<li>'.link_to('par pertinence', $tmplink, array('rel'  => 'nofollow')).'</li>';
  } ?>

</ul>
</li>
<?php
if(isset($nobots)) { $sf_response->addMeta('robots', 'noindex, nofollow', false, false, false); }
include_component('recherche', 'facets', array('label'=>'Pays &amp; Juridiction', 'id'=>'facet_pays_juridiction', 'facets' => $facets, 'query'=>$query, 'facetslink'=>$facetslink, 'tree' => true, 'mainid' => 'facet_pays'));
?>

</ul>

<?php
}
// Affichage des résultats

?>

</div>
</div>
<div class="text-justify">
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
