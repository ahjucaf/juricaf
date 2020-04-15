<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
  <head>
    <?php include_http_metas() ?>
    <?php if(has_slot("metadata")) { include_slot("metadata"); } ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/images/favicon.ico" />
    <?php include_stylesheets() ?>
	<link type="text/css" rel="stylesheet" href="/css/jquery.qtip.css" />
    <?php include_javascripts() ?>
	<script type="text/javascript" src="/js/opensearch.js"></script>
	<script type="text/javascript" src="/js/jquery.qtip.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function()
{		$('a[title]').qtip();
});
	</script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
 	<link rel="search" href="/juricaf.xml" title="Rechercher sur Juricaf" type="application/opensearchdescription+xml" />
  </head>
  <body>
    <div class="site">
      <div class="head">
        <div class="menu">
          <ul>
              <li><a href="/documentation/a_propos.php">A propos</a></li>
              <li><a href="/documentation/stats/statuts.php">Étendue des collections</a></li>
              <li><a href="/documentation/partenaires.php">Partenaires</a></li>
              <li><a href="/documentation/mentions_legales.php">Mentions légales</a></li>
              <li><a href="/documentation/contact.php">Contact</a></li>
            </ul>
        </div>
      </div>
	  <div class="reseaux_sociaux">

	   <a href="https://www.facebook.com/Juricaf" target="_blank"><img src="/images/facebook.png" alt="Facebook" title="Devenez fan sur Facebook"/></a>
          <a href="https://twitter.com/juricaf" target="_blank"><img src="/images/twitter.png" alt="Twitter" title="Suivez nous sur Twitter"/></a>
	<a href="https://itunes.apple.com/fr/app/id587420315?mt=8&affId=1578782" target="_blank"><img src="/images/appstore.png" alt="Appstore" title="Accès à la version IPhone"/></a>
      </div>
      <div class="main">
        <div class="content">
          <?php if ($sf_user->hasFlash('notice')):?><div class="flash notice"><?php echo $sf_user->getFlash('notice'); ?></div><?php endif; ?>
          <?php if ($sf_user->hasFlash('error')):?><div class="flash error"><?php echo $sf_user->getFlash('error'); ?></div><?php endif; ?>
          <div class="form_recherche">
            <form method="get" action="<?php echo url_for('recherche_resultats'); ?>">
            <table summary="Rechercher">
              <tr>
                <td align="center" width="1024px" height="250px">
                  <a href="<?php echo url_for('@recherche'); ?>"><img id="logo" style="width: 100px; height: 100px;" src="/images/juricaf.png" alt="Juricaf" /></a><br />
                  <img src="/images/slogan.png" style="width: 276px; height: 12px; margin-top: 4px;" alt="La jurisprudence francophone des cours suprêmes" itemprop="image" /><br />
                  <input type="text" name="q" value="<?php echo htmlentities(utf8_decode($sf_user->getAttribute('query'))); ?>" tabindex="10" /><br />
                  <input type="submit" value="Rechercher" tabindex="20" /> <a href="<?php echo url_for('@recherche_avancee'); ?>">recherche avancée</a>
                </td>
              </tr>
            </table>
            </form>
          </div>
          <?php echo $sf_content; ?>
		<div align="center">	 
			<a  href="https://www.lemondedudroit.fr/interviews/66303-jean-paul-jean-ahjucaf.html" target="new">Interview de JP Jean secrétaire général de l’AHJUCAF dans « Le Monde du droit » sur l’accès à la jurisprudence francophone.</a><br><br><br>
		</div>
		  <div class="top_page"><a id="top" href="#">Haut de page<img src="/images/fleche_haut.png" alt="Haut de page"/></a></div>
        </div>
      </div>
	 <a href="http://www.ahjucaf.org/"><img style="margin-left: 10px;" src="/images/ahjucaf_small.png" alt="Association des cours judiciaires suprêmes francophones" /></a>
	    <a href="http://www.ahjucaf.org/"><img style="margin-left: 10px;" src="/images/ahjucafSite.jpg" alt="Association des cours judiciaires suprêmes francophones" />Visitez le nouveau site de l'AHJUCAF</a>
	 <img style="float: right; margin-right: 10px;" src="/images/francophonie.png" alt="Organisation internationale de la francophonie" />
      <div class="bottom">
        <p>Juricaf est un projet de l'AHJUCAF, l'association des cours judiciaires suprêmes francophones,<br />
        réalisé en partenariat avec le Laboratoire Normologie Linguistique et Informatique du droit (Université&nbsp;Paris&nbsp;I).<br />
        Il est soutenu par l'Organisation internationale de la Francophonie et le Fonds francophone des inforoutes.</p>
      </div>
    </div>
   </body>
</html>
