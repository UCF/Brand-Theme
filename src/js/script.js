// Define globals for JSHint validation:
/* global console */

// Theme Specific Code Here

$(function () {

    /**
     * Navigation Affix
     */
    var $body = $('body'),
        $navContainer = $('.nav-container'),
        $jumpGroup = $('.jump-group-container'),
        offset = $navContainer.offset().top,
        jumpOffset = $jumpGroup.offset().top - 50;

    var scroll = function () {
        var scrollTop = $(window).scrollTop();
        if (scrollTop >= offset) {
            $navContainer.addClass('navbar-fixed-top');
            $body.addClass('fixed-navbar');
        } else {
            $navContainer.removeClass('navbar-fixed-top');
            $body.removeClass('fixed-navbar');
        }
        if (scrollTop >= jumpOffset) {
            $jumpGroup.addClass('jump-fixed-top');
            $body.addClass('fixed-jump');
        } else {
            $jumpGroup.removeClass('jump-fixed-top');
            $body.removeClass('fixed-jump');
        }
    };

    var onResize = function () {
        offset = $navContainer.offset().top;
        jumpOffset = $jumpGroup.offset().top;
    };

    var initNavAffix = function () {
        $(document).on('scroll', scroll);
        $body.scrollspy({ target: '.nav-container' });
        $body.scrollspy({ target: '.jump-group' });
        $(window).on('resize', onResize);
        scroll();
    };

	function smoothScroll(e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: $($(this).attr('href')).offset().top - 120
        }, 500);
    }

    /**
     * Init
     */
    var init = function () {
        initNavAffix();
        $('.jump-group').on('click', 'a', smoothScroll);
    };

    init();

});
