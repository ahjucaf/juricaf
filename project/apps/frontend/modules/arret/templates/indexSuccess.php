<?php $sf_response->setTitle(html_entity_decode($document->titre).'- Juricaf.org');

$natureConstit = array("QPC" => "Question prioritaire de constitutionnalité",
                       "DC" => "Contrôle de constitutionnalité des lois ordinaires, lois organiques, des traités, des règlements des Assemblées",
                       "LP" => "Contrôle de constitutionnalité des lois du pays de Nouvelle-Calédonie",
                       "L" => "Déclassements de textes législatifs au rang réglementaire",
                       "FNR" => "Fins de non-recevoir",
                       "LOM" => "Répartitions des compétences entre l'État et certaines collectivités d'outre-mer",
                       "AN" => "Élections à l'Assemblée nationale",
                       "SEN" => "Élections au Sénat",
                       "PDR" => "Élection présidentielle",
                       "REF" => "Référendums",
                       "ELEC" => "Divers élections : observations",
                       "ELECT" => "Divers élections : observations",
                       "D" => "Déchéance de parlementaires",
                       "I" => "Incompatibilité des parlementaires",
                       "AR16" => "Article 16 de la Constitution (pouvoirs exceptionnels du Président de la République)",
                       "NOM" => "Nomination des membres",
                       "RAPP" => "Nomination des rapporteurs-adjoints",
                       "ORGA" => "Décision d'organisation du Conseil constitutionnel",
                       "AUTR" => "Autres décisions");

function printDocument($d) {
  if (!is_object($d) || get_class($d) != 'sfOutputEscaperArrayDecorator') {
    return print_r($d);
  }
  echo "<ul>";
  $d->rewind();
  while ($sd = $d->current()) {
    echo "<li>";
    if (!is_int($d->key()))
      echo "<b>".$d->key()."</b>&nbsp;: ";
    printDocument($sd);
    echo "</li>";
    $d->next();
  }
  echo "</ul>";
}

?>
<div>
<h1><?php echo $document->titre; ?></h1>
<?php
if (isset($document->titre_supplementaire)) {
  echo '<h2>'.$document->titre_supplementaire.'</h2>';
}
?>
<?php
echo '<p>'.preg_replace ('/\n/', '</p><p>', $document->texte_arret).'</p>';
?>
</div>
<div class="extra">
<h3>Extras (affichage brut des champs disponibles)</h3>

<?php
echo extraSub($document);
echo '<pre>';

function decrap($key) {
  $crap = array("value", "storage", "requiredProperties", "modified", "newDocument", "newAttachments", "escapingMethod");
  foreach ($crap as $value) {
    if(strpos($key, $value) !== false) { $key = $value; }
  }
  return $key;
}

function extraSub($field) {
  if (isset($field)) {
    $field = (array)$field;
    echo '<ul>';
    foreach ($field as $key => $value)
    {
      if (is_array($value) || is_object($value)) {
        if(is_object($value)) { $value = (array)$value; } ;
        if (!in_array($key, array('texte_arret', '_attachments', '@attributes', '_rev'))) {
          echo '<li><strong>'.decrap($key).' : </strong>';
          $field[$key] = extraSub($value);
        }
      }
      else {
        if (!in_array($key, array('texte_arret', '_attachments', '@attributes', '_rev'))) {
          echo '<li><strong>'.decrap($key).' : </strong>'.$value.'</li>';
        }
      }
    }
    echo '</ul>';
  }
}
//var_dump($document);
?>
</pre>
</div>
<div class="download">
<?php //echo link_to('Télécharger au format juricaf', '@arretxml?id='.$document->_id); ?>
</div>
