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
        offset,
        jumpOffset;

    var scroll = function () {
        var scrollTop = $(window).scrollTop();
        if (scrollTop >= offset) {
            $navContainer.addClass('navbar-fixed-top');
            $body.addClass('fixed-navbar');
        } else {
            $navContainer.removeClass('navbar-fixed-top');
            $body.removeClass('fixed-navbar');
        }
        if ($jumpGroup.length) {
            if (scrollTop >= jumpOffset) {
                $jumpGroup.addClass('jump-fixed-top');
                $body.addClass('fixed-jump');
            } else {
                $jumpGroup.removeClass('jump-fixed-top');
                $body.removeClass('fixed-jump');
            }
        }
    };

    var onResize = function () {
        offset = $navContainer.offset().top;
        if ($jumpGroup.length) {
            jumpOffset = $jumpGroup.offset().top;
        }
    };

    var initNavAffix = function () {
        $(document).on('scroll', scroll);
        offset = $navContainer.offset().top;
        $body.scrollspy({ target: '.nav-container' });
        $(window).on('resize', onResize);
        scroll();
    };

	function smoothScroll(e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: $($(this).attr('href')).offset().top - 120
        }, 500);
    }

    var initJumpLinksInit = function () {
        if ($jumpGroup.length) {
            $body.scrollspy({ target: '.jump-group' });
            jumpOffset = $jumpGroup.offset().top - 50;
            $jumpGroup.find('.jump-group').on('click', 'a', smoothScroll);
        }
    };

    /**
     * Init
     */
    var init = function () {
        initNavAffix();
        initJumpLinksInit();
    };

    init();

});
