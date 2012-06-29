<?php
use_helper('Text');

$natureConstit = array(
      "QPC" => "Question prioritaire de constitutionnalité",
      "DC" => "Contrôle de constitutionnalité des lois ordinaires, lois organiques, des traités, des règlements des Assemblées",
      "LP" => "Contrôle de constitutionnalité des lois du pays de Nouvelle-Calédonie",
      "L" => "Déclassements de textes législatifs au rang réglementaire",
      "FNR" => "Fins de non-recevoir",
      "LOM" => "Répartitions des compétences entre l'État et certaines collectivités d'outre-mer",
      "AN" => "Élections à l'Assemblée nationale",
      "SEN" => "Élections au Sénat",
      "PDR" => "Élection présidentielle",
      "REF" => "Référendums",
      "ELEC" => "Divers élections : observations",
      "D" => "Déchéance de parlementaires",
      "I" => "Incompatibilité des parlementaires",
      "AR16" => "Article 16 de la Constitution (pouvoirs exceptionnels du Président de la République)",
      "NOM" => "Nomination des membres",
      "RAPP" => "Nomination des rapporteurs-adjoints",
      "ORGA" => "Décision d'organisation du Conseil constitutionnel",
      "AUTR" => "Autres décisions"
      );

function replaceAccents($string) {
  $table = array(
      'Å' => 'A', 'Ä' => 'A', 'Ã' => 'A', 'Â' => 'A', 'å' => 'a', 'ä' => 'a', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'Á' => 'A', 'Æ' => 'A', 'æ' => 'a', 'À' => 'A',
      'Þ' => 'B', 'þ' => 'b',
      'ç' => 'c', 'Č' => 'C', 'č' => 'c', 'ć' => 'c', 'Ç' => 'C', 'Ć' => 'C', 'đ' => 'dj', 'Đ' => 'Dj',
      'ê' => 'e', 'É' => 'E', 'ë' => 'e', 'é' => 'e', 'è' => 'e', 'Ë' => 'E', 'È' => 'E', 'Ê' => 'E',
      'í' => 'i', 'ì' => 'i', 'Î' => 'I', 'Ì' => 'I', 'î' => 'i', 'Í' => 'I', 'ï' => 'i', 'Ï' => 'I',
      'ñ' => 'n', 'Ñ' => 'N',
      'ö' => 'o', 'ø' => 'o', 'õ' => 'o', 'ô' => 'o', 'ð' => 'o', 'ò' => 'o', 'ó' => 'o', 'Ö' => 'O', 'Ô' => 'O', 'Ó' => 'O', 'Ò' => 'O', 'Õ' => 'O', 'Ø' => 'O',
      'ŕ' => 'r', 'Ŕ' => 'R',
      'š' => 's', 'Š' => 'S', 'ß' => 'Ss',
      'ü' => 'u', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'Ü' => 'U', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U',
      'ý' => 'y', 'ÿ' => 'y', 'Ý' => 'Y',
      'Ž' => 'Z', 'ž' => 'z',
	  '1) ' => '', '2) ' => '', '3) ' => '', '4) ' => '', '5) ' => '',
	  '1° ' => '', '2° ' => '', '3° ' => '', '4° ' => '', '5° ' => '',
	  '1°  ' => '', '3°  ' => '',
	  '(Sur le premier moyen)' => '', '(Sur le deuxième moyen)' => '', '(Sur le troisième moyen)' => '', '(Sur le second moyen)' => '',	'(Sur le moyen relevé d\'office)' => '', '(Sur le 1er moyen)' => '', '(Sur le 2e moyen)' => '', 
	  '(sur le premier moyen)' => '', '(sur le deuxième moyen)' => '', '(sur le troisième moyen)' => '', '(sur le second moyen)' => '',	'(sur le moyen relevé d\'office)' => '', '(sur le 1er moyen)' => '', '(sur le 2e moyen)' => '', 
	  '(Sur le deuxieme moyen)' => '', '(Sur le troisieme moyen)' => '', '(Sur le moyen releve d\'office)' => '',
	  '(sur le deuxieme moyen)' => '', '(sur le troisieme moyen)' => '', '(sur le moyen releve d\'office)' => '',
	  '(Sur le deuxieme moyen)' => '', '(Sur le troisieme moyen)' => '', '(Sur le moyen releve d\'office)' => '',
		  '\n' => ' ', '/' => ' ', '"' => ' ', '»' => ' ', '«' => ' ', '’' => ' ', '?' =>' '
 );
  return strtr($string, $table);
}

function replaceBlank($str) {
  return str_replace (' ', '_', $str);
}

function replacekey($string) {
  $table = array(
      ' - ' => ', ', ' — ' => ', '	  
  );
  return strtr($string, $table);
}

