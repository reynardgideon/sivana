<?php
include_once(get_template_directory() . '/getters/helpers.php');

class Pelatihan
{
    public static function get_peserta($id_pelatihan = null)
    {
        $pod = pods('pelatihan', $id_pelatihan);
        $peserta = $pod->field('peserta.user_login');
        return $peserta;
    }

    public static function get_mata_pelajaran_diujikan($id_pelatihan = null)
    {
        $args = array(
            'limit' => -1,
            'where' => "pelatihan.ID = " . $id_pelatihan . " AND diujikan.meta_value = 1"
        );

        $pod = pods('mata_pelajaran', $args);

        $ids = [];

        while ($pod->fetch()) {
            $ids[] =  $pod->field('ID');
        };

        return $ids;
    }

    public static function get_pengajar($id_pelatihan = null)
    {
        $pod = pods('pelatihan', $id_pelatihan);
        $pengajar = $pod->field('mata_pelajaran.pengajar.nama_lengkap');
        return $pengajar;
    }

    public static function get_tanggal_rapat_kelulusan($id_pelatihan = null)
    {
        $params = array(
            'limit' => 1,
            'where' => "pelatihan.ID='" . $id_pelatihan . "'"
        );
        $pod = pods('rapat_kelulusan', $params);

        $tgl = '';
        while ($pod->fetch()) {
            $tgl = $pod->display('tanggal_rapat');
        }

        return $tgl;
    }

