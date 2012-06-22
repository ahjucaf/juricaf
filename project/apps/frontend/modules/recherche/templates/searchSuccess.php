<?php
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
   'analyses:' => '<br>et l\'analyse ', 
   'type_recours:' => '<br>et le type de recours ', 
   'references:' => '<br>et les références ', 
   'president:' => '<br>dont les audiences ont été présidées par ', 
   'rapporteur:' => '<br>rendues par le rapporteur ', 
   'commissaire_gvt:' => '<br>avec pour commissaire du gouvernement ', 
   'avocat_gl:' => '<br>avec pour l\'avocat général ', 
   'fonds_documentaire:' => '<br>issues du fonds documentaire ', 
   'avocats:' => '<br>avec pour avocat ', 
   'decisions_attaquees:' => '<br>et la juridiction faisant l\'objet d\'un pouvoi en cassation ', 
   'sens_arret:' => '<br>ayant pour sens ', 
   'saisines:' => '<br>ayant été saisis par ', 
   'ecli:' => '<br>qui ont comme numéro ECLI ', 
   'nor:' => '<br>qui ont comme numéro NOR ', 
   'type_affaire:' => '<br>et le type d\'affaire ',
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
<div class="recherche">

  <h1><?php echo $nbResultats; ?> résultats
  <?php
  if (preg_match('/[a-z0-9]/i', $query)) { ?>
    sur la recherche  <?php echo remplacequery($query); ?>
    <span class="search_out">
      <a onclick="window.open(this.href); return false;" href="http://www.jurispedia.org/index2.php?lr=lang_fr&amp;cof=FORID%3A11&amp;ie=UTF-8&amp;q=<?php echo urlencode($query); ?>&amp;sa=++%E2%86%92++&amp;cx=010401543614658542221%3A3iznlxhkw1q&amp;siteurl=www.juricaf.org%252F#905"><img src="/images/jurispedia.png" alt="Jurispedia" title="Rechercher sur Jurispedia" /></a>
      <a onclick="window.open(this.href); return false;" href="http://www.savoirsenpartage.auf.org/discipline/9/recherche/?q=<?php echo urlencode($query); ?>"><img src="/images/savoirs_en_partage.png" alt="Savoirs en partage" title="Rechercher sur Savoirs en partage" /></a>
	  <a onclick="window.open(this.href); return false;" href="http://www.lemondedudroit.fr/component/search/?ordering=&searchphrase=all&searchword=<?php echo urlencode($query); ?>"><img src="/images/mdd.png" alt="Le Monde du droit" title="Rechercher sur le Monde du Droit" /></a>	  
      <a href="<?php echo $sf_request->getUri().'?format=rss'; ?>"><img src="/images/rss_mini.png" alt="RSS" title="Flux RSS" /></a>
    </span>
    <?php
  }
  else { ?>
    <span class="search_out">
      <a href="<?php echo $sf_request->getUri().'?format=rss'; ?>"><img src="/images/rss_mini.png" alt="RSS" title="S'abonner au flux RSS des résultats les plus récents pour cette recherche" /></a>
    </span>
    <?php
  }
  ?>
</h1>
<?php
//////////////////
//  Suppression des options
//////////////////
$myfacetslink = preg_replace('/^,/', '', $facetslink);
$currentlink = array('module'=>'recherche', 'action'=>'search', 'query' => $query, 'facets'=>$myfacetslink);
if (count($facetsset)) { ?>
  <div class="options">
  <?php
  $myfacetslink = preg_replace('/^,/', '', $facetslink);
  $noorderlink = $currentlink;
  $noorderlink['facets'] = preg_replace('/^,/', '', preg_replace('/,$/', '', preg_replace('/order:[^:,]+,?/', '', $myfacetslink)));

  foreach($facetsset as $f) { ?>
  <div class="option"><?php
    if (!preg_match('/order:/', $f)) {
      $text = preg_replace('/_/', ' ', preg_replace('/[^:]+:/', '', $f));
      $tmplink = $currentlink;
      $tmplink['facets'] = preg_replace('/^,/', '', preg_replace('/,$/', '', preg_replace('/'.preg_replace('/\|/', '\\\|', $f).',?/', '', $myfacetslink)));
      echo link_to('<img src="/images/annuler.png" alt="Annuler" title="Annuler" />Résultats filtrés sur <em>'.$text.'</em>', $tmplink);
    }
    else {
      if (preg_match('/order:perti/', $f)) {
        echo link_to('<img src="/images/annuler.png" alt="Annuler" title="Annuler" />Résultats trié par pertinence', $noorderlink);
      }
      else if (preg_match('/order:chrono/', $f)) {
        echo link_to('<img src="/images/annuler.png" alt="Annuler" title="Annuler" />Résultats trié dans l\'ordre chronologique', $noorderlink);
      }
    }
  ?>
  </div>
  <?php
  }

//////////////////
// Metadonnées
//////////////////

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
?>
  </div>
<?php
}

