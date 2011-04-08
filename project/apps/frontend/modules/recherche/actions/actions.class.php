<?php

/**
 * recherche actions.
 *
 * @package    juricaf
 * @subpackage recherche
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class rechercheActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    if($request->getParameter('q')) {
      $search = strip_tags($request->getParameter('q'));
      $search = preg_replace('/[\/\{\}\[\]]/', '', $search);
      $this->redirect('@recherche_resultats?query='.$search);
    }
  }
  
  public function executeSearch(sfWebRequest $request)
  {
    $solr = new sfBasicSolr();
    $this->query = $request->getParameter('query', 'Suisse');
    $solr_query = $this->query;
    if (preg_match('/_/', $solr_query))
    {
      $solr_query = preg_replace('/([^ :]+_[^ :]+)/i', '"\1"', $this->query);
      $solr_query = preg_replace('/_/', ' ', $solr_query);
    }
    $param = array('hl' => 'true');
    if (!preg_match('/\:\*/', $solr_query)) {
      $param['sort'] = 'date_arret desc';
      $param['facet.field']='pays';
      $param['facet.field']='juridiction';
      $param['facet']='true';
    }
      $res = $solr->search($solr_query, $request->getParameter('start', 0), $request->getParameter('start', 0)+10, $param);
    $this->resultats = $res;
  }

}
