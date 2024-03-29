<?php //////////////// BARRE DE RECHECHE ///////////////// ?>
<div <?php if($sf_request && $sf_request->getParameter('module')=="arret"){ echo('id = "hidden-mode-mobile"');}else{echo('id = "menu"');} ?> class="text-center my-2 my-lg-0">
<?php if (!isset($noform) || !$noform): ?>
<div class="container form_recherche mt-4 clearfix">
  <form class="my-2 my-lg-0 text-center" method="get" action="/recherche">
  <?php endif; ?>
      <?php if(!isset($noentete)): ?>
    <div class="d-none d-lg-block mt-4">
      <a href="/"><img style="height: 100px;" class="align-self-center" id="logo" src="/images/juricaf.png" alt="Juricaf" /></a>
      <p><small class="text-secondary">La jurisprudence francophone des Cours suprêmes</small></p>
    </div>
    <div class="pt-2 d-lg-none"></div>
    <?php else: ?>
    <?php endif; ?>
      <div class="form-inline input-group input-group-lg">
      <input id="recherche" class="form-control mx-auto" autocomplete="off" type="text"
      <?php if($sf_request && $sf_request->getParameter('query') && ($sf_request->getParameter('query') != " ")){
          echo( "value = '".htmlspecialchars($sf_request->getParameter('query'), ENT_QUOTES)."'");
          $not_autofocus=true;
      }
      elseif($sf_user && $sf_user->getAttribute('query') && ($sf_user->getAttribute('query')!= " ") ){
        echo( "value = '".htmlspecialchars($sf_user->getAttribute('query'), ENT_QUOTES)."'");
        $not_autofocus=true;
      }
      ?>
      placeholder="Rechercher une jurisprudence" name="q" aria-label="Rechercher" tabindex="10"
      <?php
        if(!isset($not_autofocus) || !$not_autofocus){
          echo('autofocus=autofocus');
        }
      ?>
      ><button class="btn btn-primary rounded-end"  type="submit">
        <span class="d-lg-none">
          <i class="bi bi-search"></i>
        </span>
        <span class="d-none d-lg-block">Rechercher</span>
      </button>
      <br>
    </div>
<?php if (!isset($noform) || !$noform): ?>
    <a class="float-end d-none d-lg-block" href="/recherche_avancee">recherche avancée</a>
  </form>
</div>
<?php endif; ?>
</div>
<?php ///////////////////////////////////////////////////////////////////////// ?>
