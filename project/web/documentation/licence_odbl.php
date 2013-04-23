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
    <title>Juricaf - Licence ODBL</title>
    <link rel="shortcut icon" href="/images/favicon.ico" />
    <link rel="stylesheet" type="text/css" media="screen" href="/css/main.css" />

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
     
	 <h1>Résumé de la licence ODbL 1.0 fr</h1>


     <p>Ceci est le résumé explicatif de <a href="http://www.vvlibri.org/fr/licence/odbl/10/fr/legalcode">la licence ODbL 1.0</a>. Merci de lire l'avertissement ci-dessous.</p>

<h2>Vous êtes libres :</h2>

<ul style="list-style-type: none;">
<li><img src="img/share.png" class="bb-image" /> <span style="font-style:italic">De partager :</span> copier, distribuer et utiliser la base de données.</li>
<li><img src="img/create.png" alt="" class="bb-image" /> <span style="font-style:italic">De créer :</span> produire des créations à partir de cette base de données.</li>

<li><img src="img/adapt.png" alt="" class="bb-image" /> <span style="font-style:italic">D'adapter :</span> modifier, transformer et construire à partir de cette base de données.</li>
</ul>

<h2>Aussi longtemps que :</h2>

<ul style="list-style-type: none;">
<li><img src="img/attribute.png" alt="" class="bb-image" /> <span style="font-style:italic">Vous mentionnez la paternité :</span> vous devez mentionnez la source de la base de données pour toute utilisation publique de la base de données, ou pour toute création produite à partir de la base de données, de la manière indiquée dans l'ODbL. Pour toute utilisation ou redistribution de la base de données, ou création produite à partir de cette base de données, vous devez clairement mentionner aux tiers la licence de la base de données et garder intacte toute mention légale sur la base de données originaire.</li>
<li><img src="img/share_alike2.png" alt="" class="bb-image" /> <span style="font-style:italic">Vous partagez aux conditions identiques :</span> si vous utilisez publiquement une version adaptée de cette base de données, ou que vous produisiez une création à partir d'une base de données adaptée, vous devez aussi offrir cette base de données adaptée selon les termes de la licence ODbL.</li>

<li><img src="img/keep_open.png" alt="" class="bb-image" /> <span style="font-style:italic">Gardez ouvert :</span> si vous redistribuez la base de données, ou une version modifiée de celle-ci, alors vous ne pouvez utiliser de mesure technique restreignant la création que si vous distribuez aussi une version sans ces restrictions.</li></ul>

<h2>Avertissement</h2>

<p>Le résumé explicatif n'est pas un contrat, mais simplement une source pratique pour faciliter la compréhension de la version complète de la licence ODbL 1.0 — il exprime en termes courants les principales notions juridiques du contrat. Ce résumé explicatif n'a pas de valeur juridique, son contenu n'apparaît pas sous cette forme dans le contrat. Seul le <a href="licence_odbl_juricaf.pdf">texte complet du contrat de licence</a> fait loi.</p>
          </div>
        </div>
      </div>
      <div class="bottom">
        <p>Juricaf est un projet de l'<a href="http://www.ahjucaf.org">AHJUCAF</a>, l'association des cours judiciaires suprêmes francophones,<br />
        réalisé en partenariat avec le Laboratoire Normologie Linguistique et Informatique du droit (Université Paris I).<br />
        Il est soutenu par l'<a href="http://www.francophonie.org">Organisation internationale de la Francophonie</a>,
        le <a href="http://inforoutes.francophonie.org">Fonds francophone des inforoutes</a> et les réseaux institutionnels francophones.</p>
        <a href="http://www.ahjucaf.org/"><img style="margin-left: 10px;" src="/images/ahjucaf.png" alt="Association des cours judiciaires suprêmes francophones" /></a>
        <a href="http://www.francophonie.org/"><img style="float: right; margin-right: 10px;" src="/images/francophonie.png" alt="Organisation internationale de la francophonie" /></a>
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
