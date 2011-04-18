<?php

include("conf/config.php");
include("utils.php");

global $solr_url_db, $last_seq;
$solrdata = "<delete><query>*:*</query></delete>";
do_post_request($solr_url_db.'/update', $solrdata, "content-type: text/xml");

$solrdata = "<commit/>";
do_post_request($solr_url_db.'/update', $solrdata, "content-type: text/xml");

