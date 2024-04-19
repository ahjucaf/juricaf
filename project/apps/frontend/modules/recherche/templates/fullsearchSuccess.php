<div class="container">
<form method="get" action="<?php echo url_for('recherche_avancee'); ?>">
<?php
if(!empty($query)) { echo 'Query : '.$query."<br />"; }
if(!empty($filter)) { echo 'Filter : '.$filter; }
?>

<h5 class="p-3 mb-2 mt-3 bg-secondary bg-gradient">Recherche avancée</h5>

<h5><b>Critères</b></h5>
<p>
<div id="active_criteria">
<div class="row">
  <div class="col-lg-3">
    <select class="form-select" name="cr[1]">
      <option value="content">Plein texte</option>
      <?php foreach($champs as $id => $libelle): ?>
      <option value="<?php echo $id; ?>"><?php echo $libelle; ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-lg-9">
    <input type="text" name="val[1]" class="text_input form-control input_search" />
  </div>
</div>
</div>
<div id="hidden_criteria" style="display:none;">
<p></p>
<div class="row">
<div class="col-lg-3">
<select class="form-select" name="cond[x]">
  <option value="AND">ET</option>
  <option value="OR">OU</option>
  <option value="NOT">SAUF</option>
</select>
</div>
</div>
<p></p>
<div class="row">
<div class="col-lg-3">
    <select class="form-select" name="cr[xp1]">
      <option value="content">Plein texte</option>
      <?php foreach($champs as $id => $libelle): ?>
      <option value="<?php echo $id; ?>"><?php echo $libelle; ?></option>
      <?php endforeach; ?>
    </select>
</div>
<div class="col-lg-9">
  <input type="text" name="val[xp1]" class="text_input form-control input_search" />
</div>
</div>
</div>
<p></p>
<div class="row">
    <div class="offset-lg-9 col-lg-3 text-end">
        <a href="#" id="more_criteria" class="btn btn-secondary" onClick="return addCriteria();">Ajouter un critère</a>
    </div>
</div>
<hr style="clear: both;"/>

<div class="row g-3">
  <div class="col-lg-3">
    <label class="col-form-label" for="date_debut"><h5><b>Période du :</b></h5></label>
  </div>
  <div class="col-auto">
    <div class="input-group">
        <input class="form-control col-auto" type="date" name="date[debut]" id="date_debut" />
        <label class="input-group-text" for="date_debut"><i class="bi bi-calendar"></i></label>
    </div>
  </div>
  <div class="col-auto">
    <label class="col-form-label" for="date_fin"><h5><b>au :</b></h5></label>
  </div>
  <div class="col-auto">
    <div class="input-group">
        <input class="form-control col-auto" type="date" name="date[fin]" id="date_fin" />
        <label class="input-group-text" for="date_fin"><i class="bi bi-calendar"></i></label>
    </div>
  </div>
</div>

<hr />

<?php include_component('arret', 'searchPays'); ?>
<hr />
<div class="float-end mb-3">
  <input class="btn btn-light mt-3" type="reset" value="Effacer" />
  <input class="btn btn-primary mt-3" type="submit" value="Valider" />
</div>
<hr style="clear: both;" />
</form>
<script>
    function addCriteria() {
        html = document.getElementById('hidden_criteria');
        actual_index = document.getElementsByClassName('input_search').length - 1;
        let div = document.createElement("div");
        div.innerHTML += html.innerHTML.replace('[x]', '['+actual_index+']').replaceAll('[xp1]', '['+ (actual_index + 1) +']');
        document.getElementById('active_criteria').append(div)
        return false;
    }
</script>
</div>
