<?php

/**
 * admin actions.
 *
 * @package    juricaf
 * @subpackage admin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class staticActions extends sfActions
{
    public function executePage(sfWebRequest $request) {
        $this->setTemplate(str_replace('_', '', $request->getParameter('template')));
    }
}
