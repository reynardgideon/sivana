<?php
/*
Template Name: Pengumuman Hasil Pelatihan
Template Post Type: dokumen
*/

include_once(get_template_directory() . '/getters/component.php');
include_once(get_template_directory() . '/getters/pelatihan.php');
include_once(get_template_directory() . '/getters/helpers.php');
include_once(get_template_directory() . '/getters/mata-pelajaran.php');

$pod = pods('pelatihan', $_GET['id_pelatihan']);

$hasil = json_decode($pod->field('hasil_pelatihan'));

$tl = (array)$hasil->tidak_lulus;
$lulus = (array)$hasil->lulus;
$mpel = (array)$tl['Mengulang Pelatihan'];
$mu = (array)$tl['Mengulang Ujian'];

$pages = ['lulus'];

if (count($mpel) > 0) {
    $pages[] = ['mpel'];
}

if (count($mu) > 0) {
    $pages[] = ['u'];
}

$lampiran = '';
$lampiran_page = '';
$lampiran_index = 1;
$judul_l1 = '';
$judul_l2 = '';
$judul_l3 = '';

if (($pod->field('jenis_evaluasilevel_2') !== 'prepost' && (count($mpel) + count($mu)) == 0) || $pod->field('jenis_evaluasilevel_2') == 'prepost') {
    $lulus_st = $pod->field('jenis_evaluasilevel_2') == 'prepost' ? 'Telah Mengikuti' : 'Lulus';
    $lampiran = ' Lampiran Pengumuman ini dinyatakan ' . $lulus_st . ', dan diberikan sertifikat sesuai dengan ketentuan yang berlaku.';
    $judul_l1 = 'PESERTA ' . $pod->display('judul') . ' YANG DINYATAKAN ' . $lulus_st;
} else {
    $lampiran = ':<div style="padding-left:30px;"><ol>';
    $lampiran .= ' <li>Lampiran I pengumuman ini dinyatakan Lulus dan diberikan sertifikat sesuai dengan ketentuan yang berlaku;</li>';

    $mengulang_ujian = (array)$tl['Mengulang Ujian'];
    if (count($mengulang_ujian) > 0) {
        $lampiran .= '<li>Lampiran II pengumuman ini dinyatakan Tidak Lulus dan harus mengikuti ujian ulangan pada waktu yang akan ditentukan kemudian;</li>';
        $judul_l2 = 'PESERTA ' . $pod->display('judul') . ' YANG DINYATAKAN TIDAK LULUS DAN HARUS MENGULANG UJIAN';
        $lampiran_index++;
    }

    $mengulang = (array)$tl['Mengulang Pelatihan'];
    if (count($mengulang) > 0) {
        $i = $lampiran_index == 2 ? 'III' : 'II';
        $lampiran .= '<li>Lampiran ' . $i . ' pengumuman ini dinyatakan Tidak Lulus dan harus mengulang pelatihan;</li>';
        $judul_l3 = 'PESERTA ' . $pod->display('judul') . ' YANG DINYATAKAN TIDAK LULUS DAN HARUS MENGULANG PELATIHAN';
    }

    $lampiran = trim($lampiran, ';</li>');

    $lampiran .= '.</li></ol></div>';
}

