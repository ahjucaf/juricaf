<?php
require_once(sfConfig::get('sf_lib_dir').'/vendor/SolrClient/Service.php');
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
    $solr = new Apache_Solr_Service('localhost', 8080, '/solr');
    if (!$solr->ping()) {
      throw new Exception("Solr not ready");
    }
    $this->query = $request->getParameter('query', 'Suisse');
    $solr_query = $this->query;
    if (preg_match('/_/', $solr_query))
    {
      $solr_query = preg_replace('/([^ :]+_[^ :]+)/i', '"\1"', $this->query);
      $solr_query = preg_replace('/_/', ' ', $solr_query);
    }
    $res = $solr->search($solr_query, $request->getParameter('start', 0), $request->getParameter('start', 0)+10, array('hl' => 'true', 'sort' => 'date_arret desc', 'facet.field'=>'pays', 'facet.field'=>'juridiction', 'facet'=>'true'));
    $this->resultats = $res;
  }

}
