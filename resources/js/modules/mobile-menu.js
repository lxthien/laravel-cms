/**
 * Mobile Menu Toggle Module (jQuery)
 * Hamburger menu open/close on mobile.
 */
$(function () {
    var $toggle = $('#mobile-menu-toggle');
    var $menu = $('#mobile-menu');
    var $iconOpen = $('#mobile-menu-icon-open');
    var $iconClose = $('#mobile-menu-icon-close');

    if (!$toggle.length || !$menu.length) return;

    $toggle.on('click', function () {
        var isOpen = !$menu.hasClass('is-hidden');

        $menu.toggleClass('is-hidden');
        $iconOpen.toggleClass('is-hidden');
        $iconClose.toggleClass('is-hidden');

        $toggle.attr('aria-expanded', !isOpen);
    });

    // Close on outside click
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#mobile-menu, #mobile-menu-toggle').length) {
            $menu.addClass('is-hidden');
            $iconOpen.removeClass('is-hidden');
            $iconClose.addClass('is-hidden');
            $toggle.attr('aria-expanded', 'false');
        }
    });
});
