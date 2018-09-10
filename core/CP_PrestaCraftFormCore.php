<?php
/**
 * This software is provided "as is" without warranty of any kind.
 *
 * Made by PrestaCraft
 *
 * Visit my website (http://prestacraft.com) for future updates, new articles and other awesome modules.
 *
 * @author     PrestaCraft
 * @copyright  PrestaCraft
 * @license    http://prestacraft.com/license
 */

require_once _PS_MODULE_DIR_.'custompopup/core/CP_PrestaCraftFormInterface.php';

abstract class CP_PrestaCraftFormCore implements CP_PrestaCraftFormInterface
{
    protected $module;
    protected $fields;
    protected $className;

    public function __construct($module, $className)
    {
        if (!$module) {
            throw new \Exception("[PrestaCraft Exception] Missing module object");
        }
        
        $this->module = $module;
        $this->className = $className;
    }

    abstract public function render();

    abstract public function getFieldsValues();

    public function buildForm()
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = 'module';
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->module = $this->module;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = 'id_module';
        $helper->submit_action = $this->className;
        $helper->currentIndex = Context::getContext()->link->getAdminLink(
            'AdminModules', false).'&configure='.$this->module->name.'&tab_module='.
            $this->module->tab.'&module_name='.$this->module->name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'uri' => $this->module->getPathUri(),
            'fields_value' => $this->getFieldsValues(),
            'languages' => Context::getContext()->controller->getLanguages(),
            'id_language' => Context::getContext()->language->id
        );

        return $helper->generateForm(array($this->fields));
    }
}
