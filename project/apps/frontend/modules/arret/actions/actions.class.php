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

  public function executeRedirect2Admin(sfWebRequest $request)
  {
      $id = $request->getParameter('id');
      return $this->redirect(sfConfig::get('app_admin_baseurl').$id);
  }

  public function executeImports(sfWebRequest $request)
  {
    $this->db  = sfCouchConnection::getInstance();
    if ($request->getParameter('selectedDate')) {
      $selectedDate = new DateTime($request->getParameter('selectedDate'));
    } else {
      $selectedDate = new DateTime();
    }

    $thirtyDaysAgo = clone $selectedDate;
    $thirtyDaysAgo->modify('-30 days');

    $startDate = $thirtyDaysAgo->format('Y-m-d');
    $endDate = $selectedDate->format('Y-m-d');

    $startKey = json_encode([substr($startDate, 0, 4), $startDate]);
    $endKey = json_encode([substr($endDate, 0, 4), $endDate]);

    $this->imports = $this->db->get('_design/stats/_view/import_pays_juridiction?group_level=3&startkey=' . $endKey  . '&endkey=' . $startKey . '&descending=true&reduce=true')->rows;
    $this->selectedDate = $selectedDate->format('d-m-Y');
  }

}
