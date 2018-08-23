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

class ResponsivePopup extends ObjectModel
{
    public $id;
    public $id_configuration;
    public $id_shop;
    public $id_lang;
    public $content;

    public static $definition = array (
        'table' => 'responsive_popup',
        'primary' => 'id_configuration',
        'fields' => array (
            'id_shop' => array ('type' => self::TYPE_INT),
            'id_lang' => array ('type' => self::TYPE_INT),
            'content' => array ('type' => self::TYPE_HTML),
        ),
    );

    /**
     * @return bool
     * @throws PrestaShopException
     */
    public static function createTable()
    {
        try {
            if (!Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'responsive_popup` (
                `id_configuration` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
                `id_shop` INT(5),
                `id_lang` INT(5),
                `content` LONGTEXT,
                PRIMARY KEY (`id_configuration`)
            ) AUTO_INCREMENT = 1 ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8')) {
                return false;
            }
            return true;
        } catch (\Exception $e) {
            throw new PrestaShopException($e->getMessage());
        }
    }
}
