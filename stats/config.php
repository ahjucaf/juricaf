<?php
$juricaf_conf = '../juricaf2solr/conf/juricaf.conf';
$juricaf_config_file = file($juricaf_conf, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($juricaf_config_file as $vars) {
  $vars = explode('=', $vars);
  $var[$vars[0]] = $vars[1];
}

$SOLRHOST = $var['SOLRHOST'];
$ORIGINALCSV = "originalbase.csv";

$HEADER2CSVID = array (
    'pays' => 0,
    'juridiction' => 1,
    'etat' => 3,
    'maj' => 4,
    'selection' => 5,
    'traduction' => 6,
    'licence' => 9
);
