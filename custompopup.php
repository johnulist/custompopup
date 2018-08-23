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

if (!defined('_PS_VERSION_') || !defined('_PS_MODULE_DIR_')) {
    exit;
}

// Core
require_once _PS_MODULE_DIR_.'custompopup/core/PrestaCraftModuleInterface.php';

// Database
require_once _PS_MODULE_DIR_.'custompopup/classes/db/ResponsivePopup.php';
require_once _PS_MODULE_DIR_.'custompopup/classes/db/ResponsivePopupPages.php';

// Forms
require_once _PS_MODULE_DIR_.'custompopup/classes/form/CustomizeCloseForm.php';
require_once _PS_MODULE_DIR_.'custompopup/classes/form/CustomizeStyleForm.php';
require_once _PS_MODULE_DIR_.'custompopup/classes/form/DisplayForm.php';
require_once _PS_MODULE_DIR_.'custompopup/classes/form/SettingsForm.php';

// Utils
require_once _PS_MODULE_DIR_.'custompopup/classes/utils/PrestaCraftTools.php';

class CustomPopup extends Module implements PrestaCraftModuleInterface
{
    public function __construct()
    {
        $this->name = 'custompopup';
        $this->tab = 'front_office_features';
        $this->version = '1.2.0';
        $this->author = 'PrestaCraft';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Custom popup notification');
        $this->description = $this->l('Customize and display a responsive popup window for chosen page(s).');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }


    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        foreach (Language::getLanguages(true) as $lang) {
            $content='CUSTOMPOPUP_CONTENT_'.$lang['id_lang'];
            Configuration::updateValue($content, $lang['name']);
        }

        PrestaCraftTools::setDefaultValues();

