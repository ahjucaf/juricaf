<?php
//$argv[1] = 'JURITEXT000021730497.xml';
if (file_exists($argv[1])) {

$dila = simplexml_load_file($argv[1]);
    
$res = explode('/', $argv[1]);
$res = array_reverse($res);

#$id = $dila->xpath('/TEXTE_JURI_JUDI/META/META_COMMUN/ID');

$juricaf_array = array(
'PAYS code="FRANCE"' => 'France',
'JURIDICTION code="'.str_replace(" ", "-", strtoupper($dila->META->META_SPEC->META_JURI->JURIDICTION)).'"' => $dila->META->META_SPEC->META_JURI->JURIDICTION,
'FORMATION code="'.str_replace(" ", "-", strtoupper($dila->META->META_SPEC->META_JURI->JURIDICTION)).'-FRANCE"' => $dila->META->META_SPEC->META_JURI->JURIDICTION.' France',
'SECTION code="'.str_replace("_", "-", strtoupper($dila->META->META_SPEC->META_JURI_JUDI->FORMATION)).'"' => ucwords(str_replace("_", " ", strtolower($dila->META->META_SPEC->META_JURI_JUDI->FORMATION))),
'NUM_ARRET' => $dila->META->META_SPEC->META_JURI->NUMERO,
'DATE_ARRET' => $dila->META->META_SPEC->META_JURI->DATE_DEC,
'TEXTE_ARRET' => '<![CDATA['.implode('<br />', $dila->xpath('/TEXTE_JURI_JUDI/TEXTE/BLOC_TEXTUEL/CONTENU/*')).']]>',
'ANALYSES' => array('ANALYSE id="1"' => array('TITRE_PRINCIPAL' => 'A faire',
                                              'SOMMAIRE' => 'A faire'
                                             )
                   ),
'SENS_ARRET' => $dila->META->META_JURI->SOLUTION,
'DECISIONS_ATTAQUEES' => array('DECISION_ATTAQUEE id="1"' => $dila->META->META_JURI_JUDI->FORM_DEC_ATT),
'PARTIES' => array('DEMANDEURS' => array('DEMANDEUR' => $dila->META->META_JURI_JUDI->DEMANDEUR),
                   'DEFENDEURS' => array('DEFENDEUR' => $dila->META->META_JURI_JUDI->DEFENDEUR)
                  ),
'REFERENCES' => $dila->META->META_COMMUN->ID,
'PUBLICATION' => $dila->META->META_SPEC->META_JURI_JUDI->PUBLI_BULL,
'CAT_PUB' => '???',
'ID' => $dila->META->META_COMMUN->ID
);

$juricaf_str = '<DOCUMENT>';

foreach ($juricaf_array as $key => $value) {
  $tag_fin = explode(' ', $key);
  $juricaf_str .= '<'.$key.'>';
  if(is_array($value)) {
    foreach ($value as $sub1_key => $sub1_value) {
      $sub1_tag_fin = explode(' ', $sub1_key);
      $juricaf_str .= '<'.$sub1_key.'>';
      if(is_array($sub1_value)) {
        foreach ($sub1_value as $sub2_key => $sub2_value) {
          $sub2_tag_fin = explode(' ', $sub2_key);
          $juricaf_str .= '<'.$sub2_key.'>';
          if(is_array($sub2_value)) {
            foreach ($sub2_value as $sub3_key => $sub3_value) {
              $sub3_tag_fin = explode(' ', $sub3_key);
              $juricaf_str .= '<'.$sub3_key.'>'.$sub3_value.'</'.$sub3_tag_fin[0].'>';
            }
          }
          else { $juricaf_str .= $sub2_value.'</'.$sub2_tag_fin[0].'>'; }
        }
        $juricaf_str .= '</'.$sub1_tag_fin[0].'>';
      }
      else { $juricaf_str .= $sub1_value.'</'.$sub1_tag_fin[0].'>'; }
    }
    $juricaf_str .= '</'.$tag_fin[0].'>';
  }
  else { $juricaf_str .= $value.'</'.$tag_fin[0].'>'; }
}

$juricaf_str .= '</DOCUMENT>';
$juricaf = simplexml_load_string($juricaf_str, 'SimpleXMLElement', LIBXML_NOENT);
$file = "../data/juricaf/XML/France/".$res[0];
$handler = fopen($file,"w");
fputs($handler,$juricaf->asXML());
}
else { exit($argv[1]." chemin incorrect"); }
?>
