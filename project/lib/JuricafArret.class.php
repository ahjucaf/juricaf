<?php

class JuricafArret extends sfCouchDocument 
{
  public static function getExcerpt($resultat, $highlighting = null) {
    $exerpt = '';
    if ($highlighting && isset($highlighting->content)) {
      foreach ($highlighting->content as $h) {
	$exerpt .= '...'.html_entity_decode($h);
      }
      $exerpt .= '...' ;
    }
    if ($resultat->analyses) {
      $exerpt .= $resultat->analyses.'...';
    }
    return  preg_replace ('/[^a-z0-9]*\.\.\.$/i', '...', truncate_text($exerpt.$resultat->texte_arret, 650, "...", true));
  }

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