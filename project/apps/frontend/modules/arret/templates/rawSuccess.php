<?php
////////////////////
///  Export TXT
///////////////////

if ($txt == true) {
    echo $document->getTexteArret();
    return;
}

////////////////////
///  Export JSON
///////////////////

if ($json == true) {
    
    function printJson($field, $balise) {
        if (!is_array($field)) {
            if ($balise == "texte_arret" || $balise == "texte_arret_anon") {
                $texte_html = str_replace("\n", "<br/>", $field);
                return $texte_html;
            }
            return $field;
        }
        $ret = array();
        if (array_keys($field)) {
            foreach ($field as $key => $value) {
                if (!is_int($key)) {
                    $ret[$key] = printJson($value, $key);
                }else{
                    $ret[] = printJson($value, $key);
                }
            }
        } else {
            foreach($field as $value) {
                $ret[] = printJson($value, $balise);
            }
        }
        return $ret;
    }
    
    $json = array();
    foreach ($document->getFields(true) as $field) {
        $json[$field] = printJson($document->{$field}, $field);
    }
    
    echo json_encode($json, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    
    return ;

} 

//////////////////
///  Export XML
//////////////////

echo '<?xml version="1.0" encoding="utf8"?>'; ?>

<DOCUMENT>
<?php

function printBalise($field, $balise)
{
  if (!is_array($field)) {
    echo $field;
    return ;
  }
  if (array_keys($field))
    foreach ($field as $k => $v) {
      if (is_array($v) && isset($v[0])) {
          foreach($v as $ssfield) {
              echo '<'.strtoupper($k).'>';
              printBalise($ssfield, $k);
              echo '</'.strtoupper($k).'>';
          }
      }else{
          if (!is_int($k)) echo '<'.strtoupper($k).'>';
          printBalise($v, $k);
          if (!is_int($k)) echo '</'.strtoupper($k).'>';
      }
    }
  else
    foreach($field as $v)
      printBalise($v, $balise);
}

foreach ($document->getFields(true) as $f) :
if (preg_match('/^_/', $f))
  continue;
echo '<'.strtoupper($f).'>';
printBalise($document->{$f}, $f);
echo '</'.strtoupper($f).'>';
endforeach; ?>
</DOCUMENT>