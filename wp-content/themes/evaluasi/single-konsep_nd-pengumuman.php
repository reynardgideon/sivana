<?php
include_once(get_template_directory() . '/getters/helpers.php');
include_once(get_template_directory() . '/getters/component.php');

$pod = pods('pelatihan', $_GET['id_pelatihan']);
$pelatihan = str_replace('PJJ', 'Pelatihan Jarak Jauh ', $pod->display('judul'));
$judul = 'HASIL ' . strtoupper($pelatihan) . ' TAHUN ' . date("Y", strtotime($pod->field('mulai')));

$hasil = (array)json_decode($pod->field('hasil_pelatihan'));

$tl = (array)$hasil['tidak_lulus'];

$romans = ['', 'I', 'II', 'III'];
$dalam = '';

$lamp = 1;
if (count($tl) > 0) {
    $dalam .= ':';
    $dalam .= '<ol style="margin-left:10px;">';
    $dalam .= '<li>';
    $dalam .= 'Lampiran I pengumuman ini dinyatakan lulus dan diberikan sertifikat sesuai dengan ketentuan yang berlaku;';
    $dalam .= '</li>';

    $lamp++;
    if (isset($tl['Mengulang Ujian'])) {
        if (count($tl['Mengulang Ujian']) > 0) {
            $dalam .= '<li>';
            $dalam .= 'Lampiran ' . $romans[$lamp] . ' pengumuman ini dinyatakan tidak lulus dan perlu mengikuti ujian ulangan pada waktu yang akan diinfokan kemudian;';
            $dalam .= '</li>';

            $lamp++;
        }
    }

    if (isset($tl['Mengulang Pelatihan'])) {
        if (count($tl['Mengulang Pelatihan']) > 0) {
            $dalam .= '<li>';
            $dalam .= 'Lampiran ' . $romans[$lamp] . ' pengumuman ini dinyatakan tidak lulus dan perlu mengulang pelatihan;';
            $dalam .= '</li>';

            $lamp++;
        }
    }
} else {
    $dalam .= 'Lampiran Pengumuman ini dinyatakan Lulus, dan diberikan sertifikat sesuai dengan ketentuan yang berlaku.';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= get_the_title() ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/css/bootstrap/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <style>
        @page {
            size: A4
        }

        * .wp-block-table table tbody td,
        table tbody th,
        table tfoot td,
        table tfoot th {
            border: 1px solid #000;
        }

        table td {
            color: black;
            font-size: 11pt;
        }

        .page {
            color: black;
            font-size: 11pt;
            line-height: 30px;
        }

        table.bordered-table td {
            border: 1px solid #000;
            font-size: 10pt;
        }

        td.tmid {
            vertical-align: middle;
            padding: 5px;
            line-height: 14px;
        }
    </style>
    <?= wp_head() ?>
</head>

<body>
    <div class="page" id="page">
        <?= Component::document_header() ?>

        <div class="has-text-justified normal">
            <p class="has-text-centered" style="line-height:11pt;">
                PENGUMUMAN<br />
                NOMOR [@NomorND]
            </p>
            <p class="has-text-centered" style="line-height:11pt;">
                TENTANG<br />
                <?= $judul ?>
            </p>

            <p class="mb-3 has-text-justify" style="text-indent: 50px;">
                Kepala Pusat Pendidikan dan Pelatihan Kekayaan Negara dan Perimbangan Keuangan, sehubungan telah berakhirnya pelaksanaan <?= $pelatihan ?> di Pusdiklat KNPK yang diselenggarakan pada tanggal <?= str_replace('s.d.', 'sampai dengan', Helpers::range_tanggal($pod->field('mulai'), $pod->field('selesai'))) ?>, dengan ini menetapkan peserta yang namanya tercantum dalam <?= $dalam ?>
            </p>
            <p class="mb-3 has-text-justify" style="text-indent: 50px;">
                Demikian pengumuman ini kami buat dan hendaknya disebarluaskan.
            </p>
        </div>

        <div class="columns mt-1">
            <div class="column is-5 is-offset-7" style="line-height:13pt;">
                Ditetapkan di Jakarta<br />
                pada tanggal [@TanggalND]<br />
                Kepala Pusat Pendidikan dan Pelatihan Kekayaan Negara dan Perimbangan Keuangan<br />
                <br />
                <br />
                <br />
                <br />
                <br />
                <div class="mt-2" style="line-height:11px;">
                    <p style="color:#ccc">Ditandatangani secara elektronik</p>
                    <p id="ttd">Heru Wibowo</p><br />
                </div>
            </div>
        </div>
    </div>
</body>

</html>