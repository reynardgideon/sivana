<?php
/*
Template Name: Undangan Rapat Kelulusan
Template Post Type: dokumen
*/

include_once(get_template_directory() . '/getters/component.php');
include_once(get_template_directory() . '/getters/pelatihan.php');

$pod = pods('pelatihan', $_GET['id_pelatihan']);
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
            border: 0;
        }

        table {
            color: black;
            font-size: 11pt;
            border: none;
        }

        .page {
            color: black;
            font-size: 11pt;
            line-height: 30px;
        }
    </style>
    <?= wp_head() ?>
</head>

<body>
    <div class="page" id="page">
        <?= Component::document_header() ?>
        <div class="has-text-centered mb-6">
            ..............................................................................................
            ..............................................................................................
            ..............................................................................................
        </div>
        <div class="has-text-justified mt-6 normal">
            <p class="mb-3 has-text-justify" style="text-indent: 50px;">
                Sehubungan dengan telah berakhirnya Pelatihan Jarak Jauh Fungsional Penata Laksana Barang di Lingkup Pemerintah Daerah Tahun 2022, dengan ini kami mengharapkan kehadiran Saudara dalam rapat kelulusan dan rekomendasi perbaikan pelatihan yang akan diselenggarakan pada:
            </p>
            <p class="mb-3 has-text-justify">
            <div class="mb-3 has-text-justify ml-2">
                <table style="color: #000; font-size: 11pt; font-family:Arial, Helvetica, sans-serif;width:100%;">
                    <tr style="height:25px;">
                        <td style="width: 20%; padding-left:10px;">
                            Hari/tanggal
                        </td>
                        <td style="width:5%;">: </td>
                        <td style="width:75%;">
                            Jumat, 21 Desember 2022
                        </td>
                    </tr>
                    <tr style="height:25px;">
                        <td style="padding-left:10px;">
                            Waktu
                        </td>
                        <td>: </td>
                        <td>
                            13.00 - 14.00 WIB
                        </td>
                    </tr>
                    <tr style="height:25px;border-color:#fff;">
                        <td style="padding-left:10px;">
                            Agenda
                        </td>
                        <td>: </td>
                        <td>
                            Rapat Kelulusan
                        </td>
                    </tr>
                    <tr style="height:25px;border:0;">
                        <td style="padding-left:10px;">
                            Tempat
                        </td>
                        <td>: </td>
                        <td>
                            Ruang Rapat Virtual Pusdiklat KNPK<br />
                            (Teams Meeting ID: 885 5031 1090 Passcode: 453796)
                        </td>
                    </tr>
                </table>
            </div>

            <p class="mb-3 has-text-justify dokumen">
                Mengingat pentingnya acara tersebut, kami harapkan Saudara hadir tepat waktu. Atas perhatian dan kerjasama Saudara, kami ucapkan terima kasih.
            </p>
        </div>
    </div>

    <div class="page normal" id="page">
        <div class="columns mt-3">
            <div class="column is-7"></div>
            <div class="column is-5" style="line-height:18px;">Lampiran<br />
                Undangan Kepala Pusdiklat KNPK<br />
                <div class="columns">
                    <div class="column is-3">Nomor<br />
                        Tanggal</div>
                    <div class="column is-9">: [@NomorND]<br />
                        : [@TanggalND]</div>
                </div>
            </div>
        </div>

        <div class="has-text-centered mt-4" style="line-height:20px;">
            <h6 class="title is-6">
                Daftar Undangan<br />
                Rapat Kelulusan <?= $pod->display('judul') ?>
            </h6>
        </div>

        <div class="has-text-justified mt-4 ml-6" style="line-height:20px;">
            <ol>
                <li>Kepala Bidang Perencanaan dan Pengembangan Pembelajaran</li>
                <li>Kepala Bidang Penyelenggaraan Pembelajaran</li>
                <li>Kepala Bidang Penjaminan Mutu Pembelajaran dan Sertifikasi</li>
                <li>Kasubbag Umum</li>
                <li>Kasubbid Program Pembelajaran dan Perencanaan Sertifikasi</li>
                <li>Kasubbid Desain Pembelajaran</li>
                <li>Kasubbid Teknologi Pembelajaran dan Manajemen Pengetahuan</li>
                <li>Kasubbid Penyelenggaraan Pembelajaran Terintegrasi</li>
                <li>Kasubbid Penyelenggaraan Pembelajaran Digital</li>
                <li>Kasubbid Penjaminan Mutu</li>
                <li>Kasubbid Evaluasi Pembelajaran dan Sertifikasi</li>
                <li>Kasubbid Pengelolaan Kinerja dan Risiko</li>

                <?php foreach (Pelatihan::get_pengajar($_GET['id_pelatihan']) as $nama) : ?>
                    <li><?= $nama ?> (Pengajar)</li>
                <?php endforeach ?>
            </ol>
            <button id="tambah_undangan" class="button  is-small is-info d-print-none" data-toggle="modal" data-target="#tambahUndangan">Tambah</button>
        </div>

        <div class="columns mt-4">
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
    </div>

    <!-- Modal -->
    <div class="modal fade" id="tambahUndangan" tabindex="-1" role="dialog" aria-labelledby="tambahUndanganLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahUndanganLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function($) {
            
        });
    </script>

</body>

</html>