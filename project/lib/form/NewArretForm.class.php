<?php

class NewArretForm extends BaseForm
{
    private $xmlData = null;
    private $path = null;
    private ?string $fileName = null;
    private bool $isNew = true;

    private string $xmlTemplate = 'template.xml';

    public function __construct($fileName = null, $defaults = array(), $options = array(), $CSRFSecret = null)
    {
        if ($fileName != null) {
            $path = sfConfig::get('app_juricaf_xmldatadir') . '/' . $fileName;
            if (!is_file($path) || !is_readable($path)) {
                return;
            }
            $this->xmlData = simplexml_load_file($path);
            if ($this->xmlData === false) {
                return;
            }
            $this->path = $path;
            $this->fileName = $fileName;
            $this->isNew = false;
        } else {
            $this->xmlData = simplexml_load_file(sfConfig::get('sf_data_dir') . '/dataXmlTemplate/' . $this->xmlTemplate);
        }
        parent::__construct($defaults, $options, $CSRFSecret);
    }

    public function configure(): void
    {
        $this->setWidgets(array(
            'PAYS' => new sfWidgetFormInputText(),
            'JURIDICTION' => new sfWidgetFormInputText(),
            'DATE_ARRET' => new sfWidgetFormInputText(),
            'NUM_ARRET' => new sfWidgetFormInputText(),
            'FONDS_DOCUMENTAIRE' => new sfWidgetFormInputText(),
            'TYPE_AFFAIRE' => new sfWidgetFormInputText(),
            'FORMATION' => new sfWidgetFormInputText(),
            'IMPORTANCE' => new sfWidgetFormInputText(),
            'TYPE_RECOURS' => new sfWidgetFormInputText(),
            'CITATION_ARTICLE' => new sfWidgetFormInputText(),
            'SOURCE' => new sfWidgetFormInputText(),
            'DEMANDEUR' => new sfWidgetFormInputText(),
            'DEFENDEUR' => new sfWidgetFormInputText(),
            'TITRE_PRINCIPAL' => new sfWidgetFormInputText(),
            'SOMMAIRE' => new sfWidgetFormInputText(),
            'TYPE' => new sfWidgetFormInputText(),
            'TITRE' => new sfWidgetFormInputText(),
            'URL' => new sfWidgetFormInputText(),
            'PUBLICATION' => new sfWidgetFormInputText(),
            'TEXTE_ARRET' => new sfWidgetFormTextarea(),
        ));
        $this->widgetSchema->setNameFormat('upload[%s]');

        $this->setValidators(array(
            'PAYS'    => new sfValidatorRegex(array('pattern' => '/\//', 'must_match' => false)),
            'JURIDICTION'    => new sfValidatorRegex(array('pattern' => '/\//', 'must_match' => false)),
            'DATE_ARRET'    => new sfValidatorDate(array('date_format_error' => 'Format de date invalide', 'min' => '1901-01-01', 'max' => date('Y-m-d'))),
            'NUM_ARRET'    => new sfValidatorString(),
            'FONDS_DOCUMENTAIRE'    => new sfValidatorString(array('required' => false)),
            'TYPE_AFFAIRE'    => new sfValidatorString(array('required' => false)),
            'FORMATION'    => new sfValidatorString(array('required' => false)),
            'IMPORTANCE'    => new sfValidatorNumber(array('min' => 1, 'max' => 100, 'required' => false), array('invalid' => 'Ce champ n\'accepte que des valeurs numeriques de 1 a 9.')),
            'TYPE_RECOURS'    => new sfValidatorString(array('required' => false)),
            'CITATION_ARTICLE'    => new sfValidatorString(array('required' => false)),
            'SOURCE'    => new sfValidatorString(array('required' => false)),
            'DEMANDEUR'    => new sfValidatorString(array('required' => false)),
            'DEFENDEUR'    => new sfValidatorString(array('required' => false)),
            'TITRE_PRINCIPAL'    => new sfValidatorString(array('required' => false)),
            'SOMMAIRE'    => new sfValidatorString(array('required' => false)),
            'TYPE'    => new sfValidatorString(array('required' => false)),
            'TITRE'    => new sfValidatorString(array('required' => false)),
            'URL'    => new sfValidatorString(array('required' => false)),
            'PUBLICATION'    => new sfValidatorString(array('required' => false)),
            'TEXTE_ARRET'    => new sfValidatorString(),

        ));

        if ($this->xmlData !== false) {
            $defaults = array(
                'PAYS' => $this->xmlData->PAYS,
                'JURIDICTION' => $this->xmlData->JURIDICTION,
                'DATE_ARRET' => $this->xmlData->DATE_ARRET,
                'NUM_ARRET' => $this->xmlData->NUM_ARRET,
                'FONDS_DOCUMENTAIRE' => $this->xmlData->FONDS_DOCUMENTAIRE,
                'TYPE_AFFAIRE' => $this->xmlData->TYPE_AFFAIRE,
                'FORMATION' => $this->xmlData->FORMATION,
                'IMPORTANCE' => $this->xmlData->IMPORTANCE,
                'TYPE_RECOURS' => $this->xmlData->TYPE_RECOURS,
                'CITATION_ARTICLE' => $this->xmlData->CITATION_ARTICLE,
                'SOURCE' => $this->xmlData->SOURCE,
                'DEMANDEUR' => $this->xmlData->PARTIES[0]->DEMANDEURS[0]->DEMANDEUR,
                'DEFENDEUR' => $this->xmlData->PARTIES[0]->DEFENDEURS[0]->DEFENDEUR,
                'TITRE_PRINCIPAL' => $this->xmlData->ANALYSES[0]->ANALYSE[0]->TITRE_PRINCIPAL,
                'SOMMAIRE' => $this->xmlData->ANALYSES[0]->ANALYSE[0]->SOMMAIRE,
                'TYPE' => $this->xmlData->REFERENCES[0]->REFERENCE[0]->TYPE,
                'TITRE' => $this->xmlData->REFERENCES[0]->REFERENCE[0]->TITRE,
                'URL' => $this->xmlData->REFERENCES[0]->REFERENCE[0]->URL,
                'PUBLICATION' => $this->xmlData->PUBLICATION,
                'TEXTE_ARRET' => $this->xmlData->TEXTE_ARRET,
            );
            $this->setDefaults($defaults);
        }

    }

