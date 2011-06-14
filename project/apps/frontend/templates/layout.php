<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/images/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
    <link rel="search" href="/juricaf.xml" title="Rechercher sur Juricaf" type="application/opensearchdescription+xml" />
  </head>
  <body>
    <div class="site">
      <div class="head">
        <div class="reseaux_sociaux">
          <a href="https://www.facebook.com/pages/Juricaf/199894740035999"><img src="/images/facebook.png" alt="Facebook" title="Devenez fan sur Facebook" /></a>
          <a href="https://twitter.com/#!/juricaf"><img src="/images/twitter.png" alt="Twitter" title="Suivez nous sur Twitter" /></a>
          <a href="#"><img src="/images/rss.png" alt="RSS" title="Flux RSS" /></a>
        </div>
        <div class="menu">
          <ul>
            <li><a href="#">A propos</a></li>
            <li><a href="#">Aide</a></li>
            <li><a href="#">Étendue des collections</a></li>
            <li><a href="#">Outils</a></li>
            <li><a href="#">Partenaires</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="#">Mentions légales</a></li>
          </ul>
        </div>
      </div>
      <div class="main">
        <div class="content">
          <?php if ($sf_user->hasFlash('notice')):?><div class="flash notice"><?php echo $sf_user->getFlash('notice'); ?></div><?php endif; ?>
          <?php if ($sf_user->hasFlash('error')):?><div class="flash error"><?php echo $sf_user->getFlash('error'); ?></div><?php endif; ?>
          <div class="form_recherche">
            <form method="get" action="<?php echo url_for('recherche_resultats'); ?>">
            <table>
              <tr>
                <td>
                  <a href="<?php echo url_for('@recherche'); ?>"><img id="logo" src="/images/juricaf1.png" alt="Juricaf" /></a><br />
                  <span style="font-family: Georgia; font-style: italic; color: #4E4C4D;">La jurisprudence francophone des cours suprêmes</span><br />
                  <input type="text" style="width: 300px; margin-top: 5px;" name="q" value="<?php echo htmlentities(utf8_decode($sf_user->getAttribute('query'))); ?>" tabindex="10" /><br />
                  <input type="submit" value="Rechercher" tabindex="20" /> <a href="#">recherche avancée</a>
                </td>
              </tr>
            </table>
            </form>
          </div>
          <hr />
          <?php echo $sf_content; ?>
        </div>
      </div>
      <div class="bottom">
        <p>Juricaf est un projet de l'<a href="http://www.ahjucaf.org">AHJUCAF</a>, l'association des cours suprêmes judiciaires francophones, réalisé en partenariat avec le LNLI.<br /> Il est soutenu par l'<a href="http://www.francophonie.org">Organisation internationale de la Francophonie</a>, le <a href="http://inforoutes.francophonie.org">Fonds francophone des inforoutes</a> et les réseaux institutionnels francophones.<br /><span class="w3c"><a href="http://validator.w3.org/check?verbose=1&amp;uri=<?php echo urlencode($sf_request->getUri()); ?>">XHTML 1.0 strict</a> - <a href="http://jigsaw.w3.org/css-validator/validator?profile=css21&amp;warning=0&amp;uri=<?php echo urlencode($sf_request->getUri()); ?>">CSS 2.1</a></span></p>
        <img style="margin-left: 10px;" src="/images/ahjucaf.png" alt="Association des cours judiciaires suprêmes francophones" />
        <img style="float: right; margin-right: 10px;" src="/images/francophonie.png" alt="Organisation internationale de la francophonie" />
      </div>
    </div>
    <script type="text/javascript">
    <!--
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
