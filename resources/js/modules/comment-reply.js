/**
 * Comment Reply Module (jQuery)
 * Click "Trả lời" → set parent_id, scroll to comment form.
 */
$(function () {
    $(document).on('click', '.reply-btn', function () {
        var parentId = $(this).data('id');
        var $form = $('#commentForm');

        $('#parent_id').val(parentId);

        if ($form.length) {
            $('html, body').animate({ scrollTop: $form.offset().top - 100 }, 400);
        }
    });
});
