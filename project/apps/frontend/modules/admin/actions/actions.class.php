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
}
