<?php

include_once(get_template_directory() . '/getters/helpers.php');

function page_saran_masukan()
{

    $pod = pods('pelatihan', get_the_ID());

    $ev_1 = (array)json_decode($pod->field('rekap_evaluasi_level_1'));

    $params = array(
        'limit' => -1,
        'where' => "pelatihan.ID = '" . get_the_ID() . "' AND kategori.meta_value='penyelenggaraan'"
    );

    $saran = pods('saran', $params);

    $total = $ev_1['responden'];    

    $i = 1;
    $data = [];
    if ($total > 0) {
        while ($saran->fetch()) {

            if ($saran->field('aktif') == 1) {

                $resp = '<p style="font-size:18px; text-decoration: underline; font-style: italic; margin-top:10px;"><a href="'.get_author_posts_url($saran->field('responden.ID')).'">'.$saran->field('responden.nama_lengkap').'</a></p>';

                $item = array(
                    $saran->field('ID'),
                    '<input type="checkbox" data-isi="' . $saran->field('isi') . '" class="check_item" value="' . $saran->field('ID') . '">',
                    $i . '.',
                    $saran->field('frekuensi'),
                    (string) number_format($saran->field('frekuensi') / $total * 100, 1, ",", ".") . '%',
                    //number_format($saran->field('persen'), 1, ",", ".") . '%',
                    Helpers::is_renbang() ? $saran->field('isi').$resp : $saran->field('isi')
                );
                $data[] = $item;
                $i++;
            }
        }
    }

    $form = '<form>
    <div class="form-group">
      <label for="recipient-name" class="col-form-label">Recipient:</label>
      <input type="text" class="form-control" id="recipient-name">
    </div>
    <div class="form-group">
      <label for="message-text" class="col-form-label">Message:</label>
      <textarea class="form-control" id="message-text"></textarea>
    </div>
  </form>';

?>
    <style>
        .text-wrap {
            white-space: normal;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/af-2.4.0/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/fc-4.1.0/fh-3.2.4/sc-2.0.7/sr-1.1.1/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/af-2.4.0/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/fc-4.1.0/fh-3.2.4/sc-2.0.7/sr-1.1.1/datatables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.1/css/fixedHeader.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.3.1/js/dataTables.fixedHeader.min.js"></script>
    <div class="card" id="sagara">
        <div class="card-header has-background-kmk-mix has-text-centered">
            <h5>SARAN DAN MASUKAN TERKAIT PENYELENGGARAAN</h5>
            <div class="card-header-right">
                <i id="tambah_saran" class="fa fa-plus" title="Tambah"></i>
                <i id="ubah_saran" class="fa fa-pencil" title="Ubah"></i>
                <i id="hapus_saran" class="fa fa-trash" title="Hapus"></i>
            </div>
        </div>
        <div class="card-content p-4">
            <div class="container">
                <table id="tabel_sagara" class="display" style="width:100%; font-size:20px;"></table>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalTitle" aria-hidden="true">
        <div class="modal-dialog .modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalTitle">ERROR</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="errorModalContent" class="modal-body">
                    Maaf, anda belum memilih item!
                </div>
                <div class="modal-footer" id="modalButtons">
                    <button type="button" class="cancelButton btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="hapusModal" tabindex="-1" role="dialog" aria-labelledby="hapusModalTitle" aria-hidden="true">
        <div class="modal-dialog .modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hapusModalTitle">HAPUS ITEM</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="hapusModalContent" class="modal-body">
                </div>
                <div class="modal-footer" id="modalButtons">
                    <button data-confirm="cancel" type="button" class="cancelButton btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button id="confirm_hapus" style="width:120px;" type="button" class="btn btn-primary confirmButton" data-dismiss="modal">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalTitle" aria-hidden="true">
        <div class="modal-dialog .modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalTitle">FORM SARAN</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="formModalContent" class="modal-body">
                    <div id="form_saran">
                        <form id="update_saran">
                            <div class="form-group">
                                <label for="frekuensi">Frekuensi</label>
                                <input type="text" class="form-control" id="frekuensi" name="frekuensi">
                            </div>
                            <div class="form-group">
                                <label for="saran">Saran</label>
                                <textarea class="form-control" rows="5" name="isi" id="isi"></textarea>
                            </div>
                            <div class="form-check ml-4">
                                <input class="form-check-input" type="checkbox" id="matriks_rekomendasi" name="matriks_rekomendasi" checked>
                                <label class="form-check-label" for="defaultCheck1">
                                    Matriks Rekomendasi
                                </label>
                            </div>
                            <input type="hidden" name="action" value="update_saran">
                            <input id="id" type="hidden" name="id" value="">
                            <input type="hidden" name="pelatihan" value="<?= get_the_id() ?>">
                            <input id="responden" type="hidden" name="responden" value="">
                            <input type="hidden" name="kategori" value="penyelenggaraan">
                        </form>
                    </div>
                </div>
                <div class="modal-footer" id="modalButtons">
                    <button data-confirm="cancel" type="button" class="cancelButton btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button id="confirm_form" data-confirm="ok" type="button" style="width:120px;" class="confirmButton btn btn-primary" data-dismiss="modal">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#tabel_sagara').DataTable({
                data: <?= json_encode($data) ?>,
                fixedHeader: {
                    header: true,
                    footer: true,
                },
                columns: [{
                        title: 'ID'
                    },
                    {
                        title: '<input type="checkbox" class="check_all">'
                    },
                    {
                        title: '#'
                    },
                    {
                        title: 'Frek'
                    },
                    {
                        title: 'Persen'
                    },
                    {
                        title: 'Uraian'
                    }
                ],
                columnDefs: [{
                        render: function(data, type, full, meta) {
                            return "<div class='text-justify text-wrap width-c'>" + data + "</div>";
                        },
                        targets: 5
                    },
                    {
                        targets: [0],
                        visible: false,
                        searchable: false,
                    },
                ],
                pageLength: 50,
                order: [],
                dom: 'Bfrtip',
                buttons: ['copy',
                    'excel',
                ],
                scrollX: true,
            });

            var checked_ids = [];
            var ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>';

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

            $("#hapus_saran").click(function() {
                if (checked_ids.length == 0) {
                    $('#errorModal').modal('show');
                } else {
                    let list = '<ol>';
                    $(".check_item:checked").each(function() {
                        list = list + '<li>' + $(this).data('isi') + '</li>';
                    });
                    list = list + '</ol>';
                    $('#hapusModalContent').html('Apakah anda yakin ingin menghapus item berikut: ' + list);
                    $('#hapusModal').modal('show');
                }
            });

            $("#ubah_saran").click(function() {
                if (checked_ids.length == 0) {
                    $('#errorModal').modal('show');
                } else {
                    var data = {
                        action: 'get_saran',
                        id: checked_ids[0]
                    };
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            if (response.matriks_rekomendasi == '') {
                                $('#matriks_rekomendasi').prop('checked', false);
                            }
                            $('#frekuensi').val(response.frekuensi);
                            $('#isi').val(response.isi);
                            $('#id').val(response.id);
                            $('#responden').val(response.responden);
                            $('#formModal').modal('show');
                        }
                    });
                }
            });

            $("#tambah_saran").click(function() {
                $('#frekuensi').val('');
                $('#saran').val('');
                $('#formModal').modal('show');
            });

            $(".close").click(function() {
                $('.modal').modal('hide');
            });

            $(".cancelButton").click(function() {
                $('.modal').modal('hide');
            });

            $("#confirm_form").click(function() {

                $(this).html('<i class="fa fa-spinner fa-spin"></i>');

                var data = $('#update_saran').serialize();

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.status == 1) {
                            $("#confirm_form").html('Submit');

                            $('.modal').modal('hide');

                            tata.success(response.message, '', {
                                position: 'tm',
                                duration: 2000
                            });

                            location.reload();

                        } else {
                            tata.error(response.message, '', {
                                position: 'tm',
                                duration: 2000
                            });
                        }
                    }
                });
            });

            $("#confirm_hapus").click(function() {

                $(this).html('<i class="fa fa-spinner fa-spin"></i>');

                var data = {
                    action: 'remove_saran',
                    kategori: 'penyelenggaraan',
                    pelatihan: <?= get_the_ID() ?>,
                    ids: checked_ids
                };

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.status == 1) {
                            $("#confirm_hapus").html('Submit');

                            $('.modal').modal('hide');

                            tata.success(response.message, '', {
                                position: 'tm',
                                duration: 2000
                            });

                            location.reload();

                        } else {
                            tata.error(response.message, '', {
                                position: 'tm',
                                duration: 2000
                            });
                        }
                    }
                });
            });

        });
    </script>

<?php } ?>