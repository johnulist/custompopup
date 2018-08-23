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
 */

require_once _PS_MODULE_DIR_.'custompopup/core/PrestaCraftFormCore.php';
require_once _PS_MODULE_DIR_.'custompopup/classes/db/ResponsivePopupPages.php';

class DisplayForm extends PrestaCraftFormCore
{
    public function __construct($moduleName)
    {
        parent::__construct($moduleName, __CLASS__);
    }

    public function render()
    {
        $rpp = new ResponsivePopupPages();
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
        $rpp = new ResponsivePopupPages();

        foreach ($rpp->getAll() as $item) {
            $fields['pages_'.$item["id_page"]] = ResponsivePopupPages::checkEnable($item["id_page"]);
        }

        return $fields;
    }
}