function citation($string) {
  $table = array(
      'Cour de cassation' => 'Cass.', 'Chambre civile 1' => 'Civ. 1ère', 'Chambre civile 2' => 'Civ. 2e', 'Chambre civile 3' => 'Civ. 3e', 'Chambre criminelle' => 'Crim.', 'Chambre sociale' => 'Soc.', 'Chambre commerciale' => 'Com.', 'Chambres reunies' =>'ch. réun.', 'Chambre commerciale' => 'Com.', 'Assemblee pleniere' => 'Ass. Plén.','Chambre mixte' => 'ch. mixte.', 'Bulletin' => '', 'Publié au bulletin' => '', 'Bulletin Criminel  Cour de Cassation Chambre criminelle' => '', 'ARRETS Cour de Cassation Chambre civile' => ''
	  
  );
  return strtr($string, $table);
}

function dateFr($date) {
  if(preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $date)) {
$Mois = array("janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");
  $d = explode('-', $date);
    $date = $d[2].' '.$Mois[date('n')].' '.$d[0];
  }
  return $date;
}

function pathToFlag($str) {
  return urlencode(str_replace("'", '_', replaceBlank($str)));
}

function sortLength($a,$b) {
  return strlen($b)-strlen($a);
}

function replaceDate($string) {
  $table = array(
  '_' => '','CSC' => 'csc'
    );
	
return strtr($string, $table);
}

function replaceDateCa($string) {
  $table = array(
  '_' => '','CSC' => 'scc'
    );
	
return strtr($string, $table);
}

function linkifyAnalyses($titrage, $pays) {
  if(is_array($titrage)) { $titrage = trim(str_replace('Array', '', implode(' ', $titrage))); }
  // identifiants
  if(preg_match('/(([0-9]{1,3}-)+([0-9]{1,3}){1})/', $titrage, $match)) {
    $identifiants[0] = $match[1]; $specifiques = $identifiants;
  }

  // les séparateurs ne sont pas harmonisés en base
  if($pays == 'France') {
    $separators = array(' -','- ',';','.',',');
    $titrage = str_replace($separators, ' - ', $titrage);
    $titrage = str_replace('  ', ' ', $titrage);
    $titrage = str_replace(' -  - ', ' - ', $titrage);
    $titrage = rtrim($titrage, '- ');
    $values = explode(' - ', $titrage);
  }
  // Canada : séparateurs harmonisés
  if($pays == 'Canada') {
    $titrage = rtrim($titrage, '. ');
    $values = explode(' — ', $titrage);
  }
  $values = array_filter($values);

  foreach ($values as $key => $value) {
    if(isset($identifiants)) {
      foreach($identifiants as $identifiant) {
        if(strpos($value, $identifiant) !== false) {
          $values[$key] = str_replace($identifiant, '', $value);
        }
      }
    }
    $values[$key] = @trim($values[$key], '* ');
  }

  $values = array_filter($values);
  $values = array_unique($values);

  $i = 0;
  $titrage = '';

  foreach ($values as $value) {
    if($i == 0) { $link[$i] = $value; }
      else { $link[$i] = $link[$i-1].' - '.$value; }
    $titrage .= link_to($value, '@recherche_resultats?query=analyses:"'.replaceAccents(str_replace(array("\n", "/",'"','»','«',"'","’","?"), " ", $link[$i])).'"').' - '; // &facets=order:pertinance
      $i++;
  }
  return rtrim($titrage, '- ').'.';
}

function printDecisionAttaquee($ref_or_da, $is_text = 0) {
  $i = 0; $temp = array();
  if(isset($ref_or_da['decision_attaquee'][0])) { $ref_or_da = $ref_or_da['decision_attaquee']; }
  foreach ($ref_or_da as $decision_attaquee) {
    if(!empty($decision_attaquee['titre'])) {
      $temp[$i] = $decision_attaquee['titre'];
    }
    elseif(!empty($decision_attaquee['formation'])) {
      $temp[$i] = $decision_attaquee['formation'];
      if(empty($decision_attaquee['url'])) {
        $temp[$i] = link_to($decision_attaquee['formation'], '@recherche_resultats?query=decisions_attaquees:"'.$decision_attaquee['formation'].'"');
      }
      if(!empty($decision_attaquee['date'])) {
        $temp[$i] .= ', '.dateFr($decision_attaquee['date']);
      }
    }
    if(!empty($decision_attaquee['url']) && !empty($temp[$i])) {
      $temp[$i] = '<a href="'.$decision_attaquee['url'].'">'.$temp[$i].'</a>';
    }
    if(!empty($decision_attaquee['nature']) && !empty($temp[$i])) {
        $temp[$i] = $temp[$i].' (Nature : '.link_to($decision_attaquee['nature'], '@recherche_resultats?query=decisions_attaquees:"'.$decision_attaquee['nature'].'"').')';
    }
    if(!empty($decision_attaquee['type']) && empty($temp[$i])) {
        $temp[$i] = link_to($decision_attaquee['type'], '@recherche_resultats?query=decisions_attaquees:"'.$decision_attaquee['type'].'"').' (type)';
    }
    $i++;
  }
  if(count($temp) > 0) {
    if(count($temp) > 1) {
      if($is_text > 0) { $type_da = 'Textes attaqués'; } else { $type_da = 'Décisions attaquées'; }
      $html_da = '<ul style="list-style-type: none;">'.$type_da.' : ';
      foreach ($temp as $value) {
        $html_da .= '<li><em>'.$value.'</em></li>';
      }
      $html_da .= '</ul>';
    }
    else {
      if($is_text > 0) { $type_da = 'Texte attaqué'; } else { $type_da = 'Décision attaquée'; }
      $html_da = $type_da.' : <em>'.$temp[0].'</em><br />';
    }
    return $html_da;
  }
}

