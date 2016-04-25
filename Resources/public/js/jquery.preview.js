(function () {

    var sizeHandler = function (e, element, options) {
        element = $(element);

        if (!element.data('natural-width') || !element.data('natural-height')) {
            return {
                width: null,
                height: null,
                class: ''
            };
        }

        var naturalWidth = element.data('natural-width');
        var naturalHeight = element.data('natural-height');

        var pageX = e.pageX;
        var pageY = e.pageY;

        var width = $(window).width();
        var height = $(window).height();

        var availableWidth;
        if (pageX > width / 2) {
            availableWidth = pageX - options.offsetX;
        } else {
            availableWidth = width - pageX - options.offsetX;
        }

        var availableHeight = height - pageY + options.offsetY;

        if (availableWidth > naturalWidth && availableHeight > naturalHeight) {
            return {
                width: naturalWidth,
                height: naturalHeight,
                class: ''
            };
        }

        var imageRatio = naturalWidth / naturalHeight;
        var availableRatio = availableWidth / availableHeight;

        if (availableRatio > imageRatio) {
            return {
                width: null,
                height: availableHeight,
                class: 'max-height'
            };
        }

        return {
            width: availableWidth,
            height: null,
            'class': 'max-width'
        };
    };

    $.preview = function (options) {
        options = $.extend(true, {}, $.preview.defaults, options);

        $(document).on('mouseover', options.selector, function (e) {
            var url = $(this).data('preview');

            var element = $('<div class="preview-container"><div class="wrapper"><img src="' + url + '" /></div></div>');

            $(this).data('preview-element', element);

            var that = $(this);

            element.find('img').load(function () {
                if (that.data('natural-width') && that.data('natural-height')) {
                    return;
                }
                that.data('natural-width', this.naturalWidth);
                that.data('natural-height', this.naturalHeight);
            });

            $('body').append(element);

            element
                .css('top', (e.pageY - options.offsetX) + 'px')
                .css('left', (e.pageX + options.offsetY) + 'px')
                .fadeIn('fast');
            options.handlers.open.apply(this, [e, options]);
        }).on('mouseout', options.selector, function (e) {
            var element = $(this).data('preview-element');
            $(element).remove();
            $(this).data('preview-element', null);
            options.handlers.close.apply(this, [e, options]);
        }).on('mousemove', options.selector, function (e) {
            var element = $(this).data('preview-element');
            if (element) {
                element.css('top', (e.pageY - options.offsetY) + 'px');

                var pageX = e.pageX;
                var width = $(window).width();
                if (pageX > width / 2) {
                    element.css('right', (width - pageX + options.offsetX) + 'px')
                        .css('left', 'auto')
                        .find('img').css('float', 'right');
                } else {
                    element.css('left', (pageX + options.offsetX) + 'px')
                        .css('right', 'auto')
                        .find('img').css('float', 'left');
                }

                var size = options.handlers.size(e, this, options);

                element.css('width', '')
                    .css('height', '')
                    .removeClass('max-height').removeClass('max-width')
                    .addClass(size.class);
                if (size.width) {
                    element.css('width', size.width + 'px');
                }
                if (size.height) {
                    element.css('height', size.height + 'px');
                }

                options.handlers.hover.apply(this, [e, options, size]);
            }
        });
    };

    $.preview.defaults = {
        selector: '[data-preview]',
        offsetX: 15,
        offsetY: 5,
        handlers: {
            size: sizeHandler,
            open: function() {
                //
            },
            close: function(event, options) {
                //
            },
            hover: function(event, options, size) {
                // size as provided by the size handler
            }
        }
    };

})(jQuery || Zepto);