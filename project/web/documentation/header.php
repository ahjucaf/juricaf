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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script>
      function initFunctions(){

        /*fonction pour la pagination*/
        pages = document.getElementsByClassName('page-item');
        for(let i=0; i< pages.length; i++){
          if(pages[i].children[0]){
            pages[i].children[0].className="page-link";
            // pages[i].children[0].classList.add('a-unstyled');
        }
          if(!pages[i].innerHTML.startsWith('<a')){
            pages[i].classList.add("page-link");
            pages[i].style.color = '#6c757d';
          }
        }
      }


    </script>
    <link rel="stylesheet" type="text/css" media="screen" href="/css/main.css" />
    <link rel="search" href="/juricaf.xml" title="Rechercher sur Juricaf" type="application/opensearchdescription+xml" />
    </head>
  <body class="container full-width" onload="initFunctions()">
    <div>
<div >
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="http://www.juricaf.org/recherche">Juricaf.org</a>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
          <ul class="navbar-nav me-auto mr-auto mt-2 mt-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="http://localhost:8002/documentation/a_propos.php">À propos</a>
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
              <a class="nav-link" href="https://www.facebook.com/Juricaf" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                  <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                </svg>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="http://twitter.com/juricaf" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-twitter" viewBox="0 0 16 16">
                  <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"/>
                </svg>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </div>
  </div>

  <div class="container form_recherche mt-5">
    <form class=" my-2 my-lg-0 text-center" method="get" action="/recherche">
      <a href="http://www.juricaf.org/recherche"><img class="align-self-center" id="logo" src="/images/juricaf.png" alt="Juricaf" /></a> <br>
      <p ><small class="font-italic">La jurisprudence francophone des cours suprêmes</small><br/></p>
        <div class="form-inline input-group input-group-lg">
        <input class="form-control mx-auto" type="text" placeholder="Rechercher une jurisprudence" name="q" aria-label="Rechercher" tabindex="10" autofocus>
        <button class="btn btn-primary"  type="submit">
          <span class="d-lg-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
              <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg>
          </span>
          <span class="d-none d-lg-block">Rechercher</span>
        </button>
        <br>
      </div>
      <a class="float-end" href="/recherche_avancee">recherche avancée</a>

    </form>
  </div>
