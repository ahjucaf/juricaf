<?php
use_helper('Text');
decorate_with(false);

function replaceUnderscore($str) {
  return str_replace ('_', ' ', $str);
}

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

if (trim($query) !== '' || isset($title_facet)) {

  if(isset($title_facet) && trim($query) == '') {
    $title = 'Juricaf : Collection '.$title_facet;
    $description = 'Les 15 arrêts les plus récents de cette collection (sur un total de '.$resultats->response->numFound.' résultats).';
  }
  if(isset($title_facet) && trim($query) !== '') {
    $title = 'Juricaf : Recherche sur "'.trim($query).'" dans la collection '.$title_facet;
    $description = 'Les 15 arrêts les plus récents correspondants à cette recherche dans cette collection (sur un total de '.$resultats->response->numFound.' résultats)';
  }
  if(!isset($title_facet) && trim($query) !== '') {
    $title = 'Juricaf : Recherche sur "'.trim($query).'"';
    $description = 'Les 15 arrêts les plus récents correspondants à cette recherche dans toutes les collections (sur un total de '.$resultats->response->numFound.' résultats)';
  }
}
echo '<?xml version="1.0" encoding="UTF-8" ?>';
?>
<rss xmlns:dc="http://purl.org/dc/elements/1.1/" version="2.0">
  <channel>
    <title><![CDATA[<?php echo $title; ?>]]></title>
    <link><?php echo $sf_request->getUri(); ?></link>
    <description><![CDATA[<?php echo $description; ?>]]></description>
    <image>
      <url><?php echo 'http://'.$sf_request->getHost().'/images/juricaf.png'; ?></url>
      <title><![CDATA[<?php echo $title; ?>]]></title>
      <link><?php echo $sf_request->getUri(); ?></link>
    </image>
<?php
foreach ($resultats->response->docs as $resultat) { ?>
    <item>
      <title><![CDATA[<?php echo $resultat->titre; ?>]]></title>
      <pubDate><?php echo gmdate('D, j M Y G:i:s T', strtotime($resultat->date_arret)); ?></pubDate>
      <dc:creator><![CDATA[<?php echo $resultat->formation; ?>]]></dc:creator>
      <link><?php echo 'http://'.$sf_request->getHost().url_for('@arret?id='.$resultat->id); ?></link>
      <guid isPermaLink="true"><?php echo 'http://'.$sf_request->getHost().url_for('@arret?id='.$resultat->id); ?></guid>
      <description><![CDATA[<?php echo JuricafArret::getExcerpt($resultat); ?>]]></description>
    </item>
<?php
}
?>
  </channel>
</rss>
