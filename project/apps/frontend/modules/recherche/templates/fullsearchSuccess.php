<form method="get" action="<?php echo url_for('recherche_avancee'); ?>">
<?php
if(!empty($query)) { echo 'Query : '.$query."<br />"; }
if(!empty($filter)) { echo 'Filter : '.$filter; }
?>
<?php include_component('recherche', 'fullsearch'); ?>
<?php include_component('arret', 'searchPays'); ?>
<hr />
<div style="float: right; padding-top: 0.6em; padding-bottom: 1em;"> <input type="reset" value="Effacer" /> <input type="submit" value="Valider" /></div>
<hr style="clear: both;" />
</form>