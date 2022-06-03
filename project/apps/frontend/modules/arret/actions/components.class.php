<?php

class arretComponents extends sfComponents
{
  public function executeStatsPaysJuridiction() {
    $this->db = sfCouchConnection::getInstance();
    try{
      $pays = $this->db->get('_design/stats/_view/pays_juridiction_date?group_level=3&stale=ok')->rows;
      $this->pays = array();
      foreach ($pays as $p) {
        if (!isset($this->pays[$p['key'][0]]))
          $this->pays[$p['key'][0]] = array();
        if (!isset($this->pays[$p['key'][0]][$p['key'][1]])) {
          $this->pays[$p['key'][0]][$p['key'][1]] = array('value'=>0, 'deb' => '9999', 'fin' => 0);
        }
        $this->pays[$p['key'][0]][$p['key'][1]]['value'] += $p['value'];
        if ($this->pays[$p['key'][0]][$p['key'][1]]['deb'] > $p['key'][2])
          $this->pays[$p['key'][0]][$p['key'][1]]['deb'] = $p['key'][2];
        if ($this->pays[$p['key'][0]][$p['key'][1]]['fin'] < $p['key'][2])
          $this->pays[$p['key'][0]][$p['key'][1]]['fin'] = $p['key'][2];
      }
    }catch(Exception $e) {$this->pays = null;}
    try{
      $nb = $this->db->get('_design/stats/_view/pays_juridiction_date?group_level=0&stale=ok')->rows;
      $nb = array_values($nb[0]);
      $this->nb = array_pop($nb);
    }catch(Exception $e) {$this->nb = 0;}
  }

  public function executeSearchPays() {
    $this->db = sfCouchConnection::getInstance();
    try{
    $this->pays = $this->db->get('_design/stats/_view/pays_juridiction_date?group_level=1&stale=ok')->rows;
    }catch(Exception $e) {$this->pays = null;}
  }
}