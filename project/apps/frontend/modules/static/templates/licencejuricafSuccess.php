<?php
// Token
@session_start();
$token = sha1(mt_rand());
$_SESSION['token'] = $token;
?>
<div class="container">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Accueil</a></li>
      <li class="breadcrumb-item"><a href="/documentation/mentions_legales.php">Mentions légales</a></li>
      <li class="breadcrumb-item"><a href="">Licence Ahjucaf</a></li>
    </ol>
</div>
    <div class="container text-justify mt-5">
      <h5 class="p-3 mb-2 bg-secondary bg-gradient">Licence AHJUCAF </h5>
      <p>Pour pouvoir bénéficier des arrêts publiés au format XML ou PDF dans Juricaf, merci de prendre contact avec le Secrétariat général de l'AHJUCAF :
      <h5 class="p-3 mb-2 bg-secondary bg-gradient">Formulaire de contact</h5>
      <form action="form2mail.php" method="post">
        <div class="form-group row">
          <label class="col-sm-2 col-form-label"> Email:</label>
          <div class="col-sm-10">
            <input type="text" name="email" class="form-control" id="inputPassword" placeholder="Entrez votre adresse mail">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Message:</label>
          <div class="col-sm-10">
            <textarea name="message" class="form-control" rows="8" cols="50"></textarea>
          </div>
        </div>
        <input name="token" type="hidden" value="<?php echo $token; ?>" />
        <input class="btn btn-primary mt-3" type="submit" value="Envoyer le mail" />
      </form>
    </div>
  </div>
</div>
