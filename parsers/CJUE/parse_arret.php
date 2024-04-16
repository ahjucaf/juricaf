<?php
require_once(dirname(__FILE__).'/XmlParserCJUE.php');

$xmlFileContent = file_get_contents($argv[1]);
$txtFileContent = file_get_contents($argv[2]);
$celexId = null;
if (preg_match('/([0-9A-Z]+).xml$/', $argv[1], $matches)) {
  $celexId = $matches[1];
}
$parser = new XmlParserCJUE($celexId, $xmlFileContent, $txtFileContent);
if ($parser->hasErrors()) {
  fwrite(STDERR, "-- Erreurs Fichier $argv[1] : \n");
  fwrite(STDERR, implode("\n", $parser->getErrors())."\n\n");
  exit(1);
}
?><?xml version="1.0" encoding="utf8"?>
<DOCUMENT>
<DATE_ARRET><?php echo $parser->date; ?></DATE_ARRET>
<FORMATION><?php echo $parser->formation ?></FORMATION>
<JURIDICTION><?php echo $parser->juridiction; ?></JURIDICTION>
<NUM_ARRET><?php echo $parser->identifiant; ?></NUM_ARRET>
<PAYS><?php echo $parser->pays; ?></PAYS>
<TEXTE_ARRET><![CDATA[
   <?php echo $parser->getTxtDoc(); ?>
]]>
</TEXTE_ARRET>
<TITRE><?php echo $parser->getTitre(); ?></TITRE>
<FONDS_DOCUMENTAIRE><?php echo $parser->fondsDocumentaire; ?></FONDS_DOCUMENTAIRE>
<?php if($parser->parties): ?>
<PARTIES>
<?php if ($parser->demandeur): ?>
    <DEMANDEURS>
        <DEMANDEUR><?php echo $parser->demandeur; ?></DEMANDEUR>
    </DEMANDEURS>
<?php endif; ?>
<?php if ($parser->defenseur): ?>
    <DEFENDEURS>
        <DEFENDEUR><?php echo $parser->defenseur ; ?></DEFENDEUR>
    </DEFENDEURS>
<?php endif; ?>
</PARTIES>
<?php endif; ?>
<TYPE><?php echo $parser->type; ?></TYPE>
<?php if ($parser->affaire): ?>
<TYPE_AFFAIRE><?php echo $parser->affaire; ?></TYPE_AFFAIRE>
<?php endif; ?>
<?php if ($parser->recours): ?>
<TYPE_RECOURS><?php echo $parser->recours; ?></TYPE_RECOURS>
<?php endif; ?>
<?php if ($parser->analyses): ?>
<ANALYSES>
<?php foreach ($parser->analyses as $analyse): ?>
    <ANALYSE>
        <TITRE_PRINCIPAL><?php echo $analyse ?></TITRE_PRINCIPAL>
    </ANALYSE>
<?php endforeach; ?>
</ANALYSES>
<?php endif; ?>
<?php if ($parser->avocats): ?>
<AVOCAT_GL><?php echo $parser->avocats; ?></AVOCAT_GL>
<?php endif; ?>
<?php if ($parser->rapporteurs): ?>
<RAPPORTEUR><?php echo $parser->rapporteurs; ?></RAPPORTEUR>
<?php endif; ?>
<SOURCE><?php echo $parser->source ?></SOURCE>
<?php if ($parser->ecli): ?>
<ECLI><?php echo $parser->ecli ?></ECLI>
<?php endif; ?>
<ID_SOURCE><?php echo $parser->idSource ?></ID_SOURCE>
<ALIMENTATION_TYPE>parsers/CJUE</ALIMENTATION_TYPE>
</DOCUMENT>
