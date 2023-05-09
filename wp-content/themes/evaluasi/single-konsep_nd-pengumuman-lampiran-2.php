<?php
include_once(get_template_directory() . '/getters/helpers.php');

$pod = pods('pelatihan', $_GET['id_pelatihan']);
$pelatihan = str_replace('PJJ', 'Pelatihan Jarak Jauh', $pod->display('judul')) . ' Tahun ' . date("Y", strtotime($pod->field('mulai')));
$judul = 'PESERTA ' . strtoupper($pelatihan) . ' DI ' . strtoupper($pod->display('lokasi')) . ' YANG DINYATAKAN TIDAK LULUS DAN PERLU MENGULANG UJIAN';

$hasil = (array) json_decode($pod->field('hasil_pelatihan'));

$tl = (array) $hasil['tidak_lulus'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= get_the_title() ?></title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">
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

        .table_td {
            color: #000;
            font-size: 11pt;
            border: 1px solid #000;
            padding: 5px;
            line-height: 20px;
            vertical-align: middle;
        }

        table th {
            border: 1px solid #000;
            vertical-align: middle;
            text-align: center;
            padding: 5px;
            color: #000;
        }

        table {
            margin: 50px 0px;
        }

        .page {
            width: 21cm;
            color: #000;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 30px;
            padding: 0px 20px;
            margin:auto;
        }

        table.bordered-table td {
            border: 1px solid #000;
            font-size: 10pt;
            line-height: 14px;
        }

        td.tmid {
            vertical-align: middle;
            padding: 5px;
            line-height: 14px;
        }

        #presentation:hover {
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
            transition: all 0.3s;
            transform: translateZ(10px);
        }

        #edit_nd {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: #db4437;
            position: fixed;
            bottom: 30px;
            right: 30px;
            cursor: pointer;
            box-shadow: 0px 2px 5px #666;
        }

        .plus {
            color: white;
            position: absolute;
            top: 0;
            display: block;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            padding: 0;
            margin: 0;
            line-height: 55px;
            font-size: 20px;
            font-family: 'Roboto';
            font-weight: 300;
        }

        #container-floating {
            position: fixed;
            width: 70px;
            height: 70px;
            bottom: 30px;
            right: 30px;
            z-index: 50px;
        }

        @media print {
            .noprint {
                visibility: hidden;
            }
        }
    </style>
    <?= wp_head() ?>
</head>

<body>
    <div class="page" id="page">
        <div class="columns mt-3">
            <div class="column is-7"></div>
            <div class="column is-5" style="line-height:18px; color: #000; margin-bottom:30px;"><span id="lampiran">Lampiran II</span><br />
                Pengumuman Kepala Pusat Pendidikan dan Pelatihan Kekayaan Negara dan Perimbangan Keuangan<br />
                <div class="columns">
                    <div class="column is-3">Nomor<br />
                        Tanggal</div>
                    <div class="column is-9">: [@NomorND]<br />
                        : [@TanggalND]</div>
                </div>
            </div>
        </div>

        <div class="has-text-centered mt-4 px-5" style="line-height:20px; margin-top: 100px; color: #000;">
            <h6 class="title is-6">
                <?= $judul ?>
            </h6>
        </div>

        <div class="has-text-left mt-2" style="line-height:20px;">
            <table style="width:100%; margin-bottom: 5px;">
                <thead class="has-text-centered">
                    <th>NO.</th>
                    <th>NAMA</th>
                    <th>NIP/NRP/NIK</th>
                    <th>UNIT</th>
                    <th>KET.</th>
                </thead>
                <?php $i = 1; ?>
                <?php foreach ((array) $tl['Mengulang Ujian'] as $obj): ?>
                    <tr>
                        <?php $peserta = pods('user', array('limit' => 1, 'where' => "nip.meta_value ='" . $obj->nip . "'")); ?>
                        <td class="table_td" style="text-align: center;"><?= $i ?>.</td>
                        <td class="table_td"><?= $peserta->field('nama_lengkap') ?></td>
                        <td class="table_td"><?= $peserta->field('nip') ?></td>
                        <td class="table_td"><?= $peserta->field('unit_kerja') ?></td>
                        <td class="table_td"><?= $obj->ket ?></td>
                        <?php $i++ ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="columns mt-4" style="margin-top:40px;">
            <div class="column is-5 is-offset-7">
                <p id="jabatan">Kepala Pusat</p>
                <br />
                <br />
                <br />
                <br />
                <div class="mt-2" style="line-height:20px;">
                    <p style="color:#ccc">Ditandatangani secara elektronik</p>
                    <p id="ttd">Heru Wibowo</p><br />
                </div>
            </div>
        </div>
    </div>

    <div id="edit_modal" class="modal" style="padding-top:0px;">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Ubah</p>
                <button class="delete cancel" aria-label="close"></button>
            </header>
            <section class="modal-card-body">
                <p class="my-2 mt-4"><b>Lampiran</b></p>
                <div class="control">
                    <input class="input" type="text" value="Lampiran II" name="lampiran">
                    <p>Contoh: Lampiran I</p>
                </div>

                <p class="my-2 mt-4"><b>Penandatangan</b></p>
                <div class="control">
                    <input class="input" type="text" value='Heru Wibowo' name="ttd">
                    <p>Contoh: Heru Wibowo</p>
                </div>

                <p class="my-2 mt-4"><b>Jabatan Penandatangan</b></p>
                <div class="control">
                    <input class="input" type="text" value='Kepala Pusat' name="jabatan">
                    <p>Contoh: Kepala Pusat</p>
                </div>

                <p class="my-2 mt-4"><b>Line Height</b></p>
                <div class="control">
                    <input class="input" type="text" value="20" name="lh">
                    <p>Contoh: 20</p>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button id="oke" class="button is-success">OK</button>
                <button class="button cancel">Cancel</button>
            </footer>
        </div>
    </div>

    <div id="container-floating" class="noprint">
        <div id="edit_nd" data-toggle="tooltip" data-placement="left" data-original-title="Create" onclick="newmail()">
            <p class="plus"><i style="cursor: pointer;" class="fa fa-edit fa-2xs"></i></p>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#edit_nd').click(function() {
                $('#edit_modal').show();
            });

            $('.cancel').click(function() {
                $('#edit_modal').hide();
            });

            $('#oke').click(function() {
                $(".table_td").css("line-height", $("input[name='lh']").val() + 'px');
                $('#lampiran').text($("input[name='lampiran']").val());
                $('#ttd').text($("input[name='ttd']").val());
                $('#jabatan').text($("input[name='jabatan']").val());
                $('#edit_modal').hide();
            });

        });
    </script>

</body>

</html>