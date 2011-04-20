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
}