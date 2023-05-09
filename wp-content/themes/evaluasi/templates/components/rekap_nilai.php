<?php
include_once(get_template_directory() . '/getters/mata-pelajaran.php');
include_once(get_template_directory() . '/getters/pengajar.php');
include_once(get_template_directory() . '/getters/helpers.php');
include_once(get_template_directory() . '/getters/daftar-nilai.php');
include_once(get_template_directory() . '/getters/pengolahan.php');
include_once(get_template_directory() . '/getters/pelatihan.php');

function page_rekap_nilai()
{

    $id_pelatihan = get_the_id();

    $pelatihan = pods('pelatihan', $id_pelatihan);

    $mps = $pelatihan->field('mata_pelajaran.ID');
    $peserta = $pelatihan->field('peserta.nip');
    $namas = $pelatihan->field('peserta.nama_lengkap');
    $komponen = (array) json_decode($pelatihan->field('komponen_na'));

    $k_nt = (int)$komponen['nt'];
    $k_nk = (int)$komponen['nk'];
    $k_npkl = (int)$komponen['npkl'];

    $np = Mata_Pelajaran::calculate_np($id_pelatihan);

    $tl = [];
    $nilai_mp = [];
    $pokok = Mata_Pelajaran::get_mp_by_jenis($id_pelatihan, 'pokok');
    $penunjang = Mata_Pelajaran::get_mp_by_jenis($id_pelatihan, 'penunjang');


    sort($pokok);
    sort($penunjang);

    foreach ($mps as $id) {
        $mp = pods('mata_pelajaran', $id);
        $npr = Mata_Pelajaran::get_npr($id);

        foreach ($peserta as $p) {
            $nt = ($npr[$p] / 100) * $np[$id];
            $nilai_mp[$p][$id] = array(
                $npr[$p],
                round($np[$id], 2),
                round($nt, 2)
            );
        }
    }

    $npkls = [];
    if ($k_npkl > 0) {
        $npkls = Daftar_Nilai::get_kompre_pkl($id_pelatihan, 'pkl');
        //$nk = Daftar_Nilai::get_kompre_pkl($id_pelatihan, 'nk');
    }


    $nks = [];
    if ($k_nk > 0) {
        $nks = Daftar_Nilai::get_kompre_pkl($id_pelatihan, 'k');
        //$nk = Daftar_Nilai::get_kompre_pkl($id_pelatihan, 'nk');
    }

    $data = [];
    $tidak_lulus = [];

    for ($i = 0; $i < count($peserta); $i++) {
        $tl = [];
        $total_nt = 0;
        $item = [];
        $item[] = $i + 1;
        $item[] = $namas[$i];
        $item[] = $peserta[$i];

        foreach ($pokok as $id) {
            $item[] = $nilai_mp[$peserta[$i]][$id][0];
            $item[] = $nilai_mp[$peserta[$i]][$id][1];
            $item[] = $nilai_mp[$peserta[$i]][$id][2];

            $total_nt += $nilai_mp[$peserta[$i]][$id][2];

            if ($nilai_mp[$peserta[$i]][$id][0] < 65) {
                $tl[] = $id;
            }
        }

        if (count($penunjang) > 0) {
            foreach ($penunjang as $id) {
                $item[] = $nilai_mp[$peserta[$i]][$id][0];
                $item[] = $nilai_mp[$peserta[$i]][$id][1];
                $item[] = $nilai_mp[$peserta[$i]][$id][2];

                $total_nt += $nilai_mp[$peserta[$i]][$id][2];

                if ($nilai_mp[$peserta[$i]][$id][0] < 60) {
                    $tl[] = $id;
                }
            }
        }

        $item[] = round($total_nt, 2);

        $na = 0;
        $nk = 0;
        $npkl = 0;

        if ($k_nk > 0) {
            //$arr_nk = array_filter($nks[$peserta[$i]]);
            $arr_nk = array_filter($nks[$peserta[$i]], function ($value) {
                return ($value !== null && $value !== false && $value !== '');
            });
            $nk = array_sum($arr_nk) / count($arr_nk);
            $item[] = round($nk, 2);
        }

        if ($k_npkl > 0) {
            //$arr_npkl = array_filter($npkls[$peserta[$i]]);
            $arr_npkl = array_filter($npkls[$peserta[$i]], function ($value) {
                return ($value !== null && $value !== false && $value !== '');
            });
            $npkl = array_sum($arr_npkl) / count($arr_npkl);
            $item[] = round($npkl, 2);
        }

        $na = ($k_nt / 100 * $total_nt) + ($k_nk / 100 * $nk) + ($k_npkl / 100 * $npkl);

        $item[] = round($na, 2);

        switch (true) {
            case $na >= 90:
                $item[] = "A";
                $item[] = "Amat Baik";
                break;
            case $na >= 76:
                $item[] = "B";
                $item[] = "Baik";
                break;
            case $na >= 65:
                $item[] = "C";
                $item[] = "Cukup";
                break;
            default:
                $item[] = "D";
                $item[] = "Kurang";
                break;
        }
        $lulus = 'Tidak Lulus';

        if (
            count($tl) == 0
            && $na > 64
            && $total_nt > 64
            && (($k_nk > 0 && $nk > 59) || $k_nk == 0)
            && (($k_npkl > 0 && $npkl > 59) || $k_npkl == 0)
        ) {
            $lulus = 'Lulus';
        }

        $item[] = $lulus;

        $tl_string = 'Mengulang ';
        $ket = '';

        if ($lulus == 'Tidak Lulus') {
            if ($pelatihan->field('jenis_evaluasi_level_2') == 'kompre') {
                $ket = count($tl) > 0 ? 'Mengulang Pelatihan' : 'Mengulang Ujian Komprehensif';
            } else {
                if (count($tl) == 0) {
                    $ket = 'Mengulang Pelatihan';
                }
                if (count($tl) > count(Pelatihan::get_mata_pelajaran_diujikan(get_the_ID())) / 2) {
                    $ket = 'Mengulang Pelatihan';
                } else {
                    foreach ($tl as $v) {
                        $x = array_search($v, $mps) + 1;
                        $tl_string .= 'MP ' . $x . ', ';
                    }
                    $ket = trim($tl_string, ', ');
                }
            }
        }
        /*
        $tl_string = '';
        if (count($tl) > 0) {
            foreach ($tl as $v) {
                $x = array_search($v, $mps) + 1;
                $tl_string .= 'MP ' . $x . ', ';
            }
            $tidak_lulus[$peserta[$i]] = $tl;
        }*/

        $item[] = $ket;

        $data[] = $item;

        $mulai = get_post_meta($id_pelatihan, 'mulai', true);
        $selesai = get_post_meta($id_pelatihan, 'selesai', true);
    }

    Pengolahan::save_rekap_nilai($id_pelatihan, $data);

    $title = '<h4 class="text-center">REKAPITULASI NILAI PESERTA</h4>';
    $title .= '<h3 class="text-center text-primary">' . strtoupper(get_the_title($id_pelatihan)) . '</h3>';
    $title .= '<h6 class="text-center">(' . Helpers::range_tanggal($mulai, $selesai) . ')</h6>';


?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <title>REKAP NILAI</title>
        <?= get_header() ?>
        <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/af-2.4.0/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/fc-4.1.0/fh-3.2.4/sc-2.0.7/sr-1.1.1/datatables.min.css" />
        <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/af-2.4.0/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/fc-4.1.0/fh-3.2.4/sc-2.0.7/sr-1.1.1/datatables.min.js"></script>
    </head>

    <body>

        <div class="card">
            <div class="card-header">
                <?= $title ?>
            </div>
            <div class="card-body">

                <table id="rekap" class="display cell-border" style="width:100%">
                    <thead>
                        <tr>
                            <th rowspan="3" style="text-align: center;">No.</th>
                            <th rowspan="3" style="text-align: center;">Nama</th>
                            <th rowspan="3" style="text-align: center;">NIP</th>
                            <th colspan="<?= count($pokok) * 3 ?>" style="text-align: center;">Mata Pelajaran Pokok</th>
                            <?php if (count($penunjang) > 0) : ?>
                                <th colspan="<?= count($penunjang) * 3 ?>" style="text-align: center;">Mata Pelajaran Penunjang</th>
                            <?php endif; ?>
                            <th rowspan="3" style="text-align: center;">Σ NT<br />(<?= $k_nt ?>%)</th>
                            <?= $k_nk > 0 ? '<th rowspan="3" style="text-align: center;">K<br/>(' . $k_nk . '%)</th>' : '' ?>
                            <?= $k_npkl > 0 ? '<th rowspan="3" style="text-align: center;">PKL<br/>(' . $k_npkl . '%)</th>' : '' ?>
                            <th rowspan="3" style="text-align: center;">NA</th>
                            <th rowspan="3" style="text-align: center;">Nilai<br />Huruf</th>
                            <th rowspan="3" style="text-align: center;">Predikat</th>
                            <th rowspan="3" style="text-align: center;">L/TL</th>
                            <th rowspan="3" style="text-align: center;">Ket.</th>

                        </tr>
                        <tr>
                            <?php $new_mps = array_merge($pokok, $penunjang); ?>
                            <?php for ($i = 0; $i < count($new_mps); $i++) : ?>
                                <th colspan="3" style="text-align: center;" title="<?= Mata_pelajaran::get_judul(($new_mps[$i])) ?>">MP <?= $i + 1 ?></th>
                            <?php endfor; ?>
                        </tr>
                        <tr>
                            <?php for ($i = 0; $i < count($new_mps); $i++) : ?>
                                <th style="text-align: center; width:200px;">NPR</th>
                                <th style="text-align: center;  width:200px;">NP</th>
                                <th style="text-align: center; width:200px;">NT</th>
                            <?php endfor; ?>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <style>
            th {
                border-top: 1px solid #dddddd;
                border-bottom: 1px solid #dddddd;
                border-right: 1px solid #dddddd;
                text-align: center;
            }

            div.dataTables_wrapper {
                width: 100%;
                margin: auto;
            }
        </style>

        <script>
            $(document).ready(function() {
                var table = $('#rekap').DataTable({
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'print',
                            title: '<?= $title ?>',
                            customize: function(win) {
                                $(win.document.body)
                                    .css('font-size', '10pt')
                                    .prepend(
                                        '<p class="text-center">Center aligned text on all viewport sizes.</p>'
                                    )
                                    .append('');

                                $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                            }
                        },
                        'copy',
                        'excel',
                        'excelHtml5'
                    ],
                    scrollX: true,
                    columnDefs: [{
                            targets: -1,
                            className: 'dt-body-center'
                        },
                        {
                            target: 2,
                            visible: false,
                        },

                    ],
                    fixedColumns: {
                        left: 2,
                    },
                    pageLength: 50,
                    order: [],
                    data: <?= json_encode($data) ?>,
                    scrollX: true,
                    scrollY: '320px',
                    scrollCollapse: true,
                    rowCallback: function(row, data, index) {
                        for (let p = 1; p <= <?= count($pokok) + count($penunjang); ?>; p++) {
                            let i = p * 3;
                            let min = p > <?= count($pokok) ?> ? 60 : 65;
                            if (data[i] < min) {
                                $(row).find('td:eq(' + (i - 1) + ')').css({
                                    'background-color': 'rgba(255, 0, 0, 0.6)',
                                    'color': 'white'
                                });
                            }
                        }

                        let j = data.length - 2;

                        if (data[j] == 'Tidak Lulus') {
                            $(row).find('td:eq(' + (j - 1) + ')').css({
                                'background-color': 'rgba(255, 0, 0, 0.6)',
                                'color': 'white'
                            });
                        }
                    }
                });

                table.on('order.dt search.dt', function() {
                    let i = 1;

                    table.cells(null, 0, {
                        search: 'applied',
                        order: 'applied'
                    }).every(function(cell) {
                        this.data(i++);
                    });
                }).draw();

                $('li[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                    $($.fn.dataTable.tables(true)).css('width', '100%');
                });

            });
        </script>
    <?php }
