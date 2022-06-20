<?php
$xmlfile_arret_metas = $argv[1];
$xmlfile_arret_txt = $argv[2];

$obj = new SimpleXMLElement(@file_get_contents($xmlfile_arret_metas));

if (!$obj) {
    fwrite(STDERR, "ERREUR: CJUE / xml non trouvé : $xmlfile_arret_metas\n");
    exit(1);
}
$date = (string)$obj->WORK->DATE_DOCUMENT->VALUE;
if (!$date) {
      fwrite(STDERR, "ERREUR: CJUE / WORK->DATE_DOCUMENT->VALUE non définie\n");
      exit(1);
}
$dateFr = date('d/m/Y', strtotime($date));
$arretId = $obj->xpath('//TYPE[. ="case"]/parent::*/IDENTIFIER');
if (!$arretId) {
      fwrite(STDERR, "ERREUR: CJUE / TYPE=case non définie\n");
      exit(1);
}
if (count($arretId) > 0) {
  $arretId = (string)$arretId[0];
}
$arretSource = (string)$obj->WORK->URI->VALUE;
$arretTxt = null;
if (!$arretSource) {
      fwrite(STDERR, "ERREUR: CJUE / WORK->URI->VALUE non définie\n");
      exit(1);
}

$arretTxt = file_get_contents($xmlfile_arret_txt);
if (!$arretTxt) {
      fwrite(STDERR, "ERREUR: CJUE / Arret texte non récupéré\n");
      exit(1);
}
$arretTxt = strip_tags($arretTxt);
$demandeur = null;
$defenseur = null;
$parties = $obj->xpath('//PARTIES/VALUE');
if ($parties) {
  $parties = (string)$parties[0];
  $tabParties = (strpos($parties, 'contre') !== false)? explode(' contre ', $parties) : explode(' v ', $parties);
  if (count($tabParties) == 2) {
    $demandeur = $tabParties[0];
    $defenseur = $tabParties[1];
  }
}
$titre = $obj->xpath('//EXPRESSION_TITLE/LANG[. ="fr"]/parent::*/VALUE');
$formation = null;
$arretType = null;
$arretAnalyses = array();
if ($titre) {
  $tabTitre = explode("#", (string)$titre[0]);
  $arretType = substr($tabTitre[0], 0, strpos($tabTitre[0], ' '));
  $arretTitre = substr($tabTitre[0], 0, strpos($tabTitre[0], ' ('));
  $posDeb = strpos($tabTitre[0], '(');
  $posFin = strpos($tabTitre[0], ')');
  $arretFormation = ($posDeb && $posFin)? ucfirst(substr($tabTitre[0], $posDeb+1, $posFin-$posDeb-1)) : null;
  $index = 2;
  while ($index < (count($tabTitre) - 1)) {
    $analyses[] = $tabTitre[$index];
    $index++;
  }
}
$arretTypeAffaire = null;
$arretTypeRecours = null;
if ($arretTypes = $obj->xpath('//CASE-LAW_HAS_TYPE_PROCEDURE_CONCEPT_TYPE_PROCEDURE/IDENTIFIER/parent::*/PREFLABEL')) {
  $arretTypeAffaire = (string)$arretTypes[0];
  if (isset($arretTypes[1])) {
    $arretTypeRecours = (string)$arretTypes[1];
  }
}
$avocats = array();
if ($avocatItems = $obj->xpath('//CASE-LAW_DELIVERED_BY_ADVOCATE-GENERAL//TYPE[. ="agent"]/parent::*/IDENTIFIER')) {
  foreach($avocatItems as $avocatItem) {
    $avocats[] = (string)$avocatItem;
  }
}
$rapporteurs = array();
if ($rapporteurItems = $obj->xpath('//CASE-LAW_DELIVERED_BY_JUDGE//TYPE[. ="agent"]/parent::*/IDENTIFIER')) {
  foreach($rapporteurItems as $rapporteurItem) {
    $rapporteurs[] = (string)$rapporteurItem;
  }
}
?><?xml version="1.0" encoding="utf8"?>
<DOCUMENT>
<DATE_ARRET><?php echo $date ; ?></DATE_ARRET>
<FORMATION><?php echo $arretFormation ?></FORMATION>
<JURIDICTION>Cour de justice de l'Union européenne</JURIDICTION>
<NUM_ARRET><?php echo $arretId; ?></NUM_ARRET>
<PAYS>CJUE</PAYS>
<TEXTE_ARRET><![CDATA[
   <?php echo $arretTxt; ?>
]]>
</TEXTE_ARRET>
<TITRE>CJUE, <?php echo  $arretTitre ; ?>, <?php echo $parties ?>, <?php echo $dateFr; ?>, <?php echo $arretId; ?></TITRE>
<FONDS_DOCUMENTAIRE>http://publications.europa.eu</FONDS_DOCUMENTAIRE>
<PARTIES>
<?php if ($demandeur): ?>
    <DEMANDEURS>
        <DEMANDEUR><?php echo $demandeur; ?></DEMANDEUR>
    </DEMANDEURS>
<?php endif; ?>
<?php if ($defenseur): ?>
    <DEFENDEURS>
        <DEFENDEUR><?php echo $defenseur ; ?></DEFENDEUR>
    </DEFENDEURS>
<?php endif; ?>
</PARTIES>
<TYPE><?php echo $arretType; ?></TYPE>
<?php if ($arretTypeAffaire): ?>
<TYPE_AFFAIRE><?php echo $arretTypeAffaire; ?></TYPE_AFFAIRE>
<?php endif; ?>
<?php if ($arretTypeRecours): ?>
<TYPE_RECOURS><?php echo $arretTypeRecours; ?></TYPE_RECOURS>
<?php endif; ?>
<?php if ($analyses): ?>
<ANALYSES>
<?php foreach ($analyses as $a): ?>
    <ANALYSE>
        <TITRE_PRINCIPAL><?php echo $a ?></TITRE_PRINCIPAL>
    </ANALYSE>
<?php endforeach; ?>
</ANALYSES>
<?php endif; ?>
<?php if ($avocats): ?>
<AVOCATS><?php echo implode(', ', $avocats); ?></AVOCATS>
<?php endif; ?>
<?php if ($rapporteurs): ?>
<RAPPORTEUR><?php echo implode(', ', $rapporteurs); ?></RAPPORTEUR>
<?php endif; ?>
<SOURCE><?php echo $arretSource ?></SOURCE>
</DOCUMENT>
