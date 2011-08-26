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
      "ELECT" => "Divers élections : observations",
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
      'Ž' => 'Z', 'ž' => 'z'
  );
  return strtr($string, $table);
}

function replaceBlank($str) {
  return str_replace (' ', '_', $str);
}

function pathToFlag($str) {
  return urlencode(str_replace("'", '_', replaceBlank($str)));
}

function sortLength($a,$b){
  return strlen($b)-strlen($a);
}


function linkifyAnalyses($titrage) {
  // identifiants
  if(preg_match('/(([0-9]{1,3}-)*([0-9]{1,3}){1})/', $titrage, $match)) {
    $identifiants[0] = $match[1]; $specifiques = $identifiants;
  }

  $separators = array(' -','- ',';','.',',');

  $titrage = str_replace($separators, ' - ', $titrage);
  $titrage = str_replace('  ', ' ', $titrage);
  $titrage = str_replace(' -  - ', ' - ', $titrage);
  $titrage = rtrim($titrage, '- ');

  $values = explode(' - ', $titrage);
  $values = array_filter($values);

  foreach ($values as $key => $value) {
    if(isset($identifiants)) {
      foreach($identifiants as $identifiant) {
        if(strpos($value, $identifiant) !== false) {
          $values[$key] = str_replace($identifiant, '', $value);
        }
      }
    }
    $values[$key] = @trim($values[$key]);
  }

  $values = array_filter($values);
  $values = array_unique($values);

  $i = 0;
  $titrage = '';

  foreach ($values as $value) {
    if($i == 0) { $link[$i] = $value; }
      else { $link[$i] = $link[$i-1].' - '.$value; }
      $titrage .= link_to($value, '@recherche_resultats?query=analyses:"'.replaceAccents($link[$i]).'"&facets=order:pertinance').' - ';
      $i++;
  }
  return rtrim($titrage, '- ').'.';
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
      $contributors .= 'Président : <em>'.$document->president.'</em><br />'; // replace br par ' ; '
    }
    if (isset($document->avocat_gl)) {
      $contributors .= 'Avocat général : <em>'.$document->avocat_gl.'</em><br />';
    }
    if (isset($document->rapporteur)) {
      $contributors .= 'Rapporteur : <em>'.$document->rapporteur.'</em><br />';
    }
    if (isset($document->commissaire_gvt)) {
      $contributors .= 'Commissaire gouvernement : <em>'.$document->commissaire_gvt.'</em><br />';
    }
    if (isset($document->avocats)) {
      $contributors .= 'Avocats : <em>'.$document->avocats.'</em><br />';
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
            if(strpos($key, 'titre') !== false) { if($document->pays == 'France') { $titrage = linkifyAnalyses($value); } else { $titrage = $value; } $analyses .= '<h2>'.$titrage.'</h2>'; $keywords .= $value.' '; }
            else { $analyses .= '<p>'.$value.'</p>'; }
            $analyses .= '</blockquote>';
          }
        }
      }
      else {
        if($values !== "null") {
          $analyses .= '<blockquote>';
            if(strpos($key, 'titre') !== false) { if($document->pays == 'France') { $titrage = linkifyAnalyses($values); } else { $titrage = $values; } $analyses .= '<h2>'.$titrage.'</h2>'; $keywords .= $values.' '; }
            else { $analyses .= '<p>'.$values.'</p>'; }
          $analyses .= '</blockquote>';
        }
      }
    }
    if(isset($references['CITATION_ANALYSE'])) {
      foreach($references['CITATION_ANALYSE'] as $value) {
        if(isset($value['nature'], $value['date'], $value['titre'])) {
          $titre = $value['nature'].' du '.$value['date'].' sur '.$value['titre'];
        }
        else { $titre = $value['titre']; }
        if(isset($value['url'])) {
          $citations_analyses .= '<a href="'.$value['url'].'">'.$titre.'</a><br />';
        }
        else { $citations_analyses .= $titre.'<br />'; }
      }
    }
  }
}

$citations_arret = '';
$sources = '';

if(isset($references['CITATION_ARRET']) || isset($references['SOURCE'])) {
  if(isset($references['CITATION_ARRET'])) {
    foreach($references['CITATION_ARRET'] as $value) {
      if(isset($value['nature'], $value['date'], $value['titre'])) {
        $titre = $value['nature'].' du '.$value['date'].' sur '.$value['titre'];
      }
      else { $titre = $value['titre']; }
      if(isset($value['url'])) {
        $citations_arret .= '<a href="'.$value['url'].'">'.$titre.'</a><br />';
      }
      else { $citations_arret .=  $titre.'<br />'; }
    }
  }

  if(isset($references['SOURCE'])) {
    foreach($references['SOURCE'] as $value) {
      if(isset($value['nature'], $value['date'], $value['titre'])) {
        $titre = $value['nature'].' du '.$value['date'].' sur '.$value['titre'];
      }
      else { $titre = $value['titre']; }
      if(isset($value['url'])) {
        $sources .= '<a href="'.$value['url'].'">'.$titre.'</a><br />';
      }
      else { $sources .= $titre.'<br />'; }
    }
  }
}

