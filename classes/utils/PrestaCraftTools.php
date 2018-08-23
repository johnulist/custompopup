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


    public static function setDefaultValues()
    {
        Configuration::updateValue('CUSTOMPOPUP_ENABLED', 1);
        Configuration::updateValue('CUSTOMPOPUP_COLOR', '#fff');
        Configuration::updateValue('CUSTOMPOPUP_BACK_COLOR', '#222');
        Configuration::updateValue('CUSTOMPOPUP_PADDING', '25');
        Configuration::updateValue('CUSTOMPOPUP_TOP_PADDING', '40');
        Configuration::updateValue('CUSTOMPOPUP_BUTTON_COLOR', '#000');
        Configuration::updateValue('CUSTOMPOPUP_BUTTON_HOVER_COLOR', '#111');
        Configuration::updateValue('CUSTOMPOPUP_BUTTON_SIZE', '26');
        Configuration::updateValue('CUSTOMPOPUP_BUTTON_TOP_PADDING', '5');
        Configuration::updateValue('CUSTOMPOPUP_BUTTON_POSITION', 'right');
        Configuration::updateValue('CUSTOMPOPUP_MAINSELECT', 1);
        Configuration::updateValue('CUSTOMPOPUP_ENABLED', 0);
        Configuration::updateValue('CUSTOMPOPUP_COOKIE', 0);
        Configuration::updateValue('CUSTOMPOPUP_DELAY', 0);
    }

    public static function getVersion()
    {
        return Tools::substr(_PS_VERSION_, 0, 3);
    }

    public static function getTemplateVars()
    {
        return array(
            'popup_cookie' => Configuration::get('CUSTOMPOPUP_COOKIE'),
            'popup_delay' => Configuration::get('CUSTOMPOPUP_DELAY'),
            'popup_enabled' => Configuration::get('CUSTOMPOPUP_ENABLED'),
            'popup_color' => Configuration::get('CUSTOMPOPUP_COLOR'),
            'back_color' => Configuration::get('CUSTOMPOPUP_BACK_COLOR'),
            'padding' => Configuration::get('CUSTOMPOPUP_PADDING'),
            'top_padding' => Configuration::get('CUSTOMPOPUP_TOP_PADDING'),
            'button_color' => Configuration::get('CUSTOMPOPUP_BUTTON_COLOR'),
            'button_hover_color' => Configuration::get('CUSTOMPOPUP_BUTTON_HOVER_COLOR'),
            'button_size' => Configuration::get('CUSTOMPOPUP_BUTTON_SIZE'),
            'button_top_padding' => Configuration::get('CUSTOMPOPUP_BUTTON_TOP_PADDING'),
            'button_position' => Configuration::get('CUSTOMPOPUP_BUTTON_POSITION'),
            'version' => self::getVersion(),
        );
    }
}
