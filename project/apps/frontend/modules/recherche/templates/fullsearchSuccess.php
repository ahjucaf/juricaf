<form method="get" action="<?php echo url_for('recherche_avancee'); ?>">
<?php include_component('recherche', 'fullsearch'); ?>
<?php include_component('arret', 'searchPays'); ?>
</form>