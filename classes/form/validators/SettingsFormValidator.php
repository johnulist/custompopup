<?php

require_once _PS_MODULE_DIR_.'custompopup/core/PrestaCraftValidatorCore.php';
require_once _PS_MODULE_DIR_.'custompopup/classes/utils/Validation.php';

class SettingsFormValidator extends PrestacraftValidatorCore
{
    public function __construct($moduleObject, $formName)
    {
        parent::__construct($moduleObject, $formName);
    }

    protected function processValidation()
    {
        $this->validation = new Validation($this->module);

        $this->validation->validate(
            $this->module->l('Cookie length'),
            $this->getField('CUSTOMPOPUP_COOKIE'),
            array('isnumber' => 1)
        );

        $this->validation->validate(
            $this->module->l('Popup delay'),
            $this->getField('CUSTOMPOPUP_DELAY'),
            array('isnumber' => 1)
        );

        foreach (Language::getLanguages(true) as $lang) {
            $this->validation->validate(
                $this->module->l('Popup content'),
                $this->getField('CUSTOMPOPUP_CONTENT_'.$lang['id_lang']),
                array('notempty' => 1)
            );
        }
    }

    protected function save()
    {
        Configuration::updateValue('CUSTOMPOPUP_ENABLED', $this->getField('CUSTOMPOPUP_COOKIE'));

        // if no errors occured
        if (!$this->validation->getError($this->module->l('Popup content'))) {
            Db::getInstance()->execute('TRUNCATE TABLE `' . _DB_PREFIX_ . 'responsive_popup`');

            foreach (Language::getLanguages(true) as $lang) {
                $popup = new ResponsivePopup();
                $popup->id_shop = Context::getContext()->shop->id;
                $popup->id_lang = $lang['id_lang'];
                $popup->content = Tools::getValue('CUSTOMPOPUP_CONTENT_'.$lang['id_lang']);
                $popup->save();
            }
        }

        Configuration::updateValue('CUSTOMPOPUP_COOKIE', Tools::getValue('CUSTOMPOPUP_COOKIE'));
        Configuration::updateValue('CUSTOMPOPUP_DELAY', Tools::getValue('CUSTOMPOPUP_DELAY'));

        $this->setSuccess(true);
    }
}