    public static function editor($id_pelatihan = null)
    {
        $title = $id_pelatihan == null ? 'TAMBAH PELATIHAN' : 'UBAH PELATIHAN';
        $pod = 'pelatihan';

        $fields = array(
            'judul' => '',
            'mulai' => '',
            'selesai' => '',
            'jenis_pelatihan' => '',
            'jenis_evaluasi_level_2' => '',
            'epaspem',
            'pic' => ''
        );

        $bobots = array(
            'nt' => '100%',
            'nk' => '',
            'npkl' => ''
        );

        $pelatihan = pods('pelatihan');
        $jenis_pelatihan = $pelatihan->fields('jenis_pelatihan', 'data');
        $jenis_evaluasi_level_2 = $pelatihan->fields('jenis_evaluasi_level_2', 'data');
        $epaspem = $pelatihan->fields('epaspem', 'data');
        $diasramakan = $pelatihan->fields('diasramakan', 'data');
        $pic = $pelatihan->fields('pic', 'data');
        $lokasi = $pelatihan->fields('lokasi', 'data');

        if ($id_pelatihan !== null) {
            $pelatihan = pods('pelatihan', $id_pelatihan);

            foreach ($fields as $k => $v) {
                if ($k == 'mulai' || $k == 'selesai') {
                    $fields[$k] = date("d-m-Y", strtotime($pelatihan->field($k)));
                } else {
                    $fields[$k] = $pelatihan->display($k);
                }
            }

            $bobot = (array) json_decode($pelatihan->display('komponen_na'));

            foreach ($bobots as $k => $v) {
                $bobots[$k] = $bobot[$k];
            }
        }

?>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">
        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
        <div class="card">
            <div class="card-header has-background-kmk-mix has-text-centered">
                <h5><?= $title ?></h5>
                <div class="card-header-right">
                    <i id="back" class="ti ti-angle-double-left" title="Kembali"></i>
                </div>
            </div>
            <div class="card-content p-4">
                <form id="form_pelatihan" action="" method="POST" class="needs-validation font-weight-bold">
                    <div class="form-group">
                        <label for="judul">Judul</label>
                        <input autocomplete="off" name="judul" value="<?= $fields['judul'] ?>" type="text" class="form-control" id="judul" required>
                    </div>
                    <div class="form-group">
                        <label for="jenis_pelatihan">Jenis Pelatihan</label>
                        <select id="jenis_pelatihan" name="jenis_pelatihan" class="form-control">
                            <?php foreach ($jenis_pelatihan as $k => $v) : ?>
                                <option value="<?= $k ?>" <?= $pelatihan->field('jenis_pelatihan') == $k ? ' selected' : '' ?>><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jenis_pelatihan">Diasramakan</label>
                        <select id="diasramakan" name="diasramakan" class="form-control">
                            <?php foreach ($diasramakan as $k => $v) : ?>
                                <option value="<?= $k ?>" <?= $pelatihan->field('diasramakan') == $k ? ' selected' : '' ?>><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mulai">Mulai</label>
                        <input autocomplete="off" name="mulai" value="<?= $fields['mulai'] ?>" type="calendar" class="datepicker form-control" id="mulai" required>
                    </div>
                    <div class="form-group">
                        <label for="selesai">Selesai</label>
                        <input autocomplete="off" name="selesai" value="<?= $fields['selesai'] ?>" type="calendar" class="datepicker form-control" id="selesai" required>
                    </div>
                    <div class="form-group">
                        <label for="jenis_evaluasi_level_2">Jenis Evaluasi Level 2</label>
                        <select id="jenis_evaluasi_level_2" name="jenis_evaluasi_level_2" class="form-control">
                            <?php foreach ($jenis_evaluasi_level_2 as $k => $v) : ?>
                                <option value="<?= $k ?>" <?= $pelatihan->field('jenis_evaluasi_level_2') == $k ? ' selected' : '' ?>><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jenis_pelatihan">Evaluasi Pasca Pembelajaran</label>
                        <select id="epaspem" name="epaspem" class="form-control">
                            <?php foreach ($epaspem as $k => $v) : ?>
                                <option value="<?= $k ?>" <?= $pelatihan->field('epaspem') == $k ? ' selected' : '' ?>><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group" id="komponen_na">
                        <label for="komponen">Komponen Nilai Akhir (%)</label>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th scope="row align-middle">Nilai Tertimbang</th>
                                    <td><input autocomplete="off" name="nt" id="nt" value="<?= $bobots['nt'] ?>" type="text" class="form-control input-sm"></td>
                                </tr>
                                <tr>
                                    <th scope="row align-middle">Nilai Komprehensif</th>
                                    <td><input autocomplete="off" name="nk" id="nk" value="<?= $bobots['nk'] ?>" type="text" class="form-control input-sm"></td>
                                </tr>
                                <tr>
                                    <th scope="row align-middle">Nilai Praktek Kerja Lapangan</th>
                                    <td><input autocomplete="off" name="npkl" id="npkl" value="<?= $bobots['npkl'] ?>" type="text" class="form-control input-sm"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="lokasi">Lokasi</label>
                        <select id="lokasi" name="lokasi" class="form-control">
                            <?php foreach ($lokasi as $k => $v) : ?>
                                <option value="<?= $k ?>" <?= $pelatihan->field('lokasi') == $k ? ' selected' : '' ?>><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pic">PIC</label>
                        <select id="pic" name="pic" class="form-control">
                            <?php foreach ($pic as $k => $v) : ?>
                                <option value="<?= $k ?>" <?= $pelatihan->field('pic.ID') == $k ? ' selected' : '' ?>><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <input type="hidden" name="action" value="action_pelatihan_single">
                    <input type="hidden" name="pod_id" value="<?= $id_pelatihan ?>">
                    <button style="width:100px;" id="cancel" type="button" class="btn btn-danger mx-2">Cancel</button>
                    <button style="width:100px;" id="submit_button" type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                var ajaxurl = '<?= admin_url('admin-ajax.php') ?>';
                $('#form_<?= $pod ?>').on('submit', function(e) {
                    e.preventDefault();
                    $('#submit_button').html('<i class="fa fa-spinner fa-spin"></i>');
                    var data = $('#form_<?= $pod ?>').serialize();

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

                                if (response.reset == 1) {
                                    $('#form_<?= $pod ?>').get(0).reset();
                                }
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

                if ($('#jenis_evaluasi_level_2').val() == 'prepost' || $('#jenis_evaluasi_level_2').val() == 'no') {
                    $('#komponen_na').hide();
                }

                $('#back').click(function() {
                    window.location.href = document.referrer;
                });

                $('#cancel').click(function() {
                    $(this).html('<i class="fa fa-spinner fa-spin"></i>');
                    window.location.href = document.referrer;
                });

                $('#jenis_evaluasi_level_2').change(function() {
                    if ($('#jenis_evaluasi_level_2').val() == 'ujian') {
                        $('#nt').val('100%');
                        $('#nk').val('');
                        $('#komponen_na').show();
                    } else if ($('#jenis_evaluasi_level_2').val() == 'kompre') {
                        $('#nt').val('40%');
                        $('#nk').val('60%');
                        $('#komponen_na').show();
                    } else {
                        $('#nt').val('');
                        $('#nk').val('');
                        $('#npkl').val('');
                        $('#komponen_na').hide();
                    }
                });


                $(".datepicker").datepicker({
                    dateFormat: "dd-mm-yy",
                    dayNames: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                    dayNamesMin: ["Mi", "Se", "Sel", "Ra", "Ka", "Ju", "Sa"],
                    monthNames: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"]
                });

            });
        </script>
    <?php
    }

