const InventoryTools = {
    initialize: function () {
        InventoryTools.attachQuantityChangeHandlers(InventoryTools.getQuantityInputs());
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
        let postData = InventoryTools.preparePostData(e.target)
        $.post(
            "http://localhost:8015/api/set-inventory.json", //url
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
    moveRowToComplete: function (id) {
        let row = $('tr[id="' + id + '"]');
        let tableBody = $('tbody.complete');
        tableBody.append(row);
    },
    preparePostData: function (input) {
        let f = $(input).parents('form');
        return {
            "id": f.attr('id'),
            "_csrfToken": f.find('input[name="_csrfToken"]').val(),
            "quantity": $(input).val(),
        }
    },
};

$(document).ready(InventoryTools.initialize);
