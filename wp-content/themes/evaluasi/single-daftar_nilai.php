<?php

include_once(get_template_directory() . '/getters/pengajar.php');
include_once(get_template_directory() . '/getters/mata_pelajaran.php');
include_once(get_template_directory() . '/getters/helpers.php');
include_once(get_template_directory() . '/getters/pelatihan.php');

$dn = pods('daftar_nilai', get_the_ID());

$data = json_decode($dn->field('data'));

$id_pel = $dn->field('jenis_nilai') == 'pkl' || $dn->field('jenis_nilai') == 'k' || $dn->field('jenis_nilai') == 'lain' || $dn->field('mata_pelajaran.pelatihan.jenis_pelatihan') == 'e-learning' ? $dn->field('pelatihan.ID') : $dn->field('mata_pelajaran.pelatihan.ID');

$peserta = [];
$terdaftar = Pelatihan::get_peserta($id_pel);

foreach ($data->peserta as $item) {
    if (in_array($item->nip, $terdaftar)) {
        $peserta[] = $item;
    }
}

$daftar_pengajar = $dn->field('mata_pelajaran.pengajar.ID');

$pengajar = '';

if (count($data->pengajar) > 1) {
    $array = $data->pengajar;
    $first = array_key_first($array);
    $last = array_key_last($array);

    $text = '';
    foreach ($array as $k => $i) {
        if ($k == $first) {
            $text .= $i->nama_lengkap;
        } else if ($k == $last) {
            $text .= ' dan ' . $i->nama_lengkap;
        } else {
            $text .= ', ' . $i->nama_lengkap;
        }
    }
    $pengajar = $text;
} else if (count($data->pengajar) == 1) {
    $pengajar = $data->pengajar[0]->nama_lengkap;
} else {
    $pengajar = '';
}


/*
foreach ($data->pengajar as $k => $p) {
    $pod_pengajar = pods('user', $daftar_pengajar[$p]);
    if ($k === array_key_first($data->pengajar)) {
        $pengajar .= $pod_pengajar->display('nama_lengkap');
    } else if ($k === array_key_last($data->pengajar)) {
        $pengajar .= ' dan ' . $pod_pengajar->display('nama_lengkap');
    } else {
        $pengajar .= ', ' . $pod_pengajar->display('nama_lengkap');
    }
}
*/

$mulai = '';
$sampai = '';
if ($dn->field('jenis_nilai') == 'pkl' || $dn->field('jenis_nilai') == 'k' || $dn->field('jenis_nilai') == 'lain') {
    $mulai = $dn->display('pelatihan.mulai');
    $selesai = $dn->display('pelatihan.selesai');
    $pelatihan = $dn->display('pelatihan');
    $mata_pelajaran = $dn->display('jenis_nilai');
    $waktu = '';
} else if ($dn->field('mata_pelajaran.pelatihan.jenis_pelatihan') == 'e-learning') {
    $mulai = $dn->display('mata_pelajaran.pelatihan.mulai');
    $selesai = $dn->display('mata_pelajaran.pelatihan.selesai');
    $pelatihan = $dn->display('mata_pelajaran.pelatihan.judul');
    $mata_pelajaran = $dn->display('mata_pelajaran.judul');
    $waktu = '';
} else {
    $mulai = $dn->display('mata_pelajaran.pelatihan.mulai');
    $selesai = $dn->display('mata_pelajaran.pelatihan.selesai');
    $pelatihan = $dn->display('mata_pelajaran.pelatihan.judul');
    $mata_pelajaran = $dn->display('mata_pelajaran.judul');

    $jadwal = array_map(
        function ($i) {
            return Helpers::tanggal($i);
        },
        $dn->field('mata_pelajaran.jadwal')
    );
    $waktu = '<tr><td><b>Waktu Penilaian</b></td><td>' . implode(', ', $jadwal) . '</td></tr>';

    if ($dn->field('jenis_nilai') == 'r') {
        $waktu = '';
    }
}



$dates  = $dn->field('mata_pelajaran.jadwal');

foreach ($dates as $k => $date) {
    if ($k === array_key_first($dates)) {
        $jadwal .= tanggal($date);
    } else if ($k === array_key_last($dates)) {
        $jadwal .= ' dan ' . tanggal($date);
    } else {
        $jadwal .= ', ' . tanggal($date);
    }
}

