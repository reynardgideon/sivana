<?php
include_once(get_template_directory() . '/getters/mata-pelajaran.php');
include_once(get_template_directory() . '/getters/pengajar.php');
include_once(get_template_directory() . '/getters/peserta.php');

class Daftar_Nilai
{
    private $pod_name = 'daftar_nilai';

    public static function get_kompre_pkl($id_pelatihan, $jn)
    {
        $params = array(
            'limit' => -1,
            'where' => "pelatihan.ID = '" . $id_pelatihan . "' AND jenis_nilai.meta_value = '" . $jn . "'"
        );

        $dn = pods('daftar_nilai', $params);

        $nilai = [];

        if ($dn->total() > 0) {
            while ($dn->fetch()) {
                $data = (array)json_decode($dn->field('data'));
                foreach ($data['peserta'] as $p) {
                    $nilai[$p->nip][] = $p->nilai;
                }
            }
            return $nilai;
        } else {
            return null;
        }
    }

    public static function get_jenis_nilai($option = false, $selected = '')
    {
        $dn = pods('daftar_nilai');

        if ($option == true) {
            $options = '';
            foreach ($dn->fields('jenis_nilai', 'data') as $val => $label) {
                $is_selected = $selected == $val ? ' selected' : '';
                $options .= '<option value="' . $val . '"' . $is_selected . '>' . $label . '</option>';
            }
            return $options;
        } else {
            return $dn->fields('jenis_nilai', 'data');
        }
    }

    public static function get_options_list($id_pelatihan)
    {
        $params = array(
            'limit' => -1,
            'where' => "mata_pelajaran.pelatihan.ID='" . $id_pelatihan . "'"
        );

        $dn = pods('daftar_nilai', $params);

        $options = '<option value="0">Tidak Ada</option>';
        if (0 < $dn->total()) {
            while ($dn->fetch()) {
                if ($dn->field('jenis_nilai') !== 'p' && $dn->field('jenis_nilai') !== 'q') {
                    $options .= "<option value='" . $dn->field('ID') . "'>" . $dn->display('jenis_nilai') . " - " . $dn->display('post_title') . "</option>";
                }
            }
        }

        return $options;
    }

