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

if (!defined('_PS_VERSION_')) {
    exit;
}

include('classes/ResponsivePopup.php');

class CustomPopup extends Module
{

    public function __construct()
    {
        $this->name = 'custompopup';
        $this->tab = 'front_office_features';
        $this->version = '1.1.0';
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

        self::setStyleDefaultValues();

        Configuration::updateValue('CUSTOMPOPUP_MAINSELECT', 1);
        Configuration::updateValue('CUSTOMPOPUP_ENABLED', 0);
        Configuration::updateValue('CUSTOMPOPUP_COOKIE', 0);
        Configuration::updateValue('CUSTOMPOPUP_DELAY', 0);

        return parent::install() &&
            $this->installDb() &&
            $this->installFixtures() &&
            $this->registerHook('home') &&
            $this->registerHook('header');
    }


    public function installDb()
    {
        if (!Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'responsive_popup` (
            `id_configuration` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
            `id_shop` INT(5),
            `id_lang` INT(5),
            `content` LONGTEXT,
            PRIMARY KEY (`id_configuration`)
        ) AUTO_INCREMENT = 1 ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8')) {
            return false;
        }
        if (!Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'responsive_popup_pages` (
            `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
            `id_page` VARCHAR(250),
            `id_shop` INT(5),
            `enabled` TINYINT,
            PRIMARY KEY (`id`)
        ) AUTO_INCREMENT = 1 ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8')) {
            return false;
        }
        return true;
    }


    public function installFixtures()
    {

        foreach (self::getAllShopId() as $shopid) {
            Db::getInstance()->insert(
                'responsive_popup_pages',
                array(
                    'id_page' => 'Homepage',
                    'id_shop' => $shopid,
                    'enabled' => '0'
                )
            );

            Db::getInstance()->insert(
                'responsive_popup_pages',
                array(
                    'id_page' => 'MyAccount',
                    'id_shop' => $shopid,
                    'enabled' => '0'
                )
            );

            Db::getInstance()->insert(
                'responsive_popup_pages',
                array(
                    'id_page' => 'Payment',
                    'id_shop' => $shopid,
                    'enabled' => '0'
                )
            );

            Db::getInstance()->insert(
                'responsive_popup_pages',
                array(
                    'id_page' => 'CreateAccount',
                    'id_shop' => $shopid,
                    'enabled' => '0'
                )
            );

            Db::getInstance()->insert(
                'responsive_popup_pages',
                array(
                    'id_page' => 'OrderConfirmation',
                    'id_shop' => $shopid,
                    'enabled' => '0'
                )
            );

            Db::getInstance()->insert(
                'responsive_popup_pages',
                array(
                    'id_page' => 'CarrierList',
                    'id_shop' => $shopid,
                    'enabled' => '0'
                )
            );

            Db::getInstance()->insert(
                'responsive_popup_pages',
                array(
                    'id_page' => 'Product',
                    'id_shop' => $shopid,
                    'enabled' => '0'
                )
            );
        }
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }
        return true;
    }


    public function getContent()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $script = '<style>
            .leftfix {
            margin-left:10px;
            }
            </style>
            <script type="text/javascript" src="'.__PS_BASE_URI__.'js/jquery/plugins/jquery.colorpicker.js"></script>
            <script>
            $("#color_0").css("background-color", "'.Configuration::get('CUSTOMPOPUP_COLOR').'");
            $("#color_1").css("background-color", "'.Configuration::get('CUSTOMPOPUP_BACK_COLOR').'");
            $("#color_2").css("background-color", "'.Configuration::get('CUSTOMPOPUP_BUTTON_COLOR').'");
            $("#color_3").css("background-color", "'.Configuration::get('CUSTOMPOPUP_BUTTON_HOVER_COLOR').'");

            $( document ).ready(function() {
            if ($("#chosen").is(":checked")) {
            $(".checkbox").show();
            } else {
            $(".checkbox").hide();
            }
            
            $("input[name=mainselect]:radio").click(function() {
                if ($(this).attr("value")=="3") {
                    $(".checkbox").show();
                }
                if ($(this).attr("value")=="2" || $(this).attr("value")=="1") {
                    $(".checkbox").hide();
                }
            });

            $( ".restore" ).click(function() {
            var ask = window.confirm("Are you sure?");
                if (ask) {
            $("#color_0").val("#fff");
            $("#color_1").val("#222");
            $("#CUSTOMPOPUP_PADDING").val("25");
            $("#CUSTOMPOPUP_TOP_PADDING").val("40");
            $("#color_0").css("background-color", "#fff");
            $("#color_1").css("background-color", "#222");
            window.alert("Default values have been restored. Click Save button to keep them.");
                }
            });
            $( ".restore2" ).click(function() {
            var ask = window.confirm("Are you sure?");
                if (ask) {
            $("#color_2").val("#000");
            $("#color_3").val("#111");
            $("#CUSTOMPOPUP_BUTTON_SIZE").val("26");
            $("#CUSTOMPOPUP_BUTTON_TOP_PADDING").val("5");
            $("#right").prop("checked",true);
            $("#color_2").css("background-color", "#000");
            $("#color_3").css("background-color", "#111");
            window.alert("Default values have been restored. Click Save button to keep them.");
                }
            });
            $( "div" ).removeClass( "col-lg-2" );

            if ('.Tools::getValue('pos').'== 1)
            {
                window.location.hash = "#settings";
            }
            else if ('.Tools::getValue('pos').'== 2) {
                window.location.hash = "#customizestyle";
            }
            else if ('.Tools::getValue('pos').'== 3) {
                window.location.hash = "#display";
            }
            });
            </script>';
        } else {
            $script = '<style>
            .leftfix {
            margin-left:10px;
            }
</style>
<script type="text/javascript" src="'.__PS_BASE_URI__.'js/jquery/plugins/jquery.colorpicker.js"></script>
<script>

            $("#color_0").css("background-color", "'.Configuration::get('CUSTOMPOPUP_COLOR').'");
            $("#color_1").css("background-color", "'.Configuration::get('CUSTOMPOPUP_BACK_COLOR').'");
            $("#color_2").css("background-color", "'.Configuration::get('CUSTOMPOPUP_BUTTON_COLOR').'");
            $("#color_3").css("background-color", "'.Configuration::get('CUSTOMPOPUP_BUTTON_HOVER_COLOR').'");
            $( document ).ready(function() {

            if ($("#chosen").is(":checked")) {
                $(".checkbox").show();
            } else {
                $(".checkbox").hide();
            }
            
            $("input[name=mainselect]:radio").click(function() {
                if ($(this).attr("value")=="3") {
                    $(".checkbox").show();
                }
                if ($(this).attr("value")=="2" || $(this).attr("value")=="1") {
                    $(".checkbox").hide();
                }
            });
            
            $( ".restore" ).click(function() {
            var ask = window.confirm("Are you sure?");
                if (ask) {
                    $("#color_0").val("#fff");
                    $("#color_1").val("#222");
                    $("#CUSTOMPOPUP_PADDING").val("25");
                    $("#CUSTOMPOPUP_TOP_PADDING").val("40");
                    window.alert("Default values have been restored. Click Save button to keep them.");
                }
            });
            $( ".restore2" ).click(function() {
            var ask = window.confirm("Are you sure?");
                if (ask) {
                    $("#color_2").val("#000");
                    $("#color_3").val("#111");
                    $("#CUSTOMPOPUP_BUTTON_SIZE").val("26");
                    $("#CUSTOMPOPUP_BUTTON_TOP_PADDING").val("5");
                    $("#right").prop("checked",true);
                    window.alert("Default values have been restored. Click Save button to keep them.");
                }
            });
            $( "div" ).removeClass( "col-lg-2" );
            });
            </script>';
        }

        return $script.$this->postProcess().$this->displayTabs();
    }


    public function displayTabs()
    {
        return
            '<script src="../modules/custompopup/views/js/remember_tab.js"></script>
        <div role="tabpanel">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-tabs-sticky" role="tablist">
                <li role="presentation" class="active">
                <a href="#settings" aria-controls="home" role="tab" 
                data-toggle="tab"><i class="icon-cogs"></i>&nbsp;&nbsp;&nbsp;'.$this->l('Main Settings').'</a>
                </li>
                <li role="presentation">
                <a href="#customizestyle" aria-controls="profile" role="tab" 
                data-toggle="tab"><i class="icon-pencil"></i>&nbsp;&nbsp;&nbsp;'.$this->l('Customize Style').'</a>
                </li>
                <li role="presentation"><a href="#display" aria-controls="profile" role="tab" 
                data-toggle="tab"><i class="icon-eye-open"></i>&nbsp;&nbsp;&nbsp;'.$this->l('Display on pages').'</a>
                </li>
                <li role="presentation"><a href="#about" aria-controls="profile" role="tab" 
                data-toggle="tab"><i class="icon-info-circle"></i>&nbsp;&nbsp;&nbsp;'.$this->l('About').'</a></li>
            </ul>

        <!-- Tab panes -->
        <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="settings">'.$this->renderSettings().'</div>
        <div role="tabpanel" class="tab-pane" id="customizestyle">
            <div class="col-xs-12 col-sm-6">'.$this->renderCustomizeStyle().'</div>
            <div class="col-xs-12 col-sm-6">'.$this->renderCustomizeClose().'</div>
        </div>
        <div role="tabpanel" class="tab-pane" id="display">'.$this->renderDisplay().'</div>
        <div role="tabpanel" class="tab-pane panel" id="about">
        '.$this->l('Have a look at my blog with tutorials and modules for PrestaShop').' -
        <a href="http://prestacraft.com" target="_blank">http://prestacraft.com</a>. '.$this->l('Thanks').'.
        <br />
        <br />'.$this->l('Made with').' <i class="icon-heart"></i> '.$this->l('by').'
         <a href="'.$this->l('http://prestacraft.com').'" target="_blank">PrestaCraft</a>.
<br /><br /><br />
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="2NL2KJBLW86SQ">
<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" 
border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/pl_PL/i/scr/pixel.gif" width="1" height="1">
</form>

        </div>

        </div>
        </div>';
    }


    public function postProcess()
    {
        require_once('classes/Validation.php');

        $Validation = new Validation();

        if (Tools::isSubmit('saveCustomPopup')) {
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

            foreach ($languages as $lang)
            {
                $Validation->validate($this->l('Popup content'),Tools::getValue('CUSTOMPOPUP_CONTENT_'.$lang['id_lang']),array(
                    'notempty' => 1,
                ));
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

        if (Tools::isSubmit('saveStyle')) {
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

        if (Tools::isSubmit('saveStyleClose')) {
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

        if (Tools::isSubmit('saveDisplay')) {
            Configuration::updateValue('CUSTOMPOPUP_MAINSELECT', Tools::getValue('mainselect'));

            if (Tools::getValue('pages_Homepage')) {
                self::updatePage('Homepage', 1);
            } else {
                self::updatePage('Homepage', 0);
            }

            if (Tools::getValue('pages_MyAccount')) {
                self::updatePage('MyAccount', 1);
            } else {
                self::updatePage('MyAccount', 0);
            }

            if (Tools::getValue('pages_Payment')) {
                self::updatePage('Payment', 1);
            } else {
                self::updatePage('Payment', 0);
            }

            if (Tools::getValue('pages_CreateAccount')) {
                self::updatePage('CreateAccount', 1);
            } else {
                self::updatePage('CreateAccount', 0);
            }

            if (Tools::getValue('pages_OrderConfirmation')) {
                self::updatePage('OrderConfirmation', 1);
            } else {
                self::updatePage('OrderConfirmation', 0);
            }

            if (Tools::getValue('pages_Categories')) {
                self::updatePage('Category', 1);
            } else {
                self::updatePage('Category', 0);
            }

            if (Tools::getValue('pages_Products')) {
                self::updatePage('Product', 1);
            } else {
                self::updatePage('Product', 0);
            }

            if (Tools::getValue('pages_OrderDetail')) {
                self::updatePage('OrderDetail', 1);
            } else {
                self::updatePage('OrderDetail', 0);
            }

            if (Tools::getValue('pages_CarrierList')) {
                self::updatePage('CarrierList', 1);
            } else {
                self::updatePage('CarrierList', 0);
            }

            $this->_clearCache('custompopup.tpl');
            return $this->displayConfirmation($this->l('The settings have been updated.'));
        }

        return '';
    }

    public function renderSettings()
    {
        $fields_form = array(
            'form' => array(
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable popup?'),
                        'name' => 'CUSTOMPOPUP_ENABLED',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Cookie length'),
                        'suffix' => $this->l('minutes'),
                        'name' => 'CUSTOMPOPUP_COOKIE',
                        'required' => true,
                        'desc' => $this->l('Type 0 to make popup always appearing'),
                        'class' => 'fixed-width-sm',
                        'hint' => $this->l('If user closes popup, it will appear again after this time')
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Popup delay'),
                        'suffix' => $this->l('seconds'),
                        'name' => 'CUSTOMPOPUP_DELAY',
                        'required' => true,
                        'desc' => $this->l('Type 0 to show immediately'),
                        'class' => 'fixed-width-sm',
                    ),
                    array(
                        'class' => 'rte',
                        'autoload_rte' => true,
                        'type' => 'textarea',
                        'lang' => true,
                        'label' => $this->l('Popup content'),
                        'name' => 'CUSTOMPOPUP_CONTENT',
                        'required' => true,
                        'cols' => 40,
                        'rows' => 10,
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save')
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table =  $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'saveCustomPopup';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&pos=1&
        configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'uri' => $this->getPathUri(),
            'fields_value' => $this->getSettingsFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function renderCustomizeStyle()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array('title' => $this->l('Popup style')),
                'submit' => array(
                    'title' => $this->l('Save')
                ),
                'buttons' => array(
                    array(
                        'title' => $this->l('Restore default values'),
                        'icon' => 'icon-rotate-left',
                        'class' => 'restore'
                    )
                ),
                'input' => array(
                    array(
                        'type' => 'color',
                        'label' => $this->l('Popup color'),
                        'name' => 'CUSTOMPOPUP_COLOR',
                        'class' => 'leftfix',
                        'hint' => $this->l('Inside the popup')
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('Background color'),
                        'name' => 'CUSTOMPOPUP_BACK_COLOR',
                        'class' => 'leftfix',
                        'hint' => $this->l('Outside the popup')
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Content padding'),
                        'name' => 'CUSTOMPOPUP_PADDING',
                        'class' => 'fixed-width-sm',
                        'suffix' => $this->l('pixels'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Content top padding'),
                        'name' => 'CUSTOMPOPUP_TOP_PADDING',
                        'class' => 'fixed-width-sm',
                        'suffix' => $this->l('pixels'),
                    ),
                ),

            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table =  $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'saveStyle';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&pos=2
        &configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'uri' => $this->getPathUri(),
            'fields_value' => $this->getStyleFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function renderCustomizeClose()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array('title' => $this->l('[X] Close button style')),
                'submit' => array(
                    'title' => $this->l('Save')
                ),
                'buttons' => array(
                    array(
                        'title' => $this->l('Restore default values'),
                        'icon' => 'icon-rotate-left',
                        'class' => 'restore2'
                    )
                ),
                'input' => array(
                    array(
                        'type' => 'color',
                        'label' => $this->l('Color'),
                        'name' => 'CUSTOMPOPUP_BUTTON_COLOR',
                        'class' => 'leftfix'
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('Hover color'),
                        'hint' => $this->l('Color shown after moving your cursor over the button'),
                        'name' => 'CUSTOMPOPUP_BUTTON_HOVER_COLOR',
                        'class' => 'leftfix'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Size'),
                        'name' => 'CUSTOMPOPUP_BUTTON_SIZE',
                        'class' => 'fixed-width-sm',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Top padding'),
                        'name' => 'CUSTOMPOPUP_BUTTON_TOP_PADDING',
                        'class' => 'fixed-width-sm',
                        'suffix' => $this->l('pixels'),
                    ),
                    array(
                        'type'      => 'radio',
                        'label'     => $this->l('Position'),
                        'name'      => 'CUSTOMPOPUP_BUTTON_POSITION',
                        'required'  => true,
                        'class'     => 't',
                        'values'    => array(
                            array(
                                'id'    => 'left',
                                'value' => 'left',
                                'label' => $this->l('Left')
                            ),
                            array(
                                'id'    => 'right',
                                'value' => 'right',
                                'label' => $this->l('Right')
                            )
                        ),
                    ),
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table =  $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'saveStyleClose';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&pos=2
        &configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'uri' => $this->getPathUri(),
            'fields_value' => $this->getStyleFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function renderDisplay()
    {

        $pages = array(
            'Homepage' => array(
                'id' => 'Homepage',
                'name' => $this->l('Homepage')
            ),
            'Products' => array(
                'id' => 'Products',
                'name' => $this->l('Products')
            ),
            'CreateAccount' => array(
                'id' => 'CreateAccount',
                'name' => $this->l('Creating account')
            ),
            'MyAccount' => array(
                'id' => 'MyAccount',
                'name' => $this->l('My account')
            ),
            'CarrierList' => array(
                'id' => 'CarrierList',
                'name' => $this->l('Order - Carrier list page')
            ),
            'Payment' => array(
                'id' => 'Payment',
                'name' => $this->l('Order - Payment page')
            ),
            'OrderConfirmation' => array(
                'id' => 'OrderConfirmation',
                'name' => $this->l('Order - Confirmation page')
            ),

        );

        $fields_form = array(
            'form' => array(
                'legend' => array('title' => $this->l('Where do you want to display the popup?')),
                'submit' => array(
                    'title' => $this->l('Save')
                ),
                'input' => array(
                    array(
                        'label'   => $this->l('Choose page(s)'),
                        'type'      => 'radio',
                        'name'      => 'mainselect',
                        'required'  => true,
                        'class'     => 't',
                        'is_bool'   => false,
                        'values'    => array(
                            array(
                                'id'    => 'homepage',
                                'value' => 1,
                                'label' => $this->l('Only on Homepage')
                            ),
                            array(
                                'id'    => 'each',
                                'value' => 2,
                                'label' => $this->l('On each page')
                            ),
                            array(
                                'id'    => 'chosen',
                                'value' => 3,
                                'label' => $this->l('On chosen pages (click to display them)')
                            ),
                        ),
                    ),
                    array(
                        'type'    => 'checkbox',
                        'name'    => 'pages',
                        'values'  => array(
                            'query' => $pages,
                            'id'    => 'id',
                            'name'  => 'name'
                        ),
                    )
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table =  $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'saveDisplay';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&pos=3
        &configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'uri' => $this->getPathUri(),
            'fields_value' => $this->getDisplayFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getSettingsFieldsValues()
    {
        $languages = Language::getLanguages(true);
        $fields = array();

        $langContent = $this->getContentForLanguages();

        foreach ($languages as $lang) {
            @$fields['CUSTOMPOPUP_CONTENT'][$lang['id_lang']] = $langContent[$lang['id_lang']];
        }

        $fields['CUSTOMPOPUP_ENABLED'] = Configuration::get('CUSTOMPOPUP_ENABLED');
        $fields['CUSTOMPOPUP_COOKIE'] = Configuration::get('CUSTOMPOPUP_COOKIE');
        $fields['CUSTOMPOPUP_DELAY'] = Configuration::get('CUSTOMPOPUP_DELAY');

        return $fields;
    }

    public function getStyleFieldsValues()
    {
        $fields = array();

        $fields['CUSTOMPOPUP_COLOR'] = Configuration::get('CUSTOMPOPUP_COLOR');
        $fields['CUSTOMPOPUP_BACK_COLOR'] = Configuration::get('CUSTOMPOPUP_BACK_COLOR');
        $fields['CUSTOMPOPUP_PADDING'] = Configuration::get('CUSTOMPOPUP_PADDING');
        $fields['CUSTOMPOPUP_TOP_PADDING'] = Configuration::get('CUSTOMPOPUP_TOP_PADDING');
        $fields['CUSTOMPOPUP_BUTTON_COLOR'] = Configuration::get('CUSTOMPOPUP_BUTTON_COLOR');
        $fields['CUSTOMPOPUP_BUTTON_HOVER_COLOR'] = Configuration::get('CUSTOMPOPUP_BUTTON_HOVER_COLOR');
        $fields['CUSTOMPOPUP_BUTTON_SIZE'] = Configuration::get('CUSTOMPOPUP_BUTTON_SIZE');
        $fields['CUSTOMPOPUP_BUTTON_TOP_PADDING'] = Configuration::get('CUSTOMPOPUP_BUTTON_TOP_PADDING');
        $fields['CUSTOMPOPUP_BUTTON_POSITION'] = Configuration::get('CUSTOMPOPUP_BUTTON_POSITION');

        return $fields;
    }

    public function getDisplayFieldsValues()
    {
        $fields = array();

        $fields['mainselect'] = Configuration::get('CUSTOMPOPUP_MAINSELECT');

        $fields['pages_Homepage'] = self::checkEnable('Homepage');
        $fields['pages_MyAccount'] = self::checkEnable('MyAccount');
        $fields['pages_Payment'] = self::checkEnable('Payment');
        $fields['pages_CreateAccount'] = self::checkEnable('CreateAccount');
        $fields['pages_OrderConfirmation'] = self::checkEnable('OrderConfirmation');
        $fields['pages_Categories'] = self::checkEnable('Category');
        $fields['pages_Products'] = self::checkEnable('Product');
        $fields['pages_OrderDetail'] = self::checkEnable('OrderDetail');
        $fields['pages_CarrierList'] = self::checkEnable('CarrierList');

        if ($fields['mainselect'] == 3 || Configuration::get('CUSTOMPOPUP_MAINSELECT') == 3) {
            $this->unregisterHook('displayFooter');
            $this->unregisterHook('home');
            $this->unregisterHook('displayMyAccountBlockfooter');
            $this->unregisterHook('displayPaymentTop');
            $this->unregisterHook('displayCustomerAccountForm');
            $this->unregisterHook('displayOrderConfirmation');
            $this->unregisterHook('displayOrderDetail');
            $this->unregisterHook('displayFooterProduct');
            $this->unregisterHook('displayCarrierList');

            if(CustomPopup::getVersion() == "1.7") {
                $this->unregisterHook('displayCustomerAccount');
                $this->unregisterHook('displayAfterCarrier');
            }

            if (self::checkEnable('Homepage')) {
                $this->registerHook('home');
            } else {
                $this->unregisterHook('home');
            }
            if (self::checkEnable('MyAccount')) {
                if(CustomPopup::getVersion() == "1.7") {
                    $this->registerHook('displayCustomerAccount');
                } else {
                    $this->registerHook('displayMyAccountBlockfooter');
                }
            } else {
                if(CustomPopup::getVersion() == "1.7") {
                    $this->unregisterHook('displayCustomerAccount');
                } else {
                    $this->unregisterHook('displayMyAccountBlockfooter');
                }
            }
            if (self::checkEnable('Payment')) {
                $this->registerHook('displayPaymentTop');
            } else {
                $this->unregisterHook('displayPaymentTop');
            }
            if (self::checkEnable('CreateAccount')) {
                $this->registerHook('displayCustomerAccountForm');
            } else {
                $this->unregisterHook('displayCustomerAccountForm');
            }
            if (self::checkEnable('OrderConfirmation')) {
                $this->registerHook('displayOrderConfirmation');
            } else {
                $this->unregisterHook('displayOrderConfirmation');
            }
            if (self::checkEnable('OrderDetail')) {
                $this->registerHook('displayOrderDetail');
            } else {
                $this->unregisterHook('displayOrderDetail');
            }
            if (self::checkEnable('Product')) {
                $this->registerHook('displayFooterProduct');
            } else {
                $this->unregisterHook('displayFooterProduct');
            }
            if (self::checkEnable('CarrierList')) {
                if(CustomPopup::getVersion() == "1.7") {
                    $this->registerHook('displayAfterCarrier');
                } else {
                    $this->registerHook('displayCarrierList');
                }
            } else {
                if(CustomPopup::getVersion() == "1.7") {
                    $this->unregisterHook('displayAfterCarrier');
                } else {
                    $this->unregisterHook('displayCarrierList');
                }
            }
        } else {
            if ($fields['mainselect'] == 1 || Configuration::get('CUSTOMPOPUP_MAINSELECT') == 1) {
                $this->registerHook('home');
                if(CustomPopup::getVersion() == "1.7") {
                    $this->unregisterHook('displayCustomerAccount');
                } else {
                    $this->unregisterHook('displayMyAccountBlockfooter');
                }
                $this->unregisterHook('displayPaymentTop');
                $this->unregisterHook('displayCustomerAccountForm');
                $this->unregisterHook('displayOrderConfirmation');
                $this->unregisterHook('displayOrderDetail');
                $this->unregisterHook('displayFooterProduct');
                $this->unregisterHook('displayCarrierList');
            }
            if ($fields['mainselect'] == 2 || Configuration::get('CUSTOMPOPUP_MAINSELECT') == 2) {
                $this->registerHook('displayFooter');
                $this->unregisterHook('home');
                if(CustomPopup::getVersion() == "1.7") {
                    $this->unregisterHook('displayCustomerAccount');
                } else {
                    $this->unregisterHook('displayMyAccountBlockfooter');
                }
                $this->unregisterHook('displayPaymentTop');
                $this->unregisterHook('displayCustomerAccountForm');
                $this->unregisterHook('displayOrderConfirmation');
                $this->unregisterHook('displayOrderDetail');
                $this->unregisterHook('displayFooterProduct');
                $this->unregisterHook('displayCarrierList');
            }
        }

        return $fields;
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
            $langContent['content_'.$langID] = $content;
        }

        $assign = $this->getAssign();
        $all = array_merge($langContent, $assign);
        $this->context->smarty->assign($all);

        return $this->display(__FILE__, 'custompopup.tpl');
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->addJS($this->_path.'views/js/jquery.cookie.js', 'all');
        $this->context->controller->addJS($this->_path.'views/js/jquery.popup.min.js', 'all');
        $this->context->controller->addCSS($this->_path.'views/css/popup.css', 'all');

        $this->context->smarty->assign(array(
            'jq' => $this->_path.'views/js/jq.js'
        ));

        return $this->display(__FILE__, 'header.tpl');
    }

    protected function existsInDb()
    {
        $langIdArray = array();

        foreach (Language::getLanguages(true) as $lang) {
            $langIdArray[] = $lang['id_lang'];
        }

        $langs = implode(",", $langIdArray);

        $result = Db::getInstance()->executeS('SELECT COUNT(*) as nr
        FROM ' . _DB_PREFIX_ . 'responsive_popup`
        WHERE id_lang IN ('.$langs.') AND id_shop='.$this->context->shop->id.'');

        // check if query was executed successfully
        if ($result) {
            // check if one or more results exist in database
            return ($result[0]['nr']>=count($langIdArray)) ? true : false;
        } else {
            return false;
        }
    }

