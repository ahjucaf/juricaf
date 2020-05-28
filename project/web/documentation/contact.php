<?php
// Token
@session_start();
$token = sha1(mt_rand());
$_SESSION['token'] = $token;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="title" content="Juricaf - Contact" />
    <meta name="description" content="La base de données de jurisprudence francophone" />
    <meta name="keywords" content="jurisprudence, cassation, cour, suprême, francophone, francophonie, ahjucaf, arrêt, décision" />
    <meta name="language" content="fr" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juricaf - Contact</title>
    <link rel="shortcut icon" href="/images/favicon.ico" />
    <link rel="stylesheet" type="text/css" media="screen" href="/css/main.css" />
    <style type="text/css">
    td, th { padding: 10px; }
    </style>
    <script type="text/javascript" src="/js/jquery-1.6.1.min.js"></script>
    <script type="text/javascript" src="/js/jquery.scrollTo-min.js"></script>
    <script type="text/javascript">function openMenu() {var menu = document.getElementById("menu"); if (menu.style.display === "block") {menu.style.display = "none";} else {menu.style.display = "block";}}</script>
    <link rel="search" href="/juricaf.xml" title="Rechercher sur Juricaf" type="application/opensearchdescription+xml" />
  </head>
  <body>
    <div class="site">
      <div class="head">


        <div class="mobile_navigation" >
          <a onclick="openMenu()"><i id="icon_menu"></i></a>
          <h1 id="headline"><a href="../index.php">JURICAF.ORG</a></h1>
          <img class="menu_logo" src="/images/juricaf.png">
        </div>

        <div class="menu" id="menu">
          <ul>
            <li><a href="a_propos.php">A propos</a></li>
            <li><a href="stats/statuts.php">Etendue des collections</a></li>
            <li><a href="partenaires.php">Partenaires</a></li>
            <li><a href="mentions_legales.php">Mentions légales</a></li>
	          <li><a href="contact.php">Contact</a></li>
          </ul>
        </div>

      </div>
	  <div class="reseaux_sociaux">

	   <a href="https://www.facebook.com/Juricaf" target="_blank"><img src="/images/facebook.png" alt="Facebook" title="Devenez fan sur Facebook"/></a>
          <a href="http://twitter.com/juricaf" target="_blank"><img src="/images/twitter.png" alt="Twitter" title="Suivez nous sur Twitter"/></a>
	<a href="https://itunes.apple.com/fr/app/id587420315?mt=8&affId=1578782" target="_blank"><img src="/images/apple.png" alt="Appstore" title="Accs  la version IPhone"/></a>
      </div>
      <div class="main">
        <div class="content">

          <div class="form_recherche">
            <form method="get" action="/recherche">
              <table summary="Rechercher">
                <tbody><tr>
                  <td width="104px" height="250px" align="center">
                    <a href="../index.php"><img id="logo" src="/images/juricaf.png" alt="Juricaf"></a><br>
                    <div id="slogan"><h2>La jurisprudence francophone des cours suprêmes</h2></div>
                  <input type="text" name="q" tabindex="10" placeholder="Rechercher parmi  1  050&nbsp; 541  décisions provenant de 45 pays et institutions francophones"><br>
                  <input type="submit" value="Rechercher" tabindex="20"> <a id="btn_avance" href="https://juricaf.org/recherche_avancee">Recherche avancée</a>
                  </td>
                </tr></tbody>
              </table>
            </form>
          </div>

          <div class="arret">


		   <h1>Formulaire de contact</h1>

            <form action="form2mail.php" method="post">
            <p>
            Entrez votre adresse mail: <input type="text" name="email" /><br />
            Message:<br />
            <textarea name="message" rows="8" cols="25" ></textarea><br />
            <input name="token" type="hidden" value="<?php echo $token; ?>" />
            <input type="submit" value="Envoi" />
            </p>
            </form>


            <h1>Réalisation du projet </h1>
            <p>Juricaf est un projet de l'AHJUCAF, l'association des cours suprêmes judiciaires francophones, réalisé en partenariat avec le laboratoire de normologie, Linguistique et Informatique juridique (LNLI).</p>
            <p>Il est soutenu par l'Organisation internationale de la Francophonie et le Fonds francophone des inforoutes.</p>

            <table summary="Direction de projet (AHJUCAF)">
              <tr>
              <h1 style="text-align:center;font-weight:bold;" >Direction de projet (AHJUCAF)</h1>
              </tr>
              <tr>
                <td><img src="img/loriferne.jpg" height="141" alt="Dominique Loriferne" /><td>M. Dominique Loriferne<br />Secrétaire général de l’AHJUCAF<br />Président de chambre honoraire <br />de la Cour de cassation </td></td>
                <td><img src="img/bcorboz.jpg" height="141" alt="Bernard Corboz" /><td>M. Bernard Corboz<br />Juge au Tribunal fédéral suisse<br />Responsable de la diffusion du droit<br /> à l’AHJUCAF</td></td>

            </table>

            <h1>Maîtrise d'oeuvre </h1>

            <table>
              <tr>
		            <td><img src="img/gadreani.jpg" height="141" alt="Guillaume Adreani" /><td>M. Guillaume Adreani<br> (direction de projet 2006-2013) <br />
               <a href="http://fr.linkedin.com/in/guillaumeadreani"><img src="http://www.linkedin.com/img/webpromo/btn_liprofile_blue_80x15_fr_FR.png?locale=" width="80" height="15" border="0" alt="Voir le profil de Stéphane Cottin sur LinkedIn"></a></td>

                <td><img src="img/s_cottin.jpg" height="141" alt="Stéphane Cottin" /></td><td>M. Stéphane Cottin<br />
                <a href="http://fr.linkedin.com/in/cottin"><img src="http://www.linkedin.com/img/webpromo/btn_liprofile_blue_80x15_fr_FR.png?locale=" width="80" height="15" border="0" alt="Voir le profil de Stéphane Cottin sur LinkedIn"></a>	</td>

                <td><img src="img/j_gasnault.jpg" height="141" alt="Jean Gasnault" /></td><td>M. Jean Gasnault<br />
                  <a href="http://fr.linkedin.com/pub/jean-gasnault/1/a22/606"><img src="http://www.linkedin.com/img/webpromo/btn_liprofile_blue_80x15_fr_FR.png?locale=" width="80" height="15" border="0" alt="Voir le profil de Jean Gasnault sur LinkedIn"></a></td>

              </tr>
            </table>
            <br />
            <table summary="Réalisation informatique">
              <tr>
                <th colspan="2"><h2 style="text-align: center;">Réalisation informatique</h2></th>
              </tr>
              <tr>
                <td><img src="img/bperson.jpg" height="141" alt="Brice Person" /><td>M. Brice Person <br /><a id="mail" href="#">email</a></td></td>
                <td><img src="img/tmorlier.jpg" height="141" alt="Tangui Morlier" /><td>M. Tangui Morlier <br /><a href='http://tangui.eu.org'>http://tangui.eu.org</a></td>
              </tr>

            </table>


          </div>
        </div>
      </div>

    </div>
    <script type="text/javascript">
    <!--

	    $(document).ready(function() {
      adresse = 'brice.persong@htzenordi.fr';
      $('#mail').attr('href', 'mailto:'+adresse.replace(RegExp('(g@ht)','g'),'@'));
    });



    function juricafSearch() {
      if (window.external && ("AddSearchProvider" in window.external)) {
        window.external.AddSearchProvider("http://www.juricaf.org/juricaf.xml");
      }
      else {
         alert("Votre navigateur ne supporte pas cette fonctionnalité");
      }
    }

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-8802834-4']);
    _gaq.push(['_trackPageview']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
    // -->
    </script>
  </body>

   <footer>
     <a href="http://www.ahjucaf.org/"><img style="height:100px;"  src="/images/ahjucaf_small.png" alt="Association des cours judiciaires suprêmes francophones"></a>
     <img style="width: 150px;height: 80px;float:right;margin-right:1%;" src="/images/francophonie.png" alt="Organisation internationale de la francophonie">
     <div class="top_page"><a id="top" href="#">Haut de page<img src="/images/fleche_haut.png" alt="Haut de page"></a></div>
     <div class="footer_content">
          <p>Juricaf est un projet de l'AHJUCAF, l'association des cours judiciaires suprêmes francophones,<br>
          réalisé en partenariat avec le Laboratoire Normologie Linguistique et Informatique du droit (Université&nbsp;Paris&nbsp;I).<br>
          Il est soutenu par l'Organisation internationale de la Francophonie et le Fonds francophone des inforoutes.</p>
    </div>
   </footer>
</html>