$i = 1;
$data = [];
foreach ($lulus as $l) {
    $item = [];
    $args =  array(
        'limit' => 1,
        'where' => "nip.meta_value = '" . $l . "'"
    );
    $pes = pods('user', $args);
    $item[] = $i;
    $item[] = $pes->display('nama_lengkap');
    $item[] = $pes->display('nip');
    $item[] = $pes->display('unit_kerja');
    $data[] = $item;
    $i++;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= get_the_title() ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/css/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <style>
        @page {
            size: A4
        }

        .page {
            color: black;
            font-size: 11pt;
            line-height: 30px;
        }

        table,
        td,
        th,
        tr {
            border: 1px solid #777;
        }
    </style>
    <?= wp_head() ?>
</head>

<body>
    <div class="page" id="nd">
        <?= Component::document_header() ?>
        <div class="has-text-centered mb-6">
            ..............................................................................................
            ..............................................................................................
            ..............................................................................................
        </div>
        <div class="has-text-justified mt-6 normal">
            <p class="mb-3 has-text-justify" style="text-indent: 50px;">
                Kepala Pusat Pendidikan dan Pelatihan Kekayaan Negara dan Perimbangan Keuangan, sehubungan telah berakhirnya pelaksanaan <?= $pod->display('judul') ?> di Pusdiklat KNPK yang diselenggarakan pada tanggal <?= Helpers::range_tanggal($pod->display('mulai'), $pod->display('selesai')) ?>, dengan ini menetapkan peserta yang namanya tercantum dalam<?= $lampiran ?>
            </p>
            <p class="mb-3 has-text-justify dokumen" style="text-indent: 50px;">
                Demikian pengumuman ini kami buat dan hendaknya disebarluaskan.
            </p>
        </div>
    </div>

    <div class="page normal" id="lampiran1">
        <div id="nomor_lampiran" class="columns mt-3">
            <div class="column is-7"></div>
            <div class="column is-5" style="line-height:14px;font-size:8pt;">LAMPIRAN<?= $lampiran_index == 1 ? '' : ' I' ?><br />
                Pengumuman Kepala Pusat Pendidikan dan Pelatihan Kekayaan Negara dan Perimbangan Keuangan
                <br />
                <div class="columns">
                    <div class="column is-3">Nomor<br />
                        Tanggal</div>
                    <div class="column is-9">: [@NomorND]<br />
                        : [@TanggalND]</div>
                </div>
            </div>
        </div>

        <div id="judul_lampiran" class="has-text-centered mt-4" style="line-height:20px;">
            <?= '<p class="has-text-centered"><b>' . strtoupper($judul_l1) . '</b></p>' ?>
        </div>
        <div id="tabel_lulus" class="has-text-centered mt-4" style="line-height:20px;">
            <table id="tabel_lampiran1" class="display dt" width="100%" style="border: 1px solid black;"></table>
        </div>

        <div id="tanda_tangan" class="columns mt-4">
            <div class="column is-5 is-offset-7">
                Kepala Pusat
                <br />
                <br />
                <br />
                <br />
                <div class="mt-2" style="line-height:20px;">
                    Ditandatangani secara elektronik
                    Heru Wibowo<br />
                    <button id="ubah_penandatangan" class="button is-small is-info d-print-none">Ubah</button>
                </div>
            </div>
        </div>
        <div class="buttons has-text-centered">
            <button id="setupDokButton" class="button is-info js-modal-trigger" data-target="setupDok">Setup Dokumen</button>
        </div>

        <div class="modal" id="setupDok">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Setup Dokumen</p>
                    <button class="delete" aria-label="close"></button>
                </header>
                <section class="modal-card-body">
                    <div class="field is-horizontal">
                        <div class="field-label is-normal">
                            <label class="label">Penandatangan</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <p class="control">
                                    <input class="input" type="ttd" placeholder="Nama Penandatangan">
                                </p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="label">Table Rows</label>
                        <div id="rows">
                            <div class="columns" id="rowGroup1">
                                <div class="column is-3">
                                    <div class="select">
                                        <select>
                                            <option>Lampiran 1</option>
                                            <option>Lampiran 2</option>
                                            <option>Lampiran 3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="column is-2">
                                    <input class="input" type="text" placeholder="Jumlah Row">
                                </div>
                                <div class="column is-6"></div>
                            </div>
                        </div>
                        <div class="buttons mt-2">
                            <button id="addGroup" class="button is-link">+</button>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button id="regeneratePage" class="button is-success">Save changes</button>
                </footer>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                var heights = $("#nomor_lampiran").height() + $("#judul_lampiran").height() + $("#tanda_tangan").height();

                var data = <?= json_encode($data) ?>

                var tl1 = $('#tabel_lampiran1').DataTable({
                    data: [],
                    columns: [{
                            title: 'NO'
                        },
                        {
                            title: 'NAMA'
                        },
                        {
                            title: 'NIP/NRP/NIK'
                        },
                        {
                            title: 'UNIT'
                        },
                    ],
                    columnDefs: [{
                            width: "5%",
                            target: 0
                        },
                        {
                            width: "30%",
                            target: 1
                        },
                        {
                            width: "15%",
                            target: 2
                        },
                        {
                            width: "50%",
                            target: 3
                        },
                        {
                            className: "dt-left",
                            target: 1
                        },
                        {
                            className: "dt-head-center",
                            targets: "_all"
                        },
                    ],
                    searching: false,
                    paging: false,
                    info: false,
                    ordering: false
                });

                data.forEach(function(item) {
                    if ($("#tabel_lampiran1").height() + heights < 900) {
                        tl1.row.add([item[0], item[1], item[2], item[3]]).draw();
                    }
                });

                var last_id = 1;                

                $('#setupDokButton').click(function() {
                    $('#setupDok').addClass('is-active');
                });

                $('.delete').click(function() {
                    $('#setupDok').removeClass('is-active');
                });

                $('#addGroup').click(function() {
                    var row = `<div id="row` + last_id + `" class="columns" id="rowGroup1">
                                <div class="column is-3">
                                    <div class="select">
                                        <select name="lampiran[]" id="select_lampiran">
                                            <option value="1">Lampiran 1</option>
                                            <option value="2">Lampiran 2</option>
                                            <option value="3">Lampiran 3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="column is-2">
                                    <input class="input" type="text" placeholder="Jumlah Row">
                                </div>
                                <div class="column is-6">                                
                                    <button id="`+ last_id + `" class="removeGroup button is-danger">-</button>
                                </div>
                            </div>`;
                    $('#rows').append(row);
                    last_id++;
                });

                $('#rows').on('click', function(e) {
                    $('#row' + e.target.id).remove();
                });

                $('#regeneratePage').click(function() {
                    $('#select_lampiran').val()
                });

            });
        </script>

</body>

</html>