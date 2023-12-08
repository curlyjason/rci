
const OrderTools = {
    init: function () {
        $('input[name="filter"]').on('keyup', OrderTools.keypressHandler)
            .on('keydown', OrderTools.filterKeyDownHandler);
        OrderTools.setOrderLineToggleListeners(OrderTools.getOrderLineToggles());
        $('div.submit').addClass('hide');
        OrderTools.setOrderLineInputListeners(OrderTools.getOrderLineInputs());
        OrderTools.checkSubmitButtonVisiblity();
        OrderTools.setRadioListeners();
    },

    filterKeyDownHandler: function (e) {
        if ($(e.target).val() === '') {
            let radios = $('input[type="radio"]');
            let allItemMode = radios[0];
            $(allItemMode).trigger('click')
        }
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
        let itemRow = $($(chosenButton).parents('tr')[0]);
        let quantityInput = itemRow.find('input.order_quantity');

        $(chosenButton).addClass('hide');
        $(hiddeButton).removeClass('hide');

        if (chosenButton.textContent.match(/add/i)) {
            itemRow.addClass('order');
            quantityInput.removeClass('hide');
        } else {
            itemRow.removeClass('order');
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

    setRadioListeners: function() {
        let radios = $('input[type="radio"]');
        radios.on('change', OrderTools.radioChanged);
        let checked = $('input[type="radio"]:checked');
        let allItemMode = radios[0];
        if(checked.length === 0) {
            $(allItemMode).attr('checked', true);
        }
    },
    radioChanged: function(){
        let checked = $('input[type="radio"]:checked');
        if (checked.val() === '1') {
            $('input[name="filter"]').val('').trigger('keyup');
            $('tr[class!="order"]').addClass('hide');
        }
        else {
            $('input[name="filter"]').val('').trigger('keyup');
            $('tr[class!="order"]').removeClass('hide');
        }
    }
};

$(document).ready(OrderTools.init);
