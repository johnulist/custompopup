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
require_once _PS_MODULE_DIR_.'custompopup/classes/db/CP_ResponsivePopupPages.php';

class CP_DisplayForm extends CP_PrestaCraftFormCore
{
    public function __construct($moduleObject)
    {
        parent::__construct($moduleObject, __CLASS__);
    }

    public function render()
    {
        $rpp = new CP_ResponsivePopupPages();
        $pages = array();

        foreach ($rpp->getAll(true) as $item) {
            $pages[$item['id_page']] = array(
                'id' => $item['id_page'],
                'name' => $item['id_page']
            );
        }

        $this->fields = array(
            'form' => array(
                'legend' => array('title' => $this->module->l('Where do you want to display the popup?')),
                'submit' => array(
                    'title' => $this->module->l('Save')
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'TAB_4',
                        'value' => '1',
                    ),
                    array(
                        'type'    => 'checkbox',
                        'name'    => 'pages',
                        'values'  => array(
                            'query' => $pages,
                            'id'    => 'id',
                            'name'  => 'name'
                        ),
                    )
                ),
            ),
        );

        return $this;
    }

    public function getFieldsValues()
    {
        $fields = array();
        $fields['TAB_4'] = Configuration::get('TAB_4');
        $rpp = new CP_ResponsivePopupPages();

        foreach ($rpp->getAll() as $item) {
            $fields['pages_'.$item["id_page"]] = CP_ResponsivePopupPages::checkEnable($item["id_page"]);
        }

        return $fields;
    }
}
