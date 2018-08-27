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

require_once _PS_MODULE_DIR_.'custompopup/core/PrestaCraftFormCore.php';

class CloseAndFooterForm extends PrestaCraftFormCore
{
    public function __construct($moduleObject)
    {
        parent::__construct($moduleObject, __CLASS__);
    }

    public function render()
    {
        $footerType = array(
            array(
                'id' => 'text',
                'name' => $this->module->l('Your text')
            ),
            array(
                'id' => 'button',
                'name' => $this->module->l('Button(s)')
            ),
            array(
                'id' => 'text_buttons',
                'name' => $this->module->l('Your text and button(s)')
            ),
        );

        $types = array(
            array(
                'id' => 'overlay',
                'name' => $this->module->l('Overlay (clicking outside popup)')
            ),
            array(
                'id' => 'button',
                'name' => $this->module->l('Button (click [X] button or footer button)')
            ),
            array(
                'id' => 'escape',
                'name' => $this->module->l('Clicking ESC key')
            ),
        );

        $align = array(
            array(
                'id' => 'left',
                'name' => $this->module->l('Left')
            ),
            array(
                'id' => 'center',
                'name' => $this->module->l('Center')
            ),
            array(
                'id' => 'right',
                'name' => $this->module->l('Right')
            ),
        );

        $this->fields = array(
            'form' => array(
                'legend' => array('title' => $this->module->l('Close & Footer')),
                'submit' => array(
                    'title' => $this->module->l('Save')
                ),
                'input' => array(
                    array(
                        'type'    => 'checkbox',
                        'name'    => 'closetype',
                        'label'   => $this->module->l('How can user close the popup?'),
                        'values'  => array(
                            'query' => $types,
                            'id'    => 'id',
                            'name'  => 'name'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->module->l('Display popup footer?'),
                        'name' => 'CUSTOMPOPUP_FOOTER',
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
                        'type'    => 'select',
                        'name'    => 'CUSTOMPOPUP_FOOTER_TYPE',
                        'label'   => $this->module->l('Footer type'),
                        'options'  => array(
                            'query' => $footerType,
                            'id'    => 'id',
                            'name'  => 'name'
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->module->l('Your text'),
                        'name' => 'CUSTOMPOPUP_FOOTER_TEXT',
                        'lang' => true,
                        'desc' => $this->module->l("If you want to display a text instead buttons, type it here."),
                        'class' => 'rte',
                        'autoload_rte' => true,
                        'type' => 'textarea',
                    ),
                    array(
                        'type'    => 'select',
                        'name'    => 'CUSTOMPOPUP_BUTTON_ALIGN',
                        'label'   => $this->module->l('Button alignment'),
                        'options'  => array(
                            'query' => $align,
                            'id'    => 'id',
                            'name'  => 'name'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->module->l('Enable [Button 1]'),
                        'name' => 'CUSTOMPOPUP_BUTTON1_ENABLED',
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
                        'label' => $this->module->l('[Button 1] Text'),
                        'name' => 'CUSTOMPOPUP_BUTTON1_TEXT',
                        'lang' => true,
                    ),

                    array(
                        'type' => 'color',
                        'label' => $this->module->l('[Button 1] Background color'),
                        'name' => 'CUSTOMPOPUP_BUTTON1_BACKGROUND',
                        'class' => 'leftfix',
                    ),

                    array(
                        'type' => 'text',
                        'label' => $this->module->l('[Button 1] URL'),
                        'name' => 'CUSTOMPOPUP_BUTTON1_URL',
                        'hint' => $this->module->l("Must begin with http:// or https://"),
                        'lang' => true,
                        'desc' => $this->module->l(
                            "If you enter here a link, it will redirect to this page on click. 
                            If not, this button will close popup on click."
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->module->l('Enable [Button 2]'),
                        'name' => 'CUSTOMPOPUP_BUTTON2_ENABLED',
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
                        'label' => $this->module->l('[Button 2] Text'),
                        'name' => 'CUSTOMPOPUP_BUTTON2_TEXT',
                        'lang' => true,
                    ),

                    array(
                        'type' => 'color',
                        'label' => $this->module->l('[Button 2] Background color'),
                        'name' => 'CUSTOMPOPUP_BUTTON2_BACKGROUND',
                        'class' => 'leftfix',
                    ),

                    array(
                        'type' => 'text',
                        'label' => $this->module->l('[Button 2] URL'),
                        'name' => 'CUSTOMPOPUP_BUTTON2_URL',
                        'hint' => $this->module->l("Must begin with http:// or https://"),
                        'lang' => true,
                        'desc' => $this->module->l(
                            "If you enter here a link, it will redirect to this page on click. 
                            If not, this button will close popup on click."
                        ),
                    ),
                ),
            ),
        );

        return $this;
    }

    public function getFieldsValues()
    {
        $fields = array();

        $fields['closetype_overlay'] = Configuration::get('CUSTOMPOPUP_OVERLAY');
        $fields['closetype_button'] = Configuration::get('CUSTOMPOPUP_BUTTON');
        $fields['closetype_escape'] = Configuration::get('CUSTOMPOPUP_ESCAPE');
        $fields['CUSTOMPOPUP_FOOTER'] = Configuration::get('CUSTOMPOPUP_FOOTER');
        $fields['CUSTOMPOPUP_BUTTON1_TEXT'] = Configuration::get('CUSTOMPOPUP_BUTTON1_TEXT');
        $fields['CUSTOMPOPUP_BUTTON2_TEXT'] = Configuration::get('CUSTOMPOPUP_BUTTON2_TEXT');
        $fields['CUSTOMPOPUP_BUTTON1_URL'] = Configuration::get('CUSTOMPOPUP_BUTTON1_URL');
        $fields['CUSTOMPOPUP_BUTTON2_URL'] = Configuration::get('CUSTOMPOPUP_BUTTON2_URL');
        $fields['CUSTOMPOPUP_BUTTON1_BACKGROUND'] = Configuration::get('CUSTOMPOPUP_BUTTON1_BACKGROUND');
        $fields['CUSTOMPOPUP_BUTTON2_BACKGROUND'] = Configuration::get('CUSTOMPOPUP_BUTTON2_BACKGROUND');
        $fields['CUSTOMPOPUP_FOOTER_TEXT'] = Configuration::get('CUSTOMPOPUP_FOOTER_TEXT');
        $fields['CUSTOMPOPUP_BUTTON_ALIGN'] = Configuration::get('CUSTOMPOPUP_BUTTON_ALIGN');
        $fields['CUSTOMPOPUP_FOOTER_TYPE'] = Configuration::get('CUSTOMPOPUP_FOOTER_TYPE');
        $fields['CUSTOMPOPUP_BUTTON1_ENABLED'] = Configuration::get('CUSTOMPOPUP_BUTTON1_ENABLED');
        $fields['CUSTOMPOPUP_BUTTON2_ENABLED'] = Configuration::get('CUSTOMPOPUP_BUTTON2_ENABLED');

        return $fields;
    }
}
