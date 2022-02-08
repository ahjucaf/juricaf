<?php
// Token
@session_start();
$token = sha1(mt_rand());
$_SESSION['token'] = $token;
$_SESSION['cap1'] = intval(rand(0, 10) + 1);
$_SESSION['cap2'] = intval(rand(0, 10) + 1);
?>

<?php include("header.php") ?>
    <div class="arret container text-justify mt-5">
		   <h5 class="p-3 mb-2 bg-secondary bg-gradient">Formulaire de contact</h5>

            <form action="form2mail.php" method="post">
              <table>
                <tr class="mt-">
                  <td>Entrez votre adresse mail :</td>
                  <td><input type="text" name="email" /></td>
                </tr>
                <tr>
                  <td style="vertical-align: top;">Message :</td>
                  <td>
                    <textarea name="message" rows="8" cols="50"></textarea>
                  </td>
                </tr>
                <tr>
                  <td>Captcha :</td>
                  <td><?php echo $_SESSION['cap1']; ?> + <?php echo $_SESSION['cap2']; ?> = <input type="text" name="captcha" size=4 /></td>
                </tr>
                <tr>
                  <td colspan="2">
                    <input name="token" type="hidden" value="<?php echo $token; ?>" />
                    <input type="checkbox" required=required/>  L’AHJUCAF traite les données recueillies pour la gestion des commentaires, avis et questions déposés par les usagers par le biais de ce formulaire<br/>
                    <br/>
                    <input type="submit" value="Envoi" />
                  </td>
                </tr>
              </table>
            </form>
          </div>
        </div>
      </div>

<?php include("footer.php") ?>
