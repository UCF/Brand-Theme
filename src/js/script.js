// Define globals for JSHint validation:
/* global console */

// Theme Specific Code Here

$(function () {

    /**
     * Navigation Affix
     */
    var $navContainer = $('.nav-container'),
        $menu = $navContainer,
        offset = $navContainer.offset().top;

    var scroll = function() {
        if ($(window).scrollTop() >= offset) {
            $menu.addClass('navbar-fixed-top');
            $('body').addClass('fixed-navbar');
        } else {
            $menu.removeClass('navbar-fixed-top');
            $('body').removeClass('fixed-navbar');
        }
    };

    var onResize = function() {
        offset = $navContainer.offset().top;
    };

    var initNavAffix = function() {
        $(document).on('scroll', scroll);
        $('body').scrollspy({ target: '.nav-container' });
        $(window).on('resize', onResize);
        scroll();
    };

    /**
     * Init
     */
    var init = function () {
        initNavAffix();
    };

    init();

});
