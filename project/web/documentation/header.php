<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="title" content="Juricaf" />
    <meta name="description" content="La jurisprudence francophone des Cours suprêmes" />
    <meta name="keywords" content="jurisprudence, cassation, cour, suprême, francophone, francophonie, ahjucaf, arrêt, décision" />
    <meta name="language" content="fr" />
    <title>Juricaf</title>
    <link rel="shortcut icon" href="/images/favicon.ico" />
    <script type="text/javascript" src="/js/jquery-1.6.1.min.js"></script>
    <script type="text/javascript" src="/js/jquery.scrollTo-min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script type="text/javascript" src="/js/main.js"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="/css/main.css" />
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
  <body class="container full-width">
    <div>
<div>
  <span id="is_mobile" class="d-lg-none"></span>
  <nav id="navbar" class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid hide-slogan" >
    <a class="navbar-brand d-lg-none" href="/"><img class="align-self-center" width="40px" height="40px" src="/images/logo_menu.png"/></a>
    <div class="d-lg-none"><p class="text-center slogan"><small class="fst-italic slogan">La jurisprudence francophone des Cours suprêmes</small><br/></p></div>
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
                <button class="btn btn-primary"  type="submit">
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
          <a class="nav-link" href="/documentation/a_propos.php">À-propos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/documentation/stats/statuts.php">Étendue des collections</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/documentation/partenaires.php">Partenaires</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/documentation/mentions_legales.php">Mentions légales</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/documentation/contact.php">Contact</a>
        </li>
      </ul>
      <ul class="navbar-nav my-2 my-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="https://www.facebook.com/Juricaf" target="_blank">
            <i class="bi bi-facebook"></i>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="http://twitter.com/juricaf" target="_blank">
            <i class="bi bi-twitter"></i>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="https://fr.linkedin.com/company/ahjucaf---cours-supr-mes-judiciaires-francophones" target="_blank">
            <i class="bi bi-linkedin"></i>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
  <div class="d-lg-none">
    <br><br>
  </div>
  </div>
  </div>
  <div <?php if($sf_request && $sf_request->getParameter('module')=="arret"){ echo('id = "hidden-mode-mobile"');}else{echo('id = "menu"');} ?>class="container form_recherche mt-5">
    <form class=" my-2 my-lg-0 text-center" method="get" action="/recherche">
      <div class="d-none d-lg-block">
        <a href="/"><img class="align-self-center" id="logo" src="/images/juricaf.png" alt="Juricaf" /></a> <br>
        <p ><small class="fst-italic slogan">La jurisprudence francophone des Cours suprêmes</small><br/></p>
      </div>
        <div class="form-inline input-group input-group-lg">
        <input id="recherche" class="form-control mx-auto" autocomplete="off" type="text"
        <?php if($sf_request && $sf_request->getParameter('query') && ($sf_request->getParameter('query') != " ")){
            echo( "value = '".$sf_request->getParameter('query')."'");
            $not_autofocus=true;
        }
        elseif($sf_user && $sf_user->getAttribute('query') && ($sf_user->getAttribute('query')!= " ") ){
          echo( "value = '".$sf_user->getAttribute('query')."'");
          $not_autofocus=true;
        }
        ?>
        placeholder="Rechercher une jurisprudence" name="q" aria-label="Rechercher" tabindex="10"
        <?php
          if(!$not_autofocus){
            echo('autofocus=autofocus');
          }
        ?>
        ><button class="btn btn-primary"  type="submit">
          <span class="d-lg-none">
            <i class="bi bi-search"></i>
          </span>
          <span class="d-none d-lg-block">Rechercher</span>
        </button>
        <br>
      </div>
      <a class="float-end d-none d-lg-block" href="/recherche_avancee">recherche avancée</a>

    </form>
  </div>
