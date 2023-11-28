
const OrderTools = {
    init: function () {
        $('input[name="filter"]').on('keyup', OrderTools.keypressHandler);
        OrderTools.setOrderLineToggleListeners(OrderTools.getOrderLineToggles());
        $('div.submit').addClass('hide');
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
        OrderTools.checkSubmitButtonVisiblity();
    },

    checkSubmitButtonVisiblity: function() {
        let inputs = $('tr[id|="ol"][class!="hide"] input.order_quantity');
        let result = true;
        inputs.each(function(index,input) {
            let value = $(input).val();
            let number_length = value.match(/[0-9]*/)[0].length
            let raw_length = value.length;
            result = result && value !== '' && raw_length === number_length;
        })
        if (result) {
            $('div.submit').removeClass('hide');
        }
        else {
            $('div.submit').addClass('hide');
        }
    }
};

$(document).ready(OrderTools.init);
