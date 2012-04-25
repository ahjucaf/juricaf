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
         <div class="reseaux_sociaux">
          <a href="http://www.parcesmotifs.net/spip.php?page=groupe&amp;id_groupe=12"><img src="/images/help.png" alt="Aide" title="Aide" /></a>
          <a href="https://www.facebook.com/Juricaf"><img src="/images/facebook.png" alt="Facebook" title="Devenez fan sur Facebook" /></a>
          <a href="https://twitter.com/#!/juricaf"><img src="/images/twitter.png" alt="Twitter" title="Suivez nous sur Twitter" /></a>
          <a href="http://www.parcesmotifs.net/spip.php?page=backend"><img src="/images/rss.png" alt="RSS" title="Flux RSS" /></a>
          <a href="javascript:juricafSearch();"><img src="/images/juricaf_search.png" alt="J" title="Ajouter Juricaf aux moteurs de recherches de votre navigateur" /></a>
        </div>
        <div class="menu">
          <ul>
            <li><a href="/documentation/a_propos.php">A propos</a></li>

            <li><a href="/documentation/stats/statuts.php">Étendue des collections</a></li>
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
            <p>
            Entrez votre adresse mail: <input type="text" name="email" /><br />
            Message:<br />
            <textarea name="message" rows="8" cols="50"></textarea><br />
            <input name="token" type="hidden" value="<?php echo $token; ?>" />
            <input type="submit" value="Envoyer le mail" />
            </p>
            </form>
			
			
            <h1>Réalisation du projet </h1>
            <p>Juricaf est un projet de l'AHJUCAF, l'association des cours suprêmes judiciaires francophones, réalisé en partenariat avec le laboratoire de normologie, Linguistique et Informatique juridique (LNLI).</p>
            <p>Il est soutenu par l'Organisation internationale de la Francophonie et le Fonds francophone des inforoutes.</p>

            <table summary="Direction de projet (AHJUCAF)">
              <tr>
                <th colspan="3"><h1 style="text-align: center;">Direction de projet (AHJUCAF)</h1></th>
              </tr>
              <tr>
                <td><img src="img/jlgillet.jpg" height="141" alt="Jean-Louis Gillet" /></td>
                <td><img src="img/bcorboz.jpg" height="141" alt="Bernard Corboz" /></td>
                <td><img src="img/gadreani.jpg" height="141" alt="Guillaume Adreani" /></td>
              </tr>
              <tr>
                <td>M. Jean-Louis Gillet<br />Secrétaire général de l’AHJUCAF<br />Président de chambre honoraire <br />de la Cour de cassation </td>
                <td>M. Bernard Corboz<br />Juge au Tribunal fédéral suisse<br />Responsable de la diffusion du droit<br /> à l’AHJUCAF</td>
                <td>M. Guillaume Adreani<br />Greffier à la Cour de cassation française<br />Chargé de mission de l'AHJUCAF<br />Responsable du projet Juricaf<br />
				<br /><a href="http://fr.linkedin.com/in/guillaumeadreani">          
                        
            <img src="http://www.linkedin.com/img/webpromo/btn_liprofile_blue_80x15_fr_FR.png?locale=" width="80" height="15" border="0" alt="Voir le profil de Guillaume Adreani sur LinkedIn">

    </a>
				</td>
              </tr>
            </table>

            <h1>Maîtrise d'oeuvre (LNLI)</h1>

            <table summary="Assistance aux questions documentaires">
              <tr>
                <th colspan="2"><h2 style="text-align: center;">Assistance aux questions documentaires</h2></th>
              </tr>
              <tr>
                <td><img src="img/s_cottin.jpg" height="141" alt="Stéphane Cottin" /></td>
                <td><img src="img/j_gasnault.jpg" height="141" alt="Jean Gasnault" /></td>
              </tr>
              <tr>
                <td>M. Stéphane Cottin
				
					<br /><a href="http://fr.linkedin.com/in/cottin">          
                        
            <img src="http://www.linkedin.com/img/webpromo/btn_liprofile_blue_80x15_fr_FR.png?locale=" width="80" height="15" border="0" alt="Voir le profil de Stéphane Cottin sur LinkedIn">

    </a>
				</td>
                <td>M. Jean Gasnault
				
					<br /><a href="http://fr.linkedin.com/pub/jean-gasnault/1/a22/606">          
                        
            <img src="http://www.linkedin.com/img/webpromo/btn_liprofile_blue_80x15_fr_FR.png?locale=" width="80" height="15" border="0" alt="Voir le profil de Jean Gasnault sur LinkedIn">

    </a>
				
				</td>
              </tr>
            </table>
            <br />
            <table summary="Réalisation informatique">
              <tr>
                <th colspan="2"><h2 style="text-align: center;">Réalisation informatique</h2></th>
              </tr>
              <tr>
                <td><img src="img/bperson.jpg" height="141" alt="Brice Person" /></td>
                <td><img src="img/tmorlier.jpg" height="141" alt="Tangui Morlier" /></td>
              </tr>
              <tr>
                <td>M. Brice Person <br /><a id="mail" href="#">email</a></td>

				
				</td>
                <td>M. Tangui Morlier <br />
				
					<a href='http://tangui.eu.org'>http://tangui.eu.org</a>

    </a>
				</td>
              </tr>
            </table>

           
          </div>
        </div>
      </div>
    <div class="bottom">
        <p>Juricaf est un projet de l'<a href="/documentation/partenaires.php#ahjucaf">AHJUCAF</a>, l'association des cours judiciaires suprêmes francophones,<br />
        réalisé en partenariat avec le Laboratoire Normologie Linguistique et Informatique du droit (Université&nbsp;Paris&nbsp;I).<br />
        Il est soutenu par l'<a href="/documentation/partenaires.php#francophonie">Organisation internationale de la Francophonie</a>,
        le <a href="/documentation/partenaires.php#inforoutes">Fonds francophone des inforoutes</a>.</p>
        <a href="/documentation/partenaires.php#ahjucaf"><img style="margin-left: 10px;" src="/images/ahjucaf.png" alt="Association des cours judiciaires suprêmes francophones" /></a>
        <a href="/documentation/partenaires.php#francophonie"><img style="float: right; margin-right: 10px;" src="/images/francophonie.png" alt="Organisation internationale de la francophonie" /></a>
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