    /**
     * @throws Exception
     */
    public function replaceFile($pays, $juri): void
    {
        $directory = sfConfig::get('app_juricaf_xmldatadir');
        $iterator = new DirectoryIterator($directory);

        $pattern = '/^(.*?)_(.*?)_(.*?)_(.*?)\.xml$/';
        preg_match($pattern, $this->fileName, $matches);
        $today = $matches[1];
        $random = $matches[4];

        foreach ($iterator as $fileInfo) {
            if (!$fileInfo->isDot() && $fileInfo->isFile()) {
                $fileName = $fileInfo->getFilename();

                if (str_contains($fileName, $today) && str_contains($fileName, $random)) {
                    $filePath = $fileInfo->getPathname();
                    if (!unlink($filePath)) {
                        throw new Exception("Impossible de supprimer le fichier $fileName.");
                    }
                }
            }
        }
        $this->fileName = $today . '_' . $pays . '_' . $juri . '_' . $random . '.xml';
    }

    /**
     * @throws Exception
     */
    public function write(): void
    {
        foreach ($this->getValues() as $key => $value) {
            if ($key === 'DEMANDEUR')
                $this->xmlData->PARTIES->DEMANDEURS->$key = $value;
            else if ($key === 'DEFENDEUR')
                $this->xmlData->PARTIES->DEFENDEURS->$key = $value;
            else if ($key === 'TITRE_PRINCIPAL')
                $this->xmlData->ANALYSES->ANALYSE->$key = $value;
            else if ($key === 'SOMMAIRE')
                $this->xmlData->ANALYSES->ANALYSE->$key = $value;
            else if ($key === 'TYPE')
                $this->xmlData->REFERENCES->REFERENCE->$key = $value;
            else if ($key === 'TITRE')
                $this->xmlData->REFERENCES->REFERENCE->$key = $value;
            else if ($key === 'URL')
                $this->xmlData->REFERENCES->REFERENCE->$key = $value;
            else
                $this->xmlData->$key = $value;
        }

        $this->loadAttributes();

        if (!is_writable(dirname($this->path))) {
            throw new Exception("Le dossier de destination n'est pas accessible en écriture.");
        }

        $formattedXml = $this->xmlData->asXML();

        $fileHandle = fopen($this->path, 'w');
        if ($fileHandle === false) {
            throw new Exception("Impossible d'ouvrir le fichier $this->path");
        }

        $writeResult = fwrite($fileHandle, $formattedXml);

        fclose($fileHandle);

        if ($writeResult === false) {
            throw new Exception("Impossible d'ecrire dans le fichier $this->path");
        }
    }

    /**
     * @throws Exception
     */
    private function loadAttributes()
    {
        $juri_tmp = str_replace(' ', '-', $this->xmlData->JURIDICTION);
        $pays_tmp = str_replace(' ', '-', $this->xmlData->PAYS);
        $pays_tmp = str_replace(['&', '%', '*', '/', '.', ';'], '', $pays_tmp);
        $juri_tmp = str_replace(['&', '%', '*', '/', '.', ';'], '', $juri_tmp);
        if ($this->getNewValue() === true) {
            $random = uniqid(rand(), true);
            $today = date('Y-m-d-His');
            $this->fileName = $today . '_' . $pays_tmp . '_' . $juri_tmp . '_' . $random . '.xml';
        } else {
            $this->replaceFile($pays_tmp, $juri_tmp);
        }
        $this->path = sfConfig::get('app_juricaf_xmldatadir') . '/' . $this->fileName;
    }

    private function formatXml($xml)
    {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml);
        return $dom->saveXML();
    }

    public function getFlatArray() {
        $xmlArray = json_decode(json_encode((array) $this->xmlData), true);

        $flatArray = array();
        array_walk_recursive($xmlArray, function($value, $key) use (&$flatArray) {
            $flatArray[$key] = $value;
        });

        return $flatArray;
    }

    public function getXmlData() {
        return $this->xmlData;
    }

    public function getFileName() {
        return $this->fileName;
    }

    public function getPaysValue() {
        return $this->xmlData->PAYS;
    }

    public function getJuriValue() {
        return $this->xmlData->JURIDICTION;
    }

    public function getDateArretValue() {
        return $this->xmlData->DATE_ARRET;
    }

    public function getNumArretValue() {
        return $this->xmlData->NUM_ARRET;
    }

    public function getPathValue() {
        return $this->path;
    }

    public function getNewValue() {
        return $this->isNew;
    }

    public function getFormatXmlData() {
        $tmpContent = file_get_contents($this->path);
        $lines = explode("\n", $tmpContent);
        array_shift($lines);
        return implode("\n", $lines);
    }
}
