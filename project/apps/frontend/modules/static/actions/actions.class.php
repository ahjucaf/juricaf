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
        $from = "juricaf@ahjucaf.org"; // l'expéditeur : remplacer ici domaine.ext par votre domaine
        $to = "Juricaf <sgahjucaf@ahjucaf.org>"; // le destinataire : mettez ici votre adresse mail valide (ou plusieurs séparé par des virgules. ex : Paul <paul@mail.com>, Jacques <jacques@mail.com>
        
        /* Préparation */
        $subject = "Contact Juricaf"; // le sujet du mail
        $this->email = $_POST['email'];
        $message = NULL;
        $this->message = $_POST['message'];

        if(!isset($_POST['token']) || ($_SESSION['token'] !== $_POST['token']) || $_SESSION['cap1'] + $_SESSION['cap2'] != $_POST['captcha']) {
            $this->resultat = "Mauvais Captcha";
            $this->token = sha1(mt_rand());
            $_SESSION['token'] = $this->token;
            return sfView::SUCCESS;
        }
        
        /* Récupération du champs email */
        if (empty($_POST['email']) || empty($_POST['message'])) {
            $this->resultat = "Erreur: vous devez spécifier une adresse email valide et un texte\n";
            $this->token = sha1(mt_rand());
            $_SESSION['token'] = $this->token;
            return sfView::SUCCESS;
        }
        /* Nettoyage */
        $test_mail = htmlentities(strip_tags($_POST['email']));
        /* Vérification que c'est bien un email valide */
        if(preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z]{2,4}$/', $test_mail)) {
            $this->email = $test_mail;
        }
        /* Nettoyage */
        $cleaned_message = htmlspecialchars(strip_tags(iconv("UTF-8", "ISO-8859-15//TRANSLIT", $_POST['message']))); // Si les variables proviennent d'une page en iso-8859-1(5), il ne faut pas utiliser iconv (ex: $cleaned_message = htmlspecialchars(strip_tags($_POST['message'])); ) et modifier en conséquence le charset de la meta http-equiv dans le html. Si c'est de l'UTF8, utf8_decode($_POST['message']) peut être utilisé à la place d'iconv lorsque celui-ci n'est pas installé sur le serveur mais le signe € ne sera pas reconnu et remplacé par des "?".
        
        /* Adresse IP */
        $ip = "\n\n-----------------------\n\nAdresse IP de l'expediteur : ".$_SERVER["REMOTE_ADDR"];
        
        /* Message */
        $message = "Message transmis par ".$this->email." :\n\n".$cleaned_message.$ip;
        
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
            //$headers .= "To: Contact <".$to.">\n"; (la variable $to est utilisée à la place)
            $headers .= "MIME-Version: 1.0\n";
            $headers .= "Content-type: text/plain; charset=iso-8859-15\n";
            
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
