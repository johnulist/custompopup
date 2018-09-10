<?php

require_once _PS_MODULE_DIR_.'custompopup/core/CP_PrestaCraftValidatorCore.php';
require_once _PS_MODULE_DIR_.'custompopup/classes/utils/CP_Validation.php';
require_once _PS_MODULE_DIR_.'custompopup/classes/db/CP_ResponsivePopupPages.php';

class CP_DisplayValidator extends CP_PrestaCraftValidatorCore
{
    public function __construct($moduleObject, $formName)
    {
        parent::__construct($moduleObject, $formName);
    }

    protected function processCP_Validation()
    {
        return true;
    }

    protected function save()
    {
        CP_ResponsivePopupPages::disableAll();

        foreach ($this->getData() as $hook => $value) {
            CP_ResponsivePopupPages::setHookValue($hook, $value);
        }
    }
}
