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
            $this->redirect('@recherche_resultats?query='.$this->protectQuery($request->getParameter('q')));
        }
        $this->db = sfCouchConnection::getInstance();
        try{
        $this->pays = $this->db->get('_design/stats/_view/pays_juridiction_date?group_level=1&stale=ok')->rows;
        }catch(Exception $e) {$this->pays = null;}
        try{
          $nb = $this->db->get('_design/stats/_view/pays_juridiction_date?group_level=0&stale=ok')->rows;
          $nb = array_values($nb[0]);
          $this->nb = array_pop($nb);
        }catch(Exception $e) {$this->nb = 0;}
    }

    protected function protectQuery($q) {
      $q = strip_tags($q);
      $q = preg_replace('/[\/\{\}\<\>]/', '', $q);
      $q = preg_replace("/\'/", '’', $q);
      $count = count_chars($q, 1);

      if (isset($count[ord('"')]) && $count[ord('"')] % 2) {
          $q = preg_replace ('/"/', '', $q);
      }
      return $q;
    }

  public function executeFiltres(sfWebRequest $request) {
      $query = $request->getParameter('q');
      if (!$query) {
        $query = '+';
      }
      $facets = array();
      if ($request->getParameter('pays') || $request->getParameter('juridiction')) {
          if ($request->getParameter('pays')) {
            if ($request->getParameter('juridiction')) {
              $facets[] = str_replace(' ', '_', 'facet_pays_juridiction:'.$request->getParameter('pays').' | '.$request->getParameter('juridiction'));
            }
            $facets[] = str_replace(' ', '_', 'facet_pays:'.$request->getParameter('pays'));
          }
      }
      return $this->redirect('@recherche_resultats?query='.$query."&facets=".implode(',', $facets).'&tri='.$request->getParameter('tri'));
}

  public function executeSearch(sfWebRequest $request)
  {
    $solr = new sfBasicSolr();
    $this->query = $this->protectQuery($request->getParameter('query', '+'));
    $this->getUser()->setAttribute('query', $this->query);
    $this->getUser()->setAttribute('facets', $this->cleanValue($request->getParameter('facets')));
    $this->getUser()->setAttribute('filter', $this->cleanValue($request->getParameter('filter')));

    $solr_query = strtolower($this->query);
    $solr_query = str_replace(' or ', ' OR ', $solr_query);
    $solr_query = str_replace(' a ', ' TO ', $solr_query);
    $solr_query = str_replace(' to ', ' TO ', $solr_query);
    $solr_query = preg_replace('/_([^ :]*):/', '=\1:', $solr_query);
    if(preg_match('/\[\d{4}\-\d{2}\-\d{2} TO \d{4}\-\d{2}\-\d{2}\]/', $solr_query)) {
      $solr_query = preg_replace('/\[(\d{4}\-\d{2}\-\d{2}) TO (\d{4}\-\d{2}\-\d{2})\]/', ' [\1T00:00:00Z TO \2T23:59:59Z]', $solr_query);
    }else if (preg_match('/\d{4}\-\d{2}\-\d{2}/', $solr_query)) {
      $solr_query = preg_replace('/(\d{4}\-\d{2}\-\d{2})/', ' [\1T00:00:00Z TO \1T23:59:59Z]', $solr_query);
    }

    // Paramètres par défaut
    $param['hl'] = 'true';
    if (!preg_match('/\:\*/', $solr_query)) {
      $param['sort'] = 'date_arret desc, id asc';
      $param['facet.field'] = array('facet_pays', 'facet_juridiction', 'facet_pays_juridiction');
      $param['facet'] = 'true';
      $param['fq'] = 'type:arret';
    }

    $this->facetsset = array();
    $this->facetslink = '';
    if ($f = preg_replace('/’/', "'", preg_replace('/[<>]/', '', $request->getParameter('facets')))) {
      $this->facetsset = preg_split('/,/', $f);
      sort($this->facetsset);
      $this->facetslink = ','.implode(',', $this->facetsset);
      foreach ($this->facetsset as $facet) {
        $f = explode(':', $facet);
        //On ne doit pas retirer les _ des facettes donc on les replace par = pour les conserver
        $solr_query .= ' '.preg_replace('/_/', '=', $f[0]).':'.$f[1];
      }
    }

    // Si l'ordre des résultats est précisé
    $this->filtre_tri = 'DESC';
    if (preg_match('/order:pertinence/', $solr_query) || $request->getParameter('tri') == 'pertinence') {
      $solr_query = ' '.preg_replace('/ order:pertinence/', '', $solr_query);
      unset($param['sort']);
      $this->nobots = 1;
      $this->filtre_tri = 'pertinence';
    }
    if (preg_match('/order:chrono/', $solr_query) || $request->getParameter('tri') == 'ASC') {
      $solr_query = ' '.preg_replace('/ order:chrono/', '', $solr_query);
      $param['sort'] = 'date_arret asc, id asc';
      $this->nobots = 1;
      $this->filtre_tri = 'ASC';
    }

    // Si la requète est vide
    if ((!$this->facetsset || !count($this->facetsset)) && !preg_match('/[a-z0-9]/i', $this->query) && !preg_match('/[a-z0-9]/i', $this->filter)) {
      return $this->redirect('@recherche');
    }

    if (preg_match('/_/', $solr_query))
    {
      $solr_query = preg_replace('/([^ :]+_[^ :]+)/i', '"\1"', $solr_query);
      $solr_query = preg_replace('/_/', ' ', $solr_query);
    }
    //Rétablissement des _ non retirables
    $solr_query = preg_replace('/=/', '_', $solr_query);

    // Début pager
    $pas = 10; // Nb de résultats par page
    $pagenum = htmlentities($request->getParameter('page', 1));

    if($request->getParameter('format') === 'rss') {
      $this->setTemplate('rss');
      $pas = 30;
      $param['sort'] = 'date_arret desc, id asc';
      $start = 0;
    }
    else { $start = ($pagenum - 1) * $pas; }

    // Interroge Solr
    $res = $solr->search($solr_query, $start, $pas, $param);

    // Un seul résultat = renvoi à l'arrêt en question
    if ($res->response->numFound == 1 && $request->getParameter('format') !== 'rss') {
      return $this->redirect('@arret?id='.$res->response->docs[0]->id);
    }

    $this->resultats = $res;
    $request->setParameter('nbResultats', $res->response->numFound);

    // Suite pager
    $lastpage = intval($res->response->numFound / $pas) + 1;

    $this->pager = array();
    $this->pager['begin'] = ($pagenum != 1) ? 1 : 0;
    $this->pager['last']  = ($pagenum != 1) ? $pagenum - 1 : 0;
    $this->pager['end']   = ($pagenum + 1 <= $lastpage) ? $lastpage : 0;
    $this->pager['next']  = ($pagenum + 1 <= $lastpage) ? $pagenum + 1 : 0;

    // Facettes et liens pays et juridiction
    $this->facets = array();
    if (isset($res->facet_counts))
      foreach($res->facet_counts->facet_fields as $k => $f) {
        foreach ($f as $n => $v) {
          if ($v)
          $this->facets[$k][$n] = $v;
        }
      }

    if($request->getParameter('format') === 'json') {
            $this->json = true;
            $this->setLayout(false);
            $this->getResponse()->setContentType('application/json');
            $this->setTemplate('json');
    } elseif ($request->getParameter('format') === 'rss') {
            $this->setLayout(false);
            $this->getResponse()->setContentType('application/rss+xml');
    }

    if (isset($this->facets['facet_pays']) && count($this->facets['facet_pays']) == 1 ) {
        $pays = array_keys($this->facets['facet_pays'])[0];
        if (in_array(str_replace(' ', '_', 'facet_pays:'.$pays), $this->facetsset)) {
            $this->filtre_pays = $pays;
        }
    }
    if (isset($this->facets['facet_pays_juridiction']) && count($this->facets['facet_pays_juridiction']) == 1 ) {
        $juridiction = array_keys($this->facets['facet_pays_juridiction'])[0];
        if (in_array(str_replace(' ', '_', 'facet_pays_juridiction:'.$juridiction), $this->facetsset)) {
            $j = explode(' | ', $juridiction);
            $this->filtre_juridiction = $juridiction;
        }
    }

    $this->rss_uri = explode('?', $request->getUri())[0] . '?format=rss';
  }

  private function convertDate($date) {
    $dates = explode('/', $date);
    return $dates[2].'-'.$dates[1].'-'.$dates[0];
  }

  private function quotize($string) {
    if (preg_match('/ /', $string))
      return '"'.$string.'"';
    return $string;
  }

  private function cleanValue($string, $lowercase = false)
  {
    $string = strip_tags($string);
    $string = preg_replace('/[\/\{\}\[\]\<\>]/', '', $string);
    $count = count_chars($string, 1);
    if (isset($count[ord('"')]) && $count[ord('"')] % 2) {
      $string = preg_replace ('/"/', '', $string);
    }
    if($lowercase) {
      $string = strtolower($string);
      $string = str_replace(array(' and ',' or ',' not '), array(' AND ',' OR ',' NOT '), $string);
    }
    return $string;
  }

  public function executeFullsearch(sfWebRequest $request)
  {
    $this->getUser()->setAttribute('query', '');

    if($request->getParameter('cr')) {
      $i = 0;
      foreach ($request->getParameter('cr') as $cr) {
        $criteres[$i] = $this->cleanValue($cr); $i++;
      }
    }

    if($request->getParameter('val')) {
      $i = 0;
      foreach ($request->getParameter('val') as $val) {
        $saisies[$i] = $this->cleanValue($val); $i++;
      }
    }

    if($request->getParameter('cond')) {
      $i = 0;
      foreach ($request->getParameter('cond') as $cond) {
        $conditions[$i] = $this->cleanValue($cond); $i++;
      }
    }

    if($request->getParameter('date')) {
      foreach ($request->getParameter('date') as $key => $date) {
        if(!empty($date)) {
          $dates[$this->cleanValue($key)] = $this->convertDate($date);
        }
      }
    }

    if($request->getParameter('references')) {
      $references = $this->cleanValue($request->getParameter('references'));
    }

    if($request->getParameter('pays')) {
      $i = 0;
      foreach ($request->getParameter('pays') as $key => $p) {
        $pays[$i] = str_replace('_', ' ', $this->cleanValue($key)); $i++;
      }
    }

    if($request->getParameter('total')) {
      $total = $this->cleanValue($request->getParameter('total'));
    }

    //// Construction du filtre
    $filter = '';

    // Critères et conditions
    if(!empty($saisies[0])) { $filter .= $criteres[0].':'.$this->quotize($saisies[0]); } if(!empty($filter) && !empty($saisies[1])) { $filter .= ' '.$conditions[0].' '; }
    if(!empty($saisies[1])) { $filter .= $criteres[1].':'.$this->quotize($saisies[1]); } if(!empty($filter) && !empty($saisies[2])) { $filter .= ' '.$conditions[1].' '; }
      if(!empty($saisies[2])) { $filter .= $criteres[2].':'.$this->quotize($saisies[2]); } if(!empty($filter) && !empty($saisies[3])) { $filter .= ' '.$conditions[2].' '; }
      if(!empty($saisies[3])) { $filter .= $criteres[3].':'.$this->quotize($saisies[3]); }

    // Dates
    if(!empty($filter) && !empty($dates)) {
      $filter .= ' ';
    }

    if(!empty($dates['debut']) && !empty($dates['fin']) && $dates['debut'] != $dates['fin']) {
      if(!empty($dates['arret'])) {
        $filter .= ' OR ';
      }
      $dates['debut'] = str_replace("--",'',$dates['debut']);
      $dates['fin'] = str_replace("--",'',$dates['fin']);

      $filter .= 'date_arret:['.$dates['debut'].' A '.$dates['fin'].']';
    }elseif( !empty($dates['debut']) || !empty($dates['fin']) ){
        $date = $dates['debut'];
        if (!empty($dates['fin'])) {
            $date = $dates['fin'];
        }
        $dates['arret'] = str_replace("--",'',$date);
        $filter .= 'date_arret:'.$dates['arret'];
    }

    // Références
    if(!empty($filter) && !empty($references)) { $filter .= ' references:'.$references; }
    elseif(!empty($references)) { $filter .= 'references:'.$references; }

    // Pays
    if(!empty($total) && !empty($pays)) {
        if(intval($total) !== count($pays)) {
            if(!empty($filter)) {
                $filter .= ' ';
            }
            $nb = count($pays); $i = 1;
            if ($nb > 1) {
                $filter .= '( ';
                foreach ($pays as $p) {
                    $quote = '';
                    if (preg_match("/[' ]/", $p))
                    $quote = '"';
                    $filter .= 'pays:'.$quote.$p.$quote;
                    if($i < $nb) { $filter .= ' OR '; }
                    $i++;
                }
                $filter .= ' ) ';
            }
            
        }
    }
    
    $filter = preg_replace('/content:/', '', $filter);
    $filter = preg_replace('/ AND /', ' ', $filter);
    $filter = preg_replace('/ NOT /', ' !', $filter);

    if ($pays && count($pays) == 1) {
        if(!$filter) {
            $filter = '+';
        }else{
            $filter = urlencode($filter);
        }
        return $this->redirect('@recherche_resultats?facets=facet_pays:'.urlencode($pays[0]).'&query='.$filter);
    }

    if(!empty($filter)) {
      return $this->redirect('@recherche_resultats?query='.urlencode($filter));
    }

  }

  public function executeActualites(sfWebRequest $request) {
    
  }

  public function executePage404() { }

}
