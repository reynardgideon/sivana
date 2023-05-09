<?php

/**
 * Template Name: Rekap Nilai
 *
 * @package WordPress
 */

include_once(get_template_directory() . '/getters/mata-pelajaran.php');
include_once(get_template_directory() . '/getters/pengajar.php');
include_once(get_template_directory() . '/getters/helpers.php');
include_once(get_template_directory() . '/getters/daftar-nilai.php');

$id_pelatihan = $_GET['id_pelatihan'];

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

for ($i = 0; $i < count($peserta); $i++) {
    $total_nt = 0;
    $item = [];
    $item[] = $i + 1;
    $item[] = $namas[$i];

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
                $tl[] = $nilai_mp[$peserta[$i]][$id][0];
            }
        }
    }

    $item[] = round($total_nt, 2);

    $na = 0;
    $nk = 0;
    $npkl = 0;

    if ($k_nk > 0) {
        $arr_nk = array_filter($nks[$peserta[$i]]);
        $nk = array_sum($arr_nk) / count($arr_nk);
        $item[] = round($nk, 2);;
    }

    if ($k_npkl > 0) {
        $arr_npkl = array_filter($npkls[$peserta[$i]]);
        $npkl = array_sum($arr_npkl) / count($arr_npkl);
        $item[] = round($npkl, 2);
    }

    $na = ($k_nt / 100 * $total_nt) + ($k_nk / 100 * $nk) + ($k_npkl / 100 * $npkl);
    //$na = $k_nk;

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
        && ($k_nk > 0 && $nk > 60) || $k_nk == 0
        && ($k_npkl > 0 && $npkl > 60) || $k_npkl == 0
    ) {
        $lulus = 'Lulus';
    }

    $item[] = $lulus;
    $data[] = $item;

    $mulai = get_post_meta($id_pelatihan, 'mulai', true);
    $selesai = get_post_meta($id_pelatihan, 'selesai', true);
}

$title = '<h4 class="text-center">REKAPITULASI NILAI PESERTA</h4>';
$title .= '<h3 class="text-center text-primary">'.strtoupper(get_the_title($id_pelatihan)).'</h3>';
$title .= '<h6 class="text-center">('.Helpers::range_tanggal($mulai, $selesai).')</h6>';
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
            <div class="card-header-right">
                <button id="back" type="button" class="btn btn-primary" style="width:100px;">Kembali</button>
            </div>
        </div>
        <div class="card-body">

            <table id="rekap" class="display cell-border" style="width:100%">
                <thead>
                    <tr>
                        <th rowspan="3" style="text-align: center;">No.</th>
                        <th rowspan="3" style="text-align: center;">Nama</th>
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
                        
                    </tr>
                    <tr>
                        <?php for ($i = 0; $i < count($mps); $i++) : ?>
                            <th colspan="3" style="text-align: center;">MP <?= $i + 1 ?></th>
                        <?php endfor; ?>
                    </tr>
                    <tr>
                        <?php for ($i = 0; $i < count($mps); $i++) : ?>
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
                            .append('')
                            ;

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }],
                scrollX: true,
                columnDefs: [{
                        targets: -1,
                        className: 'dt-body-center'
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
                scrollCollapse: true
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

            $('#back').click(function() {
                $(this).html('<i class="fa fa-spinner fa-spin"></i>');
                history.back();
            });
        });
    </script>
</body>

</html>