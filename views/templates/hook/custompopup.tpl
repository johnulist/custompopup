{*
 * This software is provided "as is" without warranty of any kind.
 *
 * Made by PrestaCraft
 *
 * Visit my website (http://prestacraft.com) for future updates, new articles and other awesome modules.
 *
 * @author     PrestaCraft
 * @copyright  2015-2017 PrestaCraft
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

{if $popup_enabled}
{literal}
    <script>
        $(function() {
            {/literal}{if $popup_delay > 0}{literal}
            setTimeout(function(){
            {/literal}{/if}{literal}
                var popup = new $.Popup();
                if ($.cookie('responsive_popup') == null) {
                    popup.open('#inline');
                    {/literal}{if $version == "1.7"}{literal}
                        $.ajax({
                            url: "{/literal}{$ajaxpath}{literal}",
                            type: "post",
                            data: {
                            },
                            success: function (response) {
                                $(".popup_content").html(response);
                                $(window).trigger('resize');
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                            }
                        });
                    {/literal}{/if}{literal}
                }
            {/literal}{if $popup_delay > 0}{literal}
            },  {/literal}{$popup_delay*1000}{literal});
            {/literal}{/if}{literal}

            $(".popup_close").click(function(){
                $.cookie('responsive_popup', 'yes', { expires: {/literal}{$popup_cookie*0.000694}{literal}, path: '/' });
            });

            var instances = $('.popup').length;
            if(instances > 1)
            {
                $( ".popup" ).last().remove();
                $( ".popup_back" ).last().remove();
                $( ".popup_close" ).last().remove();
            }
        });
    </script>{/literal}
{/if}
{literal}<style>
        div.popup {
            background-color:{/literal} {$popup_color}{literal};
            padding:{/literal} {$padding}{literal}px;
            padding-top:{/literal} {$top_padding}{literal}px;
        }
        div.popup img {
            max-width: 100%;
            height: auto;
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
    {$content_{Context::getContext()->language->id}}
</div>
