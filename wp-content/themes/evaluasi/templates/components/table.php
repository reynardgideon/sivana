<?php
include_once(get_template_directory() . '/getters/peserta.php');
include_once(get_template_directory() . '/getters/mata_pelajaran.php');

function tabel_objek_pelatihan()
{
    $title = strtoupper(str_replace('_', ' ', $_GET['section']));
    $object = $_GET['section'];
?>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link href="https://cdn.datatables.net/scroller/2.0.5/css/scroller.dataTables.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
    <div class="card">
        <div class="card-header has-background-kmk-mix has-text-centered">
            <h5><?= $title ?></h5>
            <div class="card-header-right">
                <a href="?action=tambah_<?= $object ?>"><i class="fa fa-plus" title="Tambah"></i></a>
                <a class="bulk_editor" id="bulk_editor" href="#"><i class="fa fa-table" title="Bulk Editor"></i></a>
                <?php if ($_GET['section'] !== 'peserta') : ?>
                    <a class="ubah" id="ubah_<?= $object ?>" href="#"><i class="fa fa-pencil" title="Ubah"></i></a>
                <?php endif; ?>
                <i class="hapus fa fa-trash" title="Hapus"></i>
                <i class="fa fa-cog" title="Pengaturan"></i>
            </div>
        </div>
        <div class="card-content p-4">
            <?php
            if ($object == 'pengajar') {
                call_user_func(array(strtoupper('Pelatihan'), 'get_mp_from_program'), get_the_id());
            } else {
                call_user_func(array(strtoupper($object), 'get_table'), get_the_id());
            }
            ?>
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
                    <button id="cancelButton" data-confirm="cancel" type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button id="confirmButton" data-confirm="ok" type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var page = '<?= $object ?>';
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
                        let slug = page == 'peserta' ? 'nama_lengkap' : 'judul';
                        list = list + '<li>' + $(this).data(slug) + '</li>';
                    });
                    list = list + '</ol>';
                    $('#confirmButton').data('confirm', 'submit');
                    $('#pageModalTitle').text('HAPUS ' + page.replace('_', ' ').toUpperCase());
                    $('#pageModalContent').html('Apakah anda yakin ingin menghapus ' + page.replace('_', ' ') + ' berikut: ' + list);
                    $('#pageModal').modal('show');
                }
            });

            $(".close").click(function() {
                $('#pageModal').modal('hide');
            });

            $("#cancelButton").click(function() {
                $('#pageModal').modal('hide');
            });

            $('.bulk_editor').click(function() {
                window.location.href = "?action=tambah_" + page + "&editor=bulk";
            })

            $("#confirmButton").click(function() {
                if ($('#confirmButton').data('confirm') == 'submit') {
                    $(this).html('<i class="fa fa-spinner fa-spin"></i>');
                    var data = {
                        action: 'remove_' + page,
                        ids: checked_ids,
                        id_pelatihan: <?= get_the_id() ?>,
                    };
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            $('#pageModal').modal('hide');
                            if (response.status == 1) {
                                tata.success(response.message, '', {
                                    position: 'tm',
                                    duration: 2000
                                });
                                $('.check_item').prop('checked', false);
                                location.reload();
                                //$('#' + page).DataTable().ajax.reload();
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


function get_tabel_data($pod, $q)
{
    $data = 'https://evaluasi.knpk.xyz/wp-content/themes/evaluasi/data/' . $pod . '.php?id_' . $q[0] . '=' . $q[1];
?>
    <table id="<?= $pod ?>" class="display nowrap" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th><input type="checkbox" class="check_all"></th>
                <th>#</th>
                <?php foreach (constant('FIELDS_' . strtoupper($pod)) as $f) : ?>
                    <th><?= $f['title'] ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>

    </table>

    <script>
        $(document).ready(function() {
            var table = $('#<?= $pod ?>').DataTable({
                scrollX: true,
                columnDefs: [{
                        target: 0,
                        visible: false,
                        searchable: false,
                    }, {
                        target: 1,
                        width: 20
                    },
                    {
                        target: 2,
                        width: 20
                    },
                    {
                        render: function(data, type, full, meta) {
                            return "<div<?= $pod == 'mata_palajaran' ? " class='text-wrap width-200'" : "" ?>>" + data + "</div>";
                        },
                        target: 3
                    }
                ],
                order: [],
                ajax: '<?= $data ?>',
                scrollX: true,
                scrollCollapse: true,
            });

        });
    </script>
<?php
}
