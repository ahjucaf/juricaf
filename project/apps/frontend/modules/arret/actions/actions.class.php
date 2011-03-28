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
    $this->document = new sfCouchDocument($request->getParameter('id', 'BELGIQUE-CONSEILD-ETAT-165880'));
    /*
    echo "<pre>";
    print_r($couchdb);
    echo "</pre>";
    exit;
    */
  }
}
