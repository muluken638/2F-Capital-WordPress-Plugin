jQuery(document).ready(function ($) {
    $('.approve-button').on('click', function () {
        const button = $(this);
        const postId = button.data('post-id');
        const authorId = button.data('author-id');

        $.ajax({
            url: ajaxurl, // WordPress AJAX URL
            type: 'POST',
            data: {
                action: 'approve_article',
                post_id: postId,
                author_id: authorId,
            },
            success: function (response) {
                if (response.success) {
                    button.closest('tr').find('td:nth-child(3)').text('Published'); // Update the status in the table
                    alert('Article approved successfully!');
                } else {
                    alert('Error: ' + response.data.message);
                }
            },
        });
    });
});



