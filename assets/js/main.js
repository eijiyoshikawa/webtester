/**
 * 翔星建設株式会社 - Main JavaScript
 */
(function($) {
    // Hamburger menu
    $(function () {
        $('#nav-toggle').on('click', function() {
            $('body').toggleClass('open');
        });
    });

    $('#gloval-nav a[href]').on('click', function(event) {
        $('#nav-toggle').trigger('click');
    });

    // Slick slider
    $(function () {
        $(".slider").slick({
            arrows: false,
            autoplay: true,
            adaptiveHeight: true,
            centerMode: true,
            centerPadding: "10%",
            dots: false,
            slidesToShow: 3,
            responsive: [{
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                }
            }, {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            }]
        });
    });

    // Smooth scroll
    $(function(){
        $('a[href^="#"]').click(function() {
            var speed = 500;
            var href = $(this).attr("href");
            var target = $(href == "#" || href == "" ? 'html' : href);
            var position = target.offset().top;
            $('html').animate({scrollTop:position}, speed, 'swing');
            return false;
        });
    });

    // Fade in on scroll
    $(function() {
        $(window).scroll(function(){
            $('.fadein').each(function(){
                var elemPos = $(this).offset().top;
                var scroll = $(window).scrollTop();
                var windowHeight = $(window).height();
                if (scroll > elemPos - windowHeight + 150){
                    $(this).addClass('scrollin');
                }
            });
        });
    });

    // Hamburger menu toggle
    window.onload = function () {
        var nav = document.getElementById('nav-wrapper');
        var hamburger = document.getElementById('js-hamburger');
        var blackBg = document.getElementById('js-black-bg');

        if (hamburger) {
            hamburger.addEventListener('click', function () {
                nav.classList.toggle('open');
            });
        }
        if (blackBg) {
            blackBg.addEventListener('click', function () {
                nav.classList.remove('open');
            });
        }
    };
})(jQuery);
