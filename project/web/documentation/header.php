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
    <script type="text/javascript" src="/js/jquery-1.6.1.min.js"></script>
    <script type="text/javascript" src="/js/jquery.scrollTo-min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="/css/main.css" />
    <link rel="search" href="/juricaf.xml" title="Rechercher sur Juricaf" type="application/opensearchdescription+xml" />
    </head>
  <body>
    <div>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="http://www.juricaf.org/recherche"><img id="logo" src="/images/juricaf.png" alt="Juricaf" width='50px' /></a>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
          <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="http://localhost:8002/documentation/a_propos.php">À propos<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="http://localhost:8002/documentation/stats/statuts.php">Etendue des collections </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="http://localhost:8002/documentation/partenaires.php">Partenaires</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="http://localhost:8002/documentation/mentions_legales.php">Mentions légales</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="http://localhost:8002/documentation/contact.php">Contact</a>
            </li>
          </ul>
          <ul class="navbar-nav my-2 my-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="https://www.facebook.com/Juricaf" target="_blank"><img src="/images/facebook.png" alt="Facebook" title="Devenez fan sur Facebook"/></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="http://twitter.com/juricaf" target="_blank"><img src="/images/twitter.png" alt="Twitter" title="Suivez nous sur Twitter"/></a>
            </li>
          </ul>
        </div>

    </nav>
  </div>


    <div class="form_recherche container mt-5">
      <form class=" my-2 my-lg-0 text-center" method="get" action="/recherche">
        <a href="http://www.juricaf.org/recherche"><img class="align-self-center" id="logo" src="/images/juricaf.png" alt="Juricaf" /></a> <br>
        <p ><small class="font-italic">La jurisprudence francophone des cours suprêmes</small><br/></p>
        <div class="form-inline input-group">
          <input class="form-control w-75 p-3 mx-auto" type="text" placeholder="Recherche" name="q" aria-label="Rechercher">
          <button class="btn btn-outline-success" type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg>
          </button>
        </div>
        <a href="/recherche_avancee">recherche avancée</a>
      </form>
        <!-- <form class="text-center" method="get" action="/recherche">
                <a href="http://www.juricaf.org/recherche"><img class="align-self-center" id="logo" src="/images/juricaf.png" alt="Juricaf" /></a> <br>
                <p ><small class="font-italic">La jurisprudence francophone des cours suprêmes</small><br/></p>
                <input class="form-control w-75 p-3 mx-auto" type="text" name="q" value="" tabindex="10" />
                <input class="btn btn-primary "type="submit" value="Rechercher" tabindex="20" />
                <a href="/recherche_avancee">recherche avancée</a>
        </form> -->
    </div>
