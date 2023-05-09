<?php

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'tambah_pengajar':
            if (isset($_GET['editor']) && $_GET['editor'] == 'bulk') {
                editor_bulk('pengajar', null);
            } else {
                editor_single('pengajar', null, null);
            }
            break;
        case 'ubah_pengajar':
            editor_single('pengajar', $_GET['id_pengajar'], null);
            break;
    }
} else {
    halaman_pengajar();
}

function halaman_pengajar()
{
?>

    <div class="card">
        <?= get_data_table('pengajar', null) ?>
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


    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">


    <link href="https://cdn.datatables.net/scroller/2.0.5/css/scroller.dataTables.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
    <script>
        $(document).ready(function() {
            var page = 'detail';
            var ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>';
            var checked_ids = [];

            $('table').on('change', '.check_item', function() {
                checked_ids = [];
                $(".check_item:checked").each(function() {
                    checked_ids.push($(this).val());
                });
            });

            $(".nav-item").click(function() {
                page = $(this).prop('id').replace('-tab', '');

            });

            $('li[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                $($.fn.dataTable.tables(true)).css('width', '100%');
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust().draw();
            })

            $("#check_all").change(function() {
                if (this.checked) {
                    $('.check_item').prop('checked', true);
                } else {
                    $('.check_item').prop('checked', false);
                }
            });

            $(".ubah").click(function() {
                if (checked_ids.length == 0) {
                    $('#confirmButton').data('confirm', 'ok');
                    $('#pageModalTitle').text('ERROR')
                    $('#pageModalContent').text('Maaf, anda belum memilih ' + page + ' untuk diubah');
                    $('#pageModal').modal('show');
                } else {
                    $('#ubah_' + page).attr('href', '?action=ubah_' + page + '&id_' + page + '=' + $(".check_item:checked").first().val());
                }
            });

            $(".hapus").click(function() {
                if (checked_ids.length == 0) {
                    $('#confirmButton').data('confirm', 'ok');
                    $('#pageModalTitle').text('ERROR');
                    $('#pageModalContent').text('Maaf, anda belum memilih ' + page + ' untuk dihapus');
                    $('#pageModal').modal('show');
                } else {
                    let list = '<ol>';
                    $(".check_item:checked").each(function() {
                        list = list + '<li>' + $(this).data('nama_lengkap') + '</li>';
                    });
                    list = list + '</ol>';
                    $('#confirmButton').data('confirm', 'submit');
                    $('#pageModalTitle').text('HAPUS ' + page.toUpperCase());
                    $('#pageModalContent').html('Apakah anda yakin ingin menghapus ' + page + ' berikut: ' + list);
                    $('#pageModal').modal('show');
                }
            });

            $('.bulk_editor').click(function() {
                if (checked_ids.length == 0) {
                    window.location.href = "?action=tambah_" + page + "&editor=bulk";
                } else {
                    window.location.href = "?action=ubah_" + page + "&editor=bulk&ids=" + checked_ids;
                }
            })

            $("#confirmButton").click(function() {
                if ($('#confirmButton').data('confirm') == 'submit') {

                    var data = {
                        action: 'remove_peserta',
                        ids: checked_ids,
                        id_pelatihan: <?= get_the_id() ?>,
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
                                $('#' + page).DataTable().ajax.reload();
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
