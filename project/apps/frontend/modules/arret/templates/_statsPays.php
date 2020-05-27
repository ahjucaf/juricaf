<?php
function replaceBlank($str) {
  return str_replace (' ', '_', $str);
}

function num($num) {
  return preg_replace('/(\d)(\d{3})$/', '\1&nbsp;\2', $num);
}

function pathToFlag($str) {
  return urlencode(str_replace("'", '_', replaceBlank($str)));
}

$cpt = 0;
?>
<div class="pays">
<div class="payscols" id="payscols">
	  <table><tr>

		<?php
		foreach ($pays as $p)
		{
		  $pays = preg_replace('/ /', '_', $p['key'][0]);
		  if ($cpt % 4 == 1) { echo '<tr></tr>'; }
		  $cpt++;

		  // Traitement du nom du pays : si trop long, le nom est coupé
		  $pays_nom = $p['key'][0].' ('.num($p['value']).')';
		  $link = link_to($pays_nom,'recherche/search?query=+&facets=facet_pays:'.$pays);
		  if (strlen($pays_nom) > 45) {
			  $pays_nom_min = substr($pays_nom, 0, 16) . '...';
			  $link = link_to($pays_nom_min,'recherche/search?query=+&facets=facet_pays:'.$pays);
			  //$link = str_replace("<a ", "<a title=\"$pays_nom\" ", $link ); // Ajout du titre pour que popup du nom entier apparaisse
		  }

      if (strlen($pays) > 0) {
  		  echo '<td><img src="/images/drapeaux/'.pathToFlag(ucfirst($pays)).'.png" alt="'.$pays.'" />&nbsp;'.$link .'</td>';
		  }
  }
		?>
  </tr><tr><td colspan="5" class="plus"><img src="images/+.png" alt="+" /> <a href="/documentation/stats/statuts.php">Plus de statistiques</a></td></tr></table>
	</div>

</div>

<!--Modifié-->
<div class="more" >
  <a onclick="openPays()"><i id="icon_down"></i></a>
</div>
<!---->