        return parent::install() &&
            ResponsivePopup::createTable() &&
            ResponsivePopupPages::createTable() &&
            ResponsivePopupPages::fixtures() &&
            $this->registerHook('home') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('header');
    }


    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }
        return true;
    }

    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    public function getContent()
    {
        $this->postProcess();

        $data = Tools::file_get_contents('http://prestacraft.com/version_checker.php?module='.$this->name.'&version='.$this->version.'');
        $this->context->smarty->assign('module_dir', $this->_path);
        $this->context->smarty->assign('colorpicker_path', __PS_BASE_URI__.'js/jquery/plugins/jquery.colorpicker.js');
        $this->context->smarty->assign('CUSTOMPOPUP_COLOR', Configuration::get('CUSTOMPOPUP_COLOR'));
        $this->context->smarty->assign('CUSTOMPOPUP_BACK_COLOR', Configuration::get('CUSTOMPOPUP_BACK_COLOR'));
        $this->context->smarty->assign('CUSTOMPOPUP_BUTTON_COLOR', Configuration::get('CUSTOMPOPUP_BUTTON_COLOR'));
        $this->context->smarty->assign('CUSTOMPOPUP_BUTTON_HOVER_COLOR', Configuration::get('CUSTOMPOPUP_BUTTON_HOVER_COLOR'));
        $this->context->smarty->assign('VERSION_CHECKER', $data);
        $this->context->smarty->assign('POS', trim(Tools::getValue('pos')));

        $this->context->smarty->assign('TAB_SETTINGS', $this->renderSettings());
        $this->context->smarty->assign('TAB_CUSTOMIZE_STYLE', $this->renderCustomizeStyle());
        $this->context->smarty->assign('TAB_CUSTOMIZE_CLOSE', $this->renderCustomizeClose());
        $this->context->smarty->assign('TAB_DISPLAY', $this->renderDisplay());

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->postProcess();

    }

    public function postProcess()
    {
        require_once('classes/utils/Validation.php');

        $Validation = new Validation();

        if (Tools::isSubmit('SettingsForm')) {
            Configuration::updateValue('CUSTOMPOPUP_ENABLED', Tools::getValue('CUSTOMPOPUP_ENABLED'));
            $languages = Language::getLanguages(true);

            $Validation->validate(
                $this->l('Cookie length'),
                Tools::getValue('CUSTOMPOPUP_COOKIE'),
                array('isnumber' => 1)
            );

            $Validation->validate(
                $this->l('Cookie length'),
                Tools::getValue('CUSTOMPOPUP_DELAY'),
                array('isnumber' => 1)
            );

            $langContent = array();

            foreach ($languages as $la) {
                $langContent[$la['id_lang']] = Tools::getValue('CUSTOMPOPUP_CONTENT_'.$la['id_lang']);
            }

            foreach ($languages as $lang) {
                $Validation->validate(
                    $this->l('Popup content'),
                    Tools::getValue('CUSTOMPOPUP_CONTENT_'.$lang['id_lang']),
                    array('notempty' => 1)
                );
            }

            // if no errors occured
            if (!$Validation->getError($this->l('Popup content'))) {
                Db::getInstance()->execute('TRUNCATE TABLE `' . _DB_PREFIX_ . 'responsive_popup`');

                foreach ($languages as $lang) {
                    $popup = new ResponsivePopup();
                    $popup->id_shop = $this->context->shop->id;
                    $popup->id_lang = $lang['id_lang'];
                    $popup->content = Tools::getValue('CUSTOMPOPUP_CONTENT_'.$lang['id_lang']);
                    $popup->save();
                }
            }

            if ($Validation->getAllErrors()) {
                $errors = array();

                foreach ($Validation->getAllErrors() as $value) {
                    foreach ($value as $val) {
                        $errors[]=$val;
                    }
                }

                $newLineErrors = implode("<br>", $errors);

                return $this->displayError($newLineErrors);
            }

            Configuration::updateValue('CUSTOMPOPUP_COOKIE', Tools::getValue('CUSTOMPOPUP_COOKIE'));
            Configuration::updateValue('CUSTOMPOPUP_DELAY', Tools::getValue('CUSTOMPOPUP_DELAY'));

            $this->_clearCache('custompopup.tpl');
            return $this->displayConfirmation($this->l('The settings have been updated.'));
        }

        if (Tools::isSubmit('CustomizeStyleForm')) {
            $Validation->validate(
                $this->l('Popup color'),
                Tools::getValue('CUSTOMPOPUP_COLOR'),
                array(
                    'ishex' => 1,
                    'notempty' => 1
                )
            );

            $Validation->validate(
                $this->l('Background color'),
                Tools::getValue('CUSTOMPOPUP_BACK_COLOR'),
                array(
                    'ishex' => 1,
                    'notempty' => 1
                )
            );

            $Validation->validate(
                $this->l('Content padding'),
                Tools::getValue('CUSTOMPOPUP_PADDING'),
                array(
                    'isnumber' => 1,
                    'notempty' => 1
                )
            );

            $Validation->validate(
                $this->l('Content top padding'),
                Tools::getValue('CUSTOMPOPUP_TOP_PADDING'),
                array(
                    'isnumber' => 1,
                    'notempty' => 1
                )
            );

            if (!$Validation->getError($this->l('Popup color'))) {
                Configuration::updateValue('CUSTOMPOPUP_COLOR', Tools::getValue('CUSTOMPOPUP_COLOR'));
            }

            if (!$Validation->getError($this->l('Background color'))) {
                Configuration::updateValue('CUSTOMPOPUP_BACK_COLOR', Tools::getValue('CUSTOMPOPUP_BACK_COLOR'));
            }

            if (!$Validation->getError($this->l('Content padding'))) {
                Configuration::updateValue('CUSTOMPOPUP_PADDING', Tools::getValue('CUSTOMPOPUP_PADDING'));
            }

            if (!$Validation->getError($this->l('Content top padding'))) {
                Configuration::updateValue('CUSTOMPOPUP_TOP_PADDING', Tools::getValue('CUSTOMPOPUP_TOP_PADDING'));
            }

            if ($Validation->getAllErrors()) {
                $errors = array();

                foreach ($Validation->getAllErrors() as $value) {
                    foreach ($value as $val) {
                        $errors[]=$val;
                    }
                }

                $newLineErrors = implode("<br>", $errors);

                return $this->displayError($newLineErrors);
            }

            $this->_clearCache('custompopup.tpl');
            return $this->displayConfirmation($this->l('The settings have been updated.'));
        }

        if (Tools::isSubmit('CustomizeCloseForm')) {
            Configuration::updateValue('CUSTOMPOPUP_BUTTON_POSITION', Tools::getValue('CUSTOMPOPUP_BUTTON_POSITION'));

            $Validation->validate(
                $this->l('Color'),
                Tools::getValue('CUSTOMPOPUP_BUTTON_COLOR'),
                array(
                    'ishex' => 1,
                    'notempty' => 1
                )
            );

            $Validation->validate(
                $this->l('Hover color'),
                Tools::getValue('CUSTOMPOPUP_BUTTON_HOVER_COLOR'),
                array(
                    'ishex' => 1,
                    'notempty' => 1
                )
            );

            $Validation->validate(
                $this->l('Size'),
                Tools::getValue('CUSTOMPOPUP_BUTTON_SIZE'),
                array(
                    'isnumber' => 1,
                    'notempty' => 1
                )
            );

            $Validation->validate(
                $this->l('Top padding'),
                Tools::getValue('CUSTOMPOPUP_BUTTON_TOP_PADDING'),
                array(
                    'isnumber' => 1,
                    'notempty' => 1
                )
            );

            if (!$Validation->getError($this->l('Color'))) {
                Configuration::updateValue('CUSTOMPOPUP_BUTTON_COLOR', Tools::getValue('CUSTOMPOPUP_BUTTON_COLOR'));
            }

            if (!$Validation->getError($this->l('Hover color'))) {
                Configuration::updateValue(
                    'CUSTOMPOPUP_BUTTON_HOVER_COLOR',
                    Tools::getValue('CUSTOMPOPUP_BUTTON_HOVER_COLOR')
                );
            }

            if (!$Validation->getError($this->l('Size'))) {
                Configuration::updateValue('CUSTOMPOPUP_BUTTON_SIZE', Tools::getValue('CUSTOMPOPUP_BUTTON_SIZE'));
            }

            if (!$Validation->getError($this->l('Top padding'))) {
                Configuration::updateValue(
                    'CUSTOMPOPUP_BUTTON_TOP_PADDING',
                    Tools::getValue('CUSTOMPOPUP_BUTTON_TOP_PADDING')
                );
            }

            if ($Validation->getAllErrors()) {
                $errors = array();
                foreach ($Validation->getAllErrors() as $value) {
                    foreach ($value as $val) {
                        $errors[] = $val;
                    }
                }

                $newLineErrors = implode("<br>", $errors);
                return $this->displayError($newLineErrors);
            }

            $this->_clearCache('custompopup.tpl');
            return $this->displayConfirmation($this->l('The settings have been updated.'));
        }

        if (Tools::isSubmit('DisplayForm')) {
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'pages_') === 0) {
                    if ($value) {
                        $val = 1;
                    } else {
                        $val = 0;
                    }

                    self::updatePage(str_replace("pages_", "", $key), $val);
                }
            }

            $this->_clearCache('custompopup.tpl');
            return $this->displayConfirmation($this->l('The settings have been updated.'));
        }

        return '';
    }

    public function renderSettings()
    {
        $form = new SettingsForm($this->name);
        return $form->render()->buildForm();
    }

    public function renderCustomizeStyle()
    {
        $form = new CustomizeStyleForm($this->name);
        return $form->render()->buildForm();
    }

    public function renderCustomizeClose()
    {
       $form = new CustomizeCloseForm($this->name);
       return $form->render()->buildForm();
    }

    public function renderDisplay()
    {
        $form = new Displayform($this->name);
        return $form->render()->buildForm();
    }

    public function hookDisplayHome($params)
    {
        return $this->functionHook();
    }

    public function hookDisplayFooter($params)
    {
        return $this->functionHook();
    }

    public function hookDisplayMyAccountBlockfooter($params)
    {
        return $this->functionHook();
    }

    public function hookDisplayMyAccountBlock($params)
    {
        return $this->functionHook();
    }

    public function hookDisplayPaymentTop($params)
    {
        return $this->functionHook();
    }

    public function hookDisplayAfterCarrier($params)
    {
        return $this->functionHook();
    }

    public function hookDisplayCustomerAccount($params)
    {
        return $this->functionHook();
    }

    public function hookDisplayCustomerAccountForm($params)
    {
        return $this->functionHook();
    }

    public function hookDisplayOrderConfirmation($params)
    {
        return $this->functionHook();
    }

    public function hookDisplayOrderDetail($params)
    {
        return $this->functionHook();
    }

    public function hookDisplayFooterProduct($params)
    {
        return $this->functionHook();
    }

    public function hookDisplayCarrierList($params)
    {
        return $this->functionHook();
    }

    public function functionHook()
    {
        $langContent = array();

        foreach ($this->getContentForLanguages() as $langID => $content) {
            $langContent['content_'.$langID] = trim(json_encode($content),'"');
        }

        $assign = PrestaCraftTools::getTemplateVars();
        $all = array_merge($langContent, $assign);
        $this->context->smarty->assign($all);

        return $this->display(__FILE__, 'custompopup.tpl');
    }

    public function getContentForLanguages()
    {
        $sql = array();
        $content = array();
        $languages = Language::getLanguages(true);

        // get content for current shop and all languages
        foreach ($languages as $la) {
            $sql[] = Db::getInstance()->executeS('SELECT `content`,`id_lang`
            FROM ' . _DB_PREFIX_ . 'responsive_popup
            WHERE id_lang='.$la['id_lang'].' AND id_shop='.Context::getContext()->shop->id.'');
        }

        // make assignment to array: id_lang => content
        foreach ($sql as $val) {
            for ($i=0; $i < sizeof($sql); $i++) {
                @$content[$val[$i]['id_lang']] = $val[$i]['content'];
            }
        }

        return $content;
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS($this->_path.'views/css/tingle.min.css', 'all');
        $this->context->controller->addCSS($this->_path.'views/css/popup.css', 'all');

        $this->context->smarty->assign(array(
            'prestacraft_cookie' => $this->_path.'views/js/cookie.js',
            'tingle' => $this->_path.'views/js/tingle.min.js'
        ));

        return $this->display(__FILE__, 'header.tpl');
    }



    private static function updatePage($field, $value)
    {
        Db::getInstance()->update(
            'responsive_popup_pages',
            array('enabled' => $value),
            'id_page="'.$field.'";'
        );
    }

}
