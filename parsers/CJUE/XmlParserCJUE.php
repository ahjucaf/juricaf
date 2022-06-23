<?php
class XmlParserCJUE {

  const PAYS = 'CJUE';
  const JURIDICTION = 'Cour de justice de l\'Union européenne';
  const FONDS_DOCUMENTAIRE = 'http://publications.europa.eu';
  const TEMPLATE_URL_SOURCE = 'https://eur-lex.europa.eu/legal-content/FR/TXT/PDF/?uri=CELEX:__ID__&from=FR';

  private $xmlObj;
  private $txtDoc;
  private $errors;

  public $date;
  public $pays;
  public $juridiction;
  public $avocats;
  public $rapporteurs;
  public $ecli;
  public $idSource;
  public $source;
  public $formation;
  public $titre;
  public $sousTitre;
  public $parties;
  public $identifiant;
  public $type;
  public $demandeur;
  public $defenseur;
  public $affaire;
  public $recours;
  public $analyses;

  public function __construct($celexId, $xmlMetasContent, $txtDocContent) {
      $this->setErrors(array());
      $this->analyses = array();
      try {
        $xmlObj = new SimpleXMLElement($xmlMetasContent);
      } catch (Exception $e) {
        $this->setError('Le XML des metas semble corrompu');
        return;
      }
      $this->setXmlObj($xmlObj);
      $this->setTxtDoc($txtDocContent);
      $this->setIdSource($celexId);
      $this->pays = self::PAYS;
      $this->juridiction = self::JURIDICTION;
      $this->fondsDocumentaire = self::FONDS_DOCUMENTAIRE;
      $this->source = str_replace('__ID__', $this->idSource, self::TEMPLATE_URL_SOURCE);
      $this->processParsing();
  }

  public function getXmlObj() {
    return $this->xmlObj;
  }

  public function setXmlObj($val) {
    $this->xmlObj = $val;
  }

  public function getTxtDoc() {
    return $this->txtDoc;
  }

  public function setTxtDoc($val) {
    $this->txtDoc = $val;
    if (!$this->txtDoc) {
      $this->setError('Le texte de l\'arrêt n\'à pas pu être récupéré');
    }
  }

  public function setIdSource($val) {
    $this->idSource = $val;
    if (!$this->idSource) {
      $this->setError('L\'identifiant CELEX est manquant');
    }
  }

  public function getDateFr() {
    return ($this->date)? date('d/m/Y', strtotime($this->date)) : null;
  }

  public function getTitre() {
    return sprintf('%s, %s, %s, %s, %s', $this->pays, $this->sousTitre, $this->parties, $this->getDateFr(), $this->identifiant);
  }

  public function hasErrors() {
    return count($this->errors) > 0;
  }

  public function getErrors() {
    return $this->errors;
  }

  private function setErrors(array $val) {
    $this->errors = $val;
  }

  private function setError($error) {
    $this->errors[] = $error;
  }

  private function processParsing() {
    $this->extractTitre();
    $this->extractDatasFromTitre();
    $this->extractDate();
    $this->extractAvocats();
    $this->extractRapporteurs();
    $this->extractEcli();
    $this->extractTypes();
    $this->extractMotsCles();
  }

  private function extractTitre() {
    $titres = $this->xmlObj->xpath('//OP-CODE[. ="FRA"]/parent::*/parent::*/EXPRESSION_TITLE/VALUE');
    $cleaned = array();
    if ($titres && count($titres) > 0) {
      foreach($titres as $titre) {
        $item = (string)$titre;
        if (!in_array($item, $cleaned)) {
          $cleaned[] = $item;
        }
      }
    }
    if (count($cleaned) > 1) {
      if ($cleanedWhitoutConclusions = $this->removeConclusionFromTitres($cleaned)) {
        $cleaned = $cleanedWhitoutConclusions;
      }
      foreach($cleaned as $titre) {
        if (strlen($titre) > strlen($this->titre)) {
          $this->titre = $titre;
        }
      }
    } else {
      $this->titre = (string)$cleaned[0];
    }
    if (!$this->titre) {
      $this->setError('Le titre n\'a pas pu être extrait du XML');
    }
  }

  private function removeConclusionFromTitres($titres) {
    $removed = array();
    foreach($titres as $titre) {
      if (preg_match('/^conclusion/i', $titre)) {
        continue;
      }
      $removed[] = $titre;
    }
    return $removed;
  }