    public static function get_data_table($tahun = null)
    {
        $data = array();
        $params = array(
            'limit' => -1
        );
        if ($tahun !== null) {
            $params['where'] = "YEAR(mulai.meta_value)='" . $tahun . "'";
            $params['orderby'] = 'mulai.meta_value DESC';
        }

        $pelatihan = pods('pelatihan', $params);

        $i = 1;
        if (0 < $pelatihan->total()) {
            while ($pelatihan->fetch()) {
                $judul = strlen($pelatihan->display('judul')) > 60 ? substr($pelatihan->display('judul'), 0, 60) . "..." : $pelatihan->display('judul');
                $item = array();
                $item[] = $pelatihan->display('ID');
                $item[] = '<input type="checkbox" data-judul="' . $pelatihan->display('judul') . '" class="check_item" value="' . $pelatihan->display('ID') . '">';
                $item[] = $i;
                $item[] = '<a href="' . get_the_permalink($pelatihan->field('ID')) . '" title="' . $pelatihan->display('judul') . '">' . $judul . '</a>';
                $item[] = '<a href="' . get_author_posts_url($pelatihan->field('pic.ID')) . '">' . $pelatihan->field('pic.nama_panggilan') . '</a>';
                $item[] = Helpers::tanggal($pelatihan->display('mulai'));
                $item[] = Helpers::tanggal($pelatihan->display('selesai'));
                $item[] = $pelatihan->field('mulai');
                $item[] = $pelatihan->display('judul');

                $data[] = $item;
                $i++;
            }
        }

        $data = json_encode($data);

        return $data;
    }

    public static function get_table($tahun)
    {
    ?>
        <table id="pelatihan" class="display nowrap" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><input type="checkbox" class="check_all"></th>
                    <th>#</th>
                    <th>Judul</th>
                    <th>PIC</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Date</th>
                    <th>Title</th>
                </tr>
            </thead>

        </table>

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
                var table = $('#pelatihan').DataTable({
                    scrollX: true,
                    columnDefs: [{
                            target: [0, 7],
                            visible: false,
                            searchable: false,
                        },
                        {
                            target: 8,
                            visible: false,
                            searchable: true,
                        },
                        {
                            target: 1,
                            width: 20
                        },
                        {
                            target: 2,
                            width: 20
                        }, {
                            orderable: false,
                            target: 2
                        },
                        {
                            target: 7,
                            type: 'date-euro'
                        }
                    ],
                    order: [7, 'desc'],
                    data: <?= self::get_data_table($tahun) ?>,
                    scrollX: true,
                    scrollCollapse: true,
                });

                table.on('order.dt search.dt', function() {
                    let i = 1;

                    table.cells(null, 2, {
                        search: 'applied',
                        order: 'applied'
                    }).every(function(cell) {
                        this.data(i++);
                    });
                }).draw();

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

                $("#ubah_pelatihan").click(function() {
                    if (checked_ids.length == 0) {
                        $('#confirmButton').data('confirm', 'ok');
                        $('#pageModalTitle').text('ERROR')
                        $('#pageModalContent').text('Maaf, anda belum memilih pelatihan untuk diubah');
                        $('#pageModal').modal('show');
                    } else {
                        $('#ubah_pelatihan').attr('href', '?action=ubah_pelatihan&id_pelatihan=' + $(".check_item:checked").first().val());
                    }
                });

                $(".hapus").click(function() {
                    if (checked_ids.length == 0) {
                        $('#confirmButton').data('confirm', 'ok');
                        $('#pageModalTitle').text('ERROR');
                        $('#pageModalContent').text('Maaf, anda belum memilih pelatihan untuk dihapus');
                        $('#pageModal').modal('show');
                    } else {
                        let list = '<ol>';
                        $(".check_item:checked").each(function() {
                            list = list + '<li>' + $(this).data('judul') + '</li>';
                        });
                        list = list + '</ol>';
                        $('#confirmButton').data('confirm', 'submit');
                        $('#pageModalTitle').text('HAPUS PELATIHAN');
                        $('#pageModalContent').html('Apakah anda yakin ingin menghapus pelatihan berikut: ' + list);
                        $('#pageModal').modal('show');
                    }
                });

                $('.bulk_editor').click(function() {
                    window.location.href = "?action=tambah_pelatihan&editor=bulk";
                })

                $("#confirmButton").click(function() {
                    if ($('#confirmButton').data('confirm') == 'submit') {
                        $(this).html('<i class="fa fa-spinner fa-spin"></i>');
                        var data = {
                            action: 'delete_pod',
                            ids: checked_ids
                        };
                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: data,
                            success: function(response) {
                                if (response.status == 1) {
                                    $("#confirmButton").html('Submit');
                                    tata.success(response.message, '', {
                                        position: 'tm',
                                        duration: 2000
                                    });
                                    $('.check_item').prop('checked', false);
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

        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

        <link href="https://cdn.datatables.net/scroller/2.0.5/css/scroller.dataTables.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">
        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
    <?php
    }

    public static function get_mp_from_program($id_pelatihan)
    {
        $data = 'https://evaluasi.knpk.xyz/wp-content/themes/evaluasi/data/mata_pelajaran.php?id_pelatihan=' . $id_pelatihan;
    ?>
        <table id="mata_pelajaran" class="display nowrap" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><input type="checkbox" class="check_all"></th>
                    <th>#</th>
                    <?php foreach (constant('FIELDS_' . strtoupper('mata_pelajaran')) as $f) : ?>
                        <th><?= $f['title'] ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>

        </table>

        <script>
            $(document).ready(function() {
                var table = $('#mata_pelajaran').DataTable({
                    pageLength: 20,
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
                            target: 8,
                            visible: false,
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
}
