<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />
    <link rel="schema.DCTERMS" href="http://purl.org/dc/terms/" />
    <?php if(!empty($dc_identifier_urnlex)) { ?>
<meta name="DC.identifier" scheme="urn" content="<?php echo $dc_identifier_urnlex; ?>" />
<?php } ?>
    <?php if(!empty($dc_identifier_uri)) { ?>
<meta name="DC.identifier" scheme="DCTERMS.URI" content="<?php echo $dc_identifier_uri; ?>" />
<?php } ?>
    <?php if(!empty($pays)) { ?>
<link rel="alternate" type="application/rss+xml" href="/recherche/+/facet_pays%3A<?php echo replaceBlank($pays); ?>?format=rss" title="Collection <?php echo $pays; ?>" />
<?php } ?>
    <?php if(!empty($pays) && !empty($juridiction)) { ?>
<link rel="alternate" type="application/rss+xml" href="/recherche/+/facet_pays_juridiction%3A<?php echo replaceBlank($pays); ?>_%7C_<?php echo replaceBlank($juridiction); ?>%2Cfacet_pays%3A<?php echo replaceBlank($pays); ?>?format=rss" title="Collection <?php echo $pays.' | '.$juridiction; ?>" />
<?php } ?>