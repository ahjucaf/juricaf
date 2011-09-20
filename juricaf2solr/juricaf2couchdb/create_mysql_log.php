<?php
// Crée la bdd juricaf si elle n'existe pas et une table de log nommée à la date de début de l'import en cours et le fichier de configuration php
$juricaf_conf = '../conf/juricaf.conf';
$mysql_config_file = '../conf/mysql_conf.php';
$HOST = 'localhost';
$DBTABLE = 'Log-Import-'.date('Y-m-d');

// Lit et extrait les paramêtres de configuration
$juricaf_config_file = file($juricaf_conf, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($juricaf_config_file as $vars) {
  $vars = explode('=', $vars);
  $var[$vars[0]] = $vars[1];
}

// Crée la bdd et l'utilisateur juricaf avec les droits sur celle ci si ce n'est déjà fait
try {
  $create_database = "CREATE DATABASE IF NOT EXISTS `".$var['MYSQLDBNAME']."`";
  $create_user_and_grant = "GRANT ALL PRIVILEGES ON ".$var['MYSQLDBNAME'].".* TO '".$var['MYSQLDBUSER']."'@'localhost' IDENTIFIED BY '".$var['MYSQLDBPASS']."' WITH GRANT OPTION";

  $link = mysql_connect("localhost", "root", $var['MYSQLROOTPASS']);
  $result = mysql_query($create_database);
  $result = mysql_query($create_user_and_grant);
  mysql_close($link);
}
catch (Exception $error) {
  fprintf(STDERR, 'id":"'.$DBTABLE.'","error":"MYSQL","reason":"Cannot create database or user : '.$error->getMessage()."\"\n");
}

// Crée le fichier de configuration du jour
$conf_vars = '<?php
$DBNAME = "'.$var['MYSQLDBNAME'].'";
$DBTABLE = "'.$DBTABLE.'";
$DBUSER = "'.$var['MYSQLDBUSER'].'";
$DBPASS = "'.$var['MYSQLDBPASS'].'";
$HOST = "'.$HOST.'";
?>';

try {
  $handler = fopen($mysql_config_file,"w");
  fputs($handler,$conf_vars);
}
catch (Exception $e) {
  echo "Erreur d'enregistrement de ".$mysql_config_file."\n";
  echo $e->getMessage()."\n";
  exit;
}

// Crée la table de log du jour
try {
  $bdd = new PDO('mysql:host='.$HOST.';dbname='.$var['MYSQLDBNAME'], $var['MYSQLDBUSER'], $var['MYSQLDBPASS'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
  $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $bdd->query('CREATE TABLE IF NOT EXISTS `'.$DBTABLE.'` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `id_base` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
      `erreurs` text COLLATE utf8_unicode_ci NOT NULL,
      `pays` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
      `juridiction` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
      `formation` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
      `section` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
      `num_arret` text COLLATE utf8_unicode_ci NOT NULL,
      `num_decision` text COLLATE utf8_unicode_ci NOT NULL,
      `date_arret` date NOT NULL,
      `sens_arret` text COLLATE utf8_unicode_ci NOT NULL,
      `numeros_affaires` tinyint(1) NOT NULL,
      `nor` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
      `urnlex` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
      `ecli` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
      `titre` tinyint(1) NOT NULL,
      `titre_supplementaire` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
      `type_affaire` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
      `type_recours` text COLLATE utf8_unicode_ci NOT NULL,
      `decisions_attaquees` tinyint(1) NOT NULL,
      `president` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
      `avocat_gl` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
      `rapporteur` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
      `commissaire_gvt` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
      `avocats` text COLLATE utf8_unicode_ci NOT NULL,
      `parties` tinyint(1) NOT NULL,
      `analyses` tinyint(1) NOT NULL,
      `saisines` tinyint(1) NOT NULL,
      `texte_arret` text COLLATE utf8_unicode_ci NOT NULL,
      `references` tinyint(1) NOT NULL,
      `fonds_documentaire` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
      `reseau` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
      `id_source` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
      `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
      `date_import` datetime NOT NULL,
      PRIMARY KEY (`id`),
      KEY `pays` (`pays`),
      KEY `juridiction` (`juridiction`),
      KEY `formation` (`formation`),
      KEY `date_arret` (`date_arret`),
      KEY `nor` (`nor`),
      KEY `urnlex` (`urnlex`),
      KEY `ecli` (`ecli`),
      KEY `titre_supplementaire` (`titre_supplementaire`),
      KEY `type_affaire` (`type_affaire`),
      KEY `president` (`president`),
      KEY `avocat_gl` (`avocat_gl`),
      KEY `rapporteur` (`rapporteur`),
      KEY `commissaire_gvt` (`commissaire_gvt`),
      KEY `type` (`type`),
      KEY `date_import` (`date_import`),
      FULLTEXT KEY `erreurs` (`erreurs`),
      FULLTEXT KEY `num_arret` (`num_arret`),
      FULLTEXT KEY `num_decision` (`num_decision`),
      FULLTEXT KEY `sens_arret` (`sens_arret`),
      FULLTEXT KEY `type_recours` (`type_recours`),
      FULLTEXT KEY `avocats` (`avocats`),
      FULLTEXT KEY `texte_arret` (`texte_arret`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
}
catch (Exception $error) {
  fprintf(STDERR, 'id":"'.$DBTABLE.'","error":"MYSQL","reason":"Cannot create log table : '.$error->getMessage()."\"\n");
}
?>
