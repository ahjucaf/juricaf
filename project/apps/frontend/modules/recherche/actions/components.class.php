<?php

class rechercheComponents extends sfComponents
{
  public function executeFacets() {
    $facetTree = array();
    if (!isset($this->facets[$this->id]))
      return ;
    //Cas arbo (facette / sous facette -> nb);
    if (isset($this->tree) && $this->tree ) {
      foreach($this->facets[$this->id] as $k => $v) {
        $p = preg_split('/ \| /', $k);
        if (!isset($facetTree[$p[0]]))
          $facetTree[$p[0]] = array('count' => 0, 'fid' => $this->mainid, 'fname' => $p[0], 'sub' => array());
        $facetTree[$p[0]]['count'] += $v;
        if (!isset($facetTree[$p[0]]['sub'][$p[1]]))
          $facetTree[$p[0]]['sub'][$p[1]] = array('count' => 0, 'fid' => $this->id, 'fname' => $p[0].' | '.$p[1]);
        $facetTree[$p[0]]['sub'][$p[1]]['count'] += $v;
      }
    //Cas facette classique
    }
    else {
      foreach($this->facets[$this->id] as $k => $v) {
        if (!isset($facetTree[$k]))
          $facetTree[$k] = array('count' => 0, 'fid' => $this->id, 'fname' => $k, 'sub' => array());
        $facetTree[$k]['count'] += $v;
      }
    }
    $this->facets = $facetTree;
  }

  public function executeFullsearch() { }

}
