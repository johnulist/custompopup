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

/**
 * Minimum for each PrestaCraft module.
 *
 * Interface PrestacraftModuleInterface
 */
interface PrestacraftModuleInterface
{
    public function install();

    public function uninstall();

    public function getContent();

    public function postProcess();
}
