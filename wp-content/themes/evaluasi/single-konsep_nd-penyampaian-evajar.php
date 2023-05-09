<?php
include_once(get_template_directory() . '/getters/helpers.php');
include_once(get_template_directory() . '/getters/component.php');
include_once(get_template_directory() . '/getters/mata-pelajaran.php');

$pod = pods('pelatihan', $_GET['id_pelatihan']);

$title = 'Penyampaian Rekapitulasi Evaluasi Pengajar ' . $pod->display('judul') . ' Tahun ' . date("Y",strtotime($pod->field('mulai')));
$pelaksanaan = Helpers::range_tanggal($pod->field('mulai'), $pod->field('selesai'));

$data = [];

$rekap = $pod->field('rekap_evaluasi_level_1') == '' ? [] : (array) json_decode($pod->field('rekap_evaluasi_level_1'));

$ev = (array)$rekap['pengajar'];

$i = 1;

foreach ($ev as $k => $v) {
    if ($i == count($ev)) {
        $data[] = array(
            '',
            '',
            'Rata-rata',
            $v->h,
            $v->k,
            $v->p,
            $v->ku
        );
    } else {
        $ids = explode('-', $k);
        $pengajar = pods('user', $ids[0]);
        $mp = pods('mata_pelajaran', $ids[1]);

        $data[] = array(
            $i,
            $pengajar->display('nama_lengkap'),
            $mp->display('judul'),
            $v[0],
            $v[1],
            Helpers::predikat($v[1]),
            Helpers::kuadran($v[0], $v[1])
        );
    }
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
                Sehubungan dengan telah selesainya <?= $pod->display('judul') ?> Tahun <?= date("Y", strtotime($pod->field('mulai'))) ?> yang diselenggarakan pada tanggal <?= $pelaksanaan ?>, kami sampaikan hasil Rekapitulasi Data Evaluasi Pengajar sebagai berikut:
            </p>
            <p class="mb-3 has-text-justify">
            <div class="mb-3 has-text-justify ml-2">
                <table class="bordered-table">
                    <thead style="font-weight: bold; text-align: center; vertical-align: middle;">
                        <tr>
                            <td rowspan="2" class="tmid">No.</td>
                            <td rowspan="2" class="tmid">Pengajar</td>
                            <td rowspan="2" class="tmid">Mata Pelajaran</td>
                            <td colspan="3" class="tmid">Kemampuan Pengajar dalam memberikan bimbingan secara jarak jauh</td>
                            <td rowspan="2" class="tmid" style="width:70px;">Kuadran</td>
                        </tr>
                        <tr>
                            <td class="tmid">Ekspektasi/ Harapan</td>
                            <td class="tmid">Persepsi/ Kenyataan</td>
                            <td class="tmid">Kategori</td>
                        </tr>
                    </thead>
                    <?php foreach ($data as $d) : ?>
                        <tr style="text-align: center;">
                            <?php for ($i = 0; $i < count($d); $i++) : ?>
                                <td class="tmid" style="text-align:<?= $i == 1 || $i == 2 ? 'justify' : 'center' ?>">
                                    <?= $d[$i] ?>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <p class="mb-3 has-text-justify dokumen">
                Berdasarkan hasil evaluasi tersebut, 12 (dua belar) mata pelajaran, semua pengajarnya memperoleh indeks kenyataan lebih tinggi daripada indeks harapannya. Hasil evaluasi untuk masing-masing pengajar kami sampaikan sebagaimana terlampir untuk dapat disampaikan kepada para pengajar dan dapat digunakan sebagai salah satu pertimbangan dalam membuat rekomendasi pengajar.
            </p>
            <p class="mb-3 has-text-justify dokumen">
                Atas perhatian Saudara, kami ucapkan terima kasih.
            </p>
        </div>
    </div>
</body>

</html>