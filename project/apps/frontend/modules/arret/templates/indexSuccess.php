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

function sortLength($a,$b) {
  return strlen($b)-strlen($a);
}

function dateFr($date) {
  if(preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $date)) {
    $d = explode('-', $date);
    $date = $d[2].'/'.$d[1].'/'.$d[0];
  }
  return $date;
}

function linkifyAnalyses($titrage) {
  if(is_array($titrage)) { $titrage = trim(str_replace('Array', '', implode(' ', $titrage))); }
  // identifiants
  if(preg_match('/(([0-9]{1,3}-)+([0-9]{1,3}){1})/', $titrage, $match)) {
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
    $values[$key] = @trim($values[$key], '* ');
  }

  $values = array_filter($values);
  $values = array_unique($values);

  $i = 0;
  $titrage = '';

  foreach ($values as $value) {
    if($i == 0) { $link[$i] = $value; }
      else { $link[$i] = $link[$i-1].' - '.$value; }
      $titrage .= link_to($value, '@recherche_resultats?query=analyses:"'.replaceAccents(str_replace("\n", " ", $link[$i])).'"').' - '; // &facets=order:pertinance
      $i++;
  }
  return rtrim($titrage, '- ').'.';
}


function printDecisionAttaquee($ref_or_da) {
  $i = 0; $temp = array();
  foreach ($ref_or_da as $decision_attaquee) {
    if(!empty($decision_attaquee['titre'])) {
      $temp[$i] = $decision_attaquee['titre'];
    }
    elseif(!empty($decision_attaquee['formation'])) {
      $temp[$i] = $decision_attaquee['formation'];
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
      $html_da = 'Décisions attaquées : <ul>';
      foreach ($temp as $value) {
        $html_da .= '<li><em>'.$value.'</em></li>';
      }
      $html_da .= '</ul>';
    }
    else { $html_da = 'Décision attaquée : <em>'.$temp[0].'</em><br />'; }
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
      $contributors .= 'Président : <em>'.link_to($document->president, '@recherche_resultats?query=president:"'.replaceAccents($document->president).'"').'</em><br />'; // replace br par ' ; '
    }
    if (isset($document->avocat_gl)) {
      $contributors .= 'Avocat général : <em>'.link_to($document->avocat_gl, '@recherche_resultats?query=avocat_gl:"'.replaceAccents($document->avocat_gl).'"').'</em><br />';
    }
    if (isset($document->rapporteur)) {
      $contributors .= 'Rapporteur : <em>'.link_to($document->rapporteur, '@recherche_resultats?query=rapporteur:"'.replaceAccents($document->rapporteur).'"').'</em><br />';
    }
    if (isset($document->commissaire_gvt)) {
      $contributors .= 'Commissaire gouvernement : <em>'.link_to($document->commissaire_gvt, '@recherche_resultats?query=commissaire_gvt:"'.replaceAccents($document->commissaire_gvt).'"').'</em><br />';
    }
    if (isset($document->avocats)) {
      $contributors .= 'Avocat(s) : <em>'.link_to($document->avocats, '@recherche_resultats?query=avocats:"'.replaceAccents($document->avocats).'"').'</em><br />';
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
        $titre = $value['nature'].' du '.$value['date'].' sur le '.$value['titre'];
      }
      else { $titre = $value['titre']; }
      if(isset($value['url'])) {
        $sources .= '<a href="'.$value['url'].'">'.$titre.'</a><br />';
      }
      else { $sources .= $titre.'<br />'; }
    }
  }
}

// METADONNEES //
if(!empty($document->urnlex)) { $urnlex = $document->urnlex; } else { $urnlex = ''; }

// Pays que les moteurs tiers ne doivent pas indexer
$pays_noindex = array(
  "Bénin",
  "Mali",
  "Madagascar",
  "Luxembourg",
  "Guinée",
  "Haïti",
  "Sénégal",
  "Tchad"
  );

if(in_array($document->pays, $pays_noindex)) {
  $sf_response->addMeta('robots', 'noindex', false, false, false);
}

slot("metadata");
include_partial("metadata", array('dc_identifier_urnlex' => $urnlex, 'dc_identifier_uri' => $sf_request->getUri(), 'pays' => $document->pays,'juridiction' => $document->juridiction,));
end_slot();

