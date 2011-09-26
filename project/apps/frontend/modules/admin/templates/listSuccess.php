<?php
use_helper('Text');
?><style>
.even {background-color: #DDDDDD;}
table {border-spacing: 0px;
  border-collapse: collapse; }
 td {padding-left: 10px; padding-right: 10px;}
 .light {color: #888888;}
</style>
<form name='editor' id='editor'>
<p>
      Filtrer par mots cles : <input name="qa" value="<?php echo $qa; ?>" onChange="$('#changed').val(1);"/>
</p>
<table>
    <tr><th>&nbsp;</th><th>&nbsp;</th><th>Id</th>
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
  echo '<tr id="tr1_'.$cpt.'" class="clickable '.$class.'"><td rowspan='.$nbspan.'><input class="select" name="resultat'.$cpt.'" id="resultat'.$cpt.'" type="checkbox" value="'.$resultat->id.'"/></td><td rowspan='.$nbspan.'><a href="/couchdb/_utils/document.html?ahjucaf/'.$resultat->id.'">Modifier</a></td>';
  echo '<td>'.$resultat->num_arret.'</td><td>'.$publi[$resultat->type].'</td><td>'.$resultat->pays.'</td><td>'.$resultat->juridiction.'</td><td>'.$resultat->formation.'</td><td>'.$resultat->section.'</td><td>'.$resultat->sens_arret.'</td><td>'.$resultat->type_affaire.'</td><td>'.$resultat->type_recours.'</td><td>'.$resultat->fonds_documentaire.'</td><td>'.$resultat->reseau.'</td>';
  echo '</tr>';
  if (isset($resultat->on_error) )
      echo '<tr id="tr3_'.$cpt.'" class="error clickable'.$class.'"><td colspan=11>'.$resultat->on_error.'</td></tr>';
  echo '<tr id="tr3_'.$cpt.'" class="clickable'.$class.'">';
  echo '<td colspan=11 class="light">'.$resultat->titre.' ... ';
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
      <p><input type="checkbox" id="tout" onChange="if ($('#tout:checked').val() != undefined) $('.select').attr('checked', 'checked'); else {$('.select').removeAttr('checked');}">Tout selectionner</p>
      <p>Changer la publication de tous les éléments sélectionnés : <input name="action" type="submit" value="Publier"/> <input name="action" type="submit" value="Mettre en erreur"/>
      <p>Modifier tous les éléments sélectionnés : 
<select>
<option></option>
<option>Pays</option>
<option>Juridiction</option>
<option>Formation</option>
<option>Section</option>
<option>Sens arret</option>
<option>Type affaire</option>
<option>Type recours</option>
<option>Fonds documentaire</option>
<option>Reseau</option>
</select> : <input name="modif"/><input name="action" type="submit" value="Modifier"/></p>
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
      $('.clickable').click(function() {id = $(this).attr('id').replace(/.*_/, ''); if (!$('#resultat'+id+':checked').val()) $('#resultat'+id).attr('checked', 'checked'); else $('#resultat'+id).removeAttr('checked');});
--></script>
</form>