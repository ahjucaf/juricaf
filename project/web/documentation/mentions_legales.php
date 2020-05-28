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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juricaf - Mentions légales</title>
    <link rel="shortcut icon" href="/images/favicon.ico" />
    <link rel="stylesheet" type="text/css" media="screen" href="/css/main.css" />

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
            <h1>Mentions légales </h1>
            <p>Conformément aux dispositions des articles 6-III et 19 de la loi pour la Confiance dans l'Économie Numérique, nous vous informons que Juricaf est un projet de l’AHJUCAF, l’association des Hautes juridictions de cassation des pays ayant en partage l’usage du français, association française de loi 1901 inscrite au registre des déclarations d’association de la Préfecture de police de Paris, publié au Journal officiel du 9 juin 2001.
            <br />L’AHJUCAF est inscrite au répertoire national des entreprises et de leurs établissements sous le numéro SIREN 440 233 880 (APE 913 E).
            <br />Contact et responsable de la publication : M. Jean-Louis Gillet, secrétaire général de l’AHJUCAF, 5 quai de l’Horloge 75001 PARIS Tel/Fax : 00 331 46 34 67 40.
            <br />Hébergement : ONLINE SAS BP 438 75366 PARIS CEDEX 08</p>

            <h1>Formulaire de contact</h1>
            <form action="form2mail.php" method="post">
            <p>
              Entrez votre adresse mail: <input type="text" name="email" /><br />
              Message:<br />
              <textarea name="message" rows="8" cols="25"></textarea><br />
              <input name="token" type="hidden" value="<?php echo $token; ?>" />
              <input type="submit" value="Envoyer le mail" />
            </p>
            </form>

			<h1>Droits de réutilisation des arrêts et licences</h1>
            Par contrat d’association des cours suprêmes dont la liste officielle figure sur le site Internet <a href="http://www.ahjucaf.org/-Membres-.html">http://www.ahjucaf.org/-Membres-.html</a> et notamment par l’article 5 de ses statuts <a href='http://www.ahjucaf.org/Statuts-de-l-association.html'>(http://www.ahjucaf.org/Statuts-de-l-association.html)</a>, l’AHJUCAF s’engage à « diffuser ou contribuer à diffuser en direction des institutions membres, notamment par un réseau de communication et un site Internet, des informations utiles sur l’organisation et le fonctionnement de chacune d’elles, ainsi que la jurisprudence de chacune de ces juridictions ».
            Par un protocole d’accord, les cours suivantes ont donné mandat à l’AHJUCAF de permettre la réutilisation de leurs arrêts par voie électronique.

			<p>Sauf pour les décisions provenant de France, les décisions collectées et publiées par l'AHJUCAF sous placé sous la licence ODbL 1.0. Elle est consultable à l'adresse : <a href=' http://www.juricaf.org/documentation/licence_odbl.php'>
http://www.juricaf.org/documentation/licence_odbl.php</a>

			<P>Si vous ne voulez pas ou ne souhaitez pas réutiliser les décisions sous cette licence ou si vous souhaitez disposer de fichiers XML structurés selon vos besoins ou tout autre prestation, les données seront disponibles prochainement sous licence. Merci de contacter le secrétariat pour toute information utile : <a href=' http://www.juricaf.org/documentation/licence_ahjucaf.php'>
