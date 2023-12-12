const Tooltip = {
    init: function () {
        $('.tipMe').on('keyup', Tooltip.revealTip).on('blur', Tooltip.hideTip)
            .parent().prepend($('<span class="rci-tool-tip">TAB or RETURN to accept</span><span class="rci-arrow"></span>').css('display', 'none'));
        Tooltip.count = 3;

    },
    revealTip: function (e) {
        if (Tooltip.count > 0 && e.target !== Tooltip.lastInput) {
            let input = $(e.target);

            if (input.offset().left < $(document).width() - input.offset().left - input.outerWidth()) {
                Tooltip.leftAlignTooltip(input);
            }
            else {
                Tooltip.rightAlignTooltip(input);
            }

            Tooltip.count--;
        }
        Tooltip.lastInput = e.target;
    },
    hideTip: function(e) {
        $(e.target).siblings('span').each(function (index, node) {
            $(node).css('display', 'none');
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
    },

    leftAlignTooltip(input) {
        let span = input.siblings('span');
        let tooltip = $(span[0]);
        let arrow = $(span[1]);
        arrow.css('display', 'block');
        tooltip.css('display', 'block');

        this.transform(arrow, this.centerX(arrow, input), this.bottomToTop(arrow, input) + 6);
        this.transform(tooltip, this.leftAlign(tooltip, input), this.bottomToTop(tooltip, arrow));
        },
    rightAlignTooltip(input) {
        let span = input.siblings('span');
        let tooltip = $(span[0]);
        let arrow = $(span[1]);
        arrow.css('display', 'block');
        tooltip.css('display', 'block');

        this.transform(arrow, this.centerX(arrow, input), this.bottomToTop(arrow, input) + 6);
        this.transform(tooltip, this.rightAlign(tooltip, input), this.bottomToTop(tooltip, arrow));
   }
}

$(document).ready(Tooltip.init);


