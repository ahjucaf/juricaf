<?php
@session_start();

/* les variables proviennent d'une page en UTF-8 (si on ne met pas cette ligne, iso-8859-1 est utilisé par défaut donc losque les variables proviennent d'une page en iso, il vaut mieux mettre cette ligne avec iso-8859-15 pour que le signe € soit reconnu) */
header('Content-type: text/html; charset=UTF-8');

/* Initialisation des variables */
$from = "juricaf@ahjucaf.org"; // l'expéditeur : remplacer ici domaine.ext par votre domaine
$to = "Guillaume Adreani <guillaume.adreani@ahjucaf.org>"; // le destinataire : mettez ici votre adresse mail valide (ou plusieurs séparé par des virgules. ex : Paul <paul@mail.com>, Jacques <jacques@mail.com>

/* Préparation */
$subject = "Contact Juricaf"; // le sujet du mail
$token = NULL;
$email = NULL;
$message = NULL;

/* Eviter le spam (permet d'être sur que le visiteur provient bien du site) */
if(isset($_POST['token'])) {
  if($_SESSION['token'] !== $_POST['token']) {
    $resultat = "Erreur: Votre session a expiré, veuillez recharger la page du formulaire pour en initier une nouvelle.";
  } else { $token = true; }
} else { $resultat = "Erreur: Token manquant."; }

//$token = true;

/* Récupération du champs email */
if ($token && !empty($_POST['email'])) {
  /* Nettoyage */
  $test_mail = htmlentities(strip_tags($_POST['email']));
  /* Vérification que c'est bien un email valide */
  if(preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z]{2,4}$/', $test_mail)) {
    $email = $test_mail;
  }
}
/* Récupération du champs message */
if ($token && $email && !empty($_POST['message'])) {
  /* Nettoyage */
  $cleaned_message = htmlspecialchars(strip_tags(iconv("UTF-8", "ISO-8859-15//TRANSLIT", $_POST['message']))); // Si les variables proviennent d'une page en iso-8859-1(5), il ne faut pas utiliser iconv (ex: $cleaned_message = htmlspecialchars(strip_tags($_POST['message'])); ) et modifier en conséquence le charset de la meta http-equiv dans le html. Si c'est de l'UTF8, utf8_decode($_POST['message']) peut être utilisé à la place d'iconv lorsque celui-ci n'est pas installé sur le serveur mais le signe € ne sera pas reconnu et remplacé par des "?".

  /* Adresse IP */
  $ip = "\n\n-----------------------\n\nAdresse IP de l'expediteur : ".$_SERVER["REMOTE_ADDR"];

  /* Message */
  $message = "Message transmis par ".$email." :\n\n".$cleaned_message.$ip;
}

/* Envoi*/
if ($token && $email && $message)
{
  /* En-têtes obligatoires du message */
  $headers = "From: Webmaster <".$from.">\n";
  //$headers .= "To: Contact <".$to.">\n"; (la variable $to est utilisée à la place)
  $headers .= "MIME-Version: 1.0\n";
  $headers .= "Content-type: text/plain; charset=iso-8859-15\n";

  /* Appel a la fonction mail */
  if (!mail($to, $subject, $message, $headers)){
    $resultat = "Erreur: Impossible d'envoyer le mail";
  } else {
    $resultat = "Envoi réussi";
  }
}
else {
  if(empty($resultat)) {
    $resultat = "Erreur: vous devez spécifier une adresse email valide et un texte\n";
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="fr" />
    <title>Contacter Juricaf</title>
  </head>
  <body>
  <?php echo $resultat; ?>
  </body>
</html>