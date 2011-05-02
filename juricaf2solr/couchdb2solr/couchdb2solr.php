<?php

include("conf/config.php");
include("utils.php");


global $lock, $cpt, $COMMITER;

$COMMITER = 100;

$lock = fopen($lock_seq_file, 'a+');
if (!$lock) die('error with lock file');
fseek($lock, 0);
$last_seq = rtrim(fgets($lock));

#echo "last_seq : $last_seq\n";


$cpt = 0;


function storeSeq($seq) {
  global $lock, $cpt, $lock_seq_file;
  fclose($lock);
  $lock = fopen($lock_seq_file, 'w+');
  fwrite($lock, $seq."\n");
  $cpt = 0;
  commitIndexer();
}


function updateIndexer($id) {
  global $couchdb_url_db, $solr_url_db, $last_seq;
  $couchdata = json_decode(file_get_contents($couchdb_url_db.'/'.$id));
  if (!$couchdata || !isset($couchdata->type ) || $couchdata->type != "arret")
     return;
  unset($couchdata->_rev);
  $solrdata = '<add><doc>';
  foreach($couchdata as $k => $v) {
    $k = strtolower($k);
    if ($k == 'id')
      continue;
    if ($k == '_id') {
      $k = 'id';
      $id = $v;
      echo "$last_seq : $id\n";
    }
    if (preg_match('/^_/', $k))
      continue;
    if (preg_match('/^_/', $id)) {
      $solrdata = '';
  break ;
    }
    $solrdata .= '<field name="'.$k.'">';
    $v = preg_replace('/&/', ' ', print_r($v, true));
    $v = preg_replace('/\s*([-=_~])[-=_~]+\s*/', ' \1 ', $v);
    if (preg_match('/(stdClass Object|Array)/', $v))
      $v = preg_replace('/\s\s\s*\)/', '', preg_replace('/\s*(stdClass Object|Array)\s*\(/i', ' ', preg_replace('/ *\[[^ \]]*\] \=\> */', ' ', $v)));
    if (preg_match('/date/', $k) && preg_match('/(\d{4})-(\d{2})-(\d{2})/', $v, $match))
      $v = $match[1].'-'.$match[2].'-'.$match[3].'T12:00:00.000Z';
    else if (preg_match('/date/', $k) && preg_match('/(\d{2})\/(\d{2})\/(\d{4})/', $v, $match))
      $v = $match[3].'-'.$match[2].'-'.$match[1].'T12:00:00.000Z';
    $solrdata .= preg_replace('/\n/', ' ', $v);
    $solrdata .= '</field>';
  }
  if ($solrdata) {
    $solrdata .= '</doc></add>';
    //    echo "===================\n$solrdata\n===================\n";
    try {
      do_post_request($solr_url_db.'/update', $solrdata, "content-type: text/xml");
    }
    catch (Exception $e) {
      echo "Erreur d'enregistrement de ".$id." (".$solrdata.")\n";
      echo $e->getMessage()."\n";
    }
  }
}

function deleteIndexer($id) {
  global $solr_url_db, $last_seq;
  $solrdata = "<delete><id>$id</id></delete>";
  echo "$last_seq : $id (DELETED)\n";
  do_post_request($solr_url_db.'/update', $solrdata, "content-type: text/xml");
}

function commitIndexer() {
  global $solr_url_db, $last_seq;
  $solrdata = "<commit/>";
  do_post_request($solr_url_db.'/update', $solrdata, "content-type: text/xml");
}



while(1) {

  $url = $couchdb_url_db.'/_changes?feed=continuous';
  if ($last_seq)
    $url .= '&since='.$last_seq;
#  echo "$url\n";
  $changes = fopen($url, 'r');

  while($l = fgets($changes)) {
    $cpt++;
    $change = json_decode($l);
    if (!$change) {
      echo "pb json : $l\n";
      continue;
    }

    if (isset($change->last_seq)) {
# echo "last_seq\n";
      storeSeq($change->last_seq);
      break;
    }

    $last_seq = $change->seq;

    if (isset($change->deleted)) {
      deleteIndexer($change->id);
      continue;
    }

    updateIndexer($change->id);

    if ($cpt > $COMMITER) {
      storeSeq($last_seq);
    }

  }

  fclose($changes);
 }
fclose($lock);
