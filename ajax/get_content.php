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

require_once('../../../config/config.inc.php');

$id_lang = Context::getContext()->language->id;

echo Db::getInstance()->getValue('SELECT `content` FROM '._DB_PREFIX_.'responsive_popup 
WHERE id_lang='.(int)$id_lang.'');
