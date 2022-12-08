<?php
/**
 * arret actions.
 *
 * @package    juricaf
 * @subpackage arret
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class arretActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->document = new JuricafArret($request->getParameter('id'));
    $this->pays = str_replace(' ', '_', $this->document->pays);
    $this->forward404If($this->document->isNew());
    $this->forward404If($this->document->isError());
  }

  public function executeMd(sfWebRequest $request)
  {
    $this->document = new JuricafArret($request->getParameter('id'));
    $this->forward404If($this->document->isNew());
    $this->setLayout(false);
  }

  public function executeRaw(sfWebRequest $request)
  {
    $this->document = new JuricafArret($request->getParameter('id'));
    $this->forward404If($this->document->isNew());
    $this->setLayout(false);

    $this->json = false;
    $this->txt = false;
	if($request->getParameter('format') === 'json') {
		$this->json = true;
		$this->getResponse()->setContentType('application/json');

        return ;
	}
    if($request->getParameter('format') === 'txt') {
		$this->txt = true;
		$this->getResponse()->setContentType('text/plain');

        return ;
	}
	$this->getResponse()->setContentType('text/xml');

  }

  public function executeJson(sfWebRequest $request)
  {
    $this->document = new JuricafArret($request->getParameter('id'));
    $this->forward404If($this->document->isNew());
    $this->setLayout(false);
    $this->getResponse()->setContentType('application/json');
  }

  public function executeStats(sfWebRequest $request)
  {
    return ;
  }

  public function executeAttachment(sfWebRequest $request)
  {
    $this->document = new JuricafArret($request->getParameter('id'));
    $this->forward404If($this->document->isNew());
    $this->forward404Unless(isset($this->document->_attachments) && $this->document->_attachments);
    $this->setLayout(false);
    foreach($this->document->_attachments as $uri => $a) {
        $this->getResponse()->setContentType($a['content_type']);
        $this->path = $this->document->getFile($uri);
        return ;
    }
  }
}
