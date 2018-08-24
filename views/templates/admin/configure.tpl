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

<script src="{$colorpicker_path}"></script>
<script>
    document.addEventListener('click', function (event) {
        var alertElement = findUpTag(event.target, '.pc-alert');

        if (alertElement) {
            alertElement.style.display = 'none';
        }
    });

    function findUpTag(el, selector) {
        if (el.matches(selector)) {
            return el;
        }

        while (el.parentNode) {
            el = el.parentNode;
            if (el.matches && el.matches(selector)) {
                return el;
            }
        }
        return null;
    }
</script>

{if isset($errors)}
    <div class="pc-alert pc-errors">
        {$errors}
        <br />
        <small>Click to dismiss</small>
    </div>
{/if}

{if isset($success)}
    <div class="pc-alert pc-success">
        {$success}
        <br />
        <small>Click to dismiss</small>
    </div>
{/if}


<div role="tabpanel" class="prestacraft">
    <!-- Nav tabs -->
    <div class="col-lg-3 col-md-4 col-xs-12 prestacraft-left">
        <div class="logo-container">
            <img src="{$module_dir}/views/img/pc_logo.png" class="pc-logo">
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
            {$VERSION_CHECKER}
        </div>

        <div class="pc-info">
            {include file='./extras/prestacraft.tpl'}
                <br /><br /><br />
            {include file='./extras/paypal.tpl'}
                <br /><br />
        </div>
    </div>

    <div class="col-md-8 col-lg-9 col-xs-12">
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="settings">{$TAB_SETTINGS}</div>
            <div role="tabpanel" class="tab-pane" id="customizestyle">
                <div class="row">
                <div class="col-xs-12 col-sm-6">{$TAB_CUSTOMIZE_STYLE}</div>
                <div class="col-xs-12 col-sm-6">{$TAB_CUSTOMIZE_CLOSE}</div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="display">{$TAB_DISPLAY}</div>
        </div>
    </div>
</div>