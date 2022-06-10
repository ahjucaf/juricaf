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
    public function executeContact(sfWebRequest $request) {

        @session_start();
        
        if (!isset($_POST['email'])) {
            $this->token = sha1(mt_rand());
            $_SESSION['token'] = $this->token;
            $_SESSION['cap1'] = intval(rand(0, 10) + 1);
            $_SESSION['cap2'] = intval(rand(0, 10) + 1);
            return sfView::SUCCESS;
        }
        /* Initialisation des variables */
        $from = "juricaf@ahjucaf.org";
        $to = "sgahjucaf@ahjucaf.org";
        
        /* Préparation */
        $subject = "[Juricaf] Demande de contact"; // le sujet du mail
        $this->email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $this->email = filter_var($this->email, FILTER_VALIDATE_EMAIL);
        $this->message = NULL;

        if(!isset($_POST['token']) || ($_SESSION['token'] !== $_POST['token']) || $_SESSION['cap1'] + $_SESSION['cap2'] != $_POST['captcha']) {
            $this->resultat = "Mauvais Captcha";
            $this->token = sha1(mt_rand());
            $_SESSION['token'] = $this->token;
            return sfView::SUCCESS;
        }
        
        /* Récupération du champs email */
        if (empty($this->email) || empty(htmlspecialchars($_POST['message']))) {
            $this->resultat = "Erreur: vous devez spécifier une adresse email valide et un texte\n";
            $this->token = sha1(mt_rand());
            $_SESSION['token'] = $this->token;
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
            $this->token = sha1(mt_rand());
            $_SESSION['token'] = $this->token;
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
                $this->resultat = "Envoi réussi";
            }
        }
        
    }
    
    public function executePage(sfWebRequest $request) {
        $this->setTemplate(str_replace('_', '', $request->getParameter('template')));
    }
}
