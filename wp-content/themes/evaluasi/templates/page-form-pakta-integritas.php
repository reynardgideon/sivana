<?php

/**
 * Template Name: Form Pakta Integritas
 * Template Post Type: form
 * @package WordPress
 */

$fields = array(
    'tanggal' => 'Pada hari ini, tanggal',
    'nama_lengkap' => 'Nama Lengkap',
    'nip' => 'NIP',
    'unit_kerja' => 'Unit Kerja'
);

$pod = pods('pelatihan', $_GET['id_pelatihan']);

if (isset($_GET['id_pelatihan'])) {
    $nip_p = $pod->field('peserta.nip');
    $id_p = $pod->field('peserta.ID');
    $nama_p = $pod->field('peserta.nama_lengkap');
    $unit_p = $pod->field('peserta.unit_kerja');

    $data_peserta = [];

    for ($j = 0; $j < count($nip_p); $j++) {
        $item = [];
        $item['label'] = $nama_p[$j];
        $item['nip'] = $nip_p[$j];
        $item['unit_kerja'] = $unit_p[$j];
        $item['responden'] = $id_p[$j];
        $data_peserta[] = $item;
    }
} else {
    wp_redirect(get_site_url() . '/404/');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pakta Integritas <?= get_the_title() ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    
    <script>
        $(function() {
            $("#datepicker").datepicker({
                dateFormat: "DD, dd MM yy",
                dayNames: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                dayNamesMin: ["Mi", "Se", "Sel", "Ra", "Ka", "Ju", "Sa"],
                monthNames: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"]
            });
            $('#datepicker').datepicker('setDate', 'today');
        });

        $(document).ready(function() {          

            var names = <?= json_encode($data_peserta) ?>;
            $("#nama_lengkap").autocomplete({
                source: names,
                autoFocus: true
            });

            $("#nama_lengkap").change(function() {
                var result = names.filter(obj => {
                    return obj.label === $(this).val()
                })

                if (result.length > 0) {
                    $("#nip").val(result[0].nip);
                    $("#responden").val(result[0].responden);
                    $("#unit_kerja").val(result[0].unit_kerja);
                } else {
                    alert('Maaf, nama yang anda masukkan tidak terdaftar sebagai peserta PJJ ini!')
                    $("#nama_lengkap").val('');
                    $("#nama_lengkap").focus();
                    $("#nip").val('');
                    $("#unit_kerja").val('');
                }
            });
        });
    </script>

</head>

<body>
    <div id="tes">
        <section class="hero is-fullheight has-background-grey-light">
            <div class="columns is-centered">
                <div class="column is-two-thirds-desktop has-background-white mt-5">
                    <div class="has-text-centered">
                        <figure class="image mx-5 mb-10 is-inline-block">
                            <img src="https://evaluasi.knpk.xyz/wp-content/uploads/2023/03/corpu-2.png">
                        </figure>
                        <br />
                        <h1 class="title mt-5 mx-5 is-uppercase" style="line-height:40px;">
                            PAKTA INTEGRITAS UJIAN <br />
                            <?= $pod->display('judul') ?><br />
                        </h1>
                        <p class="subtitle is-4 mx-5">
                            TAHUN <?= date("Y", strtotime($pod->field('mulai'))) ?>
                        </p>
                        <hr />
                    </div>
                    <form id="form_pakta_integritas" method="POST" action="">
                        <?php foreach ($fields as $field => $label) {
                            if ($field == 'tanggal') {
                        ?>
                                <div class="mx-6 columns is-flex is-vcentered">
                                    <div class="column is-4">
                                        <label class="label"><?= $label ?></label>
                                    </div>
                                    <div class="column is-8">
                                        <div class="control">
                                            <input id="datepicker" class="input" type="text" name="tanggal" value="">
                                        </div>
                                    </div>
                                </div>
                            <?php
                            } else {
                            ?>
                                <div class="mx-6 columns is-flex is-vcentered">
                                    <div class="column is-4">
                                        <label class="label"><?= $label ?></label>
                                    </div>

                                    <div class="column is-8">
                                        <div class="control">
                                            <input class="input" type="text" name="<?= $field ?>" value="" id="<?= $field ?>" >
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        }
                        ?>
                        <input type="hidden" name="action" value="submit_pakta_integritas">
                        <input type="hidden" name="form" value="7248">
                        <input type="hidden" name="responden" value="" id="responden">
                        <input type="hidden" name="pelatihan" value="<?= $_GET['id_pelatihan'] ?>">
                    </form>
                    <div class="has-text-justify mx-6">
                        <p class="mb-3">
                            dalam rangka menjadi Peserta Ujian di lingkungan Kementerian Keuangan, dengan ini menyatakan bahwa saya akan:
                        </p>
                        <p class="mb-3">
                        <ol class="pl-6 mb-3">
                            <li>Bersikap transparan, jujur, obyektif, dan akuntabel dalam mengikuti ujian;</li>
                            <li>Menghindari tindakan kecurangan dalam mengikuti ujian;</li>
                            <li>Menjaga kerahasiaan isi dari rangkaian ujian, baik pada pra pelaksanaan, pelaksanaan, maupun paska pelaksaaan ujian;</li>
                            <li>Bekerja secara mandiri dalam mengerjakan penugasan ujian;</li>
                            <li>Bertanggungjawab dan melakukan rangkaian ujian secara lengkap;</li>
                            <li>Menyampaikan dengan segera kepada Pengawas/Penyelenggara ujian apabila terdapat tindakan kecurangan yang diketahui;dan</li>
                            <li>Tidak mengupload di media sosial terkait pelaksanaan kegiatan ujian.</li>
                        </ol>
                        </p>
                        <p class="mb-3">
                            Persetujuan Integritas ini saya buat dengan sebenar-benarnya dan tanpa ada paksaan dari pihak manapun.
                        </p>
                        <p class="mb-3">
                            Apabila saya melanggar hal-hal yang telah saya nyatakan dalam PERSETUJUAN INTEGRITAS ini, saya bersedia dikenakan sanksi sesuai dengan ketentuan yang berlaku.
                        </p>

                    </div>
                    <div class="has-text-centered my-6 py-2">
                        <label class="checkbox">
                            <input type="checkbox">
                            Setuju
                        </label>

                        <h5 class="subtitle is-5 mt-6">Tanda Tangan</h5>
                        <form id="sign-form" method="POST" action="">
                            <canvas id="sign-pad" class="pad" height="200" width="300" style="border:1px solid #777;"></canvas>
                            <fieldset>
                                <input type="reset" value="clear">
                            </fieldset>
                        </form>
                        <button id="submit_pakta" class="button is-success is-medium mt-6">Submit</button>
                    </div>
                </div>
            </div>
        </section>
        <script src="<?php echo get_template_directory_uri(); ?>/libs/signaturepad/assets/numeric-1.2.6.min.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/libs/signaturepad/assets/bezier.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/libs/signaturepad/jquery.signaturepad.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
        <script>
            $(document).ready(function($) {
                var sign = $('#sign-form').signaturePad({
                    drawBezierCurves: true,
                    drawOnly: true,
                    displayOnly: false,
                    defaultAction: 'drawIt',
                    validateFields: false,
                    lineWidth: 0,
                    output: null,
                    sigNav: null,
                    name: null,
                    typed: null,
                    clear: 'input[type=reset]',
                    typeIt: null,
                    drawIt: null,
                    typeItDesc: null,
                    drawItDesc: null
                });

                var ajaxurl = '<?= site_url() ?>/wp-admin/admin-ajax.php';

                $('#submit_pakta').click(function() {
                    if (ValidateForm(sign.getSignature().length) == false) {
                        var data = $('#form_pakta_integritas').serialize() + '&tanda_tangan=' + sign.getSignatureString();
                        $(this).addClass('is-loading');

                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: data,

                            success: function(response) {
                                $('#submit_pakta').removeClass('is-loading');
                                $('.modal').removeClass('is-active');
                                tata.success(response.message, '', {
                                    position: 'tm',
                                    duration: 5000
                                });
                                
                                location.reload();
                            }
                        });
                    } else {
                        alert('Maaf, anda harus melengkapi semua data!');
                    }

                });
            });

            function ValidateForm(sign) {
                var formInvalid = false;
                $('#form_pakta_integritas input').each(function() {
                    if ($(this).attr('type') == 'checkbox') {
                        if ($(this).is(':checked') == false) {
                            formInvalid = true;
                        }
                    } else {
                        if ($(this).attr('name') !== 'pakta_id' && $(this).val() === '') {
                            formInvalid = true;
                        }
                    }
                });

                if (sign == 0) {
                    formInvalid = true;
                }

                return formInvalid;
            }
        </script>
        <script src="<?php echo get_template_directory_uri(); ?>/libs/signaturepad/assets/json2.min.js"></script>
    </div>
    <!-- Icons below are for demo only. Feel free to use any icon pack. Docs: https://bulma.io/documentation/elements/icon/ -->
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">

</body>

</html>