// ECLI //
if (isset($document->ecli)) {
  $creator = $document->juridiction;
  if(isset($document->section)) { $creator .= ' '.$document->section; }

  $citations = '';

  if (!empty($citations_analyses)) { $citations .= $citations_analyses; }
  if (!empty($citations_arret)) { $citations .= $citations_arret; }
  if (!empty($sources)) { $citations .= $sources; }

  //$sf_response->auto_discovery_link_tag(false, 'http://purl.org/dc/elements/1.1/', 'rel="schema.DC"');
  //<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />
  //<link rel="schema.DCTERMS" href="http://purl.org/dc/terms/" />

  // Obligatoire
  $sf_response->addMeta('DC.format', 'text/html; charset=utf-8', false, false, false);
  $sf_response->addMeta('DC.identifier', $sf_request->getUri(), false, false, false);
  $sf_response->addMeta('DC.isVersionOf', $document->ecli, false, false, false);
  $sf_response->addMeta('DC.creator', $creator, false, false, false);
  $sf_response->addMeta('DC.coverage', $document->pays, false, false, false);
  $sf_response->addMeta('DC.date', $document->date_arret, false, false, false);
  $sf_response->addMeta('DC.language', 'FR', false, false, false);
  $sf_response->addMeta('DC.publisher', 'AHJUCAF', false, false, false);
  $sf_response->addMeta('DC.accessRights', 'public', false, false, false);
  $sf_response->addMeta('DC.type', 'judicial decision', false, false, false);

  // Facultatif
  // $sf_response->addMeta('DC.title', 'Noms des parties', false, false, false);
  if(isset($document->type_affaire)) {
    $sf_response->addMeta('DC.subject', 'Affaire '.strtolower($document->type_affaire), false, false, false);
  }
  if(!empty($analyses)) {
    $sf_response->addMeta('DC.abstract', "Analyses : \n".strip_tags(str_replace('</blockquote>', " \n", $analyses)), false, false, false);
  }
  if(!empty($keywords)) {
    $sf_response->addMeta('DC.description', strip_tags(str_replace('</blockquote>', " ", $keywords)), false, false, false);
  }
  if(isset($contrib)) {
    $sf_response->addMeta('DC.contributor', strip_tags(str_replace('<br />', " ;\n", $contributors)), false, false, false);
  }
  //$sf_response->addMeta('DC.issued', 'Date de publication', false, false, false);
  if (!empty($citations)) {
    $sf_response->addMeta('DC.references', strip_tags(str_replace('<br />', " ;\n", $citations)), false, false, false);
  }
  // $sf_response->addMeta('DC.isReplacedBy', 'En cas de renumérotation', false, false, false);
}
?>
  <div class="arret">
    <h1><?php echo '<img class="drapeau" src="/images/drapeaux/'.pathToFlag($document->pays).'.png" alt="§" /> '.$document->titre; ?></h1>
    <?php
    if (isset($document->titre_supplementaire)) {
      echo '<h2>'.$document->titre_supplementaire.'</h2>';
    }
    if (isset($document->section)) {
      echo '<h3>'.$document->section.'</h3>';
    }
    if (isset($document->sens_arret)) {
      echo 'Sens de l\'arrêt : <em>'.$document->sens_arret.'</em><br />';
    }
    if (isset($document->type_affaire)) {
      if(isset($natureConstit[$document->type_affaire])) {
        echo 'Type d\'affaire : <em>'.$natureConstit[$document->type_affaire].'</em><br />';
      }
      else {
        echo 'Type d\'affaire : <em>'.$document->type_affaire.'</em><br />';
      }
    }
    if (isset($document->type_recours)) {
      echo 'Type de recours : <em>'.$document->type_recours.'</em><br />';
    }
    echo '<br />';
    if (isset($document->ecli)) {
      echo 'Identifiant ECLI : <em>'.$document->ecli.'</em> (non officiel) <img src="/images/aide.png" alt="?" style="margin-bottom: -3px; cursor: pointer;" title="Identifiant européen de la jurisprudence" /><br />';
    }
    if (isset($document->urnlex)) {
      echo 'Identifiant URN:LEX : <em>'.$document->urnlex.'</em> <img src="/images/aide.png" alt="?" style="margin-bottom: -3px; cursor: pointer;" title="A Uniform Resource Name (URN) Namespace for Sources of Law (LEX)" /><br />';
    }

    if (!empty($analyses)) {
      echo '<hr /><h3>Analyses : </h3>';
      echo $analyses;
    }
    if (!empty($citations_analyses)) {
      echo '<blockquote><p><em>Références :</em><br />'.$citations_analyses.'</p></blockquote>';
    }

    if (isset($document->saisines)) {
      echo '<hr />';
      echo '<h3>Saisine : </h3>';
      if (isset($document->saisines['saisine'])) {
        foreach($document->saisines['saisine'] as $key => $values) {
          echo '<div>';
          if(is_array($values) || is_object($values)) {
            foreach($values as $key => $value) {
              echo '<blockquote><p>';
              echo $value;
              echo '</p></blockquote>';
            }
          }
          else {
            echo '<blockquote><p>';
              echo $values;
              echo '</p></blockquote>';
          }
        }
        echo '</div>';
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
          echo '<em>'.$sep.$value.'</em>'; $i++;
        }
        echo '<br />';
      }
      if (isset($document->parties['defendeurs'])) {
        echo 'Défendeurs : ';
        $sep = ''; $i = 1;
        foreach($document->parties['defendeurs'] as $value) {
          if($i > 1) { $sep = ', '; }
          echo '<em>'.$sep.$value.'</em>'; $i++;
        }
        echo '<br />';
      }
    }

    echo '<hr />';

    if($document->pays == "Madagascar" && $document->juridiction == "Cour suprême" && trim($document->texte_arret) == "En haut a droite, cliquez sur PDF pour visualiser le fac-simile de la décision") {
    ?>
    <object data="http://www.juricaf.org/Juricaf/Arrets/Madagascar/Cour%20supr%C3%AAme/<?php echo $document->id_source; ?>.PDF" type="application/pdf" width="100%" height="1000" navpanes="0" statusbar="0" messages="0">
    Cette décision est disponible au format pdf : <a href="http://www.juricaf.org/Juricaf/Arrets/Madagascar/Cour%20supr%C3%AAme/<?php echo $document->id_source; ?>.PDF"><?php echo $document->titre; ?></a>
    </object>
    <?php
    }
    else {
      echo '<h3>Texte : </h3>';
      echo simple_format_text(trim($document->texte_arret));
    }
    if (!empty($citations_arret) || !empty($sources)) {
      echo '<p><em>Références : </em><br />';
      if (!empty($citations_arret)) { echo $citations_arret; }
      if (!empty($sources)) { echo $sources; }
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

    if(isset($document->nor) || isset($document->ecli) || isset($document->numeros_affaires)) {
      echo '<hr />';
      if (isset($document->nor)) {
        echo 'Numéro NOR : <em>'.$document->nor.'</em><br />';
      }
      if (isset($document->numeros_affaires)) {
        $sep = ''; $i = 0;
        foreach($document->numeros_affaires as $values) {
          if(is_array($values) || is_object($values)) {
            foreach($values as $value) {
              if($i > 0) { $sep = ', '; }
              $numeros = $sep.$value; $i++;
            }
          }
          else {
            if($i > 0) { $sep = ', '; }
            $numeros = $sep.$values; $i++;
          }
        }
        if($i > 1) { $s = 's'; } else { $s = ''; }
        echo 'Numéro d\'affaire'.$s.' : <em>'.$numeros.'</em><br />';
      }
    }

    if(isset($references['PUBLICATION'])) {
      echo '<hr /><h3>Publications :</h3>';
        foreach($references['PUBLICATION'] as $value) {
          if(isset($value['url'])) {
            echo '<a href="'.htmlentities($value['url']).'">'.$value['titre'].'</a><br />';
          }
          else { echo $value['titre'].'<br />'; }
        }
    }

    if(isset($contrib)) {
      echo '<hr /><h3>Composition du Tribunal :</h3>'.$contributors;
    }

    if (isset($document->fonds_documentaire)) {
      echo '<hr /><p>Origine : <em>'.$document->fonds_documentaire.'</em></p><br />';
    }
    ?>
  </div>
  <div class="extra">
    <a href="/couchdb/_utils/document.html?<?php echo sfConfig::get('app_couchdb_database');?>/<?php echo $document->_id; ?>">Admin</a>
  </div>
  <div class="download">
  <?php //echo link_to('Télécharger au format juricaf', '@arretxml?id='.$document->_id); ?>
  </div>
  <?php
///// METAS /////
// CLASSIQUES //
$sf_response->setTitle($document->titre.' - Juricaf');
if(!empty($analyses)) {
  $sf_response->addMeta('Description', strip_tags(str_replace('</blockquote>', " ", $analyses)));
}
elseif (!empty($document->texte_arret)) {
  $sf_response->addMeta('Description', truncate_text(strip_tags(str_replace("\n", " ", trim($document->texte_arret))), 260));
}
if(!empty($keywords)) {
$sf_response->addMeta('Keywords', $keywords);
}
?>
