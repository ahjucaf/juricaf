<?php
// Crée la table stats_params via detail.csv
require("config.php");
$row = 0;

if (($handle = fopen("detail.csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ';', '"')) !== FALSE) {
    $ligne[$row]['pays'] = trim($data[0]);
    $ligne[$row]['juridiction'] = trim($data[1]);
    $ligne[$row]['etat'] = trim($data[2]);
    $ligne[$row]['maj'] = trim($data[3]);
    $ligne[$row]['selection'] = trim($data[4]);
    $ligne[$row]['traduction'] = ucfirst(trim($data[5]));
    $ligne[$row]['licence'] = trim($data[6]);
    $row++;
  }
  fclose($handle);
}

if(isset($ligne)) {
  try { $bdd = new PDO('mysql:host='.$HOST.';dbname='.$DBNAME, $DBUSER, $DBPASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
      $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  catch (Exception $error) { die('Erreur : '.$error->getMessage()); }
  try {
    $bdd->query('DROP TABLE IF EXISTS `'.$DBTABLE.'`');

    $bdd->query('CREATE TABLE IF NOT EXISTS `'.$DBTABLE.'` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `pays` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
      `juridiction` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
      `etat` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
      `maj` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
      `selection` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
      `traduction` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
      `licence` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
      PRIMARY KEY (`id`),
      KEY `pays` (`pays`),
      KEY `juridiction` (`juridiction`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
  }
  catch (Exception $error) {
    fprintf(STDERR, 'id":"'.$DBTABLE.'","error":"MYSQL","reason":"Cannot create log table : '.$error->getMessage()."\"\n");
  }

  $req = $bdd->prepare('INSERT INTO '.$DBTABLE.'(id, pays, juridiction, etat, maj, selection, traduction, licence) VALUES("", :pays, :juridiction, :etat, :maj, :selection, :traduction, :licence)');

  foreach($ligne as $collection) {
    echo $collection['pays'].' '.$collection['juridiction']."\n";
    $req->execute(array(
      'pays' => $collection['pays'],
      'juridiction' => $collection['juridiction'],
      'etat' => $collection['etat'],
      'maj' => $collection['maj'],
      'selection' => $collection['selection'],
      'traduction' => $collection['traduction'],
      'licence' => $collection['licence']
    ));
  }
}
?>
