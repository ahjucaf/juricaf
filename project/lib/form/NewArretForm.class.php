<?php

class NewArretForm extends BaseForm
{
    public function configure()
    {
        $this->setWidgets(array(
            'pays'    => new sfWidgetFormInputText(),
            'juridiction'    => new sfWidgetFormInputText(),
        ));
        $this->widgetSchema->setNameFormat('upload[%s]');

        $this->setValidators(array(
            'pays'    => new sfValidatorRegex(array('pattern' => '/\//', 'must_match' => false)),
            'juridiction'    => new sfValidatorRegex(array('pattern' => '/\//', 'must_match' => false)),
        ));
    }
}
