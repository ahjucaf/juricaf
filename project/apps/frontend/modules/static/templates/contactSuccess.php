<?php $sf_response->setTitle("Juricaf - Contact"); ?>
<div class="container">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Accueil</a></li>
      <li class="breadcrumb-item"><a href="">Contact</a></li>
    </ol>
    <h5 class="p-3 mb-2 bg-secondary bg-title fw-bold ">Contact</h5>
</div>

    <div class="container text-justify mt-3">
		   <h5 class="p-3 mb-2 bg-secondary bg-gradient">Formulaire de contact</h5>
<?php if (isset($resultat)): ?>
<div class="alert alert-warning" role="alert">
<?php echo $resultat; ?>
</div>
<div id="noscript">
<p class="alert alert-danger" role="alert">
    Cette page nécessite l'activation du javascript. Si ne souhaitez pas le faire, vous pouvez vous <a href="https://www.ahjucaf.org/page/contactez-nous">contacter l'ahjucaf ici<a/>.
</p>
</div>
<script>
    document.getElementById('noscript').innerHTML = "";
</script>
<?php endif; ?>
       <div class="container">
            <form method="post">
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="text" name="email" value="<?php echo $email; ?>" class="form-control" placeholder="Email">
              </div>
              <div class="mb-3">
                <label class="form-label">Message</label>
                <textarea name="message" class="form-control" rows="8" cols="50" placeholder="Message"><?php echo $message; ?></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label">Captcha</label><br>
                <span id="captcha"><?php echo $cap1; ?> + <?php echo $cap2; ?> = </span>
                <span id="captcha_input"><input type="text" name="captcha" size=4 /></span>
              </div>
              <div class="mb-3 form-check">
                <input name="token" id="tocken" type="hidden" value="<?php echo $token; ?>" />
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" id="check_rgpd_ahjucaf" required=required/>
                    L’AHJUCAF traite les données recueillies pour la gestion des commentaires, avis et questions déposés par les usagers par le biais de ce formulaire
                </label>
              </div>
              <input type="hidden" name="tocken" value="<?php echo $token; ?>"/>
              <button type="submit" class="btn btn-primary mt-3" value="Envoi">Envoyer</button>
            </form>
          </div>
        </div>
      </div>
      </div>
<script src="/scripts/contact.js" type="text/javascript"></script>
