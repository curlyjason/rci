
const OrderTools = {
    init: function () {
        $('input[name="filter"]').on('keyup', OrderTools.keypressHandler);
        OrderTools.setOrderLineToggleListeners(OrderTools.getOrderLineToggles());
    },

    keypressHandler: function(e) {
        for (let key in itemMap) {
            if(itemMap[key].match(e.target.value)) {
                $('tr#' + key).removeClass('hide');
            }
            else {
                $('tr#' + key).addClass('hide');
            }
        }
    },
    getOrderLineToggles: function () {
        return $('button.toggleOnOrder');
    },
    setOrderLineToggleListeners: function (togglers) {
        $(togglers).each(function(index, toggle) {
            $(toggle).on('click', OrderTools.toggleOrderLine);
        });
    },
    toggleOrderLine: function(e) {
        let id = $(e.target).attr('data-target');
        if (e.target.textContent.match(/add/i)) {
            $('tr' + id).removeClass('hide');
        } else {
            $('tr' + id).addClass('hide');
        }
    },
};

$(document).ready(OrderTools.init);
