<?php

require_once _PS_MODULE_DIR_.'custompopup/core/PrestaCraftValidatorCore.php';
require_once _PS_MODULE_DIR_.'custompopup/classes/utils/Validation.php';

class CloseAndFooterValidator extends PrestacraftValidatorCore
{
    public function __construct($moduleObject, $formName)
    {
        parent::__construct($moduleObject, $formName);
    }

    protected function processValidation()
    {
        $this->validation = new Validation($this->module);

        foreach (Language::getLanguages(true) as $lang) {
            $this->validation->validate(
                $this->module->l("[Button 1] URL ({$lang["iso_code"]})"),
                $this->getField('CUSTOMPOPUP_BUTTON1_URL_'.$lang["id_lang"]),
                array('is_url_if_not_empty' => 1)
            );

            $this->validation->validate(
                $this->module->l("[Button 2] URL ({$lang["iso_code"]})"),
                $this->getField('CUSTOMPOPUP_BUTTON2_URL_'.$lang["id_lang"]),
                array('is_url_if_not_empty' => 1)
            );
        }

        $this->validation->validate(
            $this->module->l('[Button 1] Background color'),
            $this->getField('CUSTOMPOPUP_BUTTON1_BACKGROUND'),
            array('ishex' => 1)
        );

        $this->validation->validate(
            $this->module->l('[Button 2] Background color'),
            $this->getField('CUSTOMPOPUP_BUTTON2_BACKGROUND'),
            array('ishex' => 1)
        );

        $this->validation->validate(
            $this->module->l('Footer background color'),
            $this->getField('CUSTOMPOPUP_FOOTER_BACKGROUND'),
            array('ishex' => 1)
        );
    }

    protected function save()
    {
        if (!$this->validation->getError($this->module->l('[Button 1] Background color'))) {
            Configuration::updateValue('CUSTOMPOPUP_BUTTON1_BACKGROUND', $this->getField('CUSTOMPOPUP_BUTTON1_BACKGROUND'));
        }

        if (!$this->validation->getError($this->module->l('[Button 2] Background color'))) {
            Configuration::updateValue('CUSTOMPOPUP_BUTTON2_BACKGROUND', $this->getField('CUSTOMPOPUP_BUTTON2_BACKGROUND'));
        }

        if (!$this->validation->getError($this->module->l('Footer background color'))) {
            Configuration::updateValue('CUSTOMPOPUP_FOOTER_BACKGROUND', $this->getField('CUSTOMPOPUP_FOOTER_BACKGROUND'));
        }

        Configuration::updateValue('CUSTOMPOPUP_FOOTER', $this->getField('CUSTOMPOPUP_FOOTER'));
        Configuration::updateValue('CUSTOMPOPUP_BUTTON_ALIGN', $this->getField('CUSTOMPOPUP_BUTTON_ALIGN'));
        Configuration::updateValue('CUSTOMPOPUP_FOOTER_TYPE', $this->getField('CUSTOMPOPUP_FOOTER_TYPE'));
        Configuration::updateValue('CUSTOMPOPUP_BUTTON1_ENABLED', $this->getField('CUSTOMPOPUP_BUTTON1_ENABLED'));
        Configuration::updateValue('CUSTOMPOPUP_BUTTON2_ENABLED', $this->getField('CUSTOMPOPUP_BUTTON2_ENABLED'));

        Configuration::updateValue('CUSTOMPOPUP_OVERLAY', 0);
        Configuration::updateValue('CUSTOMPOPUP_BUTTON', 0);
        Configuration::updateValue('CUSTOMPOPUP_ESCAPE', 0);

        foreach ($this->getData() as $key =>$value) {
            if (strpos($key, 'closetype_') === 0) {
                $name = 'CUSTOMPOPUP_';
                $nameFull = $name.str_replace("closetype_", "", $key);

                if ($value) {
                    Configuration::updateValue($nameFull, '1');
                }
            }
        }

        foreach (Language::getLanguages(true) as $lang) {
            if (!$this->validation->getError($this->module->l('[Button 1] URL ('.$lang["iso_code"].')'))) {
                Configuration::updateValue(
                    'CUSTOMPOPUP_BUTTON1_URL_'.$lang["id_lang"],
                    $this->getField('CUSTOMPOPUP_BUTTON1_URL_'.$lang["id_lang"])
                );
            }

            if (!$this->validation->getError($this->module->l('[Button 2] URL ('.$lang["iso_code"].')'))) {
                Configuration::updateValue(
                    'CUSTOMPOPUP_BUTTON2_URL_'.$lang["id_lang"],
                    $this->getField('CUSTOMPOPUP_BUTTON2_URL_'.$lang["id_lang"])
                );
            }

            Configuration::updateValue('CUSTOMPOPUP_BUTTON1_TEXT_'.$lang["id_lang"],
                $this->getField('CUSTOMPOPUP_BUTTON1_TEXT_'.$lang["id_lang"]));

            Configuration::updateValue('CUSTOMPOPUP_BUTTON2_TEXT_'.$lang["id_lang"],
                $this->getField('CUSTOMPOPUP_BUTTON2_TEXT_'.$lang["id_lang"]));

            Configuration::updateValue('CUSTOMPOPUP_FOOTER_TEXT_'.$lang["id_lang"],
                $this->getField('CUSTOMPOPUP_FOOTER_TEXT_'.$lang["id_lang"]));
        }

    }
}