    protected function updateDbValues($array)
    {
        foreach ($array as $langID => $content) {
            Db::getInstance()->update(
                'responsive_popup',
                array('content' => $content),
                'id_lang='.$langID.' AND id_shop='.$this->context->shop->id.''
            );
        }
    }

    protected function getContentForLanguages()
    {
        $sql = array();
        $content = array();
        $languages = Language::getLanguages(true);

        // get content for current shop and all languages
        foreach ($languages as $la) {
            $sql[] = Db::getInstance()->executeS('SELECT `content`,`id_lang`
            FROM ' . _DB_PREFIX_ . 'responsive_popup
            WHERE id_lang='.$la['id_lang'].' AND id_shop='.$this->context->shop->id.'');
        }

        // make assignment to array: id_lang => content
        foreach ($sql as $val) {
            for ($i=0; $i < sizeof($sql); $i++) {
                @$content[$val[$i]['id_lang']] = $val[$i]['content'];
            }
        }

        return $content;
    }

    private static function setStyleDefaultValues()
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
    }

    private static function getAllShopId()
    {
        $id = array();
        $result = Db::getInstance()->ExecuteS('
		SELECT id_shop
		FROM `'._DB_PREFIX_.'shop`');
        foreach ($result as $row) {
            $id[$row['id_shop']] = $row['id_shop'];
        }

        return $id;
    }

    private static function updatePage($field, $value)
    {
        Db::getInstance()->update(
            'responsive_popup_pages',
            array('enabled' => $value),
            'id_page="'.$field.'";'
        );
    }

    private static function checkEnable($field)
    {
        $enabled = array();
        $result = Db::getInstance()->ExecuteS('
		SELECT enabled
		FROM `'._DB_PREFIX_.'responsive_popup_pages`
		WHERE id_page="'.$field.'"');
        foreach ($result as $row) {
            $enabled[0] = $row['enabled'];
        }

        return @$enabled[0];
    }

    private function getAssign()
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
            'version' => CustomPopup::getVersion(),
            'ajaxpath' => CustomPopup::modulePath().'ajax/get_content.php',
        );
    }

    public static function getVersion()
    {
        return Tools::substr(_PS_VERSION_, 0, 3);
    }

    public static function modulePath()
    {
        Configuration::get("PS_SSL_ENABLED") ? $protocol = "https://" : $protocol = "http://";
        $domainName = $_SERVER['SERVER_NAME'];

        return $protocol.$domainName.__PS_BASE_URI__.'modules/custompopup/';
    }
}
