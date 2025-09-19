<?php

include("conf/config.php");
include("utils.php");


global $lock, $cpt, $COMMITER, $DBERROR, $changes, $oldseq;

$COMMITER = $INITCOMMITER;
$DBERROR = 0;
$changes = null;
$oldseq = 0;

function readLockFile() {
  global $last_seq, $lock_seq_file, $lock, $changes;
  $lock = fopen($lock_seq_file, 'a+');
  if (!$lock) die('error with lock file');
  fseek($lock, 0);
  $last_seq = rtrim(fgets($lock));
  //On s'assure qu'on revient au last_seq sauvé
  if ($changes) {
    fclose($changes);
    $changes = null;
  }
  return $last_seq;
}
#echo "last_seq : $last_seq\n";
readLockFile();

$cpt = 0;


//Commit les données puis sauve
function storeSeq($seq) {
  global $lock, $cpt, $lock_seq_file;
  if (commitIndexer()) {
    fclose($lock);
    $lock = fopen($lock_seq_file, 'w+');
    fwrite($lock, $seq."\n");
    $cpt = 0;
  }
}

//Convertit un champ couchdb en solr
function getSolrValueFromField($k, $v) {
    $v = preg_replace('/&/', ' ', print_r($v, true));
    $v = preg_replace('/\s*([-=_~])[-=_~]+\s*/', ' \1 ', $v);
    if (preg_match('/(stdClass Object|Array)/', $v))
      $v = preg_replace('/\s\s\s*\)/', '', preg_replace('/\s*(stdClass Object|Array)\s*\(/i', ' ', preg_replace('/ *\[[^ \]]*\] \=\> */', ' ', $v)));
    if (preg_match('/date/', $k) && preg_match('/(\d{4})-(\d{2})-(\d{2})/', $v, $match))
      $v = $match[1].'-'.$match[2].'-'.$match[3].'T12:00:00.000Z';
    else if (preg_match('/date/', $k) && preg_match('/(\d{2})\/(\d{2})\/(\d{4})/', $v, $match))
      $v = $match[3].'-'.$match[2].'-'.$match[1].'T12:00:00.000Z';
    return preg_replace('/[\n\<]/', ' ', $v);
}

function updateIndexer($id) {
  global $couchdb_url_db, $solr_url_db, $last_seq, $virtualfields;
  if (!preg_match('/^[A-Z]+\-/', $id) )
    return;
  $couchdata = json_decode(file_get_contents($couchdb_url_db.'/'.$id));
  if (!$couchdata || !isset($couchdata->type )) {
    deleteIndexer($id);
    return;
  }
  unset($couchdata->_rev);
  $solrdata = '<add><doc>';
  //Conversion des données couchdb vers l'XML solr
  $has_anon = isset($couchdata->texte_arret_anon);
  foreach($couchdata as $k => $v) {
    $k = strtolower($k);
    if ($has_anon && $k == 'texte_arret') {
        continue;
    }
    if ($k == 'texte_arret_anon') {
        $k = 'texte_arret';
    }
    if ($k == 'id')
      continue;
    if ($k == '_id') {
      $k = 'id';
      $id = $v;
      //      echo "$last_seq : $id\n";
    }
    if (preg_match('/^_/', $k))
      continue;
    if (preg_match('/^_/', $id)) {
      $solrdata = '';
      break ;
    }
    $solrdata .= '<field name="'.$k.'">';
    $solrdata .= getSolrValueFromField($k, $v);
    $solrdata .= '</field>';
  }
  //Ajout des champs virtuels construits à partir des champs couchdb existant (utilisé pour les facettes)
  if (isset($virtualfields) && $virtualfields) {
    foreach($virtualfields as $k => $values) {
      $solrdata .= '<field name="'.$k.'">';
      foreach ($values as $v) {
	if (isset($couchdata->{$v}))
	  $solrdata .= getSolrValueFromField($v, $couchdata->{$v});
	else
	  $solrdata .= $v;
      }
      $solrdata .= '</field>';
    }
  }
  //Publication dans Solr des données.
  if ($solrdata) {
    $solrdata .= '</doc></add>';
    //    echo "===================\n$solrdata\n===================\n";
    try {
      do_post_request($solr_url_db.'/update', $solrdata, "content-type: text/xml");
    }
    catch (Exception $e) {
      echo "Erreur d'enregistrement de ".$id." (".$solrdata.")\n-------- INTERNAL MSG --------\n";
      echo $e->getMessage()."\n------------------------------\n";
      return ;
    }
    echo "$last_seq : $id (SAVED)\n";
  }
}

