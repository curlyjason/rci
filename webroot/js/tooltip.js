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
         * syntax for jquery.css('transform')
         * tooltip.css('transform', 'translate(-57px, -26px)') //(x, y)
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
            arrow.fadeIn('fast');
            tooltip.fadeIn('slow');
            // tooltip.css('top', Tooltip.topPosition(input, tooltip) - tooltip.height() - 10) + 'px'
            //     .css('left', Tooltip.leftCenteredPosition(input, tooltip) + 'px');
            // arrow.css('top', Tooltip.topPosition(input, arrow) + 'px')
            //     .css('left', Tooltip.leftCenteredPosition(input, arrow) + 'px');

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
        return $(element).offset().left + ($(targetLeft).offset().left - $(element).offset().left) + (.5 * $(targetLeft).width());
    },
    top: function(node) {return $(node).offset().top},
    bottom: function(node) {return $(node).outerHeight() + $(node).offset().top},
    left: function(node) {return $(node).offset().left},
    right: function(node) {return $(node).outerWidth() + $(node).offset().left},
    centerX: function(nodeToMove, fixedNode) {
        return this.leftAlign(nodeToMove, fixedNode) + (.5 * ($(fixedNode).outerWidth() - $(nodeToMove).outerWidth()))
    },
    leftAlign: function(nodeToMove, fixedNode) {
        return (this.left(fixedNode) - this.left(nodeToMove));
    },
    rightAlign: function(nodeToMove, fixedNode) {
        return (this.right(fixedNode) - this.right(nodeToMove));
    },
    topAlign: function(nodeToMove, fixedNode) {

    },
    transform: function(node, x, y) {
        $(node).css('transform', `translate(${x}px, ${y}px`);
    },
    topToBottom: function (nodeToMove, fixedNode) {
        return this.bottom(fixedNode) - this.top(nodeToMove);
    },
    bottomToTop: function (nodeToMove, fixedNode) {
        return this.top(fixedNode) - this.bottom(nodeToMove);
    },
    stuff: function () {
        let input = $('#quantity-1825338435');
        let span = input.siblings('span');
        let tooltip = $(span[0]);
        let arrow = $(span[1]);
        arrow.fadeIn('fast');
        tooltip.fadeIn('slow');
    }

}

$(document).ready(Tooltip.init);