$spacing = is_mobile() ? '' : ' px-6 mx-6';

$colors = array(
    'r' => 'text-danger',
    'p' => 'text-success',
    'q' => 'text-primary',
    'k' => 'text-primary',
    'k2b1' => 'text-primary',
    'pkl' => 'text-primary'
);

$tanggal = '';
if (isset($_GET['action']) && ($_GET['action'] == 'view')) {
    $tanggal = $dn->field('post_date');
} else if (isset($_GET['action']) && ($_GET['action'] == 'submit')) {
    $tanggal = date('d') . ' ' . MONTHS[date('m')] . ' ' . date('Y');
} else {
    $tanggal = $dn->field('post_date');
}

$nama_lengkap = array(
    "data" => 'nama_lengkap',
    "className" => "htMiddle htLeft nama_lengkap",
    'readOnly' => true
);

$code = array(
    "data" => 'code',
    "className" => "htMiddle htCenter code",
    'readOnly' => true
);

$nilai = array(
    "data" => 'nilai',
    "type" => 'numeric',
    'readOnly' => $_GET['action'] == 'view' ? true : false
);

$columns = [];
$colheaders = [];

if ($dn->field('coding') !== "1" || ($dn->field('coding') == "1" && $_GET['action'] !== 'submit')) {
    $columns[] = $nama_lengkap;
    $colheaders[] = '<big><b>Nama</b><big>';
}

if ($dn->field('coding') == "1") {
    $columns[] = $code;
    $colheaders[] = '<big><b>Code</b><big>';
}

$columns[] = $nilai;
$colheaders[] = '<big><b>Nilai</b><big>';
?>

