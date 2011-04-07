<?php

class JuricafArret extends sfCouchDocument 
{
  private static $fields = array('_id', 'analyses', 'date_arret', 'formation', 'juricaf_id', 'juridiction', 'num_arret', 'pays', 'section', 'texte_arret', 'titre', 'type'); 
  public function getFields() {
    return self::$fields;
  }
}