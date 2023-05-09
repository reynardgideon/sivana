$(document).ready(function () {
    var ajaxurl = 'https://sikad.knpk.xyz/wp-admin/admin-ajax.php';
    $('#post_comment').click(function () {
        $(this).addClass('is-loading');
        var data = {
            action: 'post_comment',
            pesan: tinymce.editors['comment_textarea'].getContent(),
            user: $(this).data('user'),
            root: $(this).data('ka')
        };

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: data,

            success: function (response) {
                if (response) {
                    $(this).removeClass('is-loading');
                    location.reload();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                } else {
                    $(this).removeClass('is-loading');
                }
            }
        });
    });  

});