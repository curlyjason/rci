const Tooltip = {
    init: function () {
        $('.tipMe').on('keyup', Tooltip.revealTip).on('blur', Tooltip.hideTip)
            .parent().prepend($('<span class="rci-tool-tip">TAB or RETURN to accept</span><span class="rci-arrow"></span>').fadeOut(0));
        Tooltip.count = 3;

    },
    revealTip: function (e) {
        /**
         * read the coordinates of the input
         * set the position:absolute coordinates of the span and fade in
         * this could be done with a single tool tip
         * current left span position - .5 * input width
         * current top - 1.25 * span height
         *
         * tip-top = top of input - height of span - 10
         * arrow-top = top of input
         * both left = left input + .5 * width of input - .5 * width of span
         *
         * A comprehensive example with an arrow
         * https://blog.logrocket.com/creating-beautiful-tooltips-with-only-css/
         */
        if (Tooltip.count > 0 && e.target !== Tooltip.lastInput) {
            let input = $(e.target);
            let span = input.siblings('span');
            let tooltip = $(span[0]);
            let arrow = $(span[1]);
            tooltip.fadeIn('slow');
            arrow.fadeIn('fast');
            tooltip.css('top', Tooltip.topPosition(input, tooltip) - tooltip.height() - 10) + 'px'
                .css('left', Tooltip.leftCenteredPosition(input, tooltip) + 'px');
            arrow.css('top', Tooltip.topPosition(input, arrow) + 'px')
                .css('left', Tooltip.leftCenteredPosition(input, arrow) + 'px');

            Tooltip.count--;
        }
        Tooltip.lastInput = e.target;
    },
    hideTip: function(e) {
        $(e.target).siblings('span').each(function (index, node) {
            $(node).fadeOut('fast');
        });
    },
    count: 0,
    lastInput: null,
    topPosition: function (targetTop, element) {
        return $(element).offset().top + ($(targetTop).offset().top - $(element).offset().top);
    },
    leftCenteredPosition: function (targetLeft, element) {
        return $(element).offset().left + ($(target).offset().left - $(element).offset().left) + (.5 * $(target).width());
    },
}

$(document).ready(Tooltip.init);
