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
    $res = $solr->search($this->query, $request->getParameter('start', 0), $request->getParameter('start', 0)+10);
    $this->resultats = $res->response;
/*
    echo "<pre>";
    print_r($res->response);
    echo "</pre>";
*/
    #exit;
  }

}
