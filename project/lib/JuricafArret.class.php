<?php

class JuricafArret extends sfCouchDocument 
{
  private static $fields = array('_id', 'analyses', 'date_arret', 'formation', 'juricaf_id', 'juridiction', 'num_arret', 'pays', 'section', 'texte_arret', 'titre', 'type'); 
  public function getFields() {
    $fields = array();
    foreach (self::$fields as $f) {
      if ($this->__isset($f))
	$fields[] = $f;
    }
    return $fields;
  }
  
  //Champ issu du v1
  public function getPublication() {
    $pub = $this->publication;
    if (preg_match('/^non$/i', $pub))
      return ;
    return $pub;
  }

  public function getReferences() {
    $ref = $this->references;
    if (is_array($ref))
      return $ref;
    //Gestion juricaf v1
    $references = array();
    if (!preg_match('/^non$/i', $ref))
      $references[] = array('type'=>'SOURCE', 'titre' => $ref);
    if ($p = $this->getPublication())
      $references[] = array('type'=>'PUBLICATION', 'titre'=>$p);
    return array('reference' => $references);
  }
}