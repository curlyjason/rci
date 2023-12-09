/**
 * JS tools for Set Trigger Levels page
 *
 * Planned Behavior
 * --------------------------------
 * Show the available items in a tight list with trigger value input
 * Cursor will autofocus in the filter-string input
 * Typing will create a filter-string that will reduce the list of available items
 * Changing an item's trigger value will update the db
 *
 * Data Structures and their use
 * --------------------------------
 * master search object
 *      {
 *          customers_items.id: item.name + item.description + items_vendor.sku
 *      }
 *  This will allow matching of any recorded data the user remembers for the item
 *  and will let the filter function return a customer_item.id as output
 *  This can be sent as an HTML select/option set or as a global scope json object
 *
 * on screen item list
 *  This will be some responsive html list display optimized for tight vertical space.
 *  Each row will be a separate form showing a trigger-value input with onChange behavior.
 *  Each row will be id with customer_item.id so that filter function results
 *  can be used to modify row display (toggle none/block)
 *
 * @type {{init: TriggerTools.init}}
 */
const TriggerTools = {
    init: function () {
        TriggerTools.stopFormSubmission();
        $('input[name="filter"]').on('keyup', TriggerTools.keypressHandler);
        TriggerTools.attachQuantityChangeHandlers(TriggerTools.getQuantityInputs());
    },

    stopFormSubmission: function () {
        $('form').submit(function(e){
            e.preventDefault();
        });
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

    getQuantityInputs: function () {
        return $('input[name="target_quantity"]');
    },
    attachQuantityChangeHandlers: function (inputs) {
        inputs.each(function (index, input) {
            $(input).on('change', TriggerTools.quantityChangeHandler);
        });
    },
    quantityChangeHandler: function (e) {
        $(e.target).parents('form').submit(function(e){
            e.preventDefault();
        });
        let postData = TriggerTools.preparePostData(e.target)
        $.post(
            "api/set-trigger.json", //url
            postData,
            function(data, status){ // callback to handle response
                if (data.error !== undefined) {
                    // alert(data.error);
                }
                else {
                    // alert('trigger inventory level changed')
                }
            });
    },

    preparePostData: function (input) {
        let f = $(input).parents('form');
        return {
            "id": f.attr('id'),
            "_csrfToken": f.find('input[name="_csrfToken"]').val(),
            "target_quantity": $(input).val(),
        }
    },

};

$(document).ready(TriggerTools.init);