<?php if (isset($_GET['action']) && ($_GET['action'] == 'view') || $_GET['action'] == 'submit') : ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= get_the_title() ?></title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
        <script src="https://cdn.jsdelivr.net/npm/handsontable@12.1/dist/handsontable.full.min.js"></script>
        <link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@12.1/dist/handsontable.full.min.css" />
        <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/libs/signaturepad/assets/numeric-1.2.6.min.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/libs/signaturepad/assets/bezier.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/libs/signaturepad/jquery.signaturepad.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
        <style>
            @page {
                size: 11in 25in;
            }

            .handsontable span.colHeader {
                font-weight: bold;
                font-size: 16px;
                line-height: 30px;
            }

            .handsontable table {
                font-size: 16px;
            }

            .handsontable .middle {
                vertical-align: middle;
            }

            .table .left {
                text-align: left;
            }

            .nama_lengkap {
                color: #000;
                font-weight: bold;
            }
        </style>

    </head>

    <body>
        <div id="app">
            <section class="hero is-fullheight has-background-grey-light">
                <div class="columns is-centered">
                    <div class="column is-two-thirds-desktop has-background-white mt-5">
                        <div class="has-text-centered">
                            <figure class="image mx-5 mb-10 is-inline-block is-128x128">
                                <img src="https://eps.knpk.xyz/wp-content/uploads/2022/06/corpu2.png">
                            </figure>
                            <br />
                            <h5 class="subtitle is-4 m-5 pt-5<?= ' ' . $colors[$dn->field('jenis_nilai')] ?>" style="line-height:40px;">
                                <?= $dn->display('jenis_nilai') ?>
                            </h5>
                            <h3 class="title is-3 mt-5 mx-5 is-uppercase" style="line-height:40px;">
                                <?= $pelatihan ?>
                            </h3>

                            <p class="subtitle is-5 m-5">
                                <?= Helpers::range_tanggal($mulai, $selesai) ?>
                            </p>
                            <hr />
                        </div>


                        <div id="tabel_container" class="has-text-centered<?= $spacing ?>">
                            <table class="table is-striped is-fullwidth has-text-left">
                                <tr>
                                    <td width="200"><b>Mata Pelajaran</b></td>
                                    <td><?= $mata_pelajaran ?></td>
                                </tr>
                                <?php if ($pengajar !== '') : ?>
                                    <tr>
                                        <td><b>Nama Pengajar</b></td>
                                        <td><?= $pengajar ?></td>
                                    </tr>
                                <?php endif; ?>
                                <?= $waktu ?>
                            </table>
                            <?php if ($dn->field('submitted') == 1 && $_GET['action'] == 'submit') : ?>
                                <article class="message is-success my-6">
                                    <div class="message-body">
                                        Maaf, Daftar Nilai ini sudah pernah disubmit. Untuk melakukan perubahan, silahkan hubungi PIC Evaluasi. Terima kasih.
                                    </div>
                                </article>
                            <?php else : ?>
                                <div id="daftar_nilai" class="mb-4 has-text-centered"></div>
                                <h5 class="subtitle is-5 mt-5">Tanda Tangan</h5>
                                <form id="sign-form" method="POST" action="">
                                    <canvas id="sign-pad" class="pad" height="200" width="300" style="border:1px solid #777;"></canvas>
                                    <?php if ($_GET['action'] == 'submit') : ?>
                                        <fieldset>
                                            <input type="reset" value="clear">
                                        </fieldset>
                                    <?php endif; ?>
                                </form>
                                <?php if ($_GET['action'] == 'submit') : ?>
                                    <button id="submit_daftar_nilai" class="button is-success is-medium mt-6">Submit</button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

            <script>
                const container = document.querySelector('#daftar_nilai');

                const hot = new Handsontable(container, {
                    data: <?= json_encode($peserta) ?>,
                    width: "100%",
                    columns: <?= json_encode($columns) ?>,
                    className: 'htMiddle htCenter',
                    stretchH: 'all',
                    colHeaders: <?= json_encode($colheaders) ?>,
                    colWidths: ['90%'],
                    rowHeights: 40,
                    manualRowResize: true,
                    rowHeaders: true,
                    columnSorting: {
                        sortEmptyCells: true,
                        initialConfig: {
                            column: 0,
                            sortOrder: 'asc'
                        }
                    },
                    height: 'auto',
                    afterGetRowHeader: function(col, TH) {
                        TH.className = 'middle'
                    },
                    licenseKey: 'non-commercial-and-evaluation'
                });

                var sign = $('#sign-form').signaturePad({
                    drawBezierCurves: true,
                    drawOnly: true,
                    displayOnly: <?= $_GET['action'] == 'view' ? 'true' : 'false' ?>,
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

                var ajaxurl = '<?= get_site_url() . '/wp-admin/admin-ajax.php' ?>';

                hot.addHook('afterChange', (row, amount) => {
                    var tmpdata = {
                        id: <?= get_the_id() ?>,
                        action: 'save_daftar_nilai',
                        nilai: hot.getSourceData(),
                        token: '<?= $_GET['token'] ?>'
                    };

                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: tmpdata,

                        success: function(response) {
                            tata.success(response.message, '', {
                                position: 'br',
                                duration: 2000
                            });
                        }
                    });
                });

                <?php if ($_GET['action'] == 'view' && !empty($dn->field("tanda_tangan"))) : ?>
                    sign.regenerate('<?= $dn->field("tanda_tangan") ?>').off();
                <?php endif; ?>

                function firstRowRenderer(instance, td, row, col, prop, value, cellProperties) {
                    Handsontable.renderers.TextRenderer.apply(this, arguments);
                    td.style.fontWeight = 'bold';
                    td.style.background = '#CEC';
                }

                $(document).ready(function() {
                    var ajaxurl = '<?= get_site_url() . '/wp-admin/admin-ajax.php' ?>';

                    $("#submit_daftar_nilai").click(function() {
                        if (sign.getSignature().length == 0) {
                            alert('Maaf, Daftar Nilai harus ditandatangani sebelum dikirim!');
                        } else {

                            var data = {
                                id: <?= get_the_id() ?>,
                                action: 'submit_daftar_nilai',
                                sign: sign.getSignatureString(),
                                nilai: hot.getSourceData(),
                                token: '<?= $_GET['token'] ?>'
                            };

                            $(this).addClass('is-loading');
                            $.ajax({
                                url: ajaxurl,
                                type: 'POST',
                                data: data,

                                success: function(response) {
                                    $("#submit_daftar_nilai").removeClass('is-loading');
                                    tata.success(response.message, '', {
                                        position: 'tm',
                                        duration: 5000
                                    });
                                    window.location.assign('<?= get_the_permalink() . '?action=view' ?>');
                                }
                            });
                        }
                    });
                });
            </script>

        </div>
        <!-- Icons below are for demo only. Feel free to use any icon pack. Docs: https://bulma.io/documentation/elements/icon/ -->
        <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">

    </body>

    </html>
