(function($) {
    // Slick slider - 3 slides with depth effect
    $(function () {
        $(".slider").slick({
            arrows: false,
            autoplay: true,
            autoplaySpeed: 4000,
            speed: 800,
            centerMode: true,
            centerPadding: "12%",
            dots: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            focusOnSelect: true,
            cssEase: "cubic-bezier(0.25, 0.46, 0.45, 0.94)",
            responsive: [{
                breakpoint: 768,
                settings: {
                    centerPadding: "8%"
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

    // Fullscreen menu
    window.onload = function () {
        var nav = document.getElementById('nav-wrapper');
        var hamburger = document.getElementById('js-hamburger');
        if (hamburger) {
            hamburger.addEventListener('click', function () {
                nav.classList.toggle('open');
                document.body.style.overflow = nav.classList.contains('open') ? 'hidden' : '';
            });
        }
        // Close menu when a nav link is clicked
        var spLinks = document.querySelectorAll('.sp-nav a');
        spLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                nav.classList.remove('open');
                document.body.style.overflow = '';
            });
        });
    };
})(jQuery);