if (trim($query) !== '' || isset($title_facet)) {
  $pays_noindex = array(
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
      if(strpos($title_facet, $noindex) !== false) { $sf_response->addMeta('robots', 'noindex', false, false, false); }
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
//////////////////
//  Gestion des facettes
//////////////////
if ($resultats->response->numFound !== 0) {
?>
<div class="facets">
<?php
/////// TRI ////////
?>
<p><strong>Tri</strong></p>
<ul>
  <?php
  if (!preg_match('/order:/', $facetslink)) {
    echo '<li>antéchronologique</li>';
  }
  else {
    echo '<li>'.link_to('antéchronologique', $noorderlink).'</li>';
  }

  if (preg_match('/order:chrono/', $facetslink)) {
    echo '<li>chronologique</li>';
  }
  else {
    $tmplink = $currentlink;
    $tmplink['facets'] = 'order:chrono'.preg_replace('/,?order:[a-z]*,?/', '', $facetslink);
    echo '<li>'.link_to('chronologique', $tmplink, array('rel'  => 'nofollow')).'</li>';
  }

  if (preg_match('/order:pertinence/', $facetslink)) {
    echo '<li>par pertinence</li>';
  }
  else {
    $tmplink = $currentlink;
    $tmplink['facets'] = 'order:pertinence'.preg_replace('/,?order:[a-z]*,?/', '', $facetslink);
    echo '<li>'.link_to('par pertinence', $tmplink, array('rel'  => 'nofollow')).'</li>';
  } ?>
</ul>
<?php
// Eviter le duplicate content
if(isset($nobots)) { $sf_response->addMeta('robots', 'noindex, nofollow', false, false, false); }
  ////// FACETTE Pays //////////
  //include_component('recherche', 'facets', array('label'=>'Pays', 'id'=>'pays', 'facets' => $facets, 'query'=>$query, 'facetslink'=>$facetslink));
  ////// FACETTE Juridiction //////////
  //include_component('recherche', 'facets', array('label'=>'Juridiction', 'id'=>'juridiction', 'facets' => $facets, 'query'=>$query, 'facetslink'=>$facetslink));
include_component('recherche', 'facets', array('label'=>'Pays &amp; Juridiction', 'id'=>'facet_pays_juridiction', 'facets' => $facets, 'query'=>$query, 'facetslink'=>$facetslink, 'tree' => true, 'mainid' => 'facet_pays'));
?>
</div>
<?php
}
  //////////////////////////////////
  /// Affichage des résultats
  //////////////////////////////////
?><div class="resultats">
<div class="pager">
<?php if ($resultats->response->numFound > 10) { echo include_partial('pager', array('pager' => $pager, 'currentlink' => $currentlink)); } ?>
</div>
<?php
foreach ($resultats->response->docs as $resultat) {
  echo '<div class="resultat"><h3><a href="'.url_for('@arret?id='.$resultat->id).'"><img src="/images/drapeaux/'.pathToFlag($resultat->pays).'.png" alt="§" /> '.$resultat->titre.'</a></h3>';
  echo '<p>';
  if (isset($resultats->highlighting))
    echo JuricafArret::getExcerpt($resultat, $resultats->highlighting->{$resultat->id});
  else
    echo JuricafArret::getExcerpt($resultat);
  echo '</p>';
  $formation = '';
  if ($resultat->formation) {
    $formation = ', '.$resultat->formation;
  }
  echo '<div class="extra"><span class="pays '.preg_replace('/ /', '_', $resultat->pays).'">'.$resultat->pays.'</span> - <span class="date">'.date('d/m/Y', strtotime($resultat->date_arret)).'</span> - <span class="juridiction">'.$resultat->juridiction.$formation.'</span>';
  // if(isset($resultat->ecli)) {
  //   echo ' - <span class="num">'.$resultat->ecli.'</span>';
 // } Désactivation temporaire ECLI

  /* en attente
  if(isset($resultat->urnlex)) {
    echo ' - <span class="num">'.$resultat->urnlex.'</span>';
  }
  */
  echo '</div></div>';
}
?>
</div>
<div class="pager">
<?php if ($resultats->response->numFound > 10) { echo include_partial('pager', array('pager' => $pager, 'currentlink' => $currentlink)); } ?>
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
