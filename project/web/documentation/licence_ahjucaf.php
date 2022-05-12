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
    <meta name="title" content="Juricaf" />
<meta name="description" content="La base de données de jurisprudence francophone" />
<meta name="keywords" content="jurisprudence, cassation, cour, suprême, francophone, francophonie, ahjucaf, arrêt, décision" />
<meta name="language" content="fr" />
    <title>Juricaf - Licence AHJUCAF</title>
    <link rel="shortcut icon" href="/images/favicon.ico" />
    <link rel="stylesheet" type="text/css" media="screen" href="/css/main.css" />

    <script type="text/javascript" src="/js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="/js/jquery.scrollTo-min.js"></script>
    <link rel="search" href="/juricaf.xml" title="Rechercher sur Juricaf" type="application/opensearchdescription+xml" />
  </head>
  <body>
    <div class="site">
     <div class="menu">
        <div class="menu">
          <ul>
            <li><a href="http://www.juricaf.org/documentation/a_propos.php">À propos</a></li>
            <li><a href="http://www.juricaf.org/documentation/stats/statuts.php">Étendue des collections</a></li>
            <li><a href="http://www.juricaf.org/documentation/partenaires.php">Partenaires</a></li>
            <li><a href="http://www.juricaf.org/documentation/mentions_legales.php">Mentions légales</a></li>
	    <li><a href="http://www.juricaf.org/documentation/contact.php">Contact</a></li>
			
            </ul>
        </div>
      </div>
	  <div class="reseaux_sociaux">

    <a href="https://www.facebook.com/AHJUCAFCoursSupremesJudiciairesFrancophones" target="_blank"><img src="/images/facebook.png" alt="Facebook" title="Devenez fan sur Facebook"/></a>
          <a href="https://twitter.com/ahjucaf" target="_blank"><img src="/images/twitter.png" alt="Twitter" title="Suivez nous sur Twitter"/></a>
	 </div>
      <div class="main">
        <div class="content">
          <div class="form_recherche">
            <form method="get" action="/recherche">
            <table summary="Rechercher">
              <tr>
                <td>
                  <a href="http://www.juricaf.org/recherche"><img id="logo" src="/images/juricaf.png" alt="Juricaf" /></a><br />
                  <span style="font-family: Georgia; font-style: italic; color: #4E4C4D;">La jurisprudence francophone des Cours suprêmes</span><br />
                  <input type="text" style="width: 300px; margin-top: 5px;" name="q" value="" tabindex="10" /><br />
                  <input type="submit" value="Rechercher" tabindex="20" /> <a href="/recherche_avancee">recherche avancée</a>
                </td>
              </tr>
            </table>
            </form>
          </div>
          <div class="arret">
            <h1>Licence AHJUCAF </h1>
   
   
 <p>Pour pouvoir bénéficier des arrêts publiés au format XML ou PDF dans JURICAF, merci de prendre contact avec le Secrétariat général de l'AHJUCAF : 
   

            <h1>Formulaire de contact</h1>
            <form action="form2mail.php" method="post">
            <p>
              Entrez votre adresse mail: <input type="text" name="email" /><br />
              Message:<br />
              <textarea name="message" rows="8" cols="50"></textarea><br />
              <input name="token" type="hidden" value="<?php echo $token; ?>" />
              <input type="submit" value="Envoyer le mail" />
            </p>
            </form>
          </div>
        </div>
      </div>
      <div class="bottom">
        <!--<p>JURICAF est un projet de l'<a href="http://www.ahjucaf.org">AHJUCAF</a>, l'association des Cours judiciaires suprêmes francophones,<br />
        réalisé en partenariat avec le Laboratoire Normologie Linguistique et Informatique du droit (Université Paris I).<br />
        Il est soutenu par l'<a href="http://www.francophonie.org">Organisation Internationale de la Francophonie</a>.</p>
        <a href="http://www.ahjucaf.org/"><img style="margin-left: 10px;" src="/images/ahjucaf.png" alt="Association des Cours judiciaires suprêmes francophones" /></a>
        <a href="http://www.francophonie.org/"><img style="float: right; margin-right: 10px;" src="/images/francophonie.png" alt="Organisation internationale de la fra$
 --><p>Juricaf est un projet de l'AHJUCAF, l'association des cours suprêmes judiciaires francophones,<br />
 initialement réalisé en partenariat avec le Laboratoire Normologie Linguistique et Informatique du droit (Université Paris I),<br />
      Il est soutenu par l'Organisation Internationale de la Francophonie.</p>
      </div>
    </div>
    <script type="text/javascript">
    <!--
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
