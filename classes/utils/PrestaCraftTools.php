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

class PrestaCraftTools
{
    public static function getHooks($frontOfficeOnly = false, $position = false, $displayOnly = false)
    {
        $hooks = Hook::getHooks($position, $displayOnly);
        $hooksReturn = array();

        if ($frontOfficeOnly) {
            foreach ($hooks as $hook) {
                if (!strpos($hook["name"], "Admin") && !strpos($hook["name"], "BackOffice")) {
                    $hooksReturn[] = $hook["name"];
                }
            }
        } else {
            foreach ($hooks as $hook) {
                $hooksReturn[] = $hook["name"];
            }
        }

        return $hooksReturn;
    }


    public static function getVersion()
    {
        return Tools::substr(_PS_VERSION_, 0, 3);
    }
}
