<?php

class arretComponents extends sfComponents
{
  public function executeStatsPays() {
    $db = sfCouchConnection::getInstance();
    try{
    $this->pays = $db->get('_design/stats/_view/pays_juridiction_date?group_level=1')->rows;
    }catch(Exception $e) {$this->pays = null;}
  }
}