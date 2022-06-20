<?php
$xmlfile_arret_metas = $argv[1];

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
$arretSource .= '.0002.05/DOC_1'; // Version FR du texte de l'arret
$arretTxt = file_get_contents($arretSource);
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
if ($titre) {
  $tabTitre = explode("#", (string)$titre[0]);
  $arretType = substr($tabTitre[0], 0, strpos($tabTitre[0], ' '));
  $posDeb = strpos($tabTitre[0], '(');
  $posFin = strpos($tabTitre[0], ')');
  $formation = ($posDeb && $posFin)? ucfirst(substr($tabTitre[0], $posDeb+1, $posFin-$posDeb-1)) : null;
}
?><?xml version="1.0" encoding="utf8"?>
<DOCUMENT>
<DATE_ARRET><?php echo $date ; ?></DATE_ARRET>
<FORMATION><?php echo $formation ?></FORMATION>
<JURIDICTION>Cour de justice de l'Union européenne</JURIDICTION>
<NUM_ARRET><?php echo $arretId; ?></NUM_ARRET>
<PAYS>Luxembourg</PAYS>
<TEXTE_ARRET><![CDATA[
   <?php echo $arretTxt; ?>
]]>
</TEXTE_ARRET>
<TITRE>CJUE, <?php echo  $formation ; ?>, <?php echo $dateFr; ?>, <?php echo $arretId; ?></TITRE>
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
<SOURCE><?php echo $arretSource ?></SOURCE>
</DOCUMENT>