if(isset($document->references)) {
  foreach ($document->getReferences() as $values) {
    $i = 0;
    if(is_array($values) || is_object($values)) {
      foreach($values as $keys => $value) {
        if(is_array($value) || is_object($value)) {
          if(isset($value['type'])) {
            foreach ($value as $key => $vals) {
              if($vals !== $value['type']) {
                $references[$value['type']][$i][$key] = $vals;
              }
            }
          }
        }
        else {
          if($value !== $values['type']) {
            $references[$values['type']][0][$keys] = $value;
          }
        }
      $i++;
      }
    }
  }
}

$contributors = '';

  if(isset($document->president) || isset($document->avocat_gl) || isset($document->rapporteur) || isset($document->commissaire_gvt) || isset($document->avocats)) {
    if (isset($document->president)) {
      $contributors .= '<div itemscope itemtype="http://schema.org/Person" itemprop="contributor"><span itemprop="jobTitle">Président</span> : <em itemprop="name">'.link_to($document->president, '@recherche_resultats?query=president:"'.replaceAccents($document->president).'"').'</div></em>'; // replace br par ' ; '
    }
    if (isset($document->avocat_gl)) {
      $contributors .= '<div itemscope itemtype="http://schema.org/Person" itemprop="contributor"><span itemprop="jobTitle">Avocat général</span> : <em itemprop="name">'.link_to($document->avocat_gl, '@recherche_resultats?query=avocat_gl:"'.replaceAccents($document->avocat_gl).'"').'</div></em>';
    }
    if (isset($document->rapporteur)) {
      $contributors .= '<div itemscope itemtype="http://schema.org/Person" itemprop="contributor"><span itemprop="jobTitle">Rapporteur</span> : <em itemprop="name">'.link_to($document->rapporteur, '@recherche_resultats?query=rapporteur:"'.replaceAccents($document->rapporteur).'"').'</div></em>';
    }
    if (isset($document->commissaire_gvt)) {
      $contributors .= '<div itemscope itemtype="http://schema.org/Person" itemprop="contributor"><span itemprop="jobTitle">Commissaire gouvernement</span> : <em itemprop="name">'.link_to($document->commissaire_gvt, '@recherche_resultats?query=commissaire_gvt:"'.replaceAccents($document->commissaire_gvt).'"').'</div></em>';
    }
    if (isset($document->avocats)) {
      $contributors .= '<div itemscope itemtype="http://schema.org/Person" itemprop="contributor"><span itemprop="jobTitle">Avocat(s)</span> : <em itemprop="name">'.link_to($document->avocats, '@recherche_resultats?query=avocats:"'.replaceAccents($document->avocats).'"').'</em></div>';
    }
    $contrib = true;
  }

$keywords = '';
$analyses = '';
$citations_analyses = '';

if (isset($document->analyses)) {
  if (isset($document->analyses['analyse'])) {
    foreach($document->analyses['analyse'] as $key => $values) {
      if(is_array($values) || is_object($values)) {
        foreach($values as $key => $value) {
          if($value !== "null") {
            $analyses .= '<blockquote>';
            if(strpos($key, 'titre') !== false) { if($document->pays == 'France' or $document->pays == 'Canada') { $titrage = linkifyAnalyses($value, $document->pays); } else { $titrage = $value; } $analyses .= '<h2 itemprop="keywords">'.$titrage.'</span></h2>'; $keywords .= $value.' '; }
            else { $analyses .= '<p><span itemprop="description">'.$value.'</span></p>'; }
            $analyses .= '</blockquote>';
          }
        }
      }
      else {
        if($values !== "null") {
          $analyses .= '<blockquote>';
            if(strpos($key, 'titre') !== false) { if($document->pays == 'France' or $document->pays == 'Canada') { $titrage = linkifyAnalyses($values, $document->pays); } else { $titrage = $values; } $analyses .= '<h2 itemprop="keywords">'.$titrage.'</span></h2>'; $keywords .= $values.' '; }
            else { $analyses .= '<p><span itemprop="description">'.$values.'</span></p>'; }
          $analyses .= '</blockquote>';
        }
      }
    }
    if(isset($references['CITATION_ANALYSE'])) {
      foreach($references['CITATION_ANALYSE'] as $value) {
        if(isset($value['nature'], $value['date'], $value['titre'])) {
          $titre = $value['nature'].' du '.dateFr($value['date']).', '.$value['titre'];
        }
        else { $titre = $value['titre']; }
        if(isset($value['url'])) {
          $citations_analyses .= '<a href="'.$value['url'].'">'.$titre.'</a><br />';
        }
        else { 
		$citations_analyses .= $titre.'<br />'; }
		$citations_analyses = preg_replace('#pourvoi[\x20-\x7E]n°[\x20-\x7E]([0-9]{2}[-][0-9]{2})[.]([0-9]{3})#', '<a href="http://www.juricaf.org/recherche/num_arret:$1$2$3">pourvoi n° $1$2$3</a>', $titre);
      }
    }
  }
}

