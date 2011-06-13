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
    $this->forward404If($this->document->isNew());
  }

  public function executeXml(sfWebRequest $request)
  {
    $this->document = new JuricafArret($request->getParameter('id'));
    $this->forward404If($this->document->isNew());
    $this->setLayout(false);
    $this->getResponse()->setContentType('text/xml');
  }
  public function executeStats(sfWebRequest $request)
  {
    return ;
  }
}