<?php else : ?>

    <!DOCTYPE html>
    <html lang="en">
    <?php get_header(); ?>

    <body>
        <?php get_template_part('loader'); ?>
        <div id="pcoded" class="pcoded">
            <div class="pcoded-overlay-box"></div>
            <div class="pcoded-container navbar-wrapper">
                <?php get_template_part('nav'); ?>
                <div class="pcoded-main-container">
                    <div class="pcoded-wrapper">
                        <?php get_sidebar(); ?>
                        <div class="pcoded-content">
                            <?php get_template_part('breadcrumb'); ?>
                            <div class="pcoded-inner-content">
                                <!-- Main-body start -->
                                <div class="main-body">
                                    <div class="page-wrapper">
                                        <!-- Page-body start -->
                                        <div class="page-body">
                                            <?= create_form_daftar_nilai(null, $_GET['id_pelatihan']) ?>
                                        </div>
                                        <!-- Page-body end -->
                                    </div>
                                    <div id="styleSelector"> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php get_footer(); ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/handsontable@12.1/dist/handsontable.full.min.js"></script>
        <link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@12.1/dist/handsontable.full.min.css" />


    </body>

    </html>
<?php endif; ?>

<?php
function create_form_daftar_nilai($id = null, $id_pelatihan = null)
{
    $pod_name = 'daftar_nilai';
    $form = '<form id="form_' . $pod_name . '" action="" method="POST" class="needs-validation">';

    $form .= <<<FORM
          <div class="form-group">
            <label for="jenis_nilai">Jenis Nilai</label>
            <select id="jenis_nilai" name="jenis_nilai" class="form-control selectpicker" data-live-search="true" data-dropup-auto="false">                
                <option value="p">Nilai Kehadiran</option>
                <option value="q">Nilai Aktivitas</option>
                <option value="r">Ujian</option>
                <option value="k">Nilai Komprehensif</option>
                <option value="k2b1">Nilai Komprehensif (Form 2B-1)</option>
                <option value="pkl">Nilai PKL</option>
            </select>
          </div>
          FORM;

    $mp = get_mata_pelajaran(array('id_pelatihan' => $id_pelatihan, 'type' => 'dataoptions'));
    $form .= <<<FORM
          <div class="form-group">
            <label for="mata_pelajaran">Mata Pelajaran</label>
            <select id="mata_pelajaran" name="mata_pelajaran" class="form-control selectpicker" data-live-search="true" data-dropup-auto="false">                
                $mp
            </select>
          </div>
          FORM;

    $list = get_pengajar(array('type' => 'dataoptions'));

    $form .= <<<FORM
          <div class="form-group">
            <label for="mata_pelajaran">Pengajar</label>
            <select id="mata_pelajaran" name="mata_pelajaran" class="form-control selectpicker" data-live-search="true" data-dropup-auto="false">                
                $list
            </select>
          </div>
          FORM;

    $form .= <<<FORM
            <div class="form-group">
                <label for="mata_pelajaran">Pakai Kode</label>
                <div class="form-check ml-4">
                    <input class="form-check-input" type="checkbox" id="coding" name="coding" value="1">
                    <label class="form-check-label">Ya</label>
                </div>
            </div>
            FORM;

    $form .= <<<FORM
            <div class="form-group">
                <label for="mata_pelajaran">Peserta</label>
                <div>
                    ccc
                </div>
            </div>
            FORM;

    $form .= '<input type="hidden" name="action" value="action_' . $pod_name . '_single">';
    $form .= '<input type="hidden" name="pelatihan" value="' . $_GET['id_pelatihan'] . '">';
    $form .= '<input type="hidden" name="pod_id" value="' . $id . '">';
    $form .= '<button style="width:100px;" id="back" type="button" class="btn btn-danger mx-2">Cancel</button>';
    $form .= '<button style="width:100px;" id="submit_button" type="submit" class="btn btn-primary">Submit</button>';
    $form .= '</form>';

    echo <<<PAGE
    <div class="card">
        <div class="card-header has-background-kmk-mix has-text-centered">
        <h5>TMBAH DAFTAR NILAI</h5>
        <div class="card-header-right">
            <a href="">
            <i class="ti ti-angle-double-left" title="Kembali"></i>
            </a>
        </div>
        </div>
        <div class="card-content p-4">
        $form

        <div id="daftar_nilai" class="mb-4 has-text-centered"></div>
        </div>
    </div>
    PAGE;
?>
<?php
}
?>