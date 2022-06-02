<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="title" content="Juricaf" />
<meta name="description" content="La base de données de jurisprudence francophone" />
<meta name="keywords" content="jurisprudence, cassation, cour, suprême, francophone, francophonie, ahjucaf, arrêt, décision" />
<meta name="language" content="fr" />
    <title>Juricaf</title>
    <link rel="shortcut icon" href="/images/favicon.ico" />
    <link rel="stylesheet" type="text/css" media="screen" href="/css/main.css" />

<link rel="stylesheet" type="text/css" media="screen" href="/css/jquery-ui-1.8.16.custom.css" />
    <script type="text/javascript" src="/js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="/js/jquery.scrollTo-min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.16.custom.min.js"></script>
    <link rel="search" href="/juricaf.xml" title="Rechercher sur Juricaf" type="application/opensearchdescription+xml" />
  </head>
  <body>
    <div class="site">
      <div class="head">

        <div class="reseaux_sociaux">
          <a href="http://www.parcesmotifs.net/spip.php?page=groupe&amp;id_groupe=12"><img src="/images/help.png" alt="Aide" title="Aide" /></a>
          <a href="https://www.facebook.com/AHJUCAFCoursSupremesJudiciairesFrancophones"><img src="/images/facebook.png" alt="Facebook" title="Devenez fan sur Facebook" /></a>
          <a href="https://twitter.com/#!/juricaf"><img src="/images/twitter.png" alt="Twitter" title="Suivez nous sur Twitter" /></a>
          <a href="http://www.parcesmotifs.net/spip.php?page=backend"><img src="/images/rss.png" alt="RSS" title="Flux RSS" /></a>
          <a href="javascript:juricafSearch();"><img src="/images/juricaf_search.png" alt="J" title="Ajouter Juricaf aux moteurs de recherches de votre navigateur" /></a>
        </div>
        <div class="menu">
          <ul>

            <li><a href="/documentation/a_propos.php">A propos</a></li>
            <li><a href="/stats">Étendue des collections</a></li>
<!---            <li><a href="#">Outils</a></li> -->
            <li><a href="/documentation/partenaires.php">Partenaires</a></li>
            <li><a href="/documentation/contact.php">Contact</a></li>
            <li><a href="/documentation/mentions_legales.php">Mentions légales</a></li>
          </ul>

        </div>
      </div>
      <div class="main">
        <div class="content">
          <div class="form_recherche">
            <form method="get" action="/recherche">
            <table>
              <tr>
                <td>
                  <a href="/recherche"><img id="logo" src="/images/juricaf.png" alt="Juricaf" /></a><br />
                  <span style="font-family: Georgia; font-style: italic; color: #4E4C4D;">La jurisprudence francophone des cours suprêmes</span><br />
                  <input type="text" style="width: 300px; margin-top: 5px;" name="q" value="" tabindex="10" /><br />
                  <input type="submit" value="Rechercher" tabindex="20" /> <a href="/recherche_avancee">recherche avancée</a>
                </td>
              </tr>
            </table>
            </form>
        </div>
        <hr />
        <h1>Une erreur interne est survenue</h1>
        <p>Ceci peut être du à l'utilisation de caractères spéciaux lors d'une recherche.</p>
        <p><a href="#" id="mail_admin">Nous contacter par email</a></p>
      </div>
      <div class="bottom">
        <p>Juricaf est un projet de l'<a href="http://www.ahjucaf.org">AHJUCAF</a>, l'association des cours judiciaires suprêmes francophones,<br />
        réalisé en partenariat avec le Laboratoire Normologie Linguistique et Informatique du droit (Université&nbsp;Paris&nbsp;I).<br />

        Il est soutenu par l'<a href="http://www.francophonie.org">Organisation internationale de la Francophonie</a>,
        le <a href="http://inforoutes.francophonie.org">Fonds francophone des inforoutes</a> et les réseaux institutionnels francophones.</p>
        <a href="http://www.ahjucaf.org/"><img style="margin-left: 10px;" src="/images/ahjucaf.png" alt="Association des cours judiciaires suprêmes francophones" /></a>
        <a href="http://www.francophonie.org/"><img style="float: right; margin-right: 10px;" src="/images/francophonie.png" alt="Organisation internationale de la francophonie" /></a>
      </div>
    </div>
    <script type="text/javascript">
    <!--
    $(document).ready(function() {
      adresse = 'juricafg@htahjucaf.org';
      $('#mail_admin').attr('href', 'mailto:'+adresse.replace(RegExp('(g@ht)','g'),'@'));
    });

    function juricafSearch() {
      if (window.external && ("AddSearchProvider" in window.external)) {
        window.external.AddSearchProvider("http://v2.juricaf.org/juricaf.xml");
      }
      else {
         alert("Votre navigateur ne supporte pas cette fonctionnalité");
      }
    }

      var gaJsHost = (("https:" == document.location.protocol) ? " https://ssl." : "http://www.");
      document.write(unescape("%3Cscript src='" + gaJsHost + " google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
    // -->
    </script>
    <script type="text/javascript">
    <!--
      try{
      var pageTracker = _gat._getTracker("UA-8802834-4");
      pageTracker._trackPageview("/500.html?page=" + document.location.pathname + document.location.search + "&from=" + document.referrer);
      } catch(err) {}
    // -->
    </script>
  </body>
</html>