$citations_arret = '';
$sources = '';

if(isset($references['CITATION_ARRET']) || isset($references['SOURCE'])) {
  if(isset($references['CITATION_ARRET'])) {
    foreach($references['CITATION_ARRET'] as $value) {
      if(isset($value['nature'], $value['date'], $value['titre'], $value['numero'])) {
        $titre = '<a href="http://www.juricaf.org/recherche/num_decision:'.$value['numero'].'">'.$value['nature'].' du '.dateFr($value['date']).', '.$value['titre'].'</a>';
			
      }
      else { $titre = $value['titre']; }
      if(isset($value['url'])) {
        $citations_arret .= '<a href="'.$value['url'].'">'.$titre.'</a><br />';
      }
      else { 
	  
	 $citations_arret = preg_replace('#(?<!href=")(?<!>)http://[a-z0-9._/-]+#i', '<a href="$0" target="_blank">$0</a>', $citations_arret);
	  $citations_arret = preg_replace('#([0-9]{4})[\x20-\x7E]CSC[\x20-\x7E]([0-9]{1,2})#', '<a href="http://www.juricaf.org/recherche/num_arret:$1CSC$2">$1 CSC $2</a>', $citations_arret); 
	  $citations_arret .=  $titre.'<br />'; }
    }
  }

  if(isset($references['SOURCE'])) {
    foreach($references['SOURCE'] as $value) {
      if(isset($value['nature'], $value['date'], $value['titre'])) {
        $titre = $value['nature'].' du '.dateFr($value['date']).' sur le '.$value['titre'];
      }
      else { $titre = $value['titre']; }
      if(isset($value['url'])) {
        $sources .= '<a href="'.$value['url'].'">'.$titre.'</a><br />';
      }
      else { $sources .= $titre.'<br />'; }
    }
  }
}

$decisions_attaquees = '';
$is_text = 0;

if(isset($document->decisions_attaquees)) {
  if(isset($document->decisions_attaquees['decision_attaquee'][0])) { $decisions_attaquees = $document->decisions_attaquees['decision_attaquee']; } else { $decisions_attaquees = $document->decisions_attaquees; }
  foreach($decisions_attaquees as $decision_attaquee) {
    if(isset($decision_attaquee["type"])) {
      if($decision_attaquee["type"] !== 'DECISION') { $is_text++; }
    }
  }
}

if(isset($references['DECISION_ATTAQUEE'])) { $decisions_attaquees = printDecisionAttaquee($references['DECISION_ATTAQUEE'], $is_text);}
elseif(isset($document->decisions_attaquees)) { $decisions_attaquees = printDecisionAttaquee($document->decisions_attaquees, $is_text);}

if(isset($references['PUBLICATION'])) {
       foreach($references['PUBLICATION'] as $value) {
	if($document->formation == 'Chambre criminelle')  { 
	$civcrim = ', Bull. crim.' ; 
	$civcrimlong ='Publié au bulletin des arrêts de la chambre criminelle';
	}
	if($document->formation !== 'Chambre criminelle')  { 
	$civcrim = ', Bull. civ.' ; 
	$civcrimlong ='Publié au bulletin des arrêts des chambres civiles';
	}
          if(isset($value['url'])) {
           	$bulletins = $value['titre'];
          }
          elseif(strpos(strtolower($value['titre']), 'lebon') !== false) {
		$lebon = $value['titre'];
		  }
		  elseif(strpos(strtolower($value['titre']), 'Bulletin') == false)  {
			$bulletins = $value['titre'];
		}
		 }
    } 
      $citation = ''.citation($document->juridiction).' '.citation($document->formation).', '.dateFr($document->date_arret).', pourvoi n°'.$document->num_arret.''.$civcrim.''.citation($bulletins).'';

// METADONNEES //

if(!empty($analyses)) {
  $sf_response->addMeta('Description', '');
}
elseif (!empty($document->texte_arret)) {
  $sf_response->addMeta('Description', '');
}
if(!empty($keywords)) {
$sf_response->addMeta('Keywords', '');
}

