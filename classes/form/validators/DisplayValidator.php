<?php

require_once _PS_MODULE_DIR_.'custompopup/core/PrestaCraftValidatorCore.php';
require_once _PS_MODULE_DIR_.'custompopup/classes/utils/Validation.php';
require_once _PS_MODULE_DIR_.'custompopup/classes/db/ResponsivePopupPages.php';

class DisplayValidator extends PrestacraftValidatorCore
{
    public function __construct($moduleObject, $formName)
    {
        parent::__construct($moduleObject, $formName);
    }

    protected function processValidation()
    {
        return true;
    }

    protected function save()
    {
        ResponsivePopupPages::disableAll();

        foreach ($this->getData() as $hook => $value) {
            ResponsivePopupPages::setHookValue($hook, $value);
        }
    }
}
