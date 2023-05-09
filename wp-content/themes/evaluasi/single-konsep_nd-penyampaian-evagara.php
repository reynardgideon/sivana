<?php
include_once(get_template_directory() . '/getters/helpers.php');
include_once(get_template_directory() . '/getters/component.php');

$pod = pods('pelatihan', $_GET['id_pelatihan']);

$title = 'Penyampaian Rekapitulasi Evaluasi Penyelenggaraan dan Evaluasi Pengajar ' . $pod->display('judul') . ' Tahun ' . date("Y", strtotime($pod->field('mulai')));
$pelaksanaan = Helpers::range_tanggal($pod->field('mulai'), $pod->field('selesai'));

$jenis = $pod->field('jenis_pelatihan');

$diasramakan = $pod->field('diasramakan');

$qs = [];

switch ($jenis) {
    case 'klasikal':
        $qs = $diasramakan == 0 ? ASPEK_KLASIKAL_NON_ASRAMA : ASPEK_KLASIKAL_ASRAMA;
    case 'e-learning':
        $qs = ASPEK_ELEARNING;
    default:
        $qs = ASPEK_PJJ;
}

$data = [];
$rekap = $pod->field('rekap_evaluasi_level_1') == '' ? [] : (array) json_decode($pod->field('rekap_evaluasi_level_1'));

$ev = (array)$rekap['penyelenggaraan'];

if (count($ev) > 0) {
    for ($i = 0; $i < count($ev); $i++) {
        if ($i == count($ev) - 1) {
            $aspek = 'Rata-rata';
            $index = 'rerata';
        } else {
            $aspek = $qs[$i];
            $index = 'q' . $i + 1;
        }

        $data[] = array(
            $i == count($ev) - 1 ? '' : $i + 1,
            $aspek,
            $ev[$index]->h,
            $ev[$index]->k,
            $ev[$index]->p,
            $ev[$index]->ku
        );
    }
} else {
    foreach ($qs as $q) {
        $data[] = array($q, '', '', '', '');
    }
    $data[] = array('Rata-rata', '', '', '', '');
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
            font-size:10pt;
        }

        td.tmid {
            vertical-align: middle;
            padding:5px;
            line-height:14px;
        }
    </style>
    <?= wp_head() ?>
</head>

<body>
    <div class="page" id="page">
        <?= Component::document_header() ?>
        <table style="color: #000; font-size: 11pt; font-family:Arial, Helvetica, sans-serif;width:100%;">
            <tr style="padding: 0px auto; margin: 0px auto;line-height:14pt;">
                <td style="width: 20%; padding-left:10px;">
                    Yth.
                </td>
                <td style="width:5%;">: </td>
                <td style="width:75%;">
                    Kepala Pusat Pendidikan dan Pelatihan Kekayaan Negara dan Perimbangan Keuangan
                </td>
            </tr>
            <tr style="padding: 0px auto; margin: 0px auto;line-height:14pt;">
                <td style="padding-left:10px;">
                    Dari
                </td>
                <td>: </td>
                <td>
                    Kepala Bidang Penjaminan Mutu Pembelajaran dan Sertifikasi
                </td>
            </tr>
            <tr style="padding: 0px auto; margin: 0px auto;line-height:14pt;">
                <td style="padding-left:10px;">
                    Sifat
                </td>
                <td>: </td>
                <td>
                    Biasa
                </td>
            </tr>
            <tr style="padding: 0px auto; margin: 0px auto;line-height:14pt;">
                <td style="padding-left:10px;">
                    Lampiran
                </td>
                <td>: </td>
                <td>
                    1 (satu) berkas
                </td>
            </tr>
            <tr style="padding: 0px auto; margin: 0px auto;line-height:14pt;">
                <td style="padding-left:10px;">
                    Hal
                </td>
                <td>: </td>
                <td>
                    <?= $title ?>
                </td>
            </tr>
            <tr style="padding: 0px auto; margin: 0px auto;line-height:14pt;">
                <td style="padding-left:10px;">
                    Tanggal
                </td>
                <td>: </td>
                <td>
                    <?= Helpers::tanggal(date('Y-m-d')) ?>
                </td>
            </tr>
        </table>
        <div class="has-text-justified mt-6 normal">
            <p class="mb-3 has-text-justify" style="text-indent: 50px;">
                Sehubungan dengan telah selesainya <?= $pod->display('judul') ?> Tahun <?= date("Y", strtotime($pod->field('mulai'))) ?> yang diselenggarakan pada tanggal <?= $pelaksanaan ?>, kami sampaikan hasil Rekapitulasi Data Evaluasi Penyelenggaraan Pelatihan sebagai berikut:
            </p>
            <p class="mb-3 has-text-justify">
            <div class="mb-3 has-text-justify ml-2">
                <table class="bordered-table">
                    <thead style="font-weight: bold; text-align: center; vertical-align: middle;">
                        <tr>
                            <td rowspan="2" class="tmid">No.</td>
                            <td rowspan="2" class="tmid">Aspek Penilaian</td>
                            <td rowspan="2" class="tmid">Rata-Rata<br />Kepentingan (Y)</td>
                            <td colspan="2" class="tmid">Kenyataan (X)</td>
                            <td rowspan="2" class="tmid" style="width:70px;">Kuadran</td>
                        </tr>
                        <tr>
                            <td class="tmid">Rata-Rata</td>
                            <td class="tmid">Kategori</td>
                        </tr>
                    </thead>
                    <?php foreach ($data as $d) : ?>
                        <tr style="text-align: center;">
                            <?php for ($i=0; $i < count($d); $i++): ?>
                                <td class="tmid" style="text-align:<?= $i == 1 ? 'justify' : 'center' ?>">
                                    <?= $d[$i] ?>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <p class="mb-3 has-text-justify dokumen">
                Beberapa informasi lain mengenai hasil evaluasi pada PJJ ini adalah sebagai berikut:
            </p>
        </div>
    </div>
</body>

</html>