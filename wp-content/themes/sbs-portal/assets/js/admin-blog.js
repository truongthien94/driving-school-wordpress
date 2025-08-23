jQuery(document).ready(function ($) {
    // Use event delegation on the body to ensure the click handler works even if the button is re-rendered (common in Gutenberg).
    $('body').on('click', '#publish, .editor-post-publish-button__button', function (e) {
        var isGutenberg = typeof wp !== 'undefined' && wp.data && wp.data.select('core/editor');

        var title, excerpt, hasImage;

        if (isGutenberg) {
            var editor = wp.data.select('core/editor');
            title = editor.getEditedPostAttribute('title');
            excerpt = editor.getEditedPostAttribute('excerpt');
            hasImage = editor.getEditedPostAttribute('featured_media') > 0;
        } else { // Classic Editor
            title = $('#title').val();
            excerpt = $('#excerpt').val();
            // In the classic editor, a value of -1 means no image.
            hasImage = $('#_thumbnail_id').val() !== '-1';
        }

        var errors = [];
        if (!title || title.trim() === '') {
            errors.push('・Tiêu đề bài viết là bắt buộc.');
        }
        // if (!excerpt || excerpt.trim() === '') {
        //     errors.push('・Mô tả ngắn (trích đoạn) là bắt buộc.');
        // }
        if (!hasImage) {
            errors.push('・Ảnh đại diện là bắt buộc.');
        }

        if (errors.length > 0) {
            e.preventDefault(); // Stop the form from submitting
            alert('Vui lòng nhập các trường bắt buộc sau:\n\n' + errors.join('\n'));

            // Re-enable the UI so the user can make corrections.
            if (isGutenberg) {
                // In Gutenberg, we need to unlock the editor's saving state.
                wp.data.dispatch('core/editor').unlockPostSaving('sbs-validation-lock');
                $('.editor-post-publish-button__button').removeClass('is-busy');
            } else {
                $('#publishing-action .spinner').hide();
                $('#publish').removeClass('button-primary-disabled');
            }
            return false;
        }
    });
});
