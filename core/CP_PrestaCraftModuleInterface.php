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

/**
 * Minimum for each PrestaCraft module.
 *
 * Interface PrestacraftModuleInterface
 */
interface CP_PrestacraftModuleInterface
{
    public function install();

    public function uninstall();

    public function getContent();

    public function postProcess();
}