$creator = $document->juridiction;
if(isset($document->section)) { $creator .= ' '.$document->section; }

$citations = '';

if (!empty($citations_analyses)) { $citations .= $citations_analyses; }
if (!empty($citations_arret)) { $citations .= $citations_arret; }
if (!empty($sources)) { $citations .= $sources; }

// Obligatoire pour ECLI
$sf_response->addMeta('DC.format', 'text/html; charset=utf-8', false, false, false);
if (isset($document->ecli)) { $sf_response->addMeta('DC.isVersionOf', $document->ecli, false, false, false); } // Identifiant ECLI
$sf_response->addMeta('DC.creator', htmlspecialchars($creator, ENT_QUOTES), false, false, false);
$sf_response->addMeta('DC.coverage', htmlspecialchars($document->pays, ENT_QUOTES), false, false, false);
$sf_response->addMeta('DC.date', $document->date_arret, false, false, false);
$sf_response->addMeta('DC.language', 'FR', false, false, false);
$sf_response->addMeta('DC.publisher', 'AHJUCAF', false, false, false);
$sf_response->addMeta('DC.accessRights', 'public', false, false, false);
$sf_response->addMeta('DC.type', 'judicial decision', false, false, false);

// Facultatif pour ECLI
$sf_response->addMeta('DC.title', htmlspecialchars($document->titre, ENT_QUOTES), false, false, false); // Obligatoire pour Zotero ; pour ECLI celle ci devrait accueillir les noms des parties (donc pas possible pour la France)
if(isset($document->type_affaire)) {
  $sf_response->addMeta('DC.subject', 'Affaire '.htmlspecialchars(strtolower($document->type_affaire), ENT_QUOTES), false, false, false);
}
if(!empty($analyses)) {
  $sf_response->addMeta('DC.abstract', "Analyses : \n".htmlspecialchars(strip_tags(str_replace('</blockquote>', " \n", $analyses)), ENT_QUOTES), false, false, false);
}
if(!empty($keywords)) {
  $sf_response->addMeta('DC.description', htmlspecialchars(strip_tags(str_replace('</blockquote>', " ", $keywords)), ENT_QUOTES), false, false, false);
}
if(isset($contrib)) {
  $sf_response->addMeta('DC.contributor', htmlspecialchars(strip_tags(str_replace('<br />', " ;\n", $contributors)), ENT_QUOTES), false, false, false);
}
//$sf_response->addMeta('DC.issued', 'Date de publication', false, false, false);
if (!empty($citations)) {
  $sf_response->addMeta('DC.references', htmlspecialchars(strip_tags(str_replace('<br />', " ;\n", $citations)), ENT_QUOTES), false, false, false);
}
// $sf_response->addMeta('DC.isReplacedBy', 'En cas de renumérotation', false, false, false);

