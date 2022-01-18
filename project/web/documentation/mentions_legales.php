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
    <meta name="title" content="Juricaf - Mentions légales" />
<meta name="description" content="La base de données de jurisprudence francophone" />
<meta name="keywords" content="jurisprudence, cassation, cour, suprême, francophone, francophonie, ahjucaf, arrêt, décision" />
<meta name="language" content="fr" />
    <title>Juricaf - Mentions légales</title>
    <link rel="shortcut icon" href="/images/favicon.ico" />
    <link rel="stylesheet" type="text/css" media="screen" href="/css/main.css" />

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
            <h1>Mentions légales</h1>
            <h2>Editeur</h2>
        
            <p>Le site www.juricaf.org est placé sous la responsabilité éditoriale de l’AHJUCAF (Association des Hautes JUridictions de CAssation des pays ayant en partage l’usage du Français), association loi 1901 inscrite au registre des déclarations d’association de la Préfecture de police de Paris, publié au Journal officiel du 9 juin 2001.</p>

            <p>L’AHJUCAF est inscrite au répertoire national des entreprises et de leurs établissements sous le numéro SIREN 440 233 880 (APE 913 E).</p>
        
            <p>Ahjucaf<br/>
            5 quai de l’Horloge 75001 PARIS<br/>
            Tel. : +33 1 46 34 67 40</p>

            <h2>Directeur de Publication</h2>
            
            <p>M. Jean-Paul JEAN, Secrétaire général de l’AHJUCAF<br/>
                5 quai de l’Horloge, 75001 PARIS</p>

            <h2>Coordinateurs techniques</h2>

        <p>M. Mehdi BEN MIMOUN, chargé de mission auprès du Secrétaire général de l’AHJUCAF<br/>
        5 quai de l’Horloge, 75001 PARIS</p>
        <br/>
        <p>M. Thomas FRINCHABOY, chargé de mission auprès du Secrétaire général de l’AHJUCAF<br/>
        5 quai de l’Horloge, 75001 PARIS</p>

            <h2>Hébergeur</h2>
        <p>ONLINE SAS BP 438 PARIS CEDEX 08 </p>

            <h2>Webmestre</h2>
        <p>Mme Amélie BIDARD DE LA NOE<br/>
            AHJUCAF, 5 quai de l’Horloge 75055 PARIS Cedex 01</p>

            <h2>Droits de propriété intellectuelle</h2>
        
        <p>Contexte :</p>
        
        <p>L’Association des Hautes Juridictions de Cassation des pays ayant en partage l’usage du Français (AHJUCAF) a été créée en 2001 à l’initiative de 34 Juridictions Suprêmes francophones et l’Organisation internationale de la Francophonie. Par ses statuts (article 4) l’AHJUCAF a pour mission de :<br/>
            - favoriser l’entraide, la solidarité, la coopération, les échanges d’idées et d’expériences entre les institutions judiciaires membres sur les questions relevant de leur compétence ou intéressant leur organisation et leur fonctionnement ;<br/>
            - promouvoir le rôle des Hautes Juridictions dans la consolidation de l’Etat de droit, le renforcement de la sécurité juridique, la régulation des décisions judiciaires et l’harmonisation du droit au sein des États membres.</p>

        <p>Dans ce cadre, l’AHJUCAF a créé la base de données JURICAF accessible sur internet à l’adresse www.juricaf.org. Il s’agit d’une base de données de décisions de justice en langue française de 43 pays et institutions.</p>

        <p>L’AHJUCAF est titulaire du droit sui generis (titre IV, livre III du Code de la propriété intellectuelle) sur la base de données JURICAF accessible sur Internet à l’adresse www.juricaf.org.</p>

            <h2>Mise à disposition et réutilisation des données</h2>
        <p>Les données de JURICAF sont en accès libre. Sauf pour les décisions des juridictions françaises, ces données sont protégées par le Code de la propriété intellectuelle et diffusées sous la licence ODbL 1.0. Elle est consultable à l'adresse : <a href="https://juricaf.org/documentation/licence_odbl.php">https://juricaf.org/documentation/licence_odbl.php</a></p>

        <p>La réutilisation est autorisée dans les conditions de la licence ODbL (<a href="https://vvlibri.org/fr/licence/odbl-10/legalcode/unofficial">version française de la licence ODbL</a>, <a href="https://www.mobilites-m.fr/pdf/ODbL_fr_VF_1.pdf">mode d’emploi</a> )</p>

        <p>Si vous ne voulez pas ou ne souhaitez pas réutiliser les décisions dans les conditions de la licence ODbL ou si vous souhaitez disposer de fichiers XML structurés selon vos besoins ou tout autre prestation, vous pouvez contacter le secrétariat de l’AHJUCAF pour toute information utile via le formulaire de contact de ce site. Plus d’informations dans la page politique de confidentialité/données personnelles.</p>


            <h2>Données personnelles</h2>
        <p>Pour tout ce qui concerne les données personnelles, consultez la page <a href="donnees_personnelles.php">politique de confidentialité/données personnelles</a>.</p>


        <br/><br/><br/>
        <small style="color:gray;"><i>Les  mentions légales et la politique de confidentialité de ce site ont été rédigées avec la collaboration de Maître Blandine Cornevin et Maître Irène Kris, réseau ELOKIA AVOCATS</i></small>

          </div>
        </div>
      </div>
      <div style="margin-top: 50px;">
      <hr/>
 	 <img style="margin-left: 10px;" src="/images/ahjucaf_small.png" alt="Association des cours judiciaires suprmes francophones" />
 	 <img style="float: right; margin-right: 10px;" src="/images/francophonie.png" alt="Organisation internationale de la francophonie" />
       <div class="bottom">
      <p>Juricaf est un projet de l'AHJUCAF, l'association des cours judiciaires suprêmes francophones,<br />
         réalisé en partenariat avec le Laboratoire Normologie Linguistique et Informatique du droit (Université Paris I).<br />
         Il est soutenu par l'Organisation internationale de la Francophonie et le Fonds francophone des inforoutes.</p>
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
