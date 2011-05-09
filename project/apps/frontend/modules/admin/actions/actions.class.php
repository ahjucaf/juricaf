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
		$this->getUser()->setFlash('error', "Cannot access ".sfConfig::get('app_juricaf_xmlwebdir')." directory");
		return;
	}
	echo sfConfig::get('app_juricaf_xmlwebdir');
	$today = date('Ymd');
	umask(0007);
	@mkdir($today);
	if (! @chdir($today)) {
                $this->getUser()->setFlash('error', "Cannot access ".sfConfig::get('app_juricaf_xmlwebdir')."/$today directory");
                return;
	}
	if ($pays = $this->form->getValue('pays')) {
	  @mkdir('pays_'.$pays);
	  chdir('pays_'.$pays);
	}
	if ($juri = $this->form->getValue('juridiction')) {
	  @mkdir('juridiction_'.$juri);
	  chdir('juridiction_'.$juri);
	}
	$file = $this->form->getValue('file');
	if (preg_match('/zip$/', $file->getType() ) ) {
	  exec("unzip ".$file->getTempName());
	  $this->getUser()->setFlash('notice', "L'archive ".$file->getOriginalName().' a été intégrée');
	  return $this->redirect('@recherche');
	}
	if (preg_match('/xml/', $file->getType())) {
	  $file->save($file->getOriginalName());
	  $this->getUser()->setFlash('notice', "Le fichier xml ".$file->getOriginalName().' a été intégré');
	  return $this->redirect('@recherche');
	}
	$this->getUser()->setFlash('error', "Désolé, ce type de fichier n'est pas supporté");
      }
    }
  }
}
