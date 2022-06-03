<?php

class sfBasicSolrConnector
{
  private static $solr = null;

  public static function getInstance() {
    if (!self::$solr) {
      self::$solr = new Apache_Solr_Service(sfConfig::get('app_solr_host'), sfConfig::get('app_solr_port'), sfConfig::get('app_solr_path'));
      if (!self::$solr->ping()) {
	throw new Exception("Solr not ready");
      }
    }
    return self::$solr;
  }
}

class sfBasicSolr 
{
  private $solr = null;
  public function __construct() {
    $this->solr = sfBasicSolrConnector::getInstance();
  }
  public function search($query, $start = 0, $end = 10, $param = array()) {
    return $this->solr->search($query, $start, $end, $param);
  }
}