http://www.juricaf.org/documentation/licence_ahjucaf.php</a>






            <h1>Droit d’accès et de rectification </h1>

            <h2>Décisions françaises </h2>
            Pour les arrêts français, l’AHJUCAF se conforme à la délibération n° 01-057 du 29 novembre 2001 portant recommandation sur la diffusion de données personnelles sur internet par les banques de données de jurisprudence. Les informations recueillies font l’objet d’un traitement informatique destiné à l'information sur le projet Juricaf. Le destinataire des données est l'AHJUCAF.

            Conformément à la loi « informatique et libertés » du 6 janvier 1978 modifiée en 2004, vous bénéficiez d’un droit d’accès et de rectification aux informations qui vous concernent, que vous pouvez exercer en vous adressant à  l'AHUCAF (5, quai de l'Horloge 75001 PARIS  - France, Tel/fax : 0033 1 46 34 67 40.

            <h2>Décisions non françaises </h2>
            Les décisions transmises ou collectées à l’AHJUCAF sont conformes aux lois et règlements des pays membre, à la directive européenne sur la protection des données personnelles ou tout texte international relatifs à ce sujet.

            <h1>Propriété intellectuelle</h1>
            <h2>Droits d’auteur</h2>
            Juricaf est protégée par le droit sui generis des bases de données.
            <h2>Marques déposées</h2>
            Juricaf et Ahjucaf sont des marques déposées auprès de l’Institut national de la propriété intellectuelle (numéros 3284058, 2999936 et 3170015).



            <h1>Mentions légales particulières aux collections de jurisprudences </h1>

            <h2>Belgique</h2>
            <h3>Conseil d’État</h3>
            Le contenu des arrêts de ce site est librement accessible à tous. Il est toutefois interdit de reproduire le code HTML, les graphiques, les séquences audio ou vidéo, la sélection et l'organisation des informations de ce site sans l'assentiment exprès du Conseil d'État et éventuellement de tiers, titulaires de droits.

            La création de liens vers ce site doit être communiquée à l'administrateur de ce site. Le lien doit être réalisé de manière telle qu'une nouvelle fenêtre supplémentaire s'ouvre dans le navigateur. Source&nbsp;: <a href="http://www.raadvst-consetat.be/?page=disclaimer&amp;lang=fr">http://www.raadvst-consetat.be/?page=disclaimer&amp;lang=fr</a>

            <h3>Cour constitutionnelle</h3>
            <a href=http://www.const-court.be/fr/information/Disclaimer.html">
			http://www.const-court.be/fr/information/Disclaimer.html</a>

            La reproduction des informations et des textes de ce site est autorisée moyennant mention de la source (www.const-court.be). L’attention est attirée sur le fait que certaines de ces informations et certains de ces textes peuvent relever de la protection d’un droit de propriété intellectuelle, à savoir le droit d’auteur.

			 <h2>Conseil de l'Europe</h2>
            <h3>Cour européenne des droits de l'Homme</h3>


			<a href=http://www.echr.coe.int/ECHR/FR/Bottom/Disclaimer/">
			http://www.echr.coe.int/ECHR/FR/Bottom/Disclaimer/</a>

			<p>Avis de non-responsabilité

<p>L’objet du portail de la Cour européenne des Droits de l’Homme (« la Cour ») est de permettre au public d’accéder aux informations sur la Cour et ses activités.

Par le présent avis, la Cour décline toute responsabilité découlant de l’utilisation d’informations ou de données fournies sur son site internet. La Cour, les juges, les agents du greffe et les prestataires de service du greffe ne sauraient être tenus pour responsables d’une quelconque conséquence – financière ou autre – résultant de l’utilisation d’informations ou de données fournies sur le site, notamment de l’utilisation inappropriée, incorrecte ou frauduleuse de ces informations ou données. La consultation du site implique automatiquement la pleine acceptation du présent avis de non-responsabilité.

<p>La Cour n’est pas en mesure de garantir l’absence d’erreurs sur son site ; cependant, elle s’efforce, le cas échant, de corriger celles qui sont portées à son attention.

<p>La Cour refuse toute responsabilité quant au contenu d’autres sites internet qui comporteraient un lien vers son portail ou une référence à celui-ci.

<p>Les informations et textes accessibles sur le site de la Cour peuvent être reproduits sous réserve que leur source soit mentionnée. Les utilisateurs doivent néanmoins garder à l’esprit que certaines informations et certains textes peuvent être protégés par le droit relatif à la propriété intellectuelle, en particulier le droit d’auteur.

<p>La version électronique des arrêts et décisions de la Cour est communiquée pour donner effet à l’article 44 § 3 de la Convention européenne des Droits de l’Homme, qui prévoit la publication des arrêts définitifs. Cependant, il est impossible de garantir qu’un document électronique reproduise fidèlement un texte officiellement adopté ; de plus, la version électronique peut subir des retouches de forme. Le texte original signé qui est conservé aux archives de la Cour constitue la seule version authentique. En outre, lorsqu’un arrêt ou une décision est publié dans la série officielle de la Cour, le Recueil des arrêts et décisions, la version publiée peut être considérée comme la version officielle et fait foi en cas de divergence avec la version électronique







            <h3>France</h3>
            Les décisions provenant de la France sont issues de la bases JADE, CASS, INCA et CONSTIT sous licence de la Direction de l’information légale et administrative (DILA). Source&nbsp;: <a href="http://www.legifrance.gouv.fr/Informations/Licences">http://www.legifrance.gouv.fr/Informations/Licences</a>

            <h3>Luxembourg</h3>
            En l'absence d'indication contraire, la reproduction des informations contenues sur ce site est autorisée à des fins non commerciales à condition que la source soit expressément mentionnée.

            Source&nbsp;: <a href="http://www.justice.public.lu/fr/support/notice/index.html#copyright">http://www.justice.public.lu/fr/support/notice/index.html#copyright</a>

            Les décisions reproduites sur le présent site ne sont que des copies informelles et ne font pas foi du contenu des minutes signées.
            Source&nbsp;: <a href="http://www.justice.public.lu/fr/jurisprudence/index.html">http://www.justice.public.lu/fr/jurisprudence/index.html </a>
          </div>
        </div>
      </div>

    </div>
    <script type="text/javascript">

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
