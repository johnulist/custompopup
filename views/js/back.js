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

$(".test").trigger("click");