function deleteIndexer($id) {
  global $solr_url_db, $last_seq;
  $solrdata = "<delete><id>$id</id></delete>";
  try {
    do_post_request($solr_url_db.'/update', $solrdata, "content-type: text/xml");
  }
  catch (Exception $e) {
    echo "Erreur de suppression de ".$id." (".$solrdata.")\n-------- INTERNAL MSG --------\n";
    echo $e->getMessage()."\n------------------------------\n";
    return ;
  }
  echo "$last_seq : $id (DELETED)\n";
}

//Ne pas appeler directement, passer par storeSeq
function commitIndexer() {
  global $solr_url_db, $last_seq, $COMMITER, $INITCOMMITER, $DBERROR, $MAXDBERROR, $oldseq;
  $solrdata = "<commit/>";
  try {
    do_post_request($solr_url_db.'/update', $solrdata, "content-type: text/xml");
  }catch (Exception $e) {
    echo "Erreur de commit (".$solrdata.")\n-------- INTERNAL MSG --------\n";
    echo $e->getMessage()."\n------------------------------\n";
    // En cas d'erreur de commit : on retourne au dernier lock/seq qui a fonctionné
    //et on commite deux fois plus tot pour identifier si le pb vient d'un document erronné
    // Si la variable COMMITER est à 1 c'est qu'on a identifié l'enregistrement erronné
    //donc on passe à autre chose
    if ($COMMITER < 2) {
      echo "$last_seq : COMMIT ERROR due the previous document ?\n";
      $COMMITER = $INITCOMMITER;
      $DBERROR++;
      return false;
    }
    if ($DBERROR >= $MAXDBERROR) {
      echo "$last_seq : FATAL: DB ERROR DETECTED (probably not due to document error)\n";
      exit(1);
    }
    echo "$last_seq : BACK TO COMMIT SEQ #";
    readLockFile();
    echo "$last_seq\n";
    $COMMITER = floor($COMMITER / 2);

    //Au cas où le problème viendrait d'une surcharge de Solr, on attend un peu
    sleep(1);
    return false;
  }
  $DBERROR = 0;
  $COMMITER = $INITCOMMITER;
  if ($last_seq != $oldseq)
    echo "$last_seq : COMMIT\n";
  $oldseq = $last_seq;
  return true;
}

$limit = 60;
while( $limit-- > 0 ) {

  $url = $couchdb_url_db.'/_changes?feed=continuous';
  if ($last_seq)
    $url .= '&since='.$last_seq;
  //Récupère les derniers changements
  $changes = fopen($url, 'r');

  //Pour chaque changement, on récupére le document couchdb
  while($changes && ($l = fgets($changes))) {
    $cpt++;

    //Decode le json fourni par couchdb
    $change = json_decode($l);
    if (!$change) {
      echo "pb json : $l\n";
      continue;
    }

    //On commit et sauve le dernier seq si on perd la connexion avec couchdb
    //=> Si un doc couchdb a un last_seq, c'est qu'il nous demande de forcer la sequence
    if (isset($change->last_seq)) {
      storeSeq($change->last_seq);
      break;
    }

    $last_seq = $change->seq;

    //On peut forcer un commit (pour interface d'admin)
    if ($change->id == "COMMITNOW") {
      storeSeq($last_seq);
      continue;
    }

    //Suppression si le doc a été supprimé par couchdb
    if (isset($change->deleted)) {
      deleteIndexer($change->id);
      continue;
    }
    //Sinon insère ou met à jour le document
    updateIndexer($change->id);

    //Si on n'a inséré $COMMITER docs, on commit et sauve cette valeur
    if ($cpt > $COMMITER) {
      storeSeq($last_seq);
    }

  }
  if ($changes) {
    fclose($changes);
    $changes = null;
  }
 }
fclose($lock);
