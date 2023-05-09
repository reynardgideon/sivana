<?php
include_once(get_template_directory() . '/getters/daftar-nilai.php');
include_once(get_template_directory() . '/getters/pengolahan.php');

function page_evaluasi_level_2()
{
?>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
    <div class="page-body">
        <div class="card">
            <div class="card-header has-text-centered">
                <nav>
                    <ul class="nav nav-pills nav-fill" id="nav-tab" role="tablist">
                        <li class="nav-item nav-link">
                            <a href="<?= get_the_permalink() ?>" class="red_back">
                                <h4>Info Pelatihan</h4>
                            </a>
                        </li>
                        <li class="nav-item nav-link active" id="daftar_nilai-tab" data-toggle="tab" href="#nav-daftar_nilai" role="tab" aria-controls="nav-daftar_nilai" aria-selected="true">
                            <a href="#" class="red_back">
                                <h4>Daftar Nilai</h4>
                            </a>
                        </li>
                        <li class="nav-item nav-link" id="rekap_per_mp-tab" data-toggle="tab" href="#nav-rekap_per_mp" role="tab" aria-controls="nav-rekap_per_mp" aria-selected="false">
                            <a href="#">
                                <h4>NPR</h4>
                            </a>
                        </li>
                        <li class="nav-item nav-link">
                            <a href="<?= get_site_url() . '/rekap-nilai?id_pelatihan=' . get_the_ID() ?>" class="red_back">
                                <h4>Rekap</h4>
                            </a>
                        </li>
                    </ul>
                </nav>

            </div>
            <div class="card-content p-4">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-daftar_nilai" role="tabpanel" aria-labelledby="daftar_nilai-tab">
                        <div class="card-header has-background-kmk-mix has-text-centered">
                            <h5>DAFTAR NILAI</h5>
                            <div class="card-header-right">
                                <a href="?action=tambah_daftar_nilai"><i class="fa fa-plus" title="Tambah"></i></a>
                                <a class="ubah" id="ubah_daftar_nilai" href="#"><i class="fa fa-pencil" title="Ubah"></i></a>
                                <i class="hapus fa fa-trash" title="Hapus"></i>
                                <i class="fa fa-cog" title="Pengaturan"></i>
                            </div>
                        </div>
                        <div class="card-content p-4">
                            <?php Daftar_Nilai::get_table(get_the_id()) ?>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-rekap_per_mp" role="tabpanel" aria-labelledby="rekap_per_mp-tab">
                        <div class="card-header has-background-kmk-mix has-text-centered">
                            <h5>DAFTAR MATA PELAJARAN</h5>
                        </div>
                        <div class="card-content p-4">
                            <?php Pengolahan::get_npr_table(get_the_id()) ?>
                        </div>
                    </div>

                </div>
            </div>
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

    <script>
        $(document).ready(function() {
            var page = 'daftar_nilai';
            var checked_ids = [];
            var ajaxurl = '<?= admin_url('admin-ajax.php') ?>';

            $('table').on('change', '.check_item', function() {
                checked_ids = [];
                $(".check_item:checked").each(function() {
                    checked_ids.push($(this).val());
                });
            });

            $(".nav-item").click(function() {
                page = $(this).prop('id').replace('-tab', '');
                checked_ids = [];
                $('.check_item').prop('checked', false);
                $('.check_all').prop('checked', false);
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

            $(".ubah").click(function() {
                if (checked_ids.length == 0) {
                    $('#confirmButton').data('confirm', 'ok');
                    $('#pageModalTitle').text('ERROR')
                    $('#pageModalContent').text('Maaf, anda belum memilih ' + page.replace('_', ' ') + ' untuk diubah');
                    $('#pageModal').modal('show');
                } else {
                    $('#ubah_' + page).attr('href', '?action=ubah_' + page + '&id_' + page + '=' + $(".check_item:checked").first().val());
                }
            });

            $(".hapus").click(function() {
                if (checked_ids.length == 0) {
                    $('#confirmButton').data('confirm', 'ok');
                    $('#pageModalTitle').text('ERROR');
                    $('#pageModalContent').text('Maaf, anda belum memilih ' + page.replace('_', ' ') + ' untuk dihapus');
                    $('#pageModal').modal('show');
                } else {
                    let list = '<ol>';
                    $(".check_item:checked").each(function() {
                        let slug = 'mata_pelajaran';
                        list = list + '<li>' + $(this).data(slug) + '</li>';
                    });
                    list = list + '</ol>';
                    $('#confirmButton').data('confirm', 'submit');
                    $('#pageModalTitle').text('HAPUS ' + page.replace('_', ' ').toUpperCase());
                    $('#pageModalContent').html('Apakah anda yakin ingin menghapus ' + page.replace('_', ' ') + ' berikut: ' + list);
                    $('#pageModal').modal('show');
                }
            });

            $("#confirmButton").click(function() {
                if ($('#confirmButton').data('confirm') == 'submit') {
                    $(this).html('<i class="fa fa-spinner fa-spin"></i>');
                    var data = {
                        action: 'delete_' + page,
                        ids: checked_ids
                    };
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            $("#confirmButton").html('Submit');
                            if (response.status == 1) {
                                tata.success(response.message, '', {
                                    position: 'tm',
                                    duration: 2000
                                });
                                $('.check_item').prop('checked', false);
                                $('.check_all').prop('checked', false);
                                location.reload();
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


<?php
}
