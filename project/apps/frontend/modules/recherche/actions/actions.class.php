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
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $solr = new Apache_Solr_Service('localhost', 8080, '/solr');
    if (!$solr->ping()) {
      throw new Exception("Solr not ready");
    }
    $res = $solr->search($request->getParameter('q', 'Suisse'), 0, 10);
    echo "<pre>";
    print_r($res->response);
    echo "</pre>";
    exit;
  }
}
