
const OrderTools = {
    init: function () {
        $('input[name="filter"]').on('keyup', OrderTools.keypressHandler);
        OrderTools.setOrderLineToggleListeners(OrderTools.getOrderLineToggles());
        $('div.submit').addClass('hide');
        OrderTools.setOrderLineInputListeners(OrderTools.getOrderLineInputs());
        OrderTools.checkSubmitButtonVisiblity();
    },

    keypressHandler: function(e) {
        let regex = new RegExp( e.target.value,'i' );
        for (let key in itemMap) {
            let row = $('tr#' + key);
            if(itemMap[key].match(regex)) {
                row.removeClass('hide');
            }
            else {
                row.addClass('hide');
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
        let chosenButton = e.target;
        let hiddeButton = $(chosenButton).siblings('button')[0];
        let quantityInput = $($(chosenButton).parents('tr')[0]).find('input.order_quantity');

        $(chosenButton).addClass('hide');
        $(hiddeButton).removeClass('hide');

        if (chosenButton.textContent.match(/add/i)) {
            quantityInput.removeClass('hide');
        } else {
            quantityInput.val('');
            quantityInput.addClass('hide');
        }
        OrderTools.checkSubmitButtonVisiblity();
    },

    checkSubmitButtonVisiblity: function() {
        let inputs = $('input.order_quantity:visible');
        let result = inputs.length > 0;
        inputs.each(function(index,input) {
            let value = $(input).val();
            let number_length = value.match(/[0-9 ]*/)[0].length
            let raw_length = value.length;
            result = result && value !== '' && raw_length === number_length;
        })
        if (result) {
            $('div.submit').removeClass('hide');
        }
        else {
            $('div.submit').addClass('hide');
        }
    },

    getOrderLineInputs: function () {
        return $('.order_quantity')
    },
    setOrderLineInputListeners: function (inputs) {
        $(inputs).each(function(index, input) {
            $(input).on('change', OrderTools.checkSubmitButtonVisiblity);
        });
    },
    numbersOnly: function(e) {

    },
};

$(document).ready(OrderTools.init);
