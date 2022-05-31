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
    <!-- Matomo -->
    <script>
    <!--
      var _paq = window._paq = window._paq || [];
      /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
      <?php if ($sf_params->get('query')): ?>
      _paq.push(['trackSiteSearch',
          // Search keyword searched for
          "<?php echo $sf_params->get('query'); ?>",
          // Search category selected in your search engine. If you do not need this, set to false
          "<?php echo str_replace('_', ' ', str_replace('facet_pays_juridiction:', '', $sf_params->get('facets'))). " ".$sf_params->get('filter'); ?>",
          // Number of results on the Search results page. Zero indicates a 'No Result Search Keyword'. Set to false if you don't know
          <?php echo $sf_params->get('nbResultats') ? str_replace(' ', '', $sf_params->get('nbResultats')) : "false"; ?>
      ]);
      <?php else: ?>
      _paq.push(['trackPageView']);
      <?php endif; ?>
      _paq.push(['enableLinkTracking']);
      (function() {
        var u="//juricaf.org/matomo/";
        _paq.push(['setTrackerUrl', u+'matomo.php']);
        _paq.push(['setSiteId', '1']);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
      })();
    -->
    </script>
    <!-- End Matomo Code -->
  </head>
  <body>
    <div class="site">
      <div class="head">
        <div class="menu">
          <ul>
              <li><a href="/documentation/a_propos.php">À-propos</a></li>
              <li><a href="/documentation/stats/statuts.php">Étendue des collections</a></li>
              <li><a href="/documentation/partenaires.php">Partenaires</a></li>
              <li><a href="/documentation/mentions_legales.php">Mentions légales</a></li>
              <li><a href="/documentation/contact.php">Contact</a></li>
            </ul>
        </div>
      </div>
	  <div class="reseaux_sociaux">
    <a href="https://www.facebook.com/AHJUCAFCoursSupremesJudiciairesFrancophones" target="_blank"><img src="/images/facebook.png" alt="Facebook" title="Devenez fan sur Facebook"/></a>
          <a href="https://twitter.com/ahjucaf" target="_blank"><img src="/images/twitter.png" alt="Twitter" title="Suivez nous sur Twitter"/></a>
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
                  <img src="/images/slogan.png" style="width: 276px; height: 12px; margin-top: 4px;" alt="La jurisprudence francophone des Cours suprêmes" itemprop="image" /><br />
                  <input type="text" name="q" value="<?php echo $sf_params->get('query'); ?>" tabindex="10" /><br />
                  <input type="submit" value="Rechercher" tabindex="20" /> <a href="<?php echo url_for('@recherche_avancee'); ?>">recherche avancée</a>
                </td>
              </tr>
            </table>
            </form>
          </div>
          <?php echo $sf_content; ?>
		<div style="margin-top: 50px;" align="center">	 
			<a  href="https://www.lemondedudroit.fr/interviews/66303-jean-paul-jean-ahjucaf.html" target="new">Interview de JP Jean secrétaire général de l’AHJUCAF dans « Le Monde du droit » sur l’accès à la jurisprudence francophone.</a><br><br><br>
		</div>
        </div>
      </div>
      <div>
        <table width=100%><tr>
	    <td><a href="http://www.ahjucaf.org/"><img style="margin-left: 10px;" src="/images/ahjucaf_small.png" alt="Association des Cours judiciaires suprêmes francophones" /></a></td>
	    <td><center><a href="http://www.ahjucaf.org/"><img style="margin-left: 10px;" src="/images/ahjucafSite.jpg" alt="Association des Cours judiciaires suprêmes francophones" /><br/>Accédez au site de l’AHJUCAF </a></center></td>
	    <td><img style="float: right; margin-right: 10px;" src="/images/francophonie.png" alt="Organisation internationale de la francophonie" /></td>
        </tr></table>
         
      <div class="bottom">
      <p>Juricaf est un projet de l'AHJUCAF, l'association des Cours suprêmes judiciaires francophones,<br />
        Il est soutenu par l'Organisation Internationale de la Francophonie. </p>
      </div>
    </div>
   </body>
</html>