if(!empty($document->urnlex)) { $urnlex = $document->urnlex; } else { $urnlex = ''; }

$creator = $document->juridiction;
if(isset($document->section)) { $creator .= ' '.$document->section; }

$docketNumber = $document->numeros_affaires['numero_affaire'];
$docketNumber = $document->num_arret;
$citations = '';

if (!empty($citations_analyses)) { $citations .= $citations_analyses; }
if (!empty($citations_arret)) { $citations .= $citations_arret; }
if (!empty($sources)) { $citations .= $sources; }
if (!empty($decisions_attaquees)) { $citations .= $decisions_attaquees; }

$sf_response->setTitle($document->titre);
$sf_response->addMeta('DC.accessRights', 'public', false, false, false);
$sf_response->addMeta('DC.creator', $creator, false, false, false);
$sf_response->addMeta('DC.coverage', htmlspecialchars($document->pays, ENT_QUOTES), false, false, false);
$sf_response->addMeta('DC.date', $document->date_arret, false, false, false);
$sf_response->addMeta('DC.description', $document->type_affaire, false, false, false);
$sf_response->addMeta('DC.format', 'text/html; charset=utf-8', false, false, false);
$sf_response->addMeta('DC.language', 'FR', false, false, false);
$sf_response->addMeta('DC.publisher', 'AHJUCAF', false, false, false);
$sf_response->addMeta('DC.subject', replacekey($keywords), false, false, false);
$sf_response->addMeta('DC.type', 'case', false, false, false);
$sf_response->addMeta('docketNumber', $docketNumber, false, false, false);
$sf_response->addMeta('shortTitle', $citation, false, false, false);

if(isset($contrib)) {
  $sf_response->addMeta('DC.contributor', htmlspecialchars(strip_tags(str_replace('<br />', " ;\n", $contributors)), ENT_QUOTES), false, false, false);
}
if (!empty($citations)) {
  $sf_response->addMeta('DC.references', htmlspecialchars(strip_tags(str_replace('<br />', " ;\n", $citations)), ENT_QUOTES), false, false, false);
}

    if(isset($references['PUBLICATION'])) {
         foreach($references['PUBLICATION'] as $value) {
          if(strpos(strtolower($value['titre']), 'lebon') !== false || strpos(strtolower($value['titre']), 'Publié au bulletin') !== false) 
		  $reporter = $value['titre'];
		  {
			$sf_response->addMeta('reporter', $reporter, false, false, false);
          }
		  $reporter = $value['titre'];
  $sf_response->addMeta('reporter', $reporter, false, false, false);
        }
    }
