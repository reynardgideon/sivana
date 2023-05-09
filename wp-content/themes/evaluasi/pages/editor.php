<?php

function editor_tambah_peserta2()
{
    echo 'xxx';
    $obj = 'peserta';
    $form = '<form id="form_'.$obj.'" action="" method="POST" class="needs-validation">';
    
    foreach (FIELDS_PESERTA as $label => $type) {
            $slug = str_replace(' ', '_', strtolower($label));
            $form .= create_form_input($type, $label);
    }    
    $form .= '</form>';
    editor_page($obj, $form);
    editor_script($obj);
}

function editor_page($obj, $content)
{
?>
    <div class="card">
        <div class="card-header has-background-kmk-mix has-text-centered">
            <h5><?= strtoupper('FORM '.$obj) ?></h5>
            <div class="card-header-right">
                <a href="<?= get_the_permalink() ?>">
                    <i class="ti ti-angle-double-left" title="Kembali"></i>
                </a>
            </div>
        </div>
        <div class="card-content p-4">
            <?= $content ?>
        </div>
    </div>
<?php
}

function editor_input($type, $label)
{
    $name = str_replace(' ', '_', strtolower($label));
    $r = '';
    switch ($type) {
        case 'text':
            $r = '
            <div class="form-group">
                <label for="' . $name . '">' . $label . '</label>
                <input name="' . $name . '" value="" type="' . $type . '" class="form-control" id="' . $name . '" required>                
            </div>';
            break;
    }
    return $r;
}

function editor_script($obj)
{
?>
    <script type='text/javascript'>
        $(document).ready(function() {
            var ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>';
            $('#form_<?= $obj ?>').on('submit', function(e) {
                e.preventDefault();
                $('#submit_button').html('<i class="fa fa-spinner fa-spin"></i>');
                var data = $('#form_<?= $obj ?>').serialize();
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.status == 1) {
                            tata.success(response.message, '', {
                                position: 'tm',
                                duration: 2000
                            });
                            $('#form_<?= $obj ?>').get(0).reset();
                        } else {
                            tata.error(response.message, '', {
                                position: 'tm',
                                duration: 2000
                            });
                        }
                        $('#submit_button').html('Submit');
                    }
                });
            });

            $('#back').click(function() {
                $(this).html('<i class="fa fa-spinner fa-spin"></i>');
                window.location.href = '<?php echo get_the_permalink(); ?>';
            });
        });
    </script>
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
<?php
}
?>