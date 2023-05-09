<?php

/**
 * Template Name: Pengajar
 *
 * @package WordPress
 */
?>
<!DOCTYPE html>
<html lang="en">
<?php get_header(); ?>

<body>
    <?php get_template_part('loader'); ?>
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            <?php get_template_part('nav'); ?>
            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <?php get_sidebar(); ?>
                    <div class="pcoded-content">
                        <?php get_template_part('breadcrumb'); ?>
                        <div class="pcoded-inner-content">
                            <!-- Main-body start -->
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <!-- Page-body start -->
                                    <div class="page-body">
                                        <?php
                                        if (isset($_GET['action'])) {
                                            switch ($_GET['action']) {
                                                case 'tambah':
                                                    if (isset($_GET['editor']) && $_GET['editor'] == 'bulk') {
                                                        editor_bulk('pengajar', null);
                                                    } else {
                                                        editor_single('pengajar', null, null);
                                                    }
                                                    break;
                                                case 'ubah':
                                                    editor_single('pengajar', $_GET['id_pengajar'], null);
                                                    break;
                                            }
                                        } else {
                                            halaman_pengajar();
                                        }
                                        ?>
                                    </div>
                                    <!-- Page-body end -->
                                </div>
                                <div id="styleSelector"> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php get_footer(); ?>
</body>

</html>

<?php

function halaman_pengajar()
{
?>
    <div class="card">
        <div class="card-header has-background-kmk-mix has-text-centered">
            <h5>DAFTAR PENGAJAR</h5>
            <div class="card-header-right">
                <a href="?action=tambah"><i class="fa fa-plus" title="Tambah"></i></a>
                <a class="bulk_editor" id="bulk_editor" href="#"><i class="fa fa-table" title="Bulk Editor"></i></a>
                <a class="ubah" id="ubah" href="#"><i class="fa fa-pencil" title="Ubah"></i></a>
                <i class="hapus fa fa-trash" title="Hapus" id="hapus"></i>
                <i class="fa fa-cog" title="Pengaturan"></i>
            </div>
        </div>
        <div class="card-content p-4">
            <?= get_datatable('pengajar') ?>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="pageModal" tabindex="-1" role="dialog" aria-labelledby="pageModalTitle" aria-hidden="true">
        <div class="modal-dialog .modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pageModalTitle">Modal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="pageModalContent" class="modal-body">
                </div>
                <div class="modal-footer" id="modalButtons">
                    <button id="confirmButton" data-confirm="ok" type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">
    <script>
        $(document).ready(function() {
            var obj = 'pengajar';
            var ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>';
            var checked_ids = [];

            $('table').on('change', '.check_item', function() {
                checked_ids = [];
                $(".check_item:checked").each(function() {
                    checked_ids.push($(this).val());
                });
            });

            $(".check_all").change(function() {
                if (this.checked) {
                    $('.check_item').prop('checked', true);
                    checked_ids = [];
                    $(".check_item:checked").each(function() {
                        checked_ids.push($(this).val());
                    });
                } else {
                    $('.check_item').prop('checked', false);
                }
            });

            $("#ubah").click(function() {
                if (checked_ids.length == 0) {
                    $('#confirmButton').data('confirm', 'ok');
                    $('#p#ageModalTitle').text('ERROR')
                    $('#pageModalContent').text('Maaf, anda belum memilih ' + obj + ' untuk diubah');
                    $('#pageModal').modal('show');
                } else {
                    $(this).attr('href', '?action=ubah&id_' + obj + '=' + $(".check_item:checked").first().val());
                }
            });

            $("#hapus").click(function() {
                if (checked_ids.length == 0) {
                    $('#confirmButton').data('confirm', 'ok');
                    $('#pageModalTitle').text('ERROR');
                    $('#pageModalContent').text('Maaf, anda belum memilih ' + obj + ' untuk dihapus');
                    $('#pageModal').modal('show');
                } else {
                    let list = '<ol>';
                    $(".check_item:checked").each(function() {
                        list = list + '<li>' + $(this).data('nama_lengkap') + '</li>';
                    });
                    list = list + '</ol>';
                    $('#confirmButton').data('confirm', 'submit');
                    $('#pageModalTitle').text('HAPUS ' + obj.toUpperCase());
                    $('#pageModalContent').html('Apakah anda yakin ingin menghapus ' + obj + ' berikut: ' + list);
                    $('#pageModal').modal('show');
                }
            });

            $('#bulk_editor').click(function() {
                window.location.href = "?action=tambah&editor=bulk";
            })

            $("#confirmButton").click(function() {
                if ($('#confirmButton').data('confirm') == 'submit') {

                    var data = {
                        pod_name: 'pengajar',
                        action: 'delete_pod',
                        ids: checked_ids
                    };
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
                                $('.check_item').prop('checked', false);
                                $('#' + obj).DataTable().ajax.reload();
                            } else {
                                tata.error(response.message, '', {
                                    position: 'tm',
                                    duration: 2000
                                });
                            }
                        }
                    });
                }
            });
        });
    </script>
<?php }

?>