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
    $this->getUser()->setAttribute('query', '');
    if($request->getParameter('q')) {
      $search = strip_tags($request->getParameter('q'));
      $search = preg_replace('/[\/\{\}\[\]\<\>]/', '', $search);
      $this->redirect('@recherche_resultats?query='.$search);
    }
  }

  public function executeSearch(sfWebRequest $request)
  {
    $solr = new sfBasicSolr();
    $this->query = preg_replace('/[<>]/', '', $request->getParameter('query', 'Suisse'));
    $this->getUser()->setAttribute('query', $this->query);
    $solr_query = $this->query;

    $param = array('hl' => 'true');
    if (!preg_match('/\:\*/', $solr_query)) {
      $param['sort'] = 'date_arret desc';
      $param['facet.field']= array('pays', 'juridiction');
      //      $param['facet.field']='juridiction';
      $param['facet']='true';
    }

    $this->facetsset = array();
    $this->facetslink = '';
    if ($f = preg_replace('/[<>]/', '', $request->getParameter('facets'))) {
      $this->facetsset = preg_split('/,/', $f);
      sort($this->facetsset);
      $this->facetslink = ','.implode(',', $this->facetsset);
      $solr_query .= ' '.implode(' ', $this->facetsset);
      if (preg_match('/order:pertinance/', $solr_query)) {
	$solr_query = ' '.preg_replace('/ order:pertinance/', '', $solr_query);
	unset($param['sort']);
      }
    }

    if (!count($this->facetsset) && !preg_match('/[a-z0-9]/', $this->query)) {
      return $this->redirect('@recherche');
    }

    if (preg_match('/_/', $solr_query))
    {
      $solr_query = preg_replace('/([^ :]+_[^ :]+)/i', '"\1"', $solr_query);
      $solr_query = preg_replace('/_/', ' ', $solr_query);
    }
    $pas = 10;
    $pagenum = htmlentities($request->getParameter('page', 1));
    $start = ($pagenum - 1) * $pas;

    $res = $solr->search($solr_query, $start, $pas, $param);

    $lastpage = intval($res->response->numFound / $pas) + 1;
    $this->pager = array();
    $this->pager['begin'] = ($pagenum != 1) ? 1 : 0;
    $this->pager['last']  = ($pagenum != 1) ? $pagenum - 1 : 0;
    $this->pager['end']   = ($pagenum + 1 <= $lastpage) ? $lastpage : 0;
    $this->pager['next']  = ($pagenum + 1 <= $lastpage) ? $pagenum + 1 : 0;
    
    $this->resultats = $res;
    $this->facets = array();
    if (isset($res->facet_counts))
      foreach($res->facet_counts->facet_fields as $k => $f) {
  foreach ($f as $n => $v) {
    if ($v)
      $this->facets[$k][$n] = $v;
  }
      }
  }

}
