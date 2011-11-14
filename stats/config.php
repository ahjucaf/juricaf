<?php
$juricaf_conf = '../juricaf2solr/conf/juricaf.conf';
$juricaf_config_file = file($juricaf_conf, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($juricaf_config_file as $vars) {
  $vars = explode('=', $vars);
  $var[$vars[0]] = $vars[1];
}

$HOST = 'localhost';
$DBTABLE = 'stats_params';
$DBNAME = $var['MYSQLDBNAME'];
$DBUSER = $var['MYSQLDBUSER'];
$DBPASS = $var['MYSQLDBPASS'];
?>