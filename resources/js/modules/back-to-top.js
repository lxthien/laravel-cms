/**
 * Back to top functionality
 */

import $ from 'jquery';

$(function () {
    var btn = $('#backToTopBtn');

    $(window).scroll(function () {
        if ($(window).scrollTop() > 300) {
            btn.addClass('is-visible');
        } else {
            btn.removeClass('is-visible');
        }
    });

    btn.on('click', function (e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, '300');
    });
});
