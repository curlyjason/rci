const InventoryTools = {
    initialize: function () {
        InventoryTools.attachQuantityChangeHandlers(InventoryTools.getQuantityInputs());
        InventoryTools.attachOkButtonHanders(InventoryTools.getOkButtons());
        InventoryTools.updateInventoryInstructions();
    },

    getQuantityInputs: function () {
        return $('input[name="quantity"]');
    },
    attachQuantityChangeHandlers: function (inputs) {
        inputs.each(function (index, input) {
            $(input).on('change', InventoryTools.quantityChangeHandler);
        });
    },
    quantityChangeHandler: function (e) {
        $(e.target).parents('form').submit(function(e){
            e.preventDefault();
        });
        Tooltip.hideTip(e);
        let postData = InventoryTools.preparePostData(e.target)
        $.post(
            "api/set-inventory.json", //url
            postData,
            function(data, status){ // callback to handle response
                if (data.error !== undefined) {
                    alert(data.error);
                }
                else {
                    InventoryTools.moveRowToComplete(postData.id)
                }
            });
    },

    getOkButtons: function () {
        return $('.ok-button');
    },
    attachOkButtonHanders: function (buttons) {
        buttons.each(function (index, button) {
            $(button).on('click', InventoryTools.okButtonHandler);
        });
    },
    okButtonHandler: function (e) {
        $(e.target).parents('form').find('input[name="quantity"]').trigger('change');
    },

    preparePostData: function (input) {
        let f = $(input).parents('form');
        return {
            "id": f.attr('id'),
            "_csrfToken": f.find('input[name="_csrfToken"]').val(),
            "quantity": $(input).val(),
        }
    },
    moveRowToComplete: function (id) {
        let row = $('tr[id="' + id + '"]');
        let tableBody = $('tbody.complete');
        tableBody.prepend(row);
        InventoryTools.updateInventoryInstructions();
    },
    updateInventoryInstructions: function() {
        let $todoRows = $('tbody.todo').children('tr').length;
        if ($todoRows > 0) {
            $('#open').removeClass('hide');
            $('#nextDate').addClass('hide');
            $('#doneHeader').addClass('hide');
            $('#todoHeader').removeClass('hide');
            $('div.todo').removeClass('hide');
        } else {
            $('#open').addClass('hide');
            $('#nextDate').removeClass('hide');
            $('#doneHeader').removeClass('hide');
            $('#todoHeader').addClass('hide');
            $('div.todo').addClass('hide');
        }
    }
};

$(document).ready(InventoryTools.initialize);
