var $j = jQuery.noConflict();
(function($j) {
    $j(function() {
        var jcarousel = $j('.jcarousel');

        jcarousel
            .on('jcarousel:reload jcarousel:create', function () {
                var carousel = $j(this),
                    width = carousel.innerWidth();

                if (width >= 600) {
                    width = width / 3;
                } else if (width >= 350) {
                    width = width / 2;
                }

                carousel.jcarousel('items').css('width', Math.ceil(width) + 'px');
            })
            .jcarousel({
                wrap: 'circular'
            });

        $j('.jcarousel-control-prev')
            .jcarouselControl({
                target: '-=1'
            });

        $j('.jcarousel-control-next')
            .jcarouselControl({
                target: '+=1'
            });

        $j('.jcarousel-pagination')
            .on('jcarouselpagination:active', 'a', function() {
                $j(this).addClass('active');
            })
            .on('jcarouselpagination:inactive', 'a', function() {
                $j(this).removeClass('active');
            })
            .on('click', function(e) {
                e.preventDefault();
            })
            .jcarouselPagination({
                perPage: 1,
                item: function(page) {
                    return '<a href="#' + page + '">' + page + '</a>';
                }
            });
    });
})(jQuery);