?>
  <div class="arret">
    <h1 id="titre"><?php echo '<img class="drapeau" src="/images/drapeaux/'.pathToFlag($document->pays).'.png" alt="§" /> '.$document->titre; ?></h1>
    <?php
    if (isset($document->titre_supplementaire)) {
      echo '<h2>'.$document->titre_supplementaire.'</h2>';
    }
    if (isset($document->section)) {
      echo '<h3>'.$document->section.'</h3>';
    }
    if (isset($document->sens_arret)) {
      echo 'Sens de l\'arrêt : <em>'.link_to($document->sens_arret, '@recherche_resultats?query=sens_arret:"'.replaceAccents($document->sens_arret).'"').'</em><br />';
    }
    if (isset($document->type_affaire)) {
      if(isset($natureConstit[$document->type_affaire])) {
        echo 'Type d\'affaire : <em>'.link_to($natureConstit[$document->type_affaire], '@recherche_resultats?query=type_affaire:"'.replaceAccents($document->type_affaire).'"').'</em><br />';
      }
      else {
        echo 'Type d\'affaire : <em>'.link_to($document->type_affaire, '@recherche_resultats?query=type_affaire:"'.replaceAccents($document->type_affaire).'"').'</em><br />';
      }
    }
    if (isset($document->type_recours)) {
      echo 'Type de recours : <em>'.link_to($document->type_recours, '@recherche_resultats?query=type_recours:"'.replaceAccents($document->type_recours).'"').'</em><br />';
    }

    if (isset($references['DECISION_ATTAQUEE'])) { echo printDecisionAttaquee($references['DECISION_ATTAQUEE']);}
    elseif (isset($document->decisions_attaquees)) { echo printDecisionAttaquee($document->decisions_attaquees);}

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
    else {
      if(isset($document->texte_arret)) {
        echo '<h3>Texte : </h3>';
        echo simple_format_text(trim($document->texte_arret));
      }
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

    if(isset($document->nor) || isset($document->numeros_affaires)) {
      echo '<hr />';
      if (isset($document->nor)) {
        echo 'Numéro NOR : <em>'.$document->nor.'</em><br />';
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

    if(isset($references['PUBLICATION'])) {
      echo '<hr /><h3>Publications :</h3>';
        foreach($references['PUBLICATION'] as $value) {
          if(isset($value['url'])) {
            echo '<a href="'.urlencode($value['url']).'">'.$value['titre'].'</a><br />';
          }
          elseif(strpos(strtolower($value['titre']), 'lebon') !== false || strpos(strtolower($value['titre']), 'Publié au bulletin') !== false) {
            echo link_to($value['titre'], '@recherche_resultats?query=references:"'.$value['titre'].'"').'<br />';
          }
          else { echo $value['titre'].'<br />'; }
        }
    }

    // Lien télécharger le document
    if($document->pays == 'France') {
      if(strpos($document->id_source, "CONSTEXT") !== false || strpos($document->id_source, "JURITEXT") !== false || strpos($document->id_source, "CETATEXT") !== false) {
        if(!isset($references['PUBLICATION'])) { echo '<hr /><h3>Publications :</h3>'; }
        if(strpos($document->id_source, "CONSTEXT") !== false) { echo '<a href="http://www.legifrance.gouv.fr/telecharger_rtf.do?idTexte='.$document->id_source.'&amp;origine=juriConstit">Télécharger le document</a>'; }
        if(strpos($document->id_source, "JURITEXT") !== false) { echo '<a href="http://www.legifrance.gouv.fr/telecharger_rtf.do?idTexte='.$document->id_source.'&amp;origine=juriJudi">Télécharger le document</a>'; }
        if(strpos($document->id_source, "CETATEXT") !== false) { echo '<a href="http://www.legifrance.gouv.fr/telecharger_rtf.do?idTexte='.$document->id_source.'&amp;origine=juriAdmin">Télécharger le document</a>'; }
      }
    }

    if(isset($contrib)) {
      echo '<hr /><h3>Composition du Tribunal :</h3>'.$contributors;
    }

    if (isset($document->fonds_documentaire)) {
      echo '<hr /><p>Origine : <em>'.link_to($document->fonds_documentaire, '@recherche_resultats?query=fonds_documentaire:"'.replaceAccents($document->fonds_documentaire).'"').'</em></p><br />';
    }
    ?>
  </div>
  <div class="download">
  <?php //echo link_to('Télécharger au format juricaf', '@arretxml?id='.$document->_id); ?>
  </div>
  <?php
///// METAS /////
// CLASSIQUES //
$sf_response->setTitle($document->titre.' - Juricaf');
if(!empty($analyses)) {
  $sf_response->addMeta('Description', truncate_text(strip_tags(str_replace('</blockquote>', " ", $analyses)), 260));
}
elseif (!empty($document->texte_arret)) {
  $sf_response->addMeta('Description', truncate_text(strip_tags(str_replace("\n", " ", trim($document->texte_arret))), 260));
}
if(!empty($keywords)) {
$sf_response->addMeta('Keywords', $keywords);
}
?>
<script type="text/javascript">
<!--
$('#titre').append('<span id="print"><a href="javascript:print();"><img src="/images/printer.png" alt="Imprimer" title="Imprimer" /><\/a><\/span>');

// Highlighted search box
previous_terms = '<?php echo str_replace("'", "\'", htmlspecialchars($sf_user->getAttribute('query'), ENT_NOQUOTES)); ?>';
previous_facets = '<?php echo str_replace("'", "\'", htmlspecialchars($sf_user->getAttribute('facets'), ENT_NOQUOTES)); ?>';
terms='';

$(".content").append('<div id="searchbox"><input type="text" style="width: 30%; margin-top: 2px;" name="q" id="q" value="" /><select name="critere" id="critere"><option value="content">Plein texte</option><option value="references">Références</option><option value="num_arret">Numéro d’affaire</option><option value="sens_arret">Sens</option><option value="nor">NOR</option><option value="urnlex">URN:LEX</option><option value="ecli">ECLI</option><option value="type_affaire">Type affaire</option><option value="type_recours">Type recours</option><option value="president">Président</option><option value="avocat_gl">Avocat général</option><option value="rapporteur">Rapporteur</option><option value="commissaire_gvt">Commissaire du gouvernement</option><option value="avocats">Avocat</option><option value="parties">Parties</option><option value="analyses">Analyses</option><option value="saisines">Saisine</option><option value="fonds_documentaire">Fonds documentaire</option></select> <input type="checkbox" id="previous" onclick="javascript:updateReq()" title="Inclure les termes de votre dernière recherche" /> <input type="checkbox" id="facets" onclick="javascript:updateReq()" title="Rechercher dans la dernière collection interrogée" /> <input type="button" id="rechercher" onclick="javascript:searchHighlighted()" value="Rechercher dans toutes les collections" /> <span title="Vous pouvez utiliser * pour les troncatures, ! devant un terme ou un nom de champ pour l\'exclure et l\'opérateur OR"><img src="/images/aide.png" alt="?" /></span></div>');

$("#critere").bind("change", function() { updateReq(); });

$(".arret").bind("mouseup", function(e) {
  e.stopPropagation();
  terms=window.getSelection();
  if(terms != '' && terms != ' '){
    terms=addQuotes(terms);
    if($('#critere').val() !== 'content') { $("#q").val($('#critere').val()+':'+terms); }
    else { $("#q").val(terms); }
    if(previous_terms == '' || previous_terms == ' ') { $('#previous').prop({disabled: 'disabled', title: 'Aucun terme recherché précédemment'}); }
    if(previous_facets == '') { $('#facets').prop({disabled: 'disabled', title: 'Aucun collection recherchée précédemment'}); }
    $("#searchbox").css("display", "block");
    $("#searchbox").offset({ top: e.pageY+12 });
  }
  else {
    $("#searchbox").fadeOut("fast", function () { terms = '';  });
  }
});

function searchHighlighted() {
  req = $('#q').val();
  if($('#facets').prop('checked')) { req = req+'/'+previous_facets; }
  lien = '<?php echo url_for('recherche_resultats'); ?>/'+req;
  window.open(lien);
  return false;
}

function addCritere(str) {
  critere = $('#critere').val();
  if(critere == 'content') { req = str; }
  else { req = critere+':'+str; }
  return req;
}

function addQuotes(str) {
  reg_car_spe = new RegExp("[^a-z0-9]", "gi");
  if (reg_car_spe.test(str)) { str = '\"'+str+'\"'; }
  return str;
}

function facetToCollection(str) {
  // facet_pays_juridiction:France_|_Cour_de_cassation,facet_pays:France
  var fp=new RegExp("(facet_pays:)", "g");
  var fpj=new RegExp("(facet_pays_juridiction:)", "g");
  var virg=new RegExp("(,)", "g");
  var esp=new RegExp("(_)", "g");
  collection = str.replace(fp,'');
  collection = collection.replace(fpj,'');
  if (collection.match(virg)) { collection = collection.split(virg); collection = collection[0]; }
  collection = collection.replace(esp,' ');
  return collection;
}

function updateReq() {
  if($('#previous').prop('checked')) {
    new_req = previous_terms+' '+addCritere(terms);
  }
  else {
    new_req = addCritere(terms);
  }
  if($('#facets').prop('checked')) {
    $('#rechercher').val('Rechercher dans la collection '+facetToCollection(previous_facets));
  }
  else {
    $('#rechercher').val('Rechercher dans toutes les collections');
  }
  $('#q').val(new_req);
}
// -->
</script>
