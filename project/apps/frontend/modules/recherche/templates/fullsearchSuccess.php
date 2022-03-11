<div class="container">
<form method="get" action="<?php echo url_for('recherche_avancee'); ?>">
<?php
if(!empty($query)) { echo 'Query : '.$query."<br />"; }
if(!empty($filter)) { echo 'Filter : '.$filter; }
?>
<?php include_component('recherche', 'fullsearch'); ?>
<?php include_component('arret', 'searchPays'); ?>
<hr />
<div class="float-end mb-3">
  <input class="btn btn-light mt-3" type="reset" value="Effacer" />
  <input class="btn btn-primary mt-3" type="submit" value="Valider" />
</div>
<hr style="clear: both;" />
</form>

</div>
