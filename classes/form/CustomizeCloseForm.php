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

class CustomizeCloseForm extends PrestaCraftFormCore
{
    public function __construct($name)
    {
        parent::__construct($name, __CLASS__);
    }

    public function render()
    {
        $this->fields = array(
            'form' => array(
                'legend' => array('title' => $this->module->l('[X] Close button style')),
                'submit' => array(
                    'title' => $this->module->l('Save')
                ),
                'input' => array(
                    array(
                        'type' => 'color',
                        'label' => $this->module->l('Color'),
                        'name' => 'CUSTOMPOPUP_BUTTON_COLOR',
                        'class' => 'leftfix'
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->module->l('Hover color'),
                        'hint' => $this->module->l('Color shown after moving your cursor over the button'),
                        'name' => 'CUSTOMPOPUP_BUTTON_HOVER_COLOR',
                        'class' => 'leftfix'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->module->l('Size'),
                        'name' => 'CUSTOMPOPUP_BUTTON_SIZE',
                        'class' => 'fixed-width-sm',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->module->l('Top padding'),
                        'name' => 'CUSTOMPOPUP_BUTTON_TOP_PADDING',
                        'class' => 'fixed-width-sm',
                        'suffix' => $this->module->l('pixels'),
                    ),
                    array(
                        'type'      => 'radio',
                        'label'     => $this->module->l('Position'),
                        'name'      => 'CUSTOMPOPUP_BUTTON_POSITION',
                        'required'  => true,
                        'class'     => 't',
                        'values'    => array(
                            array(
                                'id'    => 'left',
                                'value' => 'left',
                                'label' => $this->module->l('Left')
                            ),
                            array(
                                'id'    => 'right',
                                'value' => 'right',
                                'label' => $this->module->l('Right')
                            )
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

        $fields['CUSTOMPOPUP_BUTTON_COLOR'] = Configuration::get('CUSTOMPOPUP_BUTTON_COLOR');
        $fields['CUSTOMPOPUP_BUTTON_HOVER_COLOR'] = Configuration::get('CUSTOMPOPUP_BUTTON_HOVER_COLOR');
        $fields['CUSTOMPOPUP_BUTTON_SIZE'] = Configuration::get('CUSTOMPOPUP_BUTTON_SIZE');
        $fields['CUSTOMPOPUP_BUTTON_TOP_PADDING'] = Configuration::get('CUSTOMPOPUP_BUTTON_TOP_PADDING');
        $fields['CUSTOMPOPUP_BUTTON_POSITION'] = Configuration::get('CUSTOMPOPUP_BUTTON_POSITION');

        return $fields;
    }
}
