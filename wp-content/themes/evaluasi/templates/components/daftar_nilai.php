<?php

include_once(get_template_directory() . '/templates/components/table.php');

function page_daftar_nilai()
{
?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
    <?php
    $json_url = get_template_directory_uri() . '/data/daftar_nilai.php?id_pelatihan=' . get_the_id();
    $json = file_get_contents($json_url);
    $obj = json_decode($json);

    if (count($obj->data) > 0) {
        tabel_objek_pelatihan();
    } else {
    ?>
        <div class="text-center">
            <button type="button" id="generate_daftar_nilai" style="width: 200px;" class="btn btn-primary">Generate Daftar Nilai</button>
            <div class="alert alert-primary mt-3" role="alert">
                Sebelum generate Daftar Nilai, pastikan data Mata Pelajaran dan data Peserta telah diinput!
            </div>
        </div>
        <script>
            $(document).ready(function() {
                var ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>';

                $('#generate_daftar_nilai').click(function() {

                    $(this).html('<i class="fa fa-spinner fa-spin"></i>');

                    var data = {
                        action: 'generate_daftar_nilai',
                        id_pelatihan: '<?= get_the_id() ?>'
                    };

                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            tata.success(response.message, '', {
                                position: 'tm',
                                duration: 2000
                            });
                            location.reload();
                        }
                    });

                });
            });
        </script>
<?php
    }
}
?>