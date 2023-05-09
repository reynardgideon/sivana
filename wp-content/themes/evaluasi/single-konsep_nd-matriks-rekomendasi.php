<?php
include_once(get_template_directory() . '/getters/helpers.php');
include_once(get_template_directory() . '/getters/component.php');

$pod = pods('pelatihan', $_GET['id_pelatihan']);
$pelatihan = str_replace('PJJ', 'Pelatihan Jarak Jauh ', $pod->display('judul'));

$ev_1 = (array)json_decode($pod->field('rekap_evaluasi_level_1'));

$params = array(
    'limit' => -1,
    'where' => "pelatihan.ID = '" . $_GET['id_pelatihan'] . "' AND matriks_rekomendasi.meta_value=1",
    'orderby' => 'tujuan ASC'
);

$saran = pods('saran', $params);

$total = $ev_1['responden'];

$i = 1;
$data = [];
if ($total > 0) {
    while ($saran->fetch()) {
        if ($saran->field('aktif') == 1) {
            $index = $i - 1;
            $item = array(
                $i . '.',
                $saran->field('isi'),
                $saran->field('frekuensi'),
                (string) number_format($saran->field('frekuensi') / $total * 100, 1, ",", ".") . '%',
                $saran->field('analisis') == '' ? '-' : $saran->field('analisis'),
                $saran->field('tanggapan') == '' ? '-' : $saran->field('tanggapan'),
                $saran->field('uraian_rekomendasi') == '' ? '-' : $saran->field('uraian_rekomendasi'),
                $saran->field('tindak_lanjut') == '' ? '-' : $saran->field('tindak_lanjut'),
                $saran->field('target_waktu_penyelesaian') == '' ? '-' : $saran->field('target_waktu_penyelesaian')
            );
            $data[$saran->field('tujuan')][] = $item;
            $i++;
        }
    }
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
        body {
            background: rgb(204, 204, 204);
        }

        page {
            background: white;
            display: block;
            margin: 0 auto;
            padding: 1cm;
            margin-bottom: 0.5cm;
            box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
        }

        page[size="A4"][layout="landscape"] {
            width: 29.7cm;
            height: auto;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin-bottom: 1cm;
                margin-top: 1cm;
            }
        }

        .tg {
            border-collapse: collapse;
            border-spacing: 0;
        }

        .tg td {
            border-color: black;
            border-style: solid;
            border-width: 1px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            overflow: hidden;
            padding: 10px 5px;
            word-break: normal;
        }

        .tg th {
            border-color: black;
            border-style: solid;
            border-width: 1px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: bold;
            overflow: hidden;
            padding: 10px 5px;
            word-break: normal;
            
        }
    </style>
    <?= wp_head() ?>
</head>

<body>
    <page size="A4" layout="landscape">

        <div class="has-text-justified normal">
            <p class="has-text-centered font-weight-bold" style="line-height:11pt;">
                MATRIKS REKOMENDASI<br />
                <?= strtoupper($pelatihan) . ' TAHUN ' . date("Y", strtotime($pod->field('mulai'))) ?><BR />
                PUSDIKLAT KEKAYAAN NEGARA DAN PERIMBANGAN KEUANGAN<br />
                TAHUN 2023
            </p>
            <p class="has-text-justify" style="text-indent: 50px;">
            <table class="tg">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Masukan Peserta</th>
                        <th>Frek.</th>
                        <th>%</th>
                        <th>Analisis Masukan Peserta</th>
                        <th>Masukan Saat Rapat Kelulusan</th>
                        <th>Uraian Rekomendasi</th>
                        <th>Tindak Lanjut</th>
                        <th>Target Waktu Penyelesaian</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $tujuan => $d) : ?>
                        <tr>
                            <td class="tg-0lax font-weight-bold" colspan="9"><?= $tujuan ?></td>
                        </tr>
                        <?php foreach ($d as $row) : ?>
                            <tr>
                                <?php foreach ($row as $col) : ?>
                                    <td>
                                        <?= $col ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </p>
        </div>
        </div>
</body>

</html>