    public static function get_table($id_pelatihan)
    {
        $data = [];

        $params = array(
            'limit' => -1,
            'where' => "mata_pelajaran.pelatihan.ID='" . $id_pelatihan . "' OR pelatihan.ID='" . $id_pelatihan . "'"
        );

        $dn = pods('daftar_nilai', $params);

        $i = 1;
        if (0 < $dn->total()) {
            while ($dn->fetch()) {
                $judul = strlen($dn->display('mata_pelajaran')) > 60 ? substr($dn->display('mata_pelajaran'), 0, 60) . '...' : $dn->display('mata_pelajaran');

                if ($dn->field('jenis_nilai') == 'pkl' || $dn->field('jenis_nilai') == 'k' || $dn->field('jenis_nilai') == 'lain') {
                    $judul = $dn->display('post_title');
                }

                $pengajar = '';

                $data_nilai = json_decode($dn->field('data'));

                if (count($data_nilai->pengajar) > 1) {
                    $array = $data_nilai->pengajar;
                    $text = '';
                    for ($j=0; $j<count($data_nilai->pengajar); $j++) {
                        if ($j == 0) {
                            $text .= '<span class="label label-default"><a style="font-size:9pt;" href="'.get_author_posts_url($data_nilai->pengajar[$j]->id).'">'.$data_nilai->pengajar[$j]->nama_lengkap.'</a></span>';
                        } else if ($j == count($array) - 1) {
                            $text .= ' dan ' . '<span class="label label-default"><a style="font-size:9pt;" href="'.get_author_posts_url($data_nilai->pengajar[$j]->id).'">'.$data_nilai->pengajar[$j]->nama_lengkap.'</a></span>';
                        } else {
                            $text .= ', ' . '<span class="label label-default"><a style="font-size:9pt;" href="'.get_author_posts_url($data_nilai->pengajar[$j]->id).'">'.$data_nilai->pengajar[$j]->nama_lengkap.'</a></span>';
                        }
                    }                    
                    $pengajar = $text;                   
                } else if (count($data_nilai->pengajar) == 1) {
                    $pengajar = '<span class="label label-default"><a style="font-size:9pt;" href="' . get_author_posts_url($data_nilai->pengajar[0]->id) . '">' . $data_nilai->pengajar[0]->nama_lengkap . '</a></span>';
                } else {
                    $pengajar = '';
                }

                $item = array();
                $item[] = $dn->display('ID');
                $item[] = '<input type="checkbox" data-judul="' . $judul . '" class="check_item" value="' . $dn->display('ID') . '">';
                $item[] = $i;
                $item[] = $dn->field('submitted') == 1 ? '<i class="fa-sharp fa-solid fa-check text-success" style="font-size: 22px;"></i>' : '';
                $item[] = '<a href="' . get_the_permalink($dn->field('ID')) . '?action=view" title="' . $dn->display('mata_pelajaran') . '">' . $judul . '</a><br/>' . $pengajar;

                $item[] = $dn->display('jenis_nilai');
                $item[] = '<button style="width:70px;" id="' . $dn->display('ID') . '" type="button" class="get_link btn btn-sm btn-primary ">Get Link</button>';
                $data[] = $item;
                $i++;
            }

            $data = json_encode($data);
?>
            <table id="daftar_nilai" class="display nowrap" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th><input type="checkbox" class="check_all"></th>
                        <th>#</th>
                        <th><i class="bi bi-check"></i></th>
                        <th>Mata Pelajaran</th>
                        <th>Jenis Nilai</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>

            <style>
                table,
                table a {
                    font-size: 16px;
                }
            </style>

            <script>
                $(document).ready(function() {
                    var table = $('#daftar_nilai').DataTable({
                        scrollX: true,
                        columnDefs: [{
                                target: [0],
                                visible: false,
                                searchable: false,
                            }, {
                                target: 1,
                                width: 20,
                                orderable: false,
                            },
                            {
                                target: 2,
                                width: 20
                            },
                        ],
                        order: [],
                        data: <?= $data ?>,
                        scrollX: true,
                        scrollCollapse: true,
                        createdRow: function(row, data, dataIndex) {
                            if (data[1] == 1) {
                                $(row).addClass('link-success');
                            }
                        }
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

                    $('table').on('click', '.get_link', function(e) {            
                        var id = e.target.id;           
                        var ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>';
                        var data = {
                            action: 'get_link_daftar_nilai',
                            id: id
                        };

                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: data,
                            success: function(response) {
                                navigator.clipboard.writeText(response.message);
                                
                                $('#' + id).html('Link Copied');
                                $('#' + id).removeClass('btn-primary');
                                $('#' + id).addClass('btn-secondary');
                            }
                        });
                    });

                });
            </script>

        <?php
        } else {
        ?>
            <div class="text-center">
                <button type="button" id="generate_daftar_nilai" style="width: 200px;" class="btn btn-primary">Generate Daftar Nilai</button>
                <div class="alert alert-primary mt-3" role="alert">
                    Sebelum generate Daftar Nilai, pastikan data Mata Pelajaran dan data Peserta telah diinput!
                </div>
            </div>

            <script>
                $('#generate_daftar_nilai').click(function(e) {
                    var ajaxurl = '<?= admin_url('admin-ajax.php') ?>';

                    $(this).html('<i class="fa fa-spinner fa-spin"></i>');

                    var data = {
                        action: 'generate_daftar_nilai',
                        id_pelatihan: <?= $id_pelatihan ?>
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
                            $('#' + e.target.id).html('Generate Daftar Nilai');
                            window.location.reload();
                        }
                    });
                });
            </script>
        <?php
        }
    }

    public static function editor($id_pelatihan, $id_daftar_nilai = null)
    {
        $title = '';
        $jenis_nilai = '';
        $mata_pelajaran = '';
        $pengajar = '';
        $checked = '';
        $peserta = array();

        if ($id_daftar_nilai == null) {
            $title = 'TAMBAH DAFTAR NILAI';
            $jenis_nilai = self::get_jenis_nilai(true);
            $mata_pelajaran = Mata_Pelajaran::get_options_list($id_pelatihan);
            $pengajar = Pengajar::get_options_list($id_pelatihan);
            $peserta = Peserta::get_data($id_pelatihan);
        } else {
            $dn = pods('daftar_nilai', $id_daftar_nilai);
            $title = 'UBAH DAFTAR NILAI';
            $jenis_nilai = self::get_jenis_nilai(true, $dn->field('jenis_nilai'));
            $mata_pelajaran = Mata_Pelajaran::get_options_list($id_pelatihan, $dn->field('mata_pelajaran.ID'));

            $data = json_decode($dn->field('data'));

            $ids = [];
            foreach ($data->pengajar as $p) {
                $ids[] = $p->id;
            }

            $peserta = $data->peserta;
            $pengajar = Pengajar::get_options_list($id_pelatihan, $ids);

            $checked = ' checked';
        }
        ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
        <script src="https://cdn.jsdelivr.net/npm/handsontable@12.1/dist/handsontable.full.min.js"></script>
        <link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@12.1/dist/handsontable.full.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">
        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
        <div class="card">
            <div class="card-header has-background-kmk-mix has-text-centered">
                <h5><?= $title ?></h5>
                <div class="card-header-right">
                    <a href="<?= get_the_permalink(get_the_id()) ?>?section=daftar_nilai">
                        <i class="ti ti-angle-double-left" title="Kembali"></i>
                    </a>
                </div>
            </div>
            <div class="card-content p-4">
                <form id="form_daftar_nilai" action="" method="POST" class="needs-validation">
                    <div class="form-group">
                        <label for="jenis_nilai">
                            <h6>Jenis Nilai</h6>
                        </label>
                        <select id="jenis_nilai" name="jenis_nilai" class="form-control selectpicker" data-live-search="true" data-dropup-auto="false">
                            <?= $jenis_nilai ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="mata_pelajaran">
                            <h6>Mata Pelajaran</h6>
                        </label>
                        <select id="mata_pelajaran" name="mata_pelajaran" class="form-control selectpicker" data-live-search="true" data-dropup-auto="false">
                            <?= $mata_pelajaran ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="pengajar">
                            <h6>Pengajar</h6>
                        </label>
                        <select id="pengajar" name="pengajar[]" class="form-control selectpicker" data-live-search="true" data-dropup-auto="false" multiple>
                            <?= $pengajar ?>
                        </select>
                    </div>



                    <!--
                    <div class="form-group">
                        <label for="asal_nilai">Asal Nilai</label>
                        <select id="asal_nilai" name="asal_nilai" class="form-control selectpicker" data-live-search="true" data-dropup-auto="false">
                            Daftar_Nilai::get_options_list($id_pelatihan) ?>
                        </select>
                    </div>
                    -->

                    <div class="form-group">
                        <label for="pengajar">
                            <h6>Pakai Kode</h6>
                        </label>
                        <div class="form-check ml-4">
                            <input class="form-check-input" type="checkbox" id="coding" name="coding" value="" <?= $checked ?>>
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="peserta">
                            <h6>Peserta</h6>
                        </label>
                        <p><button style="width:200px;" id="reset" type="button" class="btn btn-primary" title="Reset peserta sesuai yang terdaftar pada data pelatihan!">Reset Peserta</button></p>
                    </div>

                    <div id="peserta" class="mb-5"></div>

                    <input type="hidden" name="action" value="tambah_daftar_nilai">

                    <button style="width:100px;" id="back" type="button" class="btn btn-danger mr-2">Cancel</button>
                    <button style="width:100px;" id="submit_button" type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>

        <style>
            .handsontable span.colHeader {
                font-weight: bold;
                font-size: 14px;
                line-height: 30px;
            }

            .handsontable table {
                font-size: 14px;
            }

            .handsontable .middle {
                vertical-align: middle;
            }

            .table .left {
                text-align: left;
            }
        </style>

        <script>
            const container = document.querySelector('#peserta');

            const hot = new Handsontable(container, {
                data: <?= json_encode($peserta) ?>,
                width: "80%",
                columns: [{
                        data: 'nama_lengkap',
                        className: "htMiddle htLeft nama_lengkap",
                        readOnly: true,
                    }, {
                        data: 'nip',
                        readOnly: true
                    },
                    {
                        data: 'code',
                        readOnly: true
                    }
                ],
                className: 'htMiddle htCenter',
                colHeaders: ['Nama', 'NIP', 'Code'],
                rowHeights: 40,
                manualRowResize: true,
                rowHeaders: true,
                columnSorting: true,
                height: 'auto',
                contextMenu: true,
                afterGetRowHeader: function(col, TH) {
                    TH.className = 'middle'
                },
                licenseKey: 'non-commercial-and-evaluation'
            });

            $(document).ready(function() {
                var ajaxurl = '<?= get_site_url() . '/wp-admin/admin-ajax.php' ?>';

                $('#submit_button').click(function(e) {
                    e.preventDefault();
                    var ajaxurl = '<?= admin_url('admin-ajax.php') ?>';
                    $(this).html('<i class="fa fa-spinner fa-spin"></i>');

                    var send_data = {
                        pod_id: '<?= $id_daftar_nilai ?>',
                        action: 'edit_daftar_nilai',
                        jenis_nilai: $('#jenis_nilai').val(),
                        mata_pelajaran: $('#mata_pelajaran').val(),
                        coding: $('#coding').is(':checked') ? 1 : 0,
                        id_pengajar: $("#pengajar").val(),
                        data: {
                            peserta: hot.getSourceData(),
                        },
                        pelatihan: '<?= $id_pelatihan ?>',
                    };

                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: send_data,
                        success: function(response) {
                            if (response.status == 1) {
                                tata.success(response.message, '', {
                                    position: 'tm',
                                    duration: 2000
                                });
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
                    window.location.href = '<?= get_the_permalink(get_the_id()) ?>?section=daftar_nilai';
                });

                $('#reset').click(function() {
                    $(this).html('<i class="fa fa-spinner fa-spin"></i>');
                    hot.loadData(<?= json_encode(Peserta::get_data($id_pelatihan)) ?>);
                    $(this).html('Reset Peserta');
                });
            });
        </script>
<?php
    }
}
