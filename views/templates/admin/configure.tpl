{*
 * This software is provided "as is" without warranty of any kind.
 *
 * Made by PrestaCraft
 *
 * Visit my website (http://prestacraft.com) for future updates, new articles and other awesome modules.
 *
 * @author     PrestaCraft
 * @copyright  PrestaCraft
*}

<script src="{$colorpicker_path|escape:'html_all'}"></script>

<div role="tabpanel" class="prestacraft">
    <!-- Nav tabs -->
    <div class="col-lg-3 col-md-4 col-xs-12 prestacraft-left">
        <div class="logo-container">
            <img src="{$module_dir|escape:'html_all'}/views/img/pc_logo.png" class="pc-logo">
        </div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#settings" aria-controls="home" role="tab" data-toggle="tab">
                    <i class="icon-cogs"></i>&nbsp;&nbsp;&nbsp;{l s='Main settings' mod='custompopup'}</a>
            </li>

            <li role="presentation">
                <a href="#customizestyle" aria-controls="profile" role="tab" data-toggle="tab">
                    <i class="icon-pencil"></i>&nbsp;&nbsp;&nbsp;{l s='Customize style' mod='custompopup'}
                </a>
            </li>

            <li role="presentation">
                <a href="#display" aria-controls="profile" role="tab" data-toggle="tab">
                    <i class="icon-eye-open"></i>&nbsp;&nbsp;&nbsp;{l s='Display on pages' mod='custompopup'}
                </a>
            </li>
        </ul>

        <div class="pc-checker">
            {$VERSION_CHECKER nofilter}
        </div>

        <div class="pc-info">
            {include file='./extras/prestacraft.tpl'}
                <br /><br /><br />
            {include file='./extras/paypal.tpl'}
        </div>
    </div>

    <div class="col-md-8 col-lg-9 col-xs-12">
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="settings">{$TAB_SETTINGS nofilter}</div>
            <div role="tabpanel" class="tab-pane" id="customizestyle">
                <div class="row">
                <div class="col-xs-12 col-sm-6">{$TAB_CUSTOMIZE_STYLE nofilter}</div>
                <div class="col-xs-12 col-sm-6">{$TAB_CUSTOMIZE_CLOSE nofilter}</div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="display">{$TAB_DISPLAY nofilter}</div>
        </div>
    </div>
</div>