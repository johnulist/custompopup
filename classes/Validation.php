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

class Validation extends CustomPopup
{
    private $errors = array();

    public function validate($name, $field, $rules = array())
    {
        foreach ($rules as $key => $value) {
            switch ($key) {
                case 'notempty':
                    if (!$field || trim(Tools::strlen($field))<1) {
                        $this->setError(
                            $name,
                            sprintf(
                                $this->l("%s - can not be empty."),
                                $name
                            )
                        );
                    }
                    break;

                case 'maxlength':
                    if (Tools::strlen($field) > $value) {
                        $this->setError(
                            $name,
                            sprintf(
                                $this->l("%s - value '%s' is too long. Maximum is %s characters."),
                                $name,
                                $field,
                                $value
                            )
                        );
                    }
                    break;

                case 'minlength':
                    if (Tools::strlen($field) < $value) {
                        $this->setError(
                            $name,
                            sprintf(
                                $this->l("%s - value '%s' is too short. Minimum is %s characters."),
                                $name,
                                $field,
                                $value
                            )
                        );
                    }
                    break;

                case 'isnumber':
                    if (!is_numeric($field)) {
                        $this->setError($name, sprintf($this->l("%s - value '%s' is not a number."), $name, $field));
                    }
                    break;

                case 'ishex':
                    if ((Tools::strlen($field) !=4 || Tools::strlen($field) != 7)
                        && Tools::substr($field, 0, 1)!="#") {
                        $this->setError(
                            $name,
                            sprintf(
                                $this->l("%s - value '%s' is not valid HEX color."),
                                $name,
                                $field
                            )
                        );
                    }
                    break;
            }
        }
    }

    private function setError($name, $msg)
    {
        $this->errors[$name][] = $msg;
    }

    public function getError($name)
    {
        return @$this->errors[$name];
    }

    public function getAllErrors()
    {
        return $this->errors;
    }
}
