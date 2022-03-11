<h5 class="p-3 mb-2 mt-5 bg-secondary bg-gradient">Recherche avancée</h5>

<h5><b>Critères</b></h5>
<p>
<div class="row">
  <div class="col-lg-3">
    <select class="form-select" name="cr[1]">
      <option value="content">Plein texte</option>
      <option value="num_arret">Numéro d’affaire</option>
      <option value="sens_arret">Sens</option>
      <option value="nor">NOR</option>
      <option value="urnlex">URN:LEX</option>
      <option value="ecli">ECLI</option>
      <option value="type_affaire">Type affaire</option>
      <option value="type_recours">Type recours</option>
      <option value="president">Président</option>
      <option value="avocat_gl">Avocat général</option>
      <option value="rapporteur">Rapporteur</option>
      <option value="commissaire_gvt">Commissaire du gouvernement</option>
      <option value="avocats">Avocat</option>
      <option value="parties">Parties</option>
      <option value="analyses">Analyses</option>
      <option value="saisines">Saisine</option>
      <option value="fonds_documentaire">Fonds documentaire</option>
    </select>
  </div>
  <div class="col-lg-9">
    <input type="text" name="val[1]" class="text_input form-control" />
  </div>
</div>
<p></p>
<div class="row">
<div class="col-lg-3">
<select class="form-select" name="cond[1]">
  <option value="AND">ET</option>
  <option value="OR">OU</option>
  <option value="NOT">SAUF</option>
</select>
</div>
</div>
<p></p>
<div class="row">
<div class="col-lg-3">
<select class="form-select" name="cr[2]">
  <option value="content">Plein texte</option>
  <option value="num_arret">Numéro d’affaire</option>
  <option value="sens_arret">Sens</option>
  <option value="nor">NOR</option>
  <option value="urnlex">URN:LEX</option>
  <option value="ecli" selected="selected">ECLI</option>
  <option value="type_affaire">Type affaire</option>
  <option value="type_recours">Type recours</option>
  <option value="president">Président</option>
  <option value="avocat_gl">Avocat général</option>
  <option value="rapporteur">Rapporteur</option>
  <option value="commissaire_gvt">Commissaire du gouvernement</option>
  <option value="avocats">Avocat</option>
  <option value="parties">Parties</option>
  <option value="analyses">Analyses</option>
  <option value="saisines">Saisine</option>
  <option value="fonds_documentaire">Fonds documentaire</option>
</select>
</div>
<div class="col-lg-9">
<input type="text" name="val[2]" class="text_input form-control" />
</div>
</div>
<p></p>
<div class="row">
<div class="col-lg-3">
<select class="form-select" name="cond[2]">
  <option value="AND">ET</option>
  <option value="OR">OU</option>
  <option value="NOT">SAUF</option>
</select>
</div>
</div>
<p></p>
<div class="row">
  <div class="col-lg-3">
<select class="form-select" name="cr[3]">
  <option value="content">Plein texte</option>
  <option value="num_arret">Numéro d’affaire</option>
  <option value="sens_arret">Sens</option>
  <option value="nor">NOR</option>
  <option value="urnlex">URN:LEX</option>
  <option value="ecli">ECLI</option>
  <option value="type_affaire">Type affaire</option>
  <option value="type_recours">Type recours</option>
  <option value="president">Président</option>
  <option value="avocat_gl">Avocat général</option>
  <option value="rapporteur">Rapporteur</option>
  <option value="commissaire_gvt">Commissaire du gouvernement</option>
  <option value="avocats">Avocat</option>
  <option value="parties" selected="selected">Parties</option>
  <option value="analyses">Analyses</option>
  <option value="saisines">Saisine</option>
  <option value="fonds_documentaire">Fonds documentaire</option>
</select>
</div>
<div class="col-lg-9">
<input type="text" name="val[3]" class="text_input form-control" />
</div>
</div>
<p></p>
<div class="row">
<div class="col-lg-3">
<select class="form-select" name="cond[3]">
  <option value="AND">ET</option>
  <option value="OR">OU</option>
  <option value="NOT">SAUF</option>
</select>
</div>
</div>
<p></p>
<div class="row">
<div class="col-lg-3">
<select class="form-select" name="cr[4]">
  <option value="content">Plein texte</option>
  <option value="num_arret">Numéro d’affaire</option>
  <option value="sens_arret">Sens</option>
  <option value="nor">NOR</option>
  <option value="urnlex">URN:LEX</option>
  <option value="ecli">ECLI</option>
  <option value="type_affaire">Type affaire</option>
  <option value="type_recours">Type recours</option>
  <option value="president">Président</option>
  <option value="avocat_gl">Avocat général</option>
  <option value="rapporteur">Rapporteur</option>
  <option value="commissaire_gvt">Commissaire du gouvernement</option>
  <option value="avocats">Avocat</option>
  <option value="parties">Parties</option>
  <option value="analyses" selected="selected">Analyses</option>
  <option value="saisines">Saisine</option>
  <option value="fonds_documentaire">Fonds documentaire</option>
</select>
</div>
<div class="col-lg-9">
<input type="text" name="val[4]" class="text_input form-control"/>
</div>
</div>
<p></p>

<div class="float-end mb-3">
  <input class="btn btn-primary mt-3" type="reset" value="Effacer" />
  <input class="btn btn-primary mt-3" type="submit" value="Valider" />
</div>
<hr style="clear: both;"/>

<div class="row g-3">
  <div class="col-auto">
    <label class="col-form-label"><h5><b>Date de la décision : </b></h5></label>
  </div>
  <div class="col-auto">
    <input class="form-control col-auto" type="date" name="date[arret]" id="date_arret" max=<?php echo(date('Y-m-d'))?>>
  </div>
</div>
OU
<div class="row g-3">
  <div class="col-auto">
    <label class="col-form-label"><h5><b>Période du :</b></h5></label>
  </div>
  <div class="col-auto">
    <input class="form-control col-auto" type="date" name="date[debut]" id="date_debut" size="10" />
  </div>
  <div class="col-auto">
    <label class="col-form-label"><h5><b>au :</b></h5></label>
  </div>
  <div class="col-auto">
    <input class="form-control col-auto" type="date" name="date[fin]" id="date_fin" size="10" />
  </div>
</div>

<hr />

<div class="row">
  <div class="col-lg-3">
    <label class="col-form-label"><h5><b>Références :</b></h5></label>
  </div>
  <div class="col-lg-9">
    <input class="form-control" type="text" name="references" />
  </div>
</div>
<hr />
