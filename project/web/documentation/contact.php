<?php
// Token
@session_start();
$token = sha1(mt_rand());
$_SESSION['token'] = $token;
$_SESSION['cap1'] = intval(rand(0, 10) + 1);
$_SESSION['cap2'] = intval(rand(0, 10) + 1);
?>

<?php include("header.php") ?>
    <div class="container text-justify mt-5">
		   <h5 class="p-3 mb-2 bg-secondary bg-gradient">Formulaire de contact</h5>
       <div class="container">
            <form action="form2mail.php" method="post">
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="text" name="email" class="form-control" placeholder="Email">
              </div>
              <div class="mb-3">
                <label class="form-label">Message</label>
                <textarea name="message" class="form-control" rows="8" cols="50" placeholder="Message"></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label">Captcha</label><br>
                <?php echo $_SESSION['cap1']; ?> + <?php echo $_SESSION['cap2']; ?> = <input type="text" name="captcha" size=4 />
              </div>
              <div class="mb-3 form-check">
                <input name="token" type="hidden" value="<?php echo $token; ?>" />
                <input type="checkbox" class="form-check-input" required=required/>
                <label class="form-check-label"> L’AHJUCAF traite les données recueillies pour la gestion des commentaires, avis et questions déposés par les usagers par le biais de ce formulaire</label>
              </div>
              <button type="submit" class="btn btn-primary mt-3" value="Envoi">Envoyer</button>
            </form>
          </div>
        </div>
      </div>
      </div>

<?php include("footer.php") ?>