  private function extractDatasFromTitre() {
    if (!$this->titre) return;
    if (strpos($this->titre, '#') !== false ) {
      $infos = explode("#", $this->titre);
      $firstPart = trim($infos[0]);
      $posDeb = strpos($firstPart, '(');
      $posFin = strpos($firstPart, ')');
      $this->type = trim(substr($firstPart, 0, strpos($firstPart, ' ')));
      $this->sousTitre = ($posDeb === false)? $firstPart : trim(substr($firstPart, 0, strpos($firstPart, ' (')));
      $this->formation = ($posDeb === false||$posFin === false)? null : ucfirst(trim(substr($firstPart, $posDeb+1, $posFin-$posDeb-1)));
      if (isset($infos[1])) $this->parties = trim($infos[1]);
      $this->identifiant = $this->identifyIdentifiant($infos[count($infos)-1]);
      $index = 2;
      $ending = (!$this->identifiant)? 0 : 1;
      $id = null;
      while ($index < (count($infos) - $ending)) {
        $a = trim($infos[$index]);
        $this->analyses[] = $a;
        if (!$this->identifiant && !$id) {
          foreach(explode(' ', $a) as $word) {
            if (preg_match('/[0-9]+/', $word)) {
              $id = trim($word);
            }
          }
        }
        $index++;
      }
      if (!$this->identifiant && $id) {
        $this->identifiant = $id;
      }
    } else {
      $this->setError('Le titre n\'est pas formaté pour extraire des informations');
    }
    if ($this->parties) {
      $tabParties = explode(' contre ', $this->parties);
      if (count($tabParties) > 1) {
        $this->demandeur = $tabParties[0];
        $this->defenseur = $tabParties[1];
      } else {
        $this->demandeur = $this->parties;
      }
    } else {
      $this->setError('Les parties n\'ont pas été identifiés dans le titre');
    }
    if (!$this->identifiant) {
      $this->setError('Identifiant d\'arrêt non identifié dans le titre');
    }
  }

  private function identifyIdentifiant($str) {
    $tab = explode(' ', trim($str));
    foreach($tab as $item) {
      if (preg_match('/[0-9]+/', $item)) {
        return ($item[strlen($item)-1] == '.')? substr($item, 0, -1) : $item;
      }
    }
    return null;
  }

  private function extractDate() {
    $this->date = (string)$this->xmlObj->WORK->DATE_DOCUMENT->VALUE;
    if (!$this->date) {
      $this->setError('Arret date : WORK->DATE_DOCUMENT->VALUE non défini dans le XML');
    }
  }

  private function extractAvocats() {
    $avocats = array();
    if ($avocatItems = $this->xmlObj->xpath('//CASE-LAW_DELIVERED_BY_ADVOCATE-GENERAL//TYPE[. ="agent"]/parent::*/IDENTIFIER')) {
      foreach($avocatItems as $avocatItem) {
        $avocats[] = (string)$avocatItem;
      }
    }
    $this->avocats = ($avocats)? implode(', ', $avocats) : null;
  }

  private function extractRapporteurs() {
    $rapporteurs = array();
    if ($rapporteurItems = $this->xmlObj->xpath('//CASE-LAW_DELIVERED_BY_JUDGE//TYPE[. ="agent"]/parent::*/IDENTIFIER')) {
      foreach($rapporteurItems as $rapporteurItem) {
        $rapporteurs[] = (string)$rapporteurItem;
      }
    }
    $this->rapporteurs = ($rapporteurs)? implode(', ', $rapporteurs) : null;
  }

  private function extractEcli() {
    if ($ecliItems = $this->xmlObj->xpath('//ECLI/VALUE')) {
      $this->ecli = (string)$ecliItems[0];
    }
  }

  private function extractTypes() {
    $recours = array();
    $affaires = array();
    if ($typesItems = $this->xmlObj->xpath('//CASE-LAW_HAS_TYPE_PROCEDURE_CONCEPT_TYPE_PROCEDURE/PREFLABEL')) {
      foreach($typesItems as $typesItem) {
        $type = (string)$typesItem;
        if (preg_match('/recours/i', $type)) {
          $recours[] = $type;
        } else {
          $affaires[] = $type;
        }
      }
    }
    $this->recours = ($recours)? implode(', ', array_unique($recours)) : null;
    $this->affaire = ($affaires)? implode(', ', array_unique($affaires)) : null;
  }

  private function extractMotsCles() {
    if ($items = $this->xmlObj->xpath('//RESOURCE_LEGAL_IS_ABOUT_SUBJECT-MATTER//PREFLABEL')) {
      foreach($items as $item) {
        $keyword = trim((string)$item);
        if (!in_array($keyword, $this->analyses)) {
          $this->analyses[] = $keyword;
        }
      }
    }
    if ($items = $this->xmlObj->xpath('//KEYWORDS//VALUE')) {
      foreach($items as $item) {
        $keywords = explode(PHP_EOL, strip_tags(html_entity_decode(trim((string)$item))));
        foreach($keywords as $item) {
          $keyword = trim($item);
          if (!$keyword) continue;
          if (!in_array($keyword, $this->analyses)) {
            //$this->analyses[] = $keyword; //Desactivé car trop lourd
          }
        }
      }
    }
  }
}
