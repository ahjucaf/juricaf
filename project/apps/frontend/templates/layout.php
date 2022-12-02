<?php $gitcommit = str_replace("\n", "", file_get_contents('../../.git/ORIG_HEAD'));?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
  <head>
    <?php include_http_metas() ?>
    <?php if(has_slot("metadata")) { include_slot("metadata"); } ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/images/favicon.ico" />
    <link rel="preload" href="/css/boostrap-5.css?<?php echo $gitcommit; ?>" as="style">
    <link rel="preload" href="/css/main.css?<?php echo $gitcommit; ?>" as="style">
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="/css/boostrap-5.css?<?php echo $gitcommit; ?>">
    <link rel="stylesheet" type="text/css" media="screen" href="/css/main.css?<?php echo $gitcommit; ?>" />
    <link rel="stylesheet" type="text/css" media="print" href="/css/print.css?<?php echo $gitcommit; ?>">
    <style>
    @font-face {
      font-family: "bootstrap-icons";
      src: url("/css/fonts/bootstrap-icons.woff2") format("woff2"), url("/css/fonts/bootstrap-icons.woff") format("woff");
      font-display: swap;
    }
    </style>
    <!-- Matomo -->
    <script>
    <!--
      var _paq = window._paq = window._paq || [];
      /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
      <?php if (isset($sf_params) && $sf_params->get('query')): ?>
      _paq.push(['trackSiteSearch',
          // Search keyword searched for
          "<?php echo $sf_params->get('query'); ?>",
          // Search category selected in your search engine. If you do not need this, set to false
          "<?php echo trim(str_replace('_', ' ', preg_replace('/(facet_[^:]+:|,.*)/', '', $sf_params->get('facets'))). " ".$sf_params->get('filter')); ?>",
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
  <body class="container full-width">
  <span id="is_mobile" class="d-lg-none"></span>
  <nav id="navbar" class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid hide-slogan" >
    <a class="navbar-brand" href="/"><img class="align-self-center" width="40px" height="40px" src="/images/logo_menu.png"/></a>
    <div class="d-lg-none text-center "><small class="slogan text-white">La jurisprudence francophone des Cours suprêmes</small></div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse " id="navbarToggler">
      <ul class="navbar-nav me-auto mr-auto  mt-lg-0" >
        <li class="nav-item d-lg-none header-mobile-navbar">
          <div class="container-fluid">
            <div class="float-end">
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-x"></i>
              </button>
            </div>
            <form class="my-2 my-lg-0 text-center d-flex d-lg-none navbar-search-input" method="get" action="/recherche">
                  <div class="form-inline input-group">
                <input id="recherche" class="form-control" autocomplete="off" type="text" placeholder="Rechercher une jurisprudence" name="q" aria-label="Rechercher" tabindex="10">
                <button class="btn btn-primary rounded-end"  type="submit">
                    <i class="bi bi-search"></i>
                </button>
                <br>
              </div>
            </form>
          </div>
        </li>
        <li class="nav-item d-lg-none">
          <a class="nav-link" href="/recherche_avancee">Recherche avancée</a>
        </li>
        <li class="nav-item">
          <a class="nav-link d-none d-lg-block" href="/">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/static/apropos">À propos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/static/statuts">Étendue des collections</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/static/partenaires">Partenaires</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/static/mentionslegales">Mentions légales</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/static/contact">Contact</a>
        </li>
        <li class="nav-item d-lg-none">
          <a class="nav-link" href="/static/statuts">Plus de statistiques</a>
        </li>
        <li class="nav-item d-lg-none">
          <a class="nav-link" href="/actualites">Actualités</a>
        </li>
      </ul>
      <ul class="navbar-nav my-2 my-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="https://www.facebook.com/AHJUCAFCoursSupremesJudiciairesFrancophones" target="_blank">
            <i class="bi bi-facebook"></i> <span class="d-lg-none"> Nous suivre sur Facebook</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="http://twitter.com/ahjucaf" target="_blank">
            <i class="bi bi-twitter"></i> <span class="d-lg-none"> Nous suivre sur twitter</span>
          </a>
         </li>
         <li class="nav-item">
          <a class="nav-link" href="https://www.linkedin.com/in/ahjucaf-cours-supr%C3%AAmes-judiciaires-francophones-0a7a72230" target="_blank">
            <i class="bi bi-linkedin"></i> <span class="d-lg-none"> Nous suivre sur LinkedIn</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="d-lg-none" style="height: 66px;">
</div>
<?php echo $sf_content; ?>

<div class="text-center mt-3">
    <div class="pt-3 px-5 mb-3 clearfix">
      <div class="row">
        <div class="col">
          <a href="https://www.ahjucaf.org/"><img height="86" width="100" src="/images/ahjucaf_small.png" alt="Association des cours judiciaires suprmes francophones"/></a>
        </div>
        <div class="col">
          <a href="https://www.francophonie.org/"><img height="86" width="100" src="/images/francophonie_small.png" alt="Organisation internationale de la francophonie" /></a>
        </div>
      </div>
    </div>
    <div class="small bottom p-2">
       <span class="d-lg-none">Juricaf est un projet de l'AHJUCAF, l'association des Cours suprêmes judiciaires francophones. Il est soutenu par l'Organisation Internationale de la Francophonie.</span>
       <span class="d-none d-lg-block col-6 mx-auto">Juricaf est un projet de l'AHJUCAF, l'association des Cours suprêmes judiciaires francophones. Il est soutenu par l'Organisation Internationale de la Francophonie.</span>
       <div class="pt-3 p-2">
           <img height="44" width="224" src="/images/iall.jpg" />
       </div>
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
</script>

<script type="text/javascript" src="/js/jquery-3.6.0.slim.min.js?<?php echo $gitcommit; ?>"></script>
<script type="text/javascript" src="/js/boostrap-5.js?<?php echo $gitcommit; ?>" async></script>
<script type="text/javascript" src="/js/main.js?<?php echo $gitcommit; ?>" async></script>
</body>
</html>
