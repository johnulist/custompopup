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

require_once _PS_MODULE_DIR_.'custompopup/core/CP_PrestaCraftFormCore.php';

class CP_SettingsForm extends CP_PrestaCraftFormCore
{
    public function __construct($moduleObject)
    {
        parent::__construct($moduleObject, __CLASS__);
    }

    public function render()
    {
        $this->fields = array(
            'form' => array(
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'TAB_1',
                        'value' => '1',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->module->l('Enable popup?'),
                        'name' => 'CUSTOMPOPUP_ENABLED',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->module->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->module->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->module->l('Cookie length'),
                        'suffix' => $this->module->l('minutes'),
                        'name' => 'CUSTOMPOPUP_COOKIE',
                        'required' => true,
                        'desc' => $this->module->l('Type 0 to make popup always appearing'),
                        'class' => 'fixed-width-sm',
                        'hint' => $this->module->l('If user closes popup, it will appear again after this time')
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->module->l('Popup delay'),
                        'suffix' => $this->module->l('seconds'),
                        'name' => 'CUSTOMPOPUP_DELAY',
                        'required' => true,
                        'desc' => $this->module->l('Type 0 to show immediately'),
                        'class' => 'fixed-width-sm',
                    ),
                    array(
                        'class' => 'rte',
                        'autoload_rte' => true,
                        'type' => 'textarea',
                        'lang' => true,
                        'label' => $this->module->l('Popup content'),
                        'name' => 'CUSTOMPOPUP_CONTENT',
                        'required' => true,
                        'cols' => 40,
                        'rows' => 10,
                        'desc' => '<strong>'.$this->module->l(
                            'REMEMBER TO FILL CONTENT FOR ALL LANGUAGES BEFORE SAVING'
                        ).'</strong>',
                    )
                ),
                'submit' => array(
                    'title' => $this->module->l('Save')
                )
            ),
        );

        return $this;
    }

    public function getFieldsValues()
    {
        $languages = Language::getLanguages(true);
        $fields = array();

        $fields['TAB_1'] = Configuration::get('TAB_1');

        foreach ($languages as $lang) {
            @$fields['CUSTOMPOPUP_CONTENT'][$lang['id_lang']] = Configuration::get("CUSTOMPOPUP_CONTENT", $lang['id_lang']);
        }

        $fields['CUSTOMPOPUP_ENABLED'] = Configuration::get('CUSTOMPOPUP_ENABLED');
        $fields['CUSTOMPOPUP_COOKIE'] = Configuration::get('CUSTOMPOPUP_COOKIE');
        $fields['CUSTOMPOPUP_DELAY'] = Configuration::get('CUSTOMPOPUP_DELAY');

        return $fields;
    }
}
