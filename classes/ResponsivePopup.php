<?php
/**
 * This software is provided "as is" without warranty of any kind.
 *
 * Made by PrestaCraft
 *
 * Visit my website (http://prestacraft.com) for future updates, new articles and other awesome modules.
 *
 * @author     PrestaCraft
 * @copyright  2015-2017 PrestaCraft
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class ResponsivePopup extends ObjectModel
{
    public $id;
    public $id_configuration;
    public $id_shop;
    public $id_lang;
    public $content;

    public static $definition = array(
        'table' => 'responsive_popup',
        'primary' => 'id_configuration',
        'fields' => array(
            'id_shop' => array('type' => self::TYPE_NOTHING),
            'id_lang' => array('type' => self::TYPE_NOTHING),
            'content' => array('type' => self::TYPE_HTML),
        ),
    );
}
