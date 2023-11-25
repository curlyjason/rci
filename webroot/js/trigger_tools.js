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
    },
    stopFormSubmission: function () {
        $('form').submit(function(e){
            e.preventDefault();
        });
    },
};

$(document).ready(TriggerTools.init);
