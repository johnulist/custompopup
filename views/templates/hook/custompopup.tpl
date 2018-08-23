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

{if $popup_enabled}
    {literal}
        <script>
            {/literal}{if !$popup_cookie && $popup_cookie == 0}{literal}
                prestacraftDeleteCookie('responsive_popup');
            {/literal}{/if}{literal}

            if (prestacraftGetCookie('responsive_popup') != 'yes') {

                {/literal}{if $popup_delay > 0}{literal}
                setTimeout(function(){
                {/literal}{/if}{literal}
                    var modal = new tingle.modal({
                        footer: true,
                        stickyFooter: false,
                        closeMethods: ['button'],
                        closeLabel: "Close",
                        cssClass: ['custom-class-1', 'custom-class-2'],
                        onOpen: function() {
                        },
                        onClose: function() {
                            {/literal}{if $popup_cookie && $popup_cookie > 0}{literal}
                            prestacraftSetCookie('responsive_popup', 'yes', {/literal}{$popup_cookie*0.000694}{literal});
                            {/literal}{/if}{literal}
                        },
                        beforeClose: function() {
                            return true; // close the modal
                            return false; // nothing happens
                        }
                    });

                    var content = '{/literal}{$content_{Context::getContext()->language->id}|unescape: "html" nofilter}{literal}';
                    // set content
                    modal.setContent(content);

                    // add a button
                    modal.addFooterBtn('x', 'prestacraft-close', function() {
                        modal.close();
                    });

                    // add another button
                    modal.addFooterBtn('Dangerous action !', 'tingle-btn tingle-btn--danger', function() {
                        modal.close();
                    });

                    modal.open();
                    {/literal}{if $popup_delay > 0}{literal}
                },  {/literal}{$popup_delay*1000}{literal});
                {/literal}{/if}{literal}

            }
        </script>
    {/literal}
{/if}
{literal}<style>
        div.popup {
            background-color:{/literal} {$popup_color}{literal};
            padding:{/literal} {$padding}{literal}px;
            padding-top:{/literal} {$top_padding}{literal}px;
        }

        .popup_back {
            background-color: {/literal}{$back_color}{literal};
        }
        .popup_close:hover {
            color: {/literal}{$button_hover_color}{literal};
        }
        .popup_close {
            color: {/literal}{$button_color}{literal};
            top: {/literal}{$button_top_padding}{literal}px;
            font-size: {/literal}{$button_size}{literal}px;
            {/literal}{$button_position}{literal}: 5px;
        }
    </style>
{/literal}


<div id="inline" style="display:none">

</div>
