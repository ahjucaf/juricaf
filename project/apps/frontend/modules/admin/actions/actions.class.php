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
      if (!$this->form->isValid()) {
           return;
      }
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
	  return;
	}
	if (preg_match('/xml/', $file->getType())) {
	  $file->save($this->dir.DIRECTORY_SEPARATOR.$file->getOriginalName());
	  $this->getUser()->setFlash('notice', "Le fichier xml ".$file->getOriginalName().' a été intégré');
	  return;
	}
	$this->getUser()->setFlash('error', "Désolé, ce type de fichier n'est pas supporté");
      }
  }

    public function executeNewArret(sfWebRequest $request) {
        $this->form = new NewArretForm();

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter('upload'), $request->getFiles('upload'));
            if (!$this->form->isValid()) {
                return;
            }
            $cwd = getcwd();
            if (!@chdir(sfConfig::get('app_juricaf_xmlwebdir'))) {
                $this->getUser()->setFlash('error', "Cannot access xmlwebdir " . sfConfig::get('app_juricaf_xmlwebdir') . " directory");
                return;
            }

            $today = date('Y-m-d');
            if (!$this->createAndChangeToRelativeDir($today)) {
                return;
            }

            if ($pays = $this->form->getValue('pays')) {
                if (!$this->createAndChangeToRelativeDir('pays_' . $pays))
                    return;
            }

            if ($juri = $this->form->getValue('juridiction')) {
                if (!$this->createAndChangeToRelativeDir('juridiction_' . $juri))
                    return;
            }


            if (!$this->createAndFillXmlFile($this->form)) {
                return false;
            }
        }
    }

    private function createAndFillXmlFile($form) {
        $pays = $form->getValue('pays');
        $juri = $form->getValue('juridiction');

        var_dump($pays);
        var_dump($juri);

        $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xmlContent .= '<data>' . "\n";
        $xmlContent .= '<pays>' . $pays . '</pays>' . "\n";
        $xmlContent .= '<juridiction>' . $juri . '</juridiction>' . "\n";
        $xmlContent .= '</data>' . "\n";

        $xmlFilePath = 'file.xml';

        $fileHandle = fopen($xmlFilePath, 'w');
        if ($fileHandle === false) {
            return false;
        }

        $writeResult = fwrite($fileHandle, $xmlContent);

        fclose($fileHandle);

        if ($writeResult !== false) {
            return true;
        } else {
            return false;
        }
  }

  private function getIdDocs(sfWebRequest $request) {
    $ids = array();
    $nblines = $request->getParameter('nb_resultats');
    for($i = 1 ; $i <= $nblines ; $i++) {
      if ($id = $request->getParameter('resultat'.$i))
	$ids[] = $id;
    }
    return $ids;
  }

  private function modificationType(sfWebRequest $request) {
    $newType = '';
    $onerror = '';
    if ($request->getParameter('action_publish'))
      $newType = 'arret';
    else if ($request->getParameter('action_error')) {
      $newType = 'error_arret';
      $onerror = 'Mise en erreur manuelle';
    }else if ($request->getParameter('action_delete'))
      $newType = 'delete';
    if (!$newType) return false;
    $document = null;
    $ids = $this->getIdDocs($request);
    foreach ($ids as $id) {
      $document = new JuricafArret($id);
      if ($newType == 'delete') {
	$document->delete();
	continue;
      }
      $document->type = $newType;
      if ($onerror) {
	$document->on_error = $onerror;
      }else{
	$document->on_error = null;
      }
      $document->save();
    }
    if ($document) {
      $this->iscommited = array('champ' => 'type', 'id' => $document->_id, 'valeur'=>$newType);
      if ($newType == 'delete') {
	$this->iscommited['oldid'] = $document->_id;
	unset($this->iscommited['id']);
	$this->getUser()->setFlash('admin_notice', count($ids).' document(s) supprimé(s)');
      }else{
	$msgaction = ($newType == 'error_arret') ? 'mis en erreur' : 'publié(s)';
	$this->getUser()->setFlash('admin_notice', count($ids).' document(s) '.$msgaction);
      }
    }
    return true;
  }

  private static $changeid = array('pays' => 1, 'juridiction' => 1, 'date_arret' => 1, 'arret_num' => 1);

  private function modificationChamps(sfWebRequest $request) {
    $champ = $request->getParameter('modif_champ');
    if (!$champ)
      return false;
    $valeur = $request->getParameter('modif_valeur');
    if (!$champ)
      return false;
    $doc = null; $id = null;
    $ids = $this->getIdDocs($request);
    foreach ($ids as $id) {
      $doc = new JuricafArret($id);
      $doc->{$champ} = $valeur;
      if (isset(self::$changeid[$champ])) {
	$doc = $doc->rename($doc->getTheoriticalId());
      }
      $doc->save();
    }
    if ($doc) {
      $this->iscommited = array('champ' => $champ, 'id' => $doc->_id, 'valeur'=>$valeur);
      if (isset(self::$changeid[$champ]))
	$this->iscommited['oldid'] = $id;
      $this->getUser()->setFlash('admin_notice', count($ids).' document(s) modifié(s)');
    }
    return true;
  }

  private function commitNow(sfWebrequest $request) {
    $document = new JuricafArret('COMMITNOW');
    $document->date = date('c');
    $document->save();
    $solr = new sfBasicSolr();
    $res = null;
    
    //Vérification que le commit est bien pris en compte (au moins sur la requete courrante
    $continue = 1;
    for ($i = 0 ; count($this->iscommited) && $continue && $i < 10 ; $i++) {
      $res = $this->querySolr($request);
      $continue = 0;
      foreach ($res->response->docs as $resultat) {
	if (isset($this->iscommited['id']) && $resultat->id == $this->iscommited['id']) {
	  if( $resultat->{$this->iscommited['champ']} != $this->iscommited['valeur']) {
	    $continue = 1;
	  }
	  break;
	}
	if (isset($this->iscommited['oldid']) && $resultat->id == $this->iscommited['oldid']) {
	  $continue = 1;
	  break;
	}
      }
      if (!$continue)
	break;
      usleep(250000);
    }
    if (count($this->iscommited) && $continue) {
	$this->getUser()->setFlash('admin_error', 'Les modifications effectuées ne sont pas encore visibles : l\'indexer est soit surchargé soit indisponible.');
    }
  }

  private function querySolr(sfWebrequest $request) {
    $start = 0;
    $param = array();

    $this->page = $request->getParameter('page', 1);
    if ($request->getParameter('changed'))
      $this->page = 1;
    
    $param['sort'] = 'date_import desc, id asc';
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
    
    return $solr->search($solr_query, $start + ($this->page - 1)*$this->pas, $this->pas*$this->page, $param);
  }

  public function executeList(sfWebRequest $request) {
    $this->pas = 86;

    if($this->modificationType($request) || $this->modificationChamps($request)) {
      $this->commitNow($request);
      return $this->redirect(preg_replace('/(action|modif)[^&]*/', '', $_SERVER["REQUEST_URI"]));
    }
    $this->commitNow($request);
    $this->resultats = $this->querySolr($request);
    $this->maxpage = floor($this->resultats->response->numFound / $this->pas);
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
