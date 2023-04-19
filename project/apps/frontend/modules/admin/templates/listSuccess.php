<?php
use_helper('Text');
?><style>
.even {background-color: #DDDDDD;}
table {border-spacing: 0px;
  border-collapse: collapse; }
 td {padding-left: 10px; padding-right: 10px;}
 .light {color: #888888;}
</style>
<form action="<?php echo url_for('@admin_list'); ?>" name='editor' id='editor'>
<p>
      Filtrer par mots cles : <input name="qa" value="<?php echo $qa; ?>" onChange="$('#modif_champ').val('');$('#changed').val(1);"/> <input type="submit" name="filter" value="Filtrer"/>
</p>
<?php if ($sf_user->hasFlash('admin_notice')) : ?>
<p class="even notice"><?php echo $sf_user->getFlash('admin_notice'); ?></p>
<?php endif; ?>
<?php if ($sf_user->hasFlash('admin_error')) : ?>
<p class="even error"><?php echo $sf_user->getFlash('admin_error'); ?></p>
<?php endif; ?>
<table>
    <tr><th>&nbsp;</th><th>&nbsp;</th>
<?php
$publi = array('error_arret' => 'Non publié', 'arret' => 'Publié');
foreach ($colums as $key => $label) {
   echo '<th>'.$label.'<br>';
   echo '<select onchange="$(\'#changed\').val(1);$(\'#editor\').submit();" name="'.$key.'"><option></option>';
   foreach ($facets[$key] as $f => $nb) {
     $libel = preg_replace('/^(.{15}).+(.{15})$/', '\1 ... \2', $f);
     if ($key == 'type') {
       $libel = $publi[$f];
     }
     echo '<option value="'.$f.'"';
     if (isset($options[$key]))
       echo " SELECTED ";
     echo ">$libel ($nb)</option>";
   }
   echo '</select></th>';
 }
?>
</tr>
<?php
$cpt = 0;
foreach ($resultats->response->docs as $resultat) {
  $cpt++;
  $class = '';
  if ($cpt % 2) 
    $class = ' even' ;
  $nbspan = 2;
  if (isset($resultat->on_error) )
    $nbspan = 3;
  echo '<tr id="tr1_'.$cpt.'" class="'.$class.'"><td rowspan='.$nbspan.'><input class="select" name="resultat'.$cpt.'" id="resultat'.$cpt.'" type="checkbox" value="'.$resultat->id.'"/></td><td rowspan='.$nbspan.'><a href="/couchdb/_utils/document.html?ahjucaf/'.$resultat->id.'">Modifier</a></td>';
  echo '<td class="clickable">'.$resultat->num_arret.'</td><td class="clickable">'.$publi[$resultat->type].'</td><td class="clickable">'.$resultat->pays.'</td><td class="clickable">'.$resultat->juridiction.'</td><td class="clickable">'.$resultat->formation.'</td><td class="clickable">'.$resultat->section.'</td><td class="clickable">'.$resultat->sens_arret.'</td><td class="clickable">'.$resultat->type_affaire.'</td><td class="clickable">'.$resultat->type_recours.'</td><td class="clickable">'.$resultat->fonds_documentaire.'</td><td class="clickable">'.$resultat->reseau.'</td>';
  echo '</tr>';
  if (isset($resultat->on_error) )
      echo '<tr id="tr3_'.$cpt.'" class="error clickable'.$class.'"><td colspan=11>'.$resultat->on_error.'</td></tr>';
  echo '<tr id="tr3_'.$cpt.'" class="clickable'.$class.'">';
  echo '<td colspan=11 class="light">';
  echo $resultat->titre;
  if (isset($resultat->date_import)) echo ' (importée le '.preg_replace('/T.*/', '', $resultat->date_import).')';
  echo ' : ';
  if (isset($resultats->highlighting))
    echo JuricafArret::getExcerpt($resultat, $resultats->highlighting->{$resultat->id});
  else
    echo JuricafArret::getExcerpt($resultat);
  echo '</td>';
  echo '</tr>';
  }
?></table>
<div class="even">
      <input type="hidden" name="nb_resultats" value="<?php echo $cpt; ?>"/>
      <p><input type="checkbox" id="tout" onChange="if ($('#tout:checked').val() != undefined) $('.select').attr('checked', 'checked'); else {$('.select').removeAttr('checked');}"><label for="tout">Tout selectionner</label></p>
      <p>Changer la publication de tous les éléments sélectionnés : <input name="action_publish" type="submit" value="Publier"/> <input name="action_error" type="submit" value="Mettre en erreur"/>  <input name="action_delete" type="submit" value="Supprimer"/>
      <p>Modifier pour tous les éléments sélectionnés : 
<select id="modif_champ" name="modif_champ">
<option></option>
<option value="pays">Pays</option>
<option value="juridiction">Juridiction</option>
<option value="formation">Formation</option>
<option value="section">Section</option>
<option value="sens_arret">Sens arret</option>
<option value="type_affaire">Type affaire</option>
<option value="type_recours">Type recours</option>
<option value="fonds_documentaire">Fonds documentaire</option>
<option value="reseau">Reseau</option>
<option value="date_arret">Date arret (Format AAAA-MM-JJ)</option>
</select> pour <input name="modif_valeur"/><input name="action_modif" type="submit" value="Modifier"/></p>
</div>
<div class="even">
<input type="hidden" name="page" value="<?php echo $page; ?>"/>
<input type="hidden" name="changed" id="changed" value="false"/>
   <?php if ($page > 1) : ?>
<input type="submit" name="page_precedente" value="< Page precedente"/>
   <?php endif ; if ($page < $maxpage): ?>
<input type="submit" name="page_suivante" value="Page suivante >"/>
   <?php endif; ?>
</div>
<script><!--
    $('.clickable').click(function() {try{id = $(this).attr('id').replace(/.*_/, '');}catch(err){ id = $(this).parent().attr('id').replace(/.*_/, ''); } if (!$('#resultat'+id+':checked').val()) $('#resultat'+id).attr('checked', 'checked'); else $('#resultat'+id).removeAttr('checked');});
--></script>
</form>