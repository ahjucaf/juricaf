<?php
// Token
@session_start();
$token = sha1(mt_rand());
$_SESSION['token'] = $token;
$_SESSION['cap1'] = intval(rand(0, 10) + 1);
$_SESSION['cap2'] = intval(rand(0, 10) + 1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="title" content="Juricaf - Contact" />
    <meta name="description" content="La base de données de jurisprudence francophone" />
    <meta name="keywords" content="jurisprudence, cassation, cour, suprême, francophone, francophonie, ahjucaf, arrêt, décision" />
    <meta name="language" content="fr" />
    <title>Juricaf - Contact</title>
    <link rel="shortcut icon" href="/images/favicon.ico" />
    <link rel="stylesheet" type="text/css" media="screen" href="/css/main.css" />
    <style type="text/css">
    td, th { padding: 10px; }
    </style>
    <script type="text/javascript" src="/js/jquery-1.6.1.min.js"></script>
    <script type="text/javascript" src="/js/jquery.scrollTo-min.js"></script>
    <link rel="search" href="/juricaf.xml" title="Rechercher sur Juricaf" type="application/opensearchdescription+xml" />
  </head>
  <body>
    <div class="site">
      <div class="head">
               <div class="menu">
          <ul>
            <li><a href="http://www.juricaf.org/documentation/a_propos.php">A propos</a></li>
            <li><a href="http://www.juricaf.org/documentation/stats/statuts.php">Etendue des collections</a></li>
            <li><a href="http://www.juricaf.org/documentation/partenaires.php">Partenaires</a></li>
            <li><a href="http://www.juricaf.org/documentation/mentions_legales.php">Mentions légales</a></li>
	    <li><a href="http://www.juricaf.org/documentation/contact.php">Contact</a></li>
			
            </ul>
        </div>
      </div>
	  <div class="reseaux_sociaux">

	   <a href="https://www.facebook.com/Juricaf" target="_blank"><img src="/images/facebook.png" alt="Facebook" title="Devenez fan sur Facebook"/></a>
          <a href="http://twitter.com/juricaf" target="_blank"><img src="/images/twitter.png" alt="Twitter" title="Suivez nous sur Twitter"/></a>
	<a href="https://itunes.apple.com/fr/app/id587420315?mt=8&affId=1578782" target="_blank"><img src="/images/appstore.png" alt="Appstore" title="Accs  la version IPhone"/></a>
      </div>
      <div class="main">
        <div class="content">
          <div class="form_recherche">
            <form method="get" action="/recherche">
            <table summary="Rechercher">
              <tr>
                <td>
                <a href="http://www.juricaf.org/recherche"><img id="logo" src="/images/juricaf.png" alt="Juricaf" /></a><br />
                <span style="font-family: Georgia; font-style: italic; color: #4E4C4D;">La jurisprudence francophone des cours suprêmes</span><br />
                <input type="text" style="width: 300px; margin-top: 5px;" name="q" value="" tabindex="10" /><br />
                <input type="submit" value="Rechercher" tabindex="20" /> <a href="#">recherche avancée</a>
                </td>
              </tr>
            </table>
            </form>
          </div>
          <div class="arret">
		  
		  
		   <h1>Formulaire de contact</h1>

            <form action="form2mail.php" method="post">
            <table>
            <tr><td>Entrez votre adresse mail :</td><td><input type="text" name="email" /></td></tr>
            <tr><td style="vertical-align: top;">Message :</td><td>
            <textarea name="message" rows="8" cols="50"></textarea></td></tr>
            <tr><td>Captcha :</td><td><?php echo $_SESSION['cap1']; ?> + <?php echo $_SESSION['cap2']; ?> = <input type="text" name="captcha" size=4 /></td></tr>
            <tr><td colspan="2"><input name="token" type="hidden" value="<?php echo $token; ?>" />
            <input type="checkbox" required=required/>  L’AHJUCAF traite les données recueillies pour la gestion des commentaires, avis et questions déposés par les usagers par le biais de ce formulaire<br/>
            <br/>
            <input type="submit" value="Envoi" /></td></tr>
            </table>
            </form>
           
          </div>
        </div>
      </div>
	 <a href="http://www.ahjucaf.org/"><img style="margin-left: 10px;" src="/images/ahjucaf_small.png" alt="Association des cours judiciaires suprêmes francophones" /></a>
	 <img style="float: right; margin-right: 10px;" src="/images/francophonie.png" alt="Organisation internationale de la francophonie" />
      <div class="bottom">
        <p>Juricaf est un projet de l'AHJUCAF, l'association des cours judiciaires suprêmes francophones,<br />
        réalisé en partenariat avec le Laboratoire Normologie Linguistique et Informatique du droit (Université&nbsp;Paris&nbsp;I).<br />
        Il est soutenu par l'Organisation internationale de la Francophonie et le Fonds francophone des inforoutes.</p>
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
</html>
