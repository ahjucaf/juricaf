<?php

class UploadForm extends BaseForm 
{
  public function configure() 
  {
    $this->setWidgets(array(
      'pays'    => new sfWidgetFormInputText(),
      'juridiction'    => new sfWidgetFormInputText(),
      'file'    => new sfWidgetFormInputFile(array('label' => 'Fichier')),
    ));
    $this->widgetSchema->setNameFormat('upload[%s]');
 
    $this->setValidators(array(
			       'pays'    => new sfValidatorRegex(array('pattern' => '/\//', 'must_match' => false)),
			       'juridiction'    => new sfValidatorRegex(array('pattern' => '/\//', 'must_match' => false)),
			       'file'    => new sfValidatorFile(),
			       ));
  }
}
