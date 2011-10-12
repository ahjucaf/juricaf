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
      $search = preg_replace("/\'/", '’', $search);
      $count = count_chars($search, 1);

      if (isset($count[ord('"')]) && $count[ord('"')] % 2) {
        $search = preg_replace ('/"/', '', $search);
      }
      $this->redirect('@recherche_resultats?query='.$search);
    }
  }

  public function executeSearch(sfWebRequest $request)
  {
    $solr = new sfBasicSolr();
    $this->query = preg_replace('/’/', "'", preg_replace('/[<>]/', '', $request->getParameter('query', 'Suisse')));
    $this->getUser()->setAttribute('query', $this->query);
    $this->getUser()->setAttribute('facets', self::cleanValue($request->getParameter('facets')));
    $this->getUser()->setAttribute('filter', self::cleanValue($request->getParameter('filter')));
    $solr_query = strtolower($this->query);
    $solr_query = preg_replace('/_([^ :]*):/', '=\1:', $solr_query);

    // Paramètres par défaut
    $param['hl'] = 'true';
    if (!preg_match('/\:\*/', $solr_query)) {
      $param['sort'] = 'date_arret desc, id asc';
      $param['facet.field'] = array('facet_pays', 'facet_juridiction', 'facet_pays_juridiction');
      $param['facet'] = 'true';
      $param['fq'] = 'type:arret';
    }

    if($this->filter = self::cleanValue($request->getParameter('filter'))) {
      // Gestion des dates
      if(strpos($this->filter, 'date_arret:') !== false) {
        if(preg_match('/date_arret:([0-9]{2})([0-9]{2})([0-9]{4})to([0-9]{2})([0-9]{2})([0-9]{4})/i', $this->filter, $match)) {
          $param['facet.date'] = 'date_arret';
          $param['facet.date.start'] = $match[3].'-'.$match[2].'-'.$match[1].'T00:00:00.000Z';
          $param['facet.date.end'] = $match[6].'-'.$match[5].'-'.$match[4].'T00:00:00.000Z';
          $param['facet.date.gap'] = '+1DAY';
          $param['facet.date.include'] = 'edge';
          $this->filter = preg_replace('/(( OR )|( AND )| )*date_arret:[0-9]{8}to[0-9]{8}/i', '', $this->filter);
          $this->filter = preg_replace('/(( OR )|( AND )| )*date_arret:[0-9]{8}/i', '', $this->filter);
        }
        elseif(preg_match('/date_arret:([0-9]{2})([0-9]{2})([0-9]{4})/', $this->filter, $match)) {
          $date_originale = '/date_arret:'.$match[1].$match[2].$match[3].'/i';
          $date_transformee = 'date_arret:"'.$match[3].'-'.$match[2].'-'.$match[1].'T00:00:00.000Z"';
          $this->filter = preg_replace($date_originale, $date_transformee, $this->filter);
          $this->filter = preg_replace('/(( OR )|( AND )| )*date_arret:[0-9]{8}/i', '', $this->filter);
        }
      }

      // gérer content:
      //$this->filter = preg_replace('/ (OR|AND|NOT) /', ',$1 ', $this->filter);

      // Ajout au filtre
      $param['fq'] .= ' '.$this->filter;
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
    if (preg_match('/order:pertinence/', $solr_query)) {
      $solr_query = ' '.preg_replace('/ order:pertinence/', '', $solr_query);
      unset($param['sort']);
    }
    if (preg_match('/order:chrono/', $solr_query)) {
      $solr_query = ' '.preg_replace('/ order:chrono/', '', $solr_query);
      $param['sort'] = 'date_arret asc, id asc';
    }

    // Si la requète est vide
    if (!count($this->facetsset) && !preg_match('/[a-z0-9]/i', $this->query) && !preg_match('/[a-z0-9]/i', $this->filter)) {
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
    $start = ($pagenum - 1) * $pas;

    // Interroge Solr
    $res = $solr->search($solr_query, $start, $pas, $param);

    // Un seul résultat = renvoi à l'arrêt en question
    if ($res->response->numFound == 1) {
      return $this->redirect('@arret?id='.$res->response->docs[0]->id);
    }

    // Suite pager
    $lastpage = intval($res->response->numFound / $pas) + 1;
    $this->pager = array();
    $this->pager['begin'] = ($pagenum != 1) ? 1 : 0;
    $this->pager['last']  = ($pagenum != 1) ? $pagenum - 1 : 0;
    $this->pager['end']   = ($pagenum + 1 <= $lastpage) ? $lastpage : 0;
    $this->pager['next']  = ($pagenum + 1 <= $lastpage) ? $pagenum + 1 : 0;

    $this->resultats = $res;

    // Facettes et liens pays et juridiction
    $this->facets = array();
    if (isset($res->facet_counts))
      foreach($res->facet_counts->facet_fields as $k => $f) {
        foreach ($f as $n => $v) {
          if ($v)
          $this->facets[$k][$n] = $v;
        }
      }
  }

  private function cleanValue($string, $lowercase = false)
  {
    $string = strip_tags($string);
    $string = preg_replace('/[\/\{\}\[\]\<\>]/', '', $string);
    //$string = preg_replace("/\'/", '’', $string);
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
        $criteres[$i] = self::cleanValue($cr); $i++;
      }
    }

    if($request->getParameter('val')) {
      $i = 0;
      foreach ($request->getParameter('val') as $val) {
        $saisies[$i] = self::cleanValue($val); $i++;
      }
    }

    if($request->getParameter('cond')) {
      $i = 0;
      foreach ($request->getParameter('cond') as $cond) {
        $conditions[$i] = self::cleanValue($cond); $i++;
      }
    }

    if($request->getParameter('date')) {
      foreach ($request->getParameter('date') as $key => $date) {
        if(!empty($date)) {
          $dates[self::cleanValue($key)] = self::cleanValue($date);
        }
      }
    }

    if($request->getParameter('references')) {
      $references = self::cleanValue($request->getParameter('references'));
    }

    if($request->getParameter('pays')) {
      $i = 0;
      foreach ($request->getParameter('pays') as $key => $p) {
        $pays[$i] = str_replace('_', ' ', self::cleanValue($key)); $i++;
      }
    }

    if($request->getParameter('total')) {
      $total = self::cleanValue($request->getParameter('total'));
    }

    //// Construction du filtre
    $filter = '';

    // Critères et conditions
    if(!empty($saisies[0])) { $filter .= $criteres[0].':'.$saisies[0]; } if(!empty($filter) && !empty($saisies[1])) { $filter .= ' '.$conditions[0].' '; }
    if(!empty($saisies[1])) { $filter .= $criteres[1].':'.$saisies[1]; } if(!empty($filter) && !empty($saisies[2])) { $filter .= ' '.$conditions[1].' '; }
    if(!empty($saisies[2])) { $filter .= $criteres[2].':'.$saisies[2]; } if(!empty($filter) && !empty($saisies[3])) { $filter .= ' '.$conditions[2].' '; }
    if(!empty($saisies[3])) { $filter .= $criteres[3].':'.$saisies[3]; }

    // Dates
    if(!empty($filter) && !empty($dates)) {
      $filter .= ' ';
    }

    if(!empty($dates['arret'])) { $filter .= 'date_arret:'.$dates['arret']; }
    if(!empty($dates['debut']) && !empty($dates['fin'])) {
      if(!empty($dates['arret'])) { $filter .= ' OR '; }
      $filter .= 'date_arret:'.$dates['debut'].'TO'.$dates['fin'];
    }

    // Références
    if(!empty($filter) && !empty($references)) { $filter .= ' AND references:'.$references; }
    elseif(!empty($references)) { $filter .= 'references:'.$references; }

    // Pays
    if(!empty($total) && !empty($pays)) {
      if(intval($total) !== count($pays)) {
        if(!empty($filter)) {
          $filter .= ' AND ';
        }
        $nb = count($pays); $i = 1;
        foreach ($pays as $p) {
          $filter .= 'pays:"'.$p.'"';
          if($i < $nb) { $filter .= ' OR '; }
          $i++;
        }

      }
    }

    if(!empty($query) || !empty($filter)) {
      if(!empty($query)) { $query = urlencode($query); } else { $query = '+'; }
      //$this->redirect('@recherche_resultats?query='.$query.'&filter='.urlencode($filter));
      $this->query = $query;
      $this->filter = $filter;
    }

  }

  public function executePage404() { }

}
