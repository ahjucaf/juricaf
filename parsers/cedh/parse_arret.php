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
    fwrite(STDERR, "ERREUR: json non trouvé pour $arret_id\n");
    exit(2);
}
$meta = $obj->results[0]->columns;
$obj = null;
$text = @file_get_contents("arrets/".$arret_id.".txt");

if (!$meta || !$text) {
    fwrite(STDERR, "ERREUR: $arret_id non trouvé\n");
    exit(1);
}

if (preg_match('/affaire ([^ ].*[^ ]) c\. ([^\(]*[^\( ])/i', $meta->docname, $m)) {
    $defenseur = $m[2];
    $demandeur = $m[1];
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
<CITATION_ARRET_STRASBOURG><?php echo $meta->scl; ?></CITATION_ARRET_STRASBOURG>
<CITATION_ARTICLE><?php echo $meta->article; ?></CITATION_ARTICLE>
<FONDS_DOCUMENTAIRE>HUDOC</FONDS_DOCUMENTAIRE>
<IMPORTANCE><?php echo $meta->importance - 1 ; ?></IMPORTANCE>
<PARTIES>
    <DEMANDEURS>
        <DEMANDEUR><?php echo $demandeur; ?></DEMANDEUR>
    </DEMANDEURS>
    <DEFENDEURS>
        <DEFENDEUR><?php echo $defenseur ; ?></DEFENDEUR>
    </DEFENDEURS>
</PARTIES>
<?php if (isset($meta->representedby) && $meta->representedby && $meta->representedby != 'N/A'): ?>
<AVOCATS><?php echo $meta->representedby; ?></AVOCATS>
<?php endif; ?>
<TYPE><?php echo $typedescription2typearret[$meta->typedescription]; ?></TYPE>
<TYPE_AFFAIRE><?php echo $typedescription2typeaffaire[$meta->typedescription]; ?></TYPE_AFFAIRE>
<TYPE_RECOURS><?php echo $meta->conclusion; ?></TYPE_RECOURS>
<SOURCE>http://hudoc.echr.coe.int/fre?i=<?php echo $arret_id; ?></SOURCE>
</DOCUMENT>
