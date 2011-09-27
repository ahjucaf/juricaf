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
    return  preg_replace('/[\(\{\[\]\}\)]/', '', preg_replace ('/[^a-z0-9]*\.\.\.$/i', '...', truncate_text($exerpt.$resultat->texte_arret, 650, "...", true)));
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

  public function rename($newid) {
    if (preg_match('/\-\-/', $newid))
      throw new sfException('Wrong new id: '.$newid);
    $this->delete();
    $this->_id = $newid;
    $this->storage->_id = $newid;
    unset($this->storage->_rev);
    $this->save();
    return $this;
  }

  public static function ids($str) {
    $str = strtr($str,
		 array('è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','ç'=>'c','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ñ'=>'n','ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u','ý'=>'y','ÿ'=>'y','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Ç'=>'C','È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y'));
    $str = preg_replace('/[^a-z0-9]/i', '', $str);
    return strtoupper($str);
  }

  public function getTheoriticalId() {
    return self::ids($this->pays).'-'.self::ids($this->juridiction).'-'.self::ids($this->date_arret).'-'.self::ids($this->num_arret);
  }
}