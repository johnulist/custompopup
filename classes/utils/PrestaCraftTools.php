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
    /**
     * Get hooks using PrestaShop getHooks() method, but with extra filter - in case you want only FrontOffice hooks
     *
     * @param bool $frontOfficeOnly
     * @param bool $position
     * @param bool $displayOnly
     * @return array
     */
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
}
