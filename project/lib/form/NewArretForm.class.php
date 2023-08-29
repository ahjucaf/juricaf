<?php

class NewArretForm extends BaseForm
{
    private $xmlData = null;
    private $path = null;
    private ?string $fileName = null;
    private ?string $today = null;
    private ?string $random = null;
    private bool $isNew = true;

    public function __construct($fileName = null, $defaults = array(), $options = array(), $CSRFSecret = null)
    {
        parent::__construct($defaults, $options, $CSRFSecret);

        if ($fileName !== null) {
            $path = sfConfig::get('sf_data_dir') . '/dataXml/' . $fileName;
            if (!is_file($path) || !is_readable($path)) {
                return ;
            }
            $this->xmlData = simplexml_load_file($path);
            if ($this->xmlData === false) {
                return;
            }
            $this->path = $path;
            $parts = explode('_', $fileName);
            $this->today = $parts[0];
            $this->random = end($parts);
            $this->fileName = $fileName;
            $this->isNew = false;
        }
    }

    public function configure(): void
    {
        $this->setWidgets(array(
            'PAYS'    => new sfWidgetFormInputText(),
            'JURIDICTION'    => new sfWidgetFormInputText(),

        ));
        $this->widgetSchema->setNameFormat('upload[%s]');

        $this->setValidators(array(
            'PAYS'    => new sfValidatorRegex(array('pattern' => '/\//', 'must_match' => false)),
            'JURIDICTION'    => new sfValidatorRegex(array('pattern' => '/\//', 'must_match' => false)),
        ));

        if ($this->xmlData === null) {
            $this->xmlData = new SimpleXMLElement('<data></data>');
            $this->xmlData->addChild('PAYS', '');
            $this->xmlData->addChild('JURIDICTION', '');
        }

        if ($this->xmlData !== null) {
            $this->setDefaults(array(
                'PAYS' => $this->xmlData->PAYS,
                'JURIDICTION' => $this->xmlData->JURIDICTION,
            ));
        }
    }

    public function deleteFile(): void
    {
        $directory = sfConfig::get('sf_data_dir') . '/dataXml/';
        $iterator = new DirectoryIterator($directory);

        foreach ($iterator as $fileInfo) {
            if (!$fileInfo->isDot() && $fileInfo->isFile()) {
                $fileName = $fileInfo->getFilename();

                if (str_contains($fileName, $this->today) && str_contains($fileName, $this->random)) {
                    $filePath = $fileInfo->getPathname();
                    if (unlink($filePath)) {
                        echo "Le fichier $fileName a été supprimé avec succès.";
                    } else {
                        echo "Impossible de supprimer le fichier $fileName.";
                    }
                }
            }
        }

    }

    /**
     * @throws Exception
     */
    public function write($fileNameRequest = null): void
    {
        foreach ($this->getValues() as $key => $value) {
            $this->xmlData->$key = $value;
        }

        $juri_tmp = str_replace(' ', '-', $this->xmlData->JURIDICTION);
        $pays_tmp = str_replace(' ', '-', $this->xmlData->PAYS);
        if ($this->isNew === true) {
            $this->random = uniqid(rand(), true);
            $this->today = date('Y-m-d-His');
        } else {
            $this->deleteFile();
        }
        $this->fileName = $this->today . '_' . $pays_tmp . '_' . $juri_tmp . '_' . $this->random;
        $this->path = sfConfig::get('sf_data_dir') . '/dataXml/' . $this->fileName;

        if (!is_writable(dirname($this->path))) {
            throw new Exception("Le dossier de destination n'est pas accessible en écriture.");
        }

        $formattedXml = $this->formatXml($this->xmlData->asXML());

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

    private function formatXml($xml)
    {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml);
        return $dom->saveXML();
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

    public function getPathValue() {
        return $this->path;
    }
}
