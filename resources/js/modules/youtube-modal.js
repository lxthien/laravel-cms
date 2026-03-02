/**
 * YouTube Modal Lightbox Component
 */

import $ from 'jquery';

$(function () {
    const modal = $('#youtubeModal');
    const container = $('#youtubeVideoContainer');

    // Open Modal & Inject iframe
    $('.video-card').on('click', function () {
        const videoId = $(this).data('youtube-id');
        if (videoId) {
            const iframe = `<iframe src="https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
            container.html(iframe);
            modal.addClass('is-open');
            $('body').css('overflow', 'hidden'); // Prevent background scrolling
        }
    });

    // Close Modal & Remove iframe
    $('#closeYoutubeModal, .youtube-modal').on('click', function (e) {
        // Close if clicked outside the video content or on the close button
        if (e.target === this || $(e.target).hasClass('modal-close')) {
            modal.removeClass('is-open');
            container.empty(); // Stops the video
            $('body').css('overflow', '');
        }
    });
});
