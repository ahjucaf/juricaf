<?php

$arret_id = $argv[1];
#$dest_xmlfile = $argv[2];

$typedescription2typeaffaire = array(
    "12" => "satisfaction équitable",
    "14" => "au principal",
    "15" => "au principal et satisfaction équitable",
    "18" => "révision",
    "19" => "radiation du rôle",
    "26" => "article 46 § 4",
    );
$typedescription2typearret = array(
    "12" => "Arrêt",
    "14" => "Arrêt",
    "15" => "Arrêt",
    "18" => "Arrêt",
    "19" => "Décision",
    "26" => "Arrêt",
    );

$obj = json_decode(@file_get_contents("arrets/".$arret_id.".json"));
if (!$obj) {
    fwrite(STDERR, "ERREUR: CEDH / json non trouvé pour $arret_id (arrets/".$arret_id.".json)\n");
    exit(1);
}
$meta = $obj->results[0]->columns;
if (!$meta) {
    fwrite(STDERR, "ERREUR: CEDH / meta de $arret_id non accessible (arrets/".$arret_id.".json)\n");
    exit(2);
}
$obj = null;

$text = @file_get_contents("arrets/".$arret_id.".txt");
if (!$text) {
    fwrite(STDERR, "ERREUR: CEDH / pas de texte français pour l'$arret_id trouvé (arrets/".$arret_id.".json | arrets/".$arret_id.".txt | https://hudoc.echr.coe.int/eng?i=".$arret_id.")\n");
    exit(3);
}


if (preg_match('/affaire ([^ ].*[^ ]) c\. ([^\(]*[^\( ])/i', $meta->docname, $m)) {
    $defenseur = $m[2];
    $demandeur = $m[1];
}else{
    fwrite(STDERR, "WARN: CEDH: defenseur/demandeur non trouvé pour $arret_id (".$meta->docname.")\n");
    $defenseur = null;
    $demandeur = null;
}

if (preg_match('/(.* SECTION|GRANDE CHAMBRE)\s*AFFAIRE/', $text, $m)) {
    $section = '('.$m[1].')';
}

$text = preg_replace('/\\\./', '.', $text);

?><?xml version="1.0" encoding="utf8"?>
<DOCUMENT>
<DATE_ARRET><?php echo preg_replace('/([0-9][0-9])\/([0-9][0-9])\/([0-9][0-9][0-9][0-9]) [0-9:]*/', '$3-$2-$1', $meta->judgementdate) ; ?></DATE_ARRET>
<FORMATION>COUR <?php echo $section; ?></FORMATION>
<JURIDICTION>Cour européenne des droits de l'homme</JURIDICTION>
<NUM_ARRET><?php echo $arret_id; ?></NUM_ARRET>
<PAYS>CEDH</PAYS>
<TEXTE_ARRET><![CDATA[
   <?php echo $text; ?>
]]>
</TEXTE_ARRET>
<TITRE>CEDH, <?php echo  $meta->docname ; ?>, <?php echo preg_replace('/([0-9][0-9])\/([0-9][0-9])\/([0-9][0-9][0-9][0-9]) [0-9:]*/', '$3', $meta->judgementdate); ?>, <?php echo $arret_id; ?></TITRE>
<CITATION_ARRET_STRASBOURG><![CDATA[<?php echo $meta->scl; ?>]]></CITATION_ARRET_STRASBOURG>
<CITATION_ARTICLE><?php echo $meta->article; ?></CITATION_ARTICLE>
<FONDS_DOCUMENTAIRE>HUDOC</FONDS_DOCUMENTAIRE>
<IMPORTANCE><?php echo $meta->importance - 1 ; ?></IMPORTANCE>
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
<?php if (isset($meta->representedby) && $meta->representedby && $meta->representedby != 'N/A'): ?>
<AVOCATS><?php echo $meta->representedby; ?></AVOCATS>
<?php endif; ?>
<TYPE><?php echo $typedescription2typearret[$meta->typedescription]; ?></TYPE>
<TYPE_AFFAIRE><?php echo $typedescription2typeaffaire[$meta->typedescription]; ?></TYPE_AFFAIRE>
<TYPE_RECOURS><?php echo $meta->conclusion; ?></TYPE_RECOURS>
<SOURCE>http://hudoc.echr.coe.int/fre?i=<?php echo $arret_id; ?></SOURCE>
</DOCUMENT>
