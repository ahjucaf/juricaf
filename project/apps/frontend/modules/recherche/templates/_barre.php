<?php //////////////// BARRE DE RECHECHE ///////////////// ?>
<div <?php if($sf_request && $sf_request->getParameter('module')=="arret"){ echo('id = "hidden-mode-mobile"');}else{echo('id = "menu"');} ?>class="container form_recherche mt-4 clearfix">
  <form class=" my-2 my-lg-0 text-center" method="get" action="/recherche">
    <div>
      <a href="/"><img class="align-self-center" id="logo" src="/images/juricaf.png" alt="Juricaf" /></a> <br>
      <p><small class="slogan-landing">La jurisprudence francophone des Cours suprêmes</small></p>
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
        if(!isset($not_autofocus) || !$not_autofocus){
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
<?php ///////////////////////////////////////////////////////////////////////// ?>
