<?php
include_once(get_template_directory() . '/getters/helpers.php');
include_once(get_template_directory() . '/getters/pengajar.php');

function page_edit_rekap_evaluasi_pengajar()
{
    $data = [];

    $pod = pods('pelatihan', get_the_ID());

    $mps = $pod->field('mata_pelajaran.ID');

    $rekap = $pod->field('rekap_evaluasi_level_1') == '' ? [] : (array) json_decode($pod->field('rekap_evaluasi_level_1'));

    $ev = isset($rekap['pengajar']) ? (array)$rekap['pengajar'] : [];

    if (count($ev) > 0) {
        $i = 1;
        foreach ($ev as $key => $val) {
            if ($i == count($ev)) {
                $data[] = array(
                    'Rata-rata',
                    '',
                    $val->h,
                    $val->k,
                    $val->p,
                    $val->ku,
                    '',
                    ''
                );
            } else {
                $ids = explode('-', $key);

                $mp = pods('mata_pelajaran', $ids[1]);
                $peng = pods('user', $ids[0]);

                $mapel = $mp->display('judul');

                $data[] = array(
                    $peng->display('nama_lengkap'),
                    $mp->display('judul'),
                    $val[0],
                    $val[1],
                    Helpers::predikat($val[1]),
                    Helpers::kuadran($val[0], $val[1]),
                    $ids[0],
                    $ids[1]
                );
            }
            $i++;
        }
    } else {
        $data = [];
        foreach ($mps as $mp) {
            $mapel = pods('mata_pelajaran', $mp);
            
            if (!empty($mapel->field('pengajar'))) {
                $nama_peng = $mapel->field('pengajar.nama_lengkap');

                $id_peng = $mapel->field('pengajar.ID');

                for ($i = 0; $i < count($nama_peng); $i++) {
                    $data[] = array(
                        $nama_peng[$i],
                        $mapel->display('judul'),
                        '',
                        '',
                        '',
                        '',
                        $id_peng[$i],
                        $mapel->field('ID')
                    );
                }
            }
        }

        $data[] = array('Rata-rata', '', '', '', '', '', '', '');
    }
?>
    <script src="https://bossanova.uk/jspreadsheet/v4/jexcel.js"></script>
    <link rel="stylesheet" href="https://bossanova.uk/jspreadsheet/v4/jexcel.css" type="text/css" />

    <script src="https://jsuites.net/v4/jsuites.js"></script>
    <link rel="stylesheet" href="https://jsuites.net/v4/jsuites.css" type="text/css" />

    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">
    <div class="card">
        <div class="card-header has-background-kmk-mix has-text-centered">
            <h5>EDIT REKAP EVALUASI PENGAJAR</h5>
            <div class="card-header-right">
                <i id="back" class="ti ti-angle-double-left" title="Kembali"></i>
            </div>
        </div>
        <div class="card-content p-4">
            <div id="spreadsheet"></div>
            <div class="text-center mt-3">
                <button style="width:100px;" id="cancel" type="button" class="btn btn-danger mx-2">Cancel</button>
                <button style="width:100px;" id="submit_button" type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>

    <script>
        let sheet = jspreadsheet(document.getElementById('spreadsheet'), {
            data: <?= json_encode($data) ?>,
            tableOverflow: true,
            columns: [{
                    type: 'text',
                    title: 'Nama Pengajar',
                    width: ($('.card-content').width()) * 0.2,
                    align: 'left'
                },
                {
                    type: 'text',
                    title: 'Mata Pelajaran',
                    width: ($('.card-content').width()) * 0.4,
                    align: 'left'
                },
                {
                    type: 'text',
                    title: 'Harapan',
                    width: ($('.card-content').width()) * 0.12,
                },
                {
                    type: 'text',
                    title: 'Kenyataan',
                    width: ($('.card-content').width()) * 0.12,
                },
                {
                    type: 'dropdown',
                    source: ['Sangat Baik', 'Baik', 'Cukup', 'Kurang Baik', 'tidak Baik'],
                    title: 'Kategori',
                    width: ($('.card-content').width()) * 0.12
                },
                {
                    type: 'dropdown',
                    source: ['Kuadran I', 'Kuadran II', 'Kuadran III', 'Kuadran IV'],
                    title: 'Kuadran',
                    width: ($('.card-content').width()) * 0.12
                },
                {
                    type: 'hidden',
                    title: 'ID Pengajar',
                    width: ($('.card-content').width()) * 0
                },
                {
                    type: 'hidden',
                    title: 'ID MP',
                    width: ($('.card-content').width()) * 0
                }
            ],
            rowResize: true,
        });


        var ajaxurl = '<?= admin_url('admin-ajax.php') ?>';

        $('#submit_button').click(function() {
            $(this).html('<i class="fa fa-spinner fa-spin"></i>');
            var data = {
                action: 'edit_evaluasi_level_1',
                jenis: 'pengajar',
                json: sheet.getData(),
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
                        location.href = '<?= get_the_permalink() ?>?section=rekap_evaluasi_pengajar';
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
        $('#cancel').click(function() {
            $(this).html('<i class="fa fa-spinner fa-spin"></i>');
            window.location.href = document.referrer;
        });

        $('#back').click(function() {
            window.location.href = document.referrer;
        });
    </script>
    <?php
}

function page_rekap_evaluasi_pengajar()
{
    /*
    

    $params = array(
        'limit' => -1,
        'where' => "pelatihan.ID = '" . get_the_id() . "'"
    );

    $data_form = pods('data_form', $params);

    $data = [];

    while ($data_form->fetch()) {
        $this_data = json_decode($data_form->field('data'));
        foreach ($this_data as $k => $v) {
            if (substr($k, 0, 1) == 'p' || $k == 'q9' || $k == 'q10') {
                $data[$k][] = $v;
            }
        }
    }

    unset($data['form']);
    unset($data['responden']);
    unset($data['pelatihan']);

    $rekap = [];

    foreach ($data as $key => $value) {
        if (substr($key, 0, 1) == 'p') {
            $average = array_sum($value) / count($value);
            $rekap[$key] = number_format((float)round($average, 2), 2, '.', '');
        } else {
            $counts = array_count_values($value);
            $percent = $counts['ya'] / count($value) * 100;
            $rekap[$key] = (string)round($percent, 2) . '%';
        }
    }

    $harapan = 0;
    $kenyataan = 0;

    $stored = [];
    $lainnya = [];

    foreach ($rekap as $key => $v) {
        if (substr($key, 0, 1) == 'p') {
            $ids = substr($key, 1, -1);

            if (substr($key, -1) == 'h') {
                $harapan += $v;
                $stored[$ids]['h'] = $v;
            }

            if (substr($key, -1) == 'k') {
                $kenyataan += $v;
                $stored[$ids]['k'] = $v;
            }
        } else {
            $lainnya[$key] = $v;
        }
    }

    $har = number_format((float)round($harapan / ((count($rekap) - 2) / 2), 2), 2, '.', '');
    $ken = number_format((float)round($kenyataan / ((count($rekap) - 2) / 2), 2), 2, '.', '');

    $row = array(
        'h' => $har,
        'k' => $ken,
        //'p' => Helpers::predikat($har),
        //'ku' => Helpers::kuadran($har, $ken)
    );

    $stored['rerata'] = $row;

    $rekap_ev1 = (array)json_decode($pod->field('rekap_evaluasi_level_1'));

    $rekap_ev1['pengajar'] = $stored;
    $rekap_ev1['lainnya'] = $lainnya;

    $pod->save(array(
        'rekap_evaluasi_level_1' => json_encode($rekap_ev1)
    ));
*/

    if (isset($_GET['action']) && $_GET['action'] == 'edit') {
        page_edit_rekap_evaluasi_pengajar();
    } else {
        $pod = pods('pelatihan', get_the_id());
        $ev_1 = (array)json_decode($pod->field('rekap_evaluasi_level_1'));

        $h = $ev_1['pengajar']->rerata->h;
        $k = $ev_1['pengajar']->rerata->k;


        $params = array(
            'limit' => -1,
            'where' => "pelatihan.ID = '" . get_the_id() . "'"
        );

        $evajar = pods('evaluasi_pengajar', $params);

        $data = [];
        $i = 1;

        while ($evajar->fetch()) {
            $nilai = (array) json_decode($evajar->field('nilai'));
            $item = array(
                $i,
                $evajar->display('pengajar.nama_lengkap'),
                $evajar->display('mata_pelajaran'),
                $nilai['h'],
                $nilai['k'],
                $nilai['p'],
                $nilai['ku']
            );
            $data[] = $item;
            $i++;
        }
    ?>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/af-2.4.0/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/fc-4.1.0/fh-3.2.4/sc-2.0.7/sr-1.1.1/datatables.min.css" />
        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/af-2.4.0/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/fc-4.1.0/fh-3.2.4/sc-2.0.7/sr-1.1.1/datatables.min.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">

        <div class="card" id="evajar">
            <div class="card-header has-background-kmk-mix has-text-centered">
                <h5>REKAPITULASI EVALUASI PENGAJAR</h5>
                <div class="card-header-right">
                    <a class="bulk_editor" id="bulk_editor" href="?section=<?= $_GET['section'] ?>&action=edit"><i class="fa fa-table" title="Bulk Editor"></i></a>
                </div>
            </div>
            <div class="card-content p-4">
                <div class="container">
                    <table id="tabel_evajar" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th rowspan="2">No.</th>
                                <th rowspan="2">Nama Pengajar</th>
                                <th rowspan="2">Mata Pelajaran</th>
                                <th colspan="4" style="white-space: normal;text-align:center;">Kemampuan Pengajar dalam Memberikan<br />Bimbingan secara Jarak Jauh</th>

                            </tr>
                            <tr>
                                <th>Ekspektasi/Harapan</th>
                                <th>Persepsi/Kenyataan</th>
                                <th>Kategori</th>
                                <th>Kuadran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $d) : ?>
                                <tr>
                                    <td><?= $d[0] ?></td>
                                    <td><?= $d[1] ?></td>
                                    <td style="white-space: normal;"><?= $d[2] ?></td>
                                    <td><?= $d[3] ?></td>
                                    <td><?= $d[4] ?></td>
                                    <td><?= $d[5] ?></td>
                                    <td><?= $d[6] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-center">Rata - Rata</th>
                                <th class="text-center">
                                    <h5><?= $ev_1['pengajar']->rerata->h ?></h5>
                                </th>
                                <th class="text-center">
                                    <h5><?= $ev_1['pengajar']->rerata->k ?></h5>
                                </th>
                                <th class="text-center"><?= $ev_1['pengajar']->rerata->p ?></th>
                                <th class="text-center"><?= $ev_1['pengajar']->rerata->ku ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#tabel_evajar').DataTable({
                    order: [],
                    columnDefs: [
                        // Center align the header content of column 1
                        {
                            className: "dt-head-center",
                            targets: "all"
                        },
                        // Center align the body content of columns 2, 3, & 4
                        {
                            className: "dt-body-center",
                            targets: [0, 3, 4, 5, 6]
                        }
                    ],
                    dom: 'Bfrtip',
                    buttons: ['copy',
                        'excel',
                    ],
                    scrollX: true
                });
            });
        </script>

<?php }
} ?>