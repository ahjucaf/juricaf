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
    private function setCaptchaData($in_session = false) {
        $this->token = sha1(mt_rand());
        $min = 0;
        $max = 9;
        if (!$in_session) {
            $min = 10;
            $max = 99;
        }
        $this->cap1 = intval(rand($min, $max) + 1);
        $this->cap2 = intval(rand($min, $max) + 1);
        if ($in_session) {
            $_SESSION['token'] = $this->token;
            $_SESSION['cap1'] = $this->cap1;
            $_SESSION['cap2'] = $this->cap2;
        }
        $_SESSION['captime'] = time();
    }

    public function executeContactJS(sfWebRequest $request) {
        $this->setLayout(false);
        $this->setCaptchaData(true);
        return sfView::SUCCESS;
    }

    public function executeContact(sfWebRequest $request) {

        @session_start();

        if (!isset($_POST['email'])) {
            $this->setCaptchaData();
            return sfView::SUCCESS;
        }
        /* Initialisation des variables */
        $from = "juricaf@ahjucaf.org";
        $to = "sgahjucaf@ahjucaf.org";
        $cc = 'logs@24eme.fr';

        /* Préparation */
        $subject = "[Juricaf] Demande de contact"; // le sujet du mail
        $this->email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $this->email = filter_var($this->email, FILTER_VALIDATE_EMAIL);
        $this->message = NULL;

        /* Récupération du champs email */
        if (empty($this->email) || empty(htmlspecialchars($_POST['message']))) {
            $this->resultat = "Erreur: vous devez spécifier une adresse email valide et un texte\n";
            $this->setCaptchaData();
            return sfView::SUCCESS;
        }

        /* Nettoyage */
        $this->message = htmlentities($_POST['message']);

        /* Message */
        $message  = "Message envoyé depuis https://juricaf.org/static/contact de ".$this->email." :\n";
        $message .= "-----------------------\n\n";
        $message .= $_POST['message'];
        $message .= "\n\n-----------------------\nAdresse IP de l'expediteur : ".$_SERVER["REMOTE_ADDR"];

        if (!$this->email){
            $this->resultat = "Erreur: vous devez spécifier une adresse email valide et un texte\n";
            $this->setCaptchaData();
            return sfView::SUCCESS;
        }

        if (!$_SESSION['captime'] || ((time() - $_SESSION['captime']) < 5)) {

            //Visiblement c'est un robot, donc on ne raconte pas la vérité
            $this->resultat = "Envoi OK";

            unset($_SESSION['cap1']);
            unset($_SESSION['cap2']);
            unset($_SESSION['token']);
            unset($_SESSION['captime']);
            return sfView::SUCCESS;
        }

        if(!isset($_POST['token']) || ($_SESSION['token'] !== $_POST['token']) || ($_SESSION['cap1'] + $_SESSION['cap2'] != $_POST['captcha'])) {
            $this->resultat = "Mauvais Captcha";
            $this->setCaptchaData();
            return sfView::SUCCESS;
        }

        /* Envoi*/
        if ($this->email && $message)
        {
            /* En-têtes obligatoires du message */
            $headers = "From: Webmaster <".$from.">\n";
            $headers .= "Reply-To: ".$this->email."\n";
            //$headers .= "To: Contact <".$to.">\n"; (la variable $to est utilisée à la place)
            $headers .= "MIME-Version: 1.0\n";
            $headers .= "Content-type: text/plain; charset=UTF-8\n";

            /* Appel a la fonction mail */
            if (!mail($to, $subject, $message, $headers)){
                $this->resultat = "Erreur: Impossible d'envoyer le mail";
            } else {
                mail($cc, $subject, $message, $headers);
                $this->resultat = "Envoi réussi";
                unset($_SESSION['cap1']);
                unset($_SESSION['cap2']);
                unset($_SESSION['token']);
                unset($_SESSION['captime']);
            }
        }

    }

    const SITEMAP_LIMIT = 50000;

    public function executePage(sfWebRequest $request) {
        $this->setTemplate(str_replace('_', '', $request->getParameter('template')));
    }

    public function executeSitemapIndex(sfWebRequest $request) {
        $db = sfCouchConnection::getInstance();
        $nbDocs = $db->get('_design/stats/_view/pays_juridiction_date?reduce=true')->rows[0]['value']*1;

        $this->nbSet = ceil($nbDocs / self::SITEMAP_LIMIT);

        $this->setLayout(false);
    }

    public function executeSitemapSet(sfWebRequest $request) {
        $skip = $request->getParameter('numero') * self::SITEMAP_LIMIT;

        $db = sfCouchConnection::getInstance();
        $this->rows = $db->get('_design/stats/_view/pays_juridiction_date?reduce=false&limit='.self::SITEMAP_LIMIT.'&skip='.$skip)->rows;

        $this->setLayout(false);
    }

    public function executeSitemapSetPages(sfWebRequest $request) {

        $this->setLayout(false);
    }
}