?>
  <div class="arret" itemscope itemtype="http://schema.org/Article">
    <h1 id="titre" itemprop="name"><?php echo '<img class="drapeau" src="/images/drapeaux/'.pathToFlag($document->pays).'.png" alt="§" /> '.$document->titre.'' ?></h1>
    <?php
    if (isset($document->titre_supplementaire)) {
      echo '<h2 itemprop="alternativeHeadline">'.$document->titre_supplementaire.'</span></h2>';
    }
    if (isset($document->section)) {
      echo '<h3>'.$document->section.'</h3>';
    }
	    if (isset($document->sens_arret)) {
      echo 'Sens de l\'arrêt : <em>'.link_to($document->sens_arret, '@recherche_resultats?query=sens_arret:"'.replaceAccents($document->sens_arret).'"').'</em><br />';
    }
    if (isset($document->type_affaire)) {
      if(isset($natureConstit[$document->type_affaire])) {
        echo 'Type d\'affaire : <em><span itemprop="about">'.link_to($natureConstit[$document->type_affaire], '@recherche_resultats?query=type_affaire:"'.replaceAccents($document->type_affaire).'"').'</span></em><br />';
      }
      else {
        echo 'Type d\'affaire : <em><span itemprop="about">'.link_to($document->type_affaire, '@recherche_resultats?query=type_affaire:"'.replaceAccents($document->type_affaire).'"').'</span></em><br />';
      }
    }
    if (isset($document->type_recours)) {
      echo 'Type de recours : <em>'.link_to($document->type_recours, '@recherche_resultats?query=type_recours:"'.replaceAccents($document->type_recours).'"').'</em><br />';
    }

	echo '<p><h3>Numérotation : </h3>';
	if (isset($document->num_arret)and ($document->pays !== 'Canada')and ($document->juridiction !== 'Conseil constitutionnel')){
        echo 'Numéro d\'arrêt : '.$document->num_arret.'<br />';
      }
	  
	   if (isset($document->num_arret)and ($document->juridiction == 'Conseil constitutionnel')) {
        echo 'Numéro de décision : '.$document->num_arret.'<br />';
      }
	  
	   if (isset($document->num_arret) and ($document->pays == 'Canada')) {
        echo 'Référence neutre : '.$document->num_arret.'<br />';
      }
	        if (isset($document->id_source)) {
        echo 'Numéro NOR : '.$document->id_source.'<br />';
      }
	  
	  
	     if(isset($document->nor) || isset($document->numeros_affaires)) {
      if (isset($document->nor)) {
        echo 'Numéro NOR : '.$document->nor.'<br />';
      }
	  
      if (isset($document->numeros_affaires)) {
        $numeros = '';
        foreach($document->numeros_affaires as $values) {
          if(is_array($values) || is_object($values)) {
            $nb_num_affaires = count($values);
            foreach($values as $value) {
              $sep = '';
              if (!empty($numeros)) { $sep = ', '; }
              $numeros .= $sep.$value;
            }
          }
          else {
            $nb_num_affaires = count($document->numeros_affaires);
            $sep = '';
            if (!empty($numeros)) { $sep = ', '; }
            $numeros .= $sep.$values;
          }
        }
 
	   if($nb_num_affaires > 1) { $s = 's'; } else { $s = ''; }
        echo 'Numéro d\'affaire'.$s.' : <em>'.$numeros.'</em><br />';
      }
	  
      if (isset($document->num_decision)) {
        echo 'Numéro de décision : <em>'.$document->num_decision.'</em><br />';
      }
    }
	 
	  if (isset($document->urnlex)) {
      echo 'Identifiant URN:LEX : '.$document->urnlex.' <img src="/images/aide.png" alt="?" style="margin-bottom: -3px; cursor: pointer;" title="A Uniform Resource Name (URN) Namespace for Sources of Law (LEX)" /><br />';
    }
		
    if (!empty($analyses)) {
      echo '<hr /><h3>Analyses : </h3>';
      echo $analyses;
    }

  //bdd CNIL
    if (isset($document->protection_donnees)) { //bdd CNIL
      echo '<hr /><h3>Analyses protection des données personnelles : </h3>';
      echo ''.$document->protection_donnees.'';
    }

      if (isset($document->cote_donnees)) {
      echo '<hr /><h3>Intérêt pour la protection des données personnelles : '.$document->cote_donnees.'</h3>';

    }

    if (!empty($citations_analyses)) {
      echo '<p><h3>Références :</h3><br />'.$citations_analyses.'</p>';
    }
    if (isset($document->saisines)) {
      echo '<hr />';
      echo '<h3>Saisine : </h3>';
      if (isset($document->saisines)) {
        if(is_array($document->saisines)) {
          foreach($document->saisines as $key => $values) {
            echo '<div>';
            if(is_array($values) || is_object($values)) {
              foreach($values as $key => $value) {
                echo '<blockquote><p>';
                echo simple_format_text($value);
                echo '</p></blockquote>';
              }
            }
            else {
              echo '<blockquote><p>';
                echo simple_format_text($values);
                echo '</p></blockquote>';
            }
          }
          echo '</div>';
        }
        else {
          echo '<div><blockquote><p>';
		  
		  $document->saisines = preg_replace('#(article[\x20-\x7E][a-z0-9._-]{1,})([\x20-\x7E]de[\x20-\x7E]la[\x20-\x7E])(Constitution)#', '<a href="http://www.juricaf.org/recherche/$1 $3">$1$2$3</a>', $document->saisines);
		$document->saisines = preg_replace('#(?<!href=")(?<!>)http://[a-z0-9._/-]+#i', '<a href="$0" target="_blank">$0</a>', $document->saisines);
		  
		  $document->saisines = preg_replace('#([a-z0-9._-]{2,}-[a-z0-9._-]{1,})([\x20-\x7E]*DC)#', '<a href="http://www.juricaf.org/recherche/num_arret:$1">$1$2</a>', $document->saisines);
		  
          echo simple_format_text($document->saisines);
          echo '</p></blockquote></div>';
        }
      }
    }

    if (isset($document->parties)) {
      echo '<hr />';
      echo '<h3>Parties : </h3>';
      if (isset($document->parties['demandeurs'])) {
        echo 'Demandeurs : ';
        $sep = ''; $i = 1;
        foreach($document->parties['demandeurs'] as $value) {
          if($i > 1) { $sep = ', '; }
          echo '<em>'.$sep.link_to($value, '@recherche_resultats?query=parties:"'.str_replace(array("\n", "\r"), ' ', replaceAccents($value)).'"').'</em>'; $i++;
        }
        echo '<br />';
      }
      if (isset($document->parties['defendeurs'])) {
        echo 'Défendeurs : ';
        $sep = ''; $i = 1;
        foreach($document->parties['defendeurs'] as $value) {
          if($i > 1) { $sep = ', '; }
          echo '<em>'.$sep.link_to($value, '@recherche_resultats?query=parties:"'.str_replace(array("\n", "\r"), ' ', replaceAccents($value)).'"').'</em>'; $i++;
        }
        echo '<br />';
      }
    }

    echo '<hr />';

    if($document->pays == "Madagascar" && $document->juridiction == "Cour suprême" && trim($document->texte_arret) == "En haut a droite, cliquez sur PDF pour visualiser le fac-simile de la décision") {
    ?>
    <object data="http://<?php echo $sf_request->getHost(); ?>/pdf/madagascar/cour_supreme/<?php echo $document->id_source; ?>.pdf" type="application/pdf" width="100%" height="1000" navpanes="0" statusbar="0" messages="0">
    Fac-similé disponible au format PDF : <a href="/pdf/madagascar/cour_supreme/<?php echo $document->id_source; ?>.pdf"><?php echo $document->titre; ?></a>
    </object>
    <?php
    }
   if($document->pays == "CEMAC" && trim($document->texte_arret) == "Le fac-simile de l'arrêt n'est disponible qu'au format PDF") {
    ?>
    <object data="http://<?php echo $sf_request->getHost(); ?>/pdf/CEMAC/cour_de_justice/<?php echo $document->id_source; ?>.pdf" type="application/pdf" width="100%" height="1000" navpanes="0" statusbar="0" messages="0">
    Fac-similé disponible au format PDF : <a href="/pdf/CEMAC/cour_de_justice/<?php echo $document->id_source; ?>.pdf"><?php echo $document->titre; ?></a>
    </object>
    <?php
    }

    else {
	if(!empty($document->texte_arret)) { $texte_arret = $document->texte_arret; } else { $texte_arret = ''; }
     
$texte_arret = preg_replace('#État[\x20-\x7E][(]décision[\x20-\x7E]n°[\x20-\x7E]([0-9]{5,6})#', 'État (<a href="http://www.juricaf.org/recherche/num_arret:$1">décision n° $1</a>', $texte_arret);
$texte_arret = preg_replace('#État[\x20-\x7E]n°[\x20-\x7E]([0-9]{5,6})#', 'État <a href="http://www.juricaf.org/recherche/num_arret:$1">n° $1</a>', $texte_arret);
$texte_arret = preg_replace('#arrêt[\x20-\x7E]n°[\x20-\x7E]([0-9]{2}[A-Z]{2}[0-9]{5})#', 'arrêt <a href="http://www.juricaf.org/recherche/num_arret:$1">n° $1</a>', $texte_arret);
$texte_arret = preg_replace('#(?<!href=")(?<!>)http://[a-z0-9._/-]+#i', '<a href="$0" target="_blank">$0</a>', $texte_arret);
$texte_arret = preg_replace('#([0-9]{4})[\x20-\x7E]CSC[\x20-\x7E]([0-9]{1,2})#', '<a href="http://www.juricaf.org/recherche/num_arret:$1CSC$2">$1 CSC $2</a>', $texte_arret);
$texte_arret = preg_replace('#(loi[\x20-\x7E]n°[\x20-\x7E][a-z0-9._-]{2,})#', '<a href="http://www.juricaf.org/recherche/texte_arret:$1">$1</a>', $texte_arret);
$texte_arret = preg_replace('#(décret[\x20-\x7E]n°[\x20-\x7E][a-z0-9._-]{2,})#', '<a href="http://www.juricaf.org/recherche/texte_arret:$1">$1</a>', $texte_arret);
//$texte_arret = preg_replace('#(article[\x20-\x7E][a-z0-9._-]{1,}[\x20-\x7E]du[\x20-\x7E][c,C]ode[\x20-\x7E].{1,})([,.])#', '<a href="http://www.juricaf.org/recherche/$1">$1$2</a>', $texte_arret);
//$texte_arret = preg_replace('#(article[\x20-\x7E][a-z0-9._-]{1,})([\x20-\x7E]de[\x20-\x7E]la[\x20-\x7E])(Constitution)#', '<a href="http://www.juricaf.org/recherche/texte_arret:"\$1 de la $3\"">$1$2$3</a>', $texte_arret); Pertinence mauvaise
$texte_arret = preg_replace('#([a-z0-9._-]{2,}-[a-z0-9._-]{1,})([\x20-\x7E]*DC)#', '<a href="http://www.juricaf.org/recherche/num_arret:$1">$1$2</a>', $texte_arret);

	echo '<h3>Texte : </h3>';
	  echo '<span itemprop="articleBody">'.simple_format_text(trim($texte_arret)).'</span>';
     }
    if (!empty($citations_arret) || !empty($sources) || !empty($decisions_attaquees)) {
      echo '<p><h3>Références : </h3>';
      if (!empty($citations_arret)) { echo $citations_arret; }
      if (!empty($sources)) { echo $sources; }
      if (!empty($decisions_attaquees)) { echo $decisions_attaquees; }
      echo '</p>';
    }
    if(isset($references['ARRET'])) {
      echo '<hr /><h3>Référence :</h3>';
        foreach($references['ARRET'] as $value) {
          if(isset($value['titre'])) {
            echo $value['titre'].'<br />';
          }
          if(isset($value['contenu'])) {
            echo $value['contenu'].'<br />';
          }
        }
    }
    // Lien télécharger le document
    if($document->pays == 'France') {
      if(strpos($document->id_source, "CONSTEXT") !== false || strpos($document->id_source, "JURITEXT") !== false || strpos($document->id_source, "CETATEXT") !== false)


	  {
    { echo '<hr /><h3>Publications :</h3>'; }
	
		  		if (isset($document->num_arret)and ($document->pays == 'France')and ($document->juridiction == 'Cour de cassation')){
	 echo 'Citation de la décision: '.$citation.'';
	 	echo '<br>'.$civcrimlong.' '.citation($bulletins).''; 
	 
	}
	
	echo '<br>'.$lebon.'<br>'; 
	
       if(strpos($document->id_source, "CONSTEXT") !== false) { echo '<a href="http://www.legifrance.gouv.fr/telecharger_rtf.do?idTexte='.$document->id_source.'&amp;origine=juriConstit" target="_blank"><img src="/images/rtf.png" alt="RTF" title="Télécharger au format RTF" />Télécharger au format RTF</a>'; }
        if(strpos($document->id_source, "JURITEXT") !== false) { echo '<a href="http://www.legifrance.gouv.fr/telecharger_rtf.do?idTexte='.$document->id_source.'&amp;origine=juriJudi" target="_blank"><img src="/images/rtf.png" alt="RTF" title="Télécharger au format RTF" />Télécharger au format RTF</a>'; }
        if(strpos($document->id_source, "CETATEXT") !== false) { echo '<a href="http://www.legifrance.gouv.fr/telecharger_rtf.do?idTexte='.$document->id_source.'&amp;origine=juriAdmin" target="_blank"><img src="/images/rtf.png" alt="RTF" title="Télécharger au format RTF" />Télécharger au format RTF</a>'; }
      }
    }
	
	if($document->pays == 'Canada') {
        echo '<p><a href="http://csc.lexum.org/fr/'.date('Y', strtotime($document->date_arret)).'/'.replaceDate($document->num_arret).'/'.replaceDate($document->num_arret).'.pdf" target="_blank"><img src="/images/pdf.png" alt="PDF" title="Télécharger au format PDF" />Télécharger au format PDF</a>				
		<br><a href="http://csc.lexum.org/fr/'.date('Y', strtotime($document->date_arret)).'/'.replaceDate($document->num_arret).'/'.replaceDate($document->num_arret).'.docx" target="_blank"><img src="/images/rtf.png" alt="DOCX" title="Télécharger au format DOCX" />Télécharger au format DOCX</a>	
		<br><a href="http://csc.lexum.org/fr/'.date('Y', strtotime($document->date_arret)).'/'.replaceDate($document->num_arret).'/'.replaceDate($document->num_arret).'.html" target="_blank"><img src="/images/web.png" alt="Web" title="Lien vers le site des jugements de la Cour suprême" />Version d\'origine</a>	
		<br><a href="http://csc.lexum.org/en/'.date('Y', strtotime($document->date_arret)).'/'.replaceDateCa($document->num_arret).'/'.replaceDateCa($document->num_arret).'.html" target="_blank"><img src="/images/web.png" alt="Web" title="Lien vers le site des jugements de la Cour suprême" />Version en anglais</a><p/>	
				
		';
   	}
	
    if(isset($contrib)) {
      echo '<hr /><h3>Composition du Tribunal :</h3>'.$contributors;
    }
	echo '<h3>Origine de la décision</h3>';
	
  if (isset($document->pays)) {
        echo '<div itemprop="author" itemscope itemtype="http://schema.org/Organization"> <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">Pays : <em itemprop="addressCountry">'.replaceAccents($document->pays).'</span></em>';
		}
		
 if (isset($document->juridiction)) {
        echo 'Juridiction : <em><span itemprop="name">'.replaceAccents($document->juridiction).'</span></em></span></div>';
      }
 if (isset($document->formation)) {
        echo 'Formation : <em>'.replaceAccents($document->formation).'</em><br />';
      }
	  
 if (isset($document->date_arret)) {
        echo 'Date de la décision : <span itemprop="dateCreated">'.date('d/m/Y', strtotime($document->date_arret)).'</span>' ;
      }

    if (isset($document->fonds_documentaire)) {
	      echo '<p>Fonds documentaire : <em itemprop="publisher">'.replaceAccents($document->fonds_documentaire).'</em></p>';
    }
    ?>
	
  </div>
  <div class="download">
  <?php //echo link_to('Télécharger au format juricaf', '@arretxml?id='.$document->_id); ?>
  </div> </div> 
  
<script type="text/javascript">
<!--
$('#titre').append('<span id="print"><div class="addthis_toolbox addthis_default_style "><a href="http://www.addthis.com/bookmark.php?v=250&amp;pubid=ra-4f0fffc35925f215" class="addthis_button_compact">Partager</a></div><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4f0fffc35925f215"></script><\/span>');
// -->
</script>
