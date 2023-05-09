<?php
include_once(get_template_directory() . '/getters/helpers.php');
include_once(get_template_directory() . '/getters/component.php');

$pod = pods('pelatihan', $_GET['id_pelatihan']);
$pelatihan = str_replace('PJJ', 'Pelatihan Jarak Jauh ', $pod->display('judul'));
$hal = 'Undangan Rapat Kelulusan dan Rapat Rekomendasi ' . $pelatihan . ' Tahun ' . date("Y", strtotime($pod->field('mulai')));

$judul = $pod->display('judul') . ' Tahun ' . date("Y", strtotime($pod->field('mulai')));

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
        @media print {
            @page {
                size: A4;
                margin-bottom: 1cm;
                margin-top: 1cm;
            }
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
                    Nomor
                </td>
                <td style="width:5%;">: </td>
                <td style="width:75%;">
                    [@NomorND]
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
                    1 (satu) lembar
                </td>
            </tr>
            <tr style="padding: 0px auto; margin: 0px auto;line-height:14pt;">
                <td style="padding-left:10px;">
                    Hal
                </td>
                <td>: </td>
                <td>
                    <?= $hal ?>
                </td>
            </tr>
        </table>

        <div class="has-text-justified mt-6 normal">
            <p class="mb-3 has-text-justify">
                Yth. Para Pejabat/Pegawai Terlampir
            </p>
            </p>
            <p class="mb-3 has-text-justify" style="text-indent: 50px;">
                Sehubungan dengan telah berakhirnya <?= $pod->display('judul') ?> Tahun <?= date("Y", strtotime($pod->field('mulai'))) ?>, dengan ini kami mengharapkan kehadiran Saudara dalam Rapat Kelulusan dan Rapat Rekomendasi yang akan diselenggarakan pada:
            </p>
            <p class="mb-3 has-text-justify">
            <table style="color: #000; font-size: 11pt; font-family:Arial, Helvetica, sans-serif;width:100%;">
                <tr style="padding: 0px auto; margin: 0px auto;line-height:14pt;">
                    <td style="width: 20%; padding-left:10px;">
                        Hari/Tanggal
                    </td>
                    <td style="width:5%;">: </td>
                    <td style="width:75%;">
                        Senin/13 Maret 2023
                    </td>
                </tr>
                <tr style="padding: 0px auto; margin: 0px auto;line-height:14pt;">
                    <td style="width: 20%; padding-left:10px;">
                        Waktu
                    </td>
                    <td style="width:5%;">: </td>
                    <td style="width:75%;">
                        <ol style="margin:5px 0px; margin-left:20px;">
                            <li>
                                Pukul 13.30 s.d. 14.30 (Rapat Kelulusan <?= $judul ?>)
                            </li>
                            <li>
                                Pukul 14.30 s.d. 15.30 (Rapat Rekomendasi <?= $judul ?>)
                            </li>
                        </ol>
                    </td>
                </tr>
                <tr style="padding: 0px auto; margin: 0px auto;line-height:14pt;">
                    <td style="width: 20%; padding-left:10px;">
                        Tempat
                    </td>
                    <td style="width:5%;">: </td>
                    <td style="width:75%;">
                        Ruang Rapat Virtual Pusdiklat KNPK/Microsoft Teamsmeeting<br />
                        (Meeting ID: 436 702 883 663 Passcode: r3DWUA
                    </td>
                </tr>
            </table>
            </p>
            <p class="mb-3 has-text-justify"">
                Mengingat pentingnya acara tersebut, kami harapkan Saudara hadir tepat waktu.
            </p>
            <p class=" mb-3 has-text-justify" style="text-indent: 50px;">
                Dapat kami sampaikan bahwa dalam rangka mewujudkan Zona Integritas menuju Wilayah Birokrasi Bersih dan Melayani(ZI-WBBM), Pusdiklat Kekayaan Negara dan Perimbangan Keuangan berkomitmen untuk memberikan pelayanan dengan SIGAP (Santun, Inovatif, Gesit, Akuntabel dan Paripurna).
            </p>          

        </div>
    </div>

    <div class="page" id="page">
        <p class="mb-3 has-text-justify" style="text-indent: 50px;">
            Dapat kami sampaikan bahwa dalam rangka mewujudkan Zona Integritas menuju Wilayah Birokrasi Bersih dan Melayani(ZI-WBBM), Pusdiklat Kekayaan Negara dan Perimbangan Keuangan berkomitmen untuk memberikan pelayanan dengan SIGAP (Santun, Inovatif, Gesit, Akuntabel dan Paripurna).
        </p>
    </div>
</body>

</html>