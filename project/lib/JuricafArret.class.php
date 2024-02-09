<?php

class JuricafArret extends sfCouchDocument 
{
  public function isError() {
    return ($this->type != 'arret');
  }

  public static function getExcerpt($resultat, $highlighting = null) {
    $exerpt = '';
    if ($resultat->analyses) {
      $exerpt .= $resultat->analyses.'...';
      $exerpt = truncate_text($exerpt,150,"...",true). " ";
    }
    if ($highlighting && isset($highlighting->content)) {
      foreach ($highlighting->content as $h) {
	$exerpt .= '...'.html_entity_decode($h);
      }
      $exerpt .= '...' ;
    }

    return  preg_replace('/[\(\{\[\]\}\)]/', '', preg_replace ('/[^a-z0-9]*\.\.\.$/i', '...', truncate_text($exerpt.$resultat->texte_arret, 650, "...", true)));
  }

  private static $hidden_fields = ['error', 'reason', 'date_import'];
  public function getFields($choose_anon = false) {
    $fields = array();
    $has_anon = ($this->storage->texte_arret_anon);
    foreach ($this->storage as $key => $v) {
        if (in_array($key, self::$hidden_fields) || ($choose_anon && $has_anon && $key == 'texte_arret') ) {
            continue;
        }
        $fields[] = $key;
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

  public function isTexteArretAnon() {
      return !empty($this->texte_arret_anon);
  }

  public function getTexteArret() {
      if ($this->isTexteArretAnon()) {
          return $this->texte_arret_anon;
      }
      return $this->texte_arret;
  }

  public function getDescription($limit = 250) {
      if (isset($this->analyses) && isset($this->analyses['analyse']) && count($this->analyses['analyse'])) {
        $description = 'Arrêt '.$this->juridiction;
        foreach($this->analyses['analyse'] as $a) {
            if (is_array($a) && isset($a['sommaire'])) {
                $description .= ' '.$a['sommaire'];
            }elseif(is_string($a)){
                $description .= ' '.$a;
            }
        }
        return preg_replace('/  +/' , ' ', preg_replace('/\n/', ' ', truncate_text($description, $limit, "...", true)));
      }
      return preg_replace('/  +/' , ' ', preg_replace('/\n/', ' ', truncate_text($this->getTexteArret(), $limit, "...", true)));
  }

  public function getKeywords() {
      $keywords = 'jurisprudence - '.$this->pays.' - '.$this->juridiction.' - '.$this->formation.' - '.$this->type_affaire;
      if (isset($this->analyses) && isset($this->analyses['analyse']) && count($this->analyses['analyse'])) {
        foreach($this->analyses['analyse'] as $a) {
            if (is_array($a) && isset($a['titre_principal'])) {
                $keywords .= ' '.$a['titre_principal'];
            }
        }
      }
      return $keywords;
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
