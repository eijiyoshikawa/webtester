(function($) {
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
                    slidesToScroll: 1
                }
            }, {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
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

    // Header scroll effect
    $(window).scroll(function(){
        if ($(window).scrollTop() > 50) {
            $('header').addClass('is-scrolled');
        } else {
            $('header').removeClass('is-scrolled');
        }
    });

    // Fade in on scroll (supports all fadein variants)
    var animSelectors = '.fadein, .fadein-left, .fadein-right, .fadein-scale';
    $(function() {
        // Check on load for elements already in view
        $(animSelectors).each(function(){
            var elemPos = $(this).offset().top;
            var scroll = $(window).scrollTop();
            var windowHeight = $(window).height();
            if (scroll > elemPos - windowHeight + 100){
                $(this).addClass('scrollin');
            }
        });
        // Check on scroll
        $(window).scroll(function(){
            $(animSelectors).each(function(){
                var elemPos = $(this).offset().top;
                var scroll = $(window).scrollTop();
                var windowHeight = $(window).height();
                if (scroll > elemPos - windowHeight + 100){
                    $(this).addClass('scrollin');
                }
            });
        });
    });

    // Hamburger menu
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
