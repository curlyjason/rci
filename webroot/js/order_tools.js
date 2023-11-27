
const OrderTools = {
    init: function () {
        $('input[name="filter"]').on('keyup', OrderTools.keypressHandler);
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
};

$(document).ready(OrderTools.init);
