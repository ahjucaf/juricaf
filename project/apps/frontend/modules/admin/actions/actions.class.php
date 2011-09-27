<?php

/**
 * admin actions.
 *
 * @package    juricaf
 * @subpackage admin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class adminActions extends sfActions
{
  private function createAndChangeToRelativeDir($relative_dir) {
    if (!isset($this->dir) || !$this->dir) {
      $this->dir = getcwd();
      umask(0007);
    }

    $relative_dir = preg_replace('/ /', '_', $relative_dir);
    @mkdir($relative_dir);
    if (! @chdir($relative_dir)) {
      $this->getUser()->setFlash('error', "Cannot access subdir ".$this->dir."/$relative_dir directory");
      return false;
    }
    $this->dir .= DIRECTORY_SEPARATOR.$relative_dir;
    return true;
  }
  public function executeUpload(sfWebRequest $request)
  {
    $this->form = new UploadForm();
 
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('upload'), $request->getFiles('upload'));
      if ($this->form->isValid())
      {
	$cwd = getcwd();
	if (!@chdir(sfConfig::get('app_juricaf_xmlwebdir'))) {
	  $this->getUser()->setFlash('error', "Cannot access xmlwebdir ".sfConfig::get('app_juricaf_xmlwebdir')." directory");
	  return;
	}

	$today = date('Y-m-d');
	if (! $this->createAndChangeToRelativeDir($today)) {
	  return;
	}
	
	if ($pays = $this->form->getValue('pays')) {
	  if (! $this->createAndChangeToRelativeDir('pays_'.$pays))
	    return;
	}
	
	if ($juri = $this->form->getValue('juridiction')) {
	  if (! $this->createAndChangeToRelativeDir('juridiction_'.$juri))
	    return;
	}
	
	$file = $this->form->getValue('file');
	if (preg_match('/zip$/', $file->getType() ) ) {
	  exec("unzip ".$file->getTempName());
	  $this->getUser()->setFlash('notice', "L'archive ".$file->getOriginalName().' a été intégrée');
	  return $this->redirect('@recherche');
	}
	if (preg_match('/xml/', $file->getType())) {
	  $file->save($this->dir.DIRECTORY_SEPARATOR.$file->getOriginalName());
	  $this->getUser()->setFlash('notice', "Le fichier xml ".$file->getOriginalName().' a été intégré');
	  return $this->redirect('@recherche');
	}
	$this->getUser()->setFlash('error', "Désolé, ce type de fichier n'est pas supporté");
      }
    }
  }
  public function executeList(sfWebRequest $request) {
    $start = 0;
    $pas = 30;
    $param = array();//'hl' => 'true');

    $this->page = $request->getParameter('page', 1);
    if ($request->getParameter('changed'))
      $this->page = 1;

    $param['sort'] = 'date_arret desc';
    $param['facet.field']= array('type', 'facet_pays', 'facet_juridiction', 'facet_formation', 'facet_section', 'facet_sens_arret', 'facet_type_affaire', 'facet_type_recours', 'facet_fonds_documentaire', 'facet_reseau', 'on_error');
    $param['facet.sort']='index';
    $param['facet']='true';

    $this->colums = array('on_error' => 'Id / Erreur', 'type'=>'Publication', 'facet_pays' => 'Pays', 'facet_juridiction' => 'Juridiction', 'facet_formation' => 'Formation', 'facet_section' => 'Section', 'facet_sens_arret' => 'Sens Arret', 'facet_type_affaire' => 'Type affaire', 'facet_type_recours' => 'Type recours', 'facet_fonds_documentaire' => 'Fonds documentaire', 'facet_reseau' => 'Réseau');
    
    $this->qa = $request->getParameter('qa');
    $solr_query = $this->qa;
    $this->options = array();
    if ($request->getParameter('page_suivante'))
	$this->page++;
    if ($request->getParameter('page_precedente'))
	$this->page--;

    foreach ($this->colums as $k => $l) {
      if ($p = $request->getParameter($k)) {
	$solr_query .= ' '.$k.':"'.$p.'"';
	$this->options[$k] = 1;
      }
    }

    if (!$solr_query)
      $solr_query = '(type:arret OR type:error_arret)';
    
    $solr = new sfBasicSolr();

    $this->resultats = $solr->search($solr_query, $start + ($this->page - 1)*$pas, $pas*$this->page, $param);
    $this->maxpage = floor($this->resultats->response->numFound / $pas);
    $this->facets = array();
    if (isset($this->resultats->facet_counts))
      foreach($this->resultats->facet_counts->facet_fields as $k => $f) {
	foreach ($f as $n => $v) {
	  if ($v)
	    $this->facets[$k][$n] = $v;
	}
      }
  }
}
