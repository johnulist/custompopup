<?php

require_once _PS_MODULE_DIR_.'custompopup/classes/utils/Validation.php';

abstract class PrestaCraftValidatorCore
{
    protected $module;
    protected $formName;
    protected $validatorFile;
    protected $validation;
    private $data;
    private $errors;
    private $success = false;

    public function __construct($moduleObject, $formName)
    {
        if ($moduleObject instanceof Module) {
            $this->module = $moduleObject;
        } else {
            throw new \Exception("[PrestaCraft Exception] Incorrect module instance");
        }

        $validatorDir = _PS_MODULE_DIR_.$this->module->name.'/classes/form/validators/';
        $formDir = _PS_MODULE_DIR_.$this->module->name.'/classes/form/';
        $validatorName = str_replace('Form', 'Validator', $formName);

        if (!file_exists($formDir .$formName.'.php')) {
            throw new \Exception("[PrestaCraft Exception] Form '{$formName}' file not found in classes/form directory");
        } else {
            if (!file_exists($validatorDir.$validatorName.'.php')) {
                throw new \Exception(
                    "[PrestaCraft Exception] Validator '{$validatorName}'
                    file not found in classes/form/validators directory"
                );
            }

            $this->formName = $formName;
            $this->validatorFile = $validatorDir.$formName.'Validator.php';
            $this->validation = new Validation($moduleObject);

            require_once $validatorDir.$validatorName.'.php';
        }
    }

    abstract protected function processValidation();

    public function validate()
    {
        if (!$this->getData() || count($this->getData()) == 0) {
            throw new \Exception("[PrestaCraft Exception] Form data for '{$this->formName}' is not provided");
        }

        if (Tools::isSubmit($this->formName)) {
            $this->processValidation();
            $this->displayErrorsIfOccured();
            $this->save();
        }
    }

    abstract protected function save();

    protected function displayErrorsIfOccured()
    {
        if ($this->validation->getAllErrors()) {
            $errors = array();

            foreach ($this->validation->getAllErrors() as $value) {
                foreach ($value as $val) {
                    $errors[] = $val;
                }
            }

            $newLineErrors = implode("<br>", $errors);

            $this->setErrors($newLineErrors);
        }
    }

    public function setSuccess($success)
    {
        $this->success = $success;
    }

    public function getSuccess()
    {
        return $this->success;
    }

    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function setData($array)
    {
        $this->data = $array;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getField($field)
    {
        if (array_key_exists($field, $this->data)) {
            return $this->data[$field];
        } else {
            throw new \Exception("[PrestaCraft Exception] Data key not found in getField() method");
        }
    }
}
