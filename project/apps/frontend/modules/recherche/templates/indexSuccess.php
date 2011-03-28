<?php $sf_response->setTitle('Bienvenue sur Juricaf.org'); ?>
<div class="form_recherche">
  <img src="/images/juricaf.png" alt="Juricaf" />
  <form method="get" action="<?php echo url_for('recherche_resultats'); ?>">
    <input type="text" name="q" tabindex="10" style="width: 300px;" /><br />
    <input type="submit" value="Rechercher" tabindex="20" />
    <input type="button" value="Recherche avancée" tabindex="30" />
  </form>
</div>
