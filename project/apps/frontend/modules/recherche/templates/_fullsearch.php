<script type="text/javascript">
<!--
aujourdhui = new Date();
datemini = new Date();
datemini.setTime(aujourdhui.getTime() + (-200*365*24*60*60*1000)); // 200 ans

$(function() {
  $('input[name^="date"]').each(function() {
    $(this).datepicker({
    yearRange: '1850:c',
    minDate: datemini,
    maxDate: aujourdhui,
    changeMonth : true,
    changeYear : true
    //onClose: function() {  }
    });
  })
});

jQuery(function($){
  $.datepicker.regional['fr'] = {
    closeText: 'Fermer',
    prevText: '&#x3c;Préc',
    nextText: 'Suiv&#x3e;',
    currentText: 'Courant',
    monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin',
    'Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
    monthNamesShort: ['Jan','Fév','Mar','Avr','Mai','Jun',
    'Jul','Aoû','Sep','Oct','Nov','Déc'],
    dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
    dayNamesShort: ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'],
    dayNamesMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
    weekHeader: 'Sm',
    dateFormat: 'dd/mm/yy',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''};
  $.datepicker.setDefaults($.datepicker.regional['fr']);
});
-->
</script>

<h1>Recherche avancée</h1>
<h2>Critères</h2>
<p>
<select name="cr[1]">
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
<input type="text" name="val[1]" class="text_input" />
<br />
<select name="cond[1]">
  <option value="AND">ET</option>
  <option value="OR">OU</option>
  <option value="NOT">SAUF</option>
</select>
<br />
<select name="cr[2]">
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
<input type="text" name="val[2]" class="text_input" />
<br />
<select name="cond[2]">
  <option value="AND">ET</option>
  <option value="OR">OU</option>
  <option value="NOT">SAUF</option>
</select>
<br />
<select name="cr[3]">
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
<input type="text" name="val[3]" class="text_input" />
<br />
<select name="cond[3]">
  <option value="AND">ET</option>
  <option value="OR">OU</option>
  <option value="NOT">SAUF</option>
</select>
<br />
<select name="cr[4]">
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
<input type="text" name="val[4]" class="text_input" /><br />
</p>
<hr />
<div class="calendars">
<h2>Date de la décision</h2>
<p>
<input type="text" name="date[arret]" id="date_arret" size="10" /><label for="date_arret"><img src="/images/calendar.png" alt="" /></label>
</p>
</div>
<div class="calendars">
<p>OU</p>
</div>
<div class="calendars">
<h2>Période</h2>
<p>
du <input type="text" name="date[debut]" id="date_debut" size="10" /><label for="date_debut"><img src="/images/calendar.png" alt="" /></label>
au <input type="text" name="date[fin]" id="date_fin" size="10" /><label for="date_fin"><img src="/images/calendar.png" alt="" /></label>
</p>
</div>
<hr style="clear: both;" />
<h2>Références</h2>
<p><input type="text" name="references" style="width:90.5%" /></p>
<hr />