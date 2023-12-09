const Tooltip = {
    init: function () {
        $('.tipMe').on('keyup', Tooltip.revealTip).on('blur', Tooltip.hideTip)
            .parent().prepend($('<span class="rci-tool-tip">TAB or RETURN to accept</span>').fadeOut(0));
        Tooltip.count = 3;

    },
    revealTip: function (e) {
        /**
         * read the coordinates of the input
         * set the position:absolute coordinates of the span and fade in
         * this could be done with a single tool tip
         * current left span position - .5 * input width
         * current top - 1.25 * span height
         */
        if (Tooltip.count > 0 && e.target !== Tooltip.lastInput) {
            let input = $(e.target);
            let inputWidthFactor = input.width() * .5;
            let span = input.siblings('span');
            let spanHeightFactor = span.height() * 1.25;
            span.fadeIn('slow');
            span.css('top', span.position().top - spanHeightFactor)
                .css('left', span.position().left - inputWidthFactor);
            Tooltip.count--;
        }
        Tooltip.lastInput = e.target;
    },
    hideTip: function(e) {
        $(e.target).siblings('span').fadeOut('fast');
    },
    count: 0,
    lastInput: null,
}

$(document).ready(Tooltip.init);
