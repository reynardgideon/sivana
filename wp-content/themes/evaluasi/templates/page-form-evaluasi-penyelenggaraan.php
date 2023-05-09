<?php

include_once(get_template_directory() . '/getters/helpers.php');

/**
 * Template Name: Form Evaluasi Penyelenggaraan
 * Template Post Type: form
 * @package WordPress
 */

if (isset($_GET['id_pelatihan'])) {

    $pelatihan = pods('pelatihan', $_GET['id_pelatihan']);

    if ($pelatihan->exists()) {
        $nama_pelatihan = $pelatihan->display('judul');
        $mps = $pelatihan->field('mata_pelajaran.ID');
        $jenis_pelatihan = $pelatihan->field('jenis_pelatihan');
        $secara = $jenis_pelatihan == 'klasikal' ? 'luring' : 'daring';

        if ($jenis_pelatihan !== 'e-learning') {
            $judul_mps = [];
            $id_mps = [];
            $pengajars = [];
            $nama_pengajars = [];
            foreach ($mps as $mp) {
                $mapel = pods('mata_pelajaran', $mp);
                if ($mapel->exists()) {
                    $nama_peng = $mapel->field('pengajar.nama_lengkap');
                    $id_peng = $mapel->field('pengajar.ID');

                    for ($i = 0; $i < count($nama_peng); $i++) {
                        $pengajars[] = $id_peng[$i];
                        $nama_pengajars[] = $nama_peng[$i];
                        $judul_mps[] = $mapel->display('judul');
                        $id_mps[] = $mapel->field('ID');
                    }
                } else {
                    wp_redirect(get_site_url() . '/404/');
                    exit();
                }
            }
        }

        $nip_p = $pelatihan->field('peserta.nip');
        $id_p = $pelatihan->field('peserta.ID');
        $nama_p = $pelatihan->field('peserta.nama_lengkap');
        $unit_p = $pelatihan->field('peserta.unit_kerja');

        $data_peserta = [];

        for ($j = 0; $j < count($nip_p); $j++) {
            $item = [];
            $item['label'] = $nama_p[$j];
            $item['nip'] = $nip_p[$j];
            $item['unit'] = $unit_p[$j];
            $item['responden'] = $id_p[$j];
            $data_peserta[] = $item;
        }
    } else {
        wp_redirect(get_site_url() . '/404/');
        exit();
    }

    $form = [];

    if (isset($_GET['id_data'])) {
        $data_form = pods('data_form', $_GET['id_data']);

        if ($data_form->exists()) {
            $form['nama'] = $data_form->display('responden.nama_lengkap');
            $form['nip'] = $data_form->display('responden.nip');
            $form['unit'] = $data_form->display('responden.unit_kerja');
            $form['data'] = (array)json_decode($data_form->field('data'));
            $form['responden'] = $data_form->display('responden.ID');
        }
    }
} else {
    wp_redirect(get_site_url() . '/404/');
    exit();
}

$blended = array(
    'Kesesuaian materi pembelajaran dengan harapan/kebutuhan peserta',
    'Bahan ajar mudah dipahami',
    'Kesesuaian metode pembelajaran dengan materi Pengajar Jarak Jauh',
    'Ketercukupan waktu penyelenggaraan Pelatihan Jarak Jauh dengan jumlah materi yang diberikan',
    'Kesigapan penyelenggara dalam melayani peserta selama proses Pelatihan Jarak Jauh',
    'Ketercukupan waktu dalam mengerjakan penugasan, kuis, atau ujian',
    'Fasilitas Pelatihan Jarak Jauh mudah diakses',
    'Fasilitas Pelatihan Jarak Jauh mudah digunakan',
    'Ketercukupan konsumsi',
    'Lingkungan belajar (ruang kelas) berfungsi dengan baik',
    'Lingkungan belajar (ruang asrama) berfungsi dengan baik'
);

$blended_non_asrama = array(
    'Kesesuaian materi pembelajaran dengan harapan/kebutuhan peserta',
    'Bahan ajar mudah dipahami',
    'Kesesuaian metode pembelajaran dengan materi Pengajar Jarak Jauh',
    'Ketercukupan waktu penyelenggaraan Pelatihan Jarak Jauh dengan jumlah materi yang diberikan',
    'Kesigapan penyelenggara dalam melayani peserta selama proses Pelatihan Jarak Jauh',
    'Ketercukupan waktu dalam mengerjakan penugasan, kuis, atau ujian',
    'Fasilitas Pelatihan Jarak Jauh mudah diakses',
    'Fasilitas Pelatihan Jarak Jauh mudah digunakan',
    'Ketercukupan konsumsi',
    'Lingkungan belajar (ruang kelas) berfungsi dengan baik'
);

$pjj = array(
    'Kesesuaian materi pembelajaran dengan harapan/kebutuhan peserta',
    'Bahan ajar mudah dipahami',
    'Kesesuaian metode pembelajaran dengan materi Pengajar Jarak Jauh',
    'Ketercukupan waktu penyelenggaraan Pelatihan Jarak Jauh dengan jumlah materi yang diberikan',
    'Kesigapan penyelenggara dalam melayani peserta selama proses Pelatihan Jarak Jauh',
    'Ketercukupan waktu dalam mengerjakan penugasan, kuis, atau ujian',
    'Fasilitas Pelatihan Jarak Jauh mudah diakses',
    'Fasilitas Pelatihan Jarak Jauh mudah digunakan'
);

$klasikal = array(
    'Kesesuaian materi pembelajaran dengan harapan/kebutuhan peserta',
    'Bahan ajar mudah dipahami',
    'Kesesuaian metode pembelajaran dengan materi pelatihan',
    'Kesigapan penyelenggara dalam melayani peserta selama proses pembelajaran',
    'Ketercukupan konsumsi',
    'Lingkungan belajar (ruang kelas) berfungsi dengan baik',
    'Lingkungan belajar (ruang asrama) berfungsi dengan baik'
);

$klasikal_non_asrama = array(
    'Kesesuaian materi pembelajaran dengan harapan/kebutuhan peserta',
    'Bahan ajar mudah dipahami',
    'Kesesuaian metode pembelajaran dengan materi pelatihan',
    'Kesigapan penyelenggara dalam melayani peserta selama proses pembelajaran',
    'Ketercukupan konsumsi',
    'Lingkungan belajar (ruang kelas) berfungsi dengan baik'
);

$elearning = array(
    'Kesesuaian materi pembelajaran dengan harapan/kebutuhan peserta',
    'Bahan ajar mudah dipahami',
    'Kesesuaian metode pembelajaran dengan materi e-learning',
    'Kesigapan penyelenggara dalam melayani peserta selama proses e-learning',
    'Menu fasilitas e-learning mudah digunakan',
    'Fasilitas e-learning dapat diakses setiap saat'
);

$zi = array(
    'Bagaimana pendapat Saudara tentang kesesuaian persyaratan pelayanan dengan jenis pelayanannya yang disediakan oleh Pusdiklat Kekayaan Negara dan Perimbangan Keuangan?',
    'Bagaimana pemahaman Saudara tentang tentang kemudahan prosedur pelayanan di Pusdiklat Kekayaan Negara dan Perimbangan Keuangan?',
    'Bagaimana pendapat Saudara tentang kecepatan waktu Pusdiklat Kekayaan Negara dan Perimbangan Keuangan dalam memberikan pelayanan?',
    'Bagaimana pendapat Saudara tentang kesesuaian layanan antara yang tercantum dalam standar pelayanan dengan hasil yang diberikan?',
    'Bagaimana pendapat Saudara tentang kompetensi/kemampuan petugas dalam memberi pelayanan?',
    'Bagaimana pendapat Saudara, perilaku petugas dalam memberikan pelayanan terkait kesopanan dan keramahan?',
    'Bagaimana pendapat Saudara tentang kualitas sarana dan prasarana?',
    'Bagaimana pendapat Saudara tentang penanganan pengaduan pengguna layanan di Pusdiklat Kekayaan Negara dan Perimbangan Keuangan?'
);

$qs = array(
    'pjj' => $pjj,
    'e-learning' => $elearning,
    'klasikal' => $klasikal,
    'klasikal_non_asrama' => $klasikal_non_asrama,
    'blended' => $blended,
    'blended_non_asrama' => $blended_non_asrama
);

$steps = [];

if ($jenis_pelatihan == 'e-learning') {
    $steps = array(
        'biodata',
        'evagara',
        'saran',
        'zi_wbbm'
    );
} else {
    $steps = array(
        'biodata',
        'evagara',
        'evajar',
        'saran',
        'zi_wbbm'
    );
}

$p = ' mx-2';
if (is_mobile() == 1) {
    $p = '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= get_the_title() ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/smartwizard@6/dist/css/smart_wizard_all.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/smartwizard@6/dist/js/jquery.smartWizard.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">
    <style>
        body {
            background-color: lightgray;
            font-size: 14pt;
            color: #000;
        }

        hr.divider {
            border-top: 1px solid lightgray;
        }

        .has-background-kmk-mix {
            background: #fc4a1a;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(to right, #fba511, #fdcc2c);
            /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to right, #fba511, #fdcc2c);
            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            color: white;
            border-radius: 5px 5px 0px 0px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: bold;
        }

        .has-text-kmk-darkblue {
            color: #014e73;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#smartwizard').smartWizard({
                theme: 'arrows',
                keyboardSettings: {
                    keyNavigation: true,
                    keyLeft: [74], // J key code
                    keyRight: [75] // K key code
                },
                anchor: {
                    enableDoneStateNavigation: false
                }
            });

            var names = <?= json_encode($data_peserta) ?>;
            $("#nama").autocomplete({
                source: names,
                autoFocus: true
            });

            $("#nama").change(function() {
                var result = names.filter(obj => {
                    return obj.label === $(this).val()
                })

                if (result.length > 0) {
                    $("#nip").val(result[0].nip);
                    $("#responden").val(result[0].responden);
                    $("#unit").val(result[0].unit);
                } else {
                    alert('Maaf, nama yang anda masukkan tidak terdaftar sebagai peserta PJJ ini!')
                    $("#nama").val('');
                    $("#nama").focus();
                    $("#nip").val('');
                    $("#unit").val('');
                }
            });
        });
    </script>
</head>

<body>
    <div id="app">
        <section class="is-fullheight">
            <form id="form_evagara" method="POST" action="">
                <div class="columns is-centered">
                    <div class="column is-two-thirds-desktop my-3">
                        <div class="card py-5<?= $p ?>">
                            <div class="has-text-centered mt-5">
                                <figure class="image is-inline-block is-128x128">
                                    <img src="https://eps.knpk.xyz/wp-content/uploads/2022/06/corpu2.png">
                                </figure>
                                <br />
                                <br />
                                <h3 class="title is-4 my-5 px-5">
                                    EVALUASI PENYELENGGARAAN<?= $jenis_pelatihan !== 'e-learning' ? ' DAN EVALUASI PENGAJAR' : ''; ?>
                                </h3>
                                <h4 class="title is-4 my-5 px-5" style="line-height:35px;">
                                    <?= strtoupper($nama_pelatihan) ?><br />
                                </h4>
                            </div>

                            <!-- SmartWizard html -->
                            <div id="smartwizard">
                                <ul class="nav">
                                    <?php for ($s = 0; $s < count($steps); $s++) : ?>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#step-<?= $s + 1 ?>">
                                                <div class="num"><?= $s + 1 ?></div>
                                                <?= ucwords(str_replace('_', ' ', $steps[$s])) ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>

                                <div class="tab-content">
                                    <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">
                                        <div class="has-text-justify">
                                            <p class="mb-4 px-5 has-text-justify">
                                                ISILAH pada salah satu alternatif jawaban yang tersedia untuk setiap butir pertanyaan terhadap evaluasi penyelenggaraan dan evaluasi pengajar pada <?= $nama_pelatihan ?> pada tanggal <?= Helpers::range_tanggal($pelatihan->display('mulai'), $pelatihan->display('selesai')) ?> secara <?= $secara ?>.
                                            </p>
                                            <p class="mb-4 px-5 has-text-justify">
                                                Keterangan:<br />
                                                <b>Kepentingan</b> : menyatakan pendapat Anda tentang penting tidaknya setiap pernyataan yang akan mempengaruhi kepuasan Anda.<br />
                                                <b>Ekspektasi</b>: menggambarkan harapan Anda terhadap pelayanan penyelenggaraan pelatihan yang seharusnya diberikan oleh Pusdiklat KNPK.<br />
                                                <b>Persepsi</b> : menggambarkan apa yang Anda rasakan/pendapat Anda mengenai pelayanan penyelenggaraan pelatihan di Pusdiklat KNPK.
                                            </p>

                                            <p class="mb-4 px-5 has-text-justify">
                                                Skor 1 = Tidak Penting / Tidak Baik<br />
                                                Skor 2 = Kurang Penting / Kurang Baik<br />
                                                Skor 3 = Cukup Penting / Cukup Baik<br />
                                                Skor 4 = Penting / Baik<br />
                                                Skor 5 = Sangat Penting / Sangat Baik
                                            </p>
                                            <p class="mb-4 px-5 has-text-justify">
                                                Bagi Bapak/Ibu yang memberikan penilaian 3, 2, dan 1, kami mohon masukannya terkait hal tersebut untuk perbaikan kedepan.
                                            </p>
                                        </div>

                                        <div class="mx-3 mt-3">
                                            <header class="card-header has-background-kmk-mix">
                                                <p class="card-header-title has-text-white">
                                                    DATA PESERTA
                                                </p>
                                            </header>
                                            <div class="<?= $p ?>">
                                                <div class="mt-4 is-vcentered">
                                                    <p><label class="label" for="nama">Nama</label></p>
                                                    <p><input class="input" id="nama" type="text" name="nama" value="<?= $form['nama'] ?>" required></p>
                                                </div>
                                                <div class="mt-4 is-vcentered">
                                                    <p><label class="label">NIP</label></p>
                                                    <p><input id="nip" class="input" type="text" name="nip" value="<?= $form['nip'] ?>" required disabled></p>
                                                </div>
                                                <div class="mt-4 is-vcentered">
                                                    <p><label class="label">Unit Kerja</label></p>
                                                    <p><input id="unit" class="input" type="text" name="unit" value="<?= $form['unit'] ?>" required disabled></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">
                                        <div class="mx-3 mt-3">
                                            <header class="card-header has-background-kmk-mix">
                                                <p class="card-header-title has-text-white">
                                                    EVALUASI PENYELENGGARAAN
                                                </p>
                                            </header>
                                            <div class="card-content<?= $p ?>">
                                                <?php
                                                $q_index = ($jenis_pelatihan == 'klasikal' || $jenis_pelatihan == 'blended') && $pelatihan->display('diasramakan') !== 'Ya' ? $jenis_pelatihan.'_non_asrama' : $jenis_pelatihan;
                                                
                                                foreach ($qs[$q_index] as $q => $v) {
                                                    $i = $q + 1;
                                                    $harapan = 'q' . $i . 'h';
                                                    $kenyataan = 'q' . $i . 'k';
                                                ?>
                                                    <article class="message is-light">
                                                        <div class="message-header">
                                                            <p><?= $i . '. ' . $v ?></p>
                                                        </div>
                                                        <div class="message-body">
                                                            <div class="table-container">
                                                                <table class="table is-fullwidth is-striped">
                                                                    <tr>
                                                                        <td></td>
                                                                        <td>1</td>
                                                                        <td>2</td>
                                                                        <td>3</td>
                                                                        <td>4</td>
                                                                        <td>5</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Kepentingan (Sebelum Pelatihan)</td>
                                                                        <?php
                                                                        for ($x = 1; $x < 6; $x++) {
                                                                            $req = '';
                                                                            $sel = '';
                                                                            if ($x == 1) {
                                                                                $req = ' required';
                                                                            }

                                                                            if ($x == $form['data'][$harapan]) {
                                                                                $sel = ' checked';
                                                                            }
                                                                        ?>
                                                                            <td><input type="radio" name="<?= $harapan ?>" value="<?= $x ?>" <?= $req . $sel ?>></td>
                                                                        <?php
                                                                        }
                                                                        ?>

                                                                    </tr>
                                                                    <tr>
                                                                        <td>Persepsi/Kenyataan (Setelah Pelatihan)</td>
                                                                        <?php
                                                                        for ($x = 1; $x < 6; $x++) {
                                                                            $req = '';
                                                                            $sel = '';
                                                                            if ($x == 1) {
                                                                                $req = ' required';
                                                                            }

                                                                            if ($x == $form['data'][$kenyataan]) {
                                                                                $sel = ' checked';
                                                                            }
                                                                        ?>
                                                                            <td><input type="radio" name="<?= $kenyataan ?>" value="<?= $x ?>" <?= $req . $sel ?>></td>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </tr>
                                                                </table>
                                                            </diV>
                                                        </div>
                                                    </article>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($jenis_pelatihan !== 'e-learning') : ?>
                                        <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">
                                            <div class="mx-3 mt-3">
                                                <header class="card-header  has-background-kmk-mix">
                                                    <p class="card-header-title has-text-white">
                                                        EVALUASI PENGAJAR
                                                    </p>
                                                </header>
                                                <div class="card-content<?= $p ?>">
                                                    <p class="mb-5">
                                                        Pada bagian ini Anda diminta untuk memberikan penilaian terhadap kemampuan Pengajar dalam memberikan bimbingan secara jarak jauh.
                                                    </p>
                                                    <?php
                                                    for ($i = 0; $i < count($judul_mps); $i++) {
                                                        $h = 'p' . $pengajars[$i] . '-' . $id_mps[$i] . 'h';
                                                        $k = 'p' . $pengajars[$i] . '-' . $id_mps[$i] . 'k';
                                                    ?>
                                                        <article class="message is-light">
                                                            <div class="message-header">
                                                                <p><?= $i + 1 . '. ' . $nama_pengajars[$i] . ' - ' . $judul_mps[$i] ?></p>
                                                            </div>
                                                            <div class="message-body">
                                                                <div class="table-container">
                                                                    <table class="table is-fullwidth is-striped">
                                                                        <tr>
                                                                            <td></td>
                                                                            <td>1</td>
                                                                            <td>2</td>
                                                                            <td>3</td>
                                                                            <td>4</td>
                                                                            <td>5</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Harapan (Sebelum Pelatihan)</td>
                                                                            <?php
                                                                            for ($x = 1; $x < 6; $x++) {
                                                                                $req = '';
                                                                                $sel = '';
                                                                                if ($x == 1) {
                                                                                    $req = ' required';
                                                                                }

                                                                                if ($x == $form['data'][$h]) {
                                                                                    $sel = ' checked';
                                                                                }
                                                                            ?>
                                                                                <td><input type="radio" name="<?= $h ?>" value="<?= $x ?>" <?= $req . $sel ?>></td>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Kenyataan (Setelah Pelatihan)</td>
                                                                            <?php
                                                                            for ($x = 1; $x < 6; $x++) {
                                                                                $req = '';
                                                                                $sel = '';
                                                                                if ($x == 1) {
                                                                                    $req = ' required';
                                                                                }

                                                                                if ($x == $form['data'][$k]) {
                                                                                    $sel = ' checked';
                                                                                }
                                                                            ?>
                                                                                <td><input type="radio" name="<?= $k ?>" value="<?= $x ?>" <?= $req . $sel ?>></td>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </article>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4">
                                        <div class="mx-3 mt-3">
                                            <header class="card-header has-background-kmk-mix">
                                                <p class="card-header-title has-text-white">
                                                    EVALUASI LAINNYA
                                                </p>
                                            </header>
                                            <div class="card-content mx-3">
                                                <article class="message is-light">
                                                    <div class="message-header">
                                                        <p>Saya mengetahui tujuan pembelajaran sebelum pelaksanaan Pelatihan dimulai
                                                        </p>
                                                    </div>
                                                    <div class="message-body">
                                                        <div class="control">
                                                            <label class="radio">
                                                                <input type="radio" name="q9" value="ya" required<?= $form['data']['q9'] == 'ya' ? ' checked' : '' ?>>
                                                                Ya
                                                            </label>
                                                            <label class="radio">
                                                                <input type="radio" name="q9" value="tidak"<?= $form['data']['q9'] == 'tidak' ? ' checked' : '' ?>>
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                </article>

                                                <article class="message is-light">
                                                    <div class="message-header">
                                                        <p>Saya akan merekomendasikan Pelatihan ini kepada orang lain
                                                        </p>
                                                    </div>
                                                    <div class="message-body">
                                                        <div class="control">
                                                            <label class="radio">
                                                                <input type="radio" name="q10" value="ya" required<?= $form['data']['q10'] == 'ya' ? ' checked' : '' ?>>
                                                                Ya
                                                            </label>
                                                            <label class="radio">
                                                                <input type="radio" name="q10" value="tidak"<?= $form['data']['q10'] == 'tidak' ? ' checked' : '' ?>>
                                                                Tidak
                                                            </label>
                                                        </div>
                                                    </div>
                                                </article>

                                                <article class="message is-light">
                                                    <div class="message-header">
                                                        <p>Saran terkait Penyelenggaraan pelatihan</p>
                                                    </div>
                                                    <div class="message-body">
                                                        <textarea class="textarea" rows="5" name="q11"><?= $form['data']['q11'] ?></textarea>
                                                    </div>
                                                </article>
                                                <article class="message is-light">
                                                    <div class="message-header">
                                                        <p>Saran terkait Pengajar (mohon dapat disebutkan nama mata pelajarannya saja, bukan nama pengajar)</p>
                                                    </div>
                                                    <div class="message-body">
                                                        <textarea class="textarea" rows="5" name="q12"><?= $form['data']['q12'] ?></textarea>
                                                    </div>
                                                </article>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="step-5" class="tab-pane" role="tabpanel" aria-labelledby="step-5">
                                        <div class="mx-3 mt-3">
                                            <header class="card-header has-background-kmk-mix">
                                                <p class="card-header-title has-text-white">
                                                    EVALUASI TERKAIT ZI-WBBM
                                                </p>
                                            </header>
                                            <div class="card-content mx-3">
                                                <p class="mb-5">
                                                    Dalam rangka pembangunan Zona Integritas Menuju Wilayah Birokrasi Bersih dan Melayani (ZI-WBBM) di Pusdiklat Kekayaan Negara dan Perimbangan Keuangan, kami mohon perkenan untuk mengisi survey di bawah ini. Terdapat 9 Pertanyaan yang perlu Saudara jawab
                                                </p>
                                                <p class="mb-5">
                                                    Berilah penilaian antara 1 sampai dengan 4, dengan penjelasan sebagai berikut:
                                                    1 = Sangat Tidak Baik
                                                    2 = Tidak Baik
                                                    3 = Baik
                                                    4 = Sangat Baik
                                                </p>
                                                <p class="mb-5">
                                                    Keterangan khusus untuk pertanyaan nomor 9 yang perlu Saudara jawab: Ya atau Tidak
                                                </p>

                                                <?php for ($i = 0; $i < count($zi); $i++) : ?>
                                                    <table class="table is-fullwidth is-striped">
                                                        <tr>
                                                            <article class="message is-light">
                                                                <div class="message-header">
                                                                    <p><?= $zi[$i] ?></p>
                                                                </div>
                                                                <div class="message-body">
                                                                    <nav class="level" style="margin: 0px 150px;">
                                                                        <?php
                                                                        for ($x = 1; $x < 5; $x++) {
                                                                            $req = '';
                                                                            $sel = '';
                                                                            if ($x == 1) {
                                                                                $req = ' required';
                                                                            }

                                                                            if ($x == $form['data']['zi'.$i + 1 ]) {
                                                                                $sel = ' checked';
                                                                            }
                                                                        ?>
                                                                            <div>
                                                                                <label class="radio">
                                                                                    <input type="radio" name="zi<?= $i + 1 ?>" value="<?= $x ?>"<?= $req.$sel ?>>
                                                                                    <?= $x ?>
                                                                                </label>
                                                                            </div>
                                                                        <?php
                                                                        }
                                                                        ?>                                                                        
                                                                    </nav>
                                                                </div>
                                                            </article>
                                                        </tr>
                                                    </table>
                                                <?php endfor; ?>

                                                <article class="message is-light">
                                                    <div class="message-header">
                                                        <p>Apakah anda dipungut biaya dalam menerima layanan Pusdiklat Kekayaan Negara dan Perimbangan Keuangan?</p>
                                                    </div>
                                                    <div class="message-body">
                                                        <nav class="level" style="margin: 0px auto;">
                                                            <div>
                                                                <label class="radio">
                                                                    <input type="radio" name="zi9" value="1" required<?= $form['data']['zi9'] == '1' ? ' checked' : '' ?>> Ya
                                                                </label>
                                                                <label class="radio">
                                                                    <input type="radio" name="zi9" value="0"<?= $form['data']['zi9'] == '0' ? ' checked' : '' ?>> Tidak
                                                                </label>
                                                            </div>
                                                        </nav>
                                                    </div>
                                                </article>
                                                <article class="message is-light">
                                                    <div class="message-header">
                                                        <p>Saran dan masukan terkait kepuasan Layanan pelatihan di Pusdiklat KNPK</p>
                                                    </div>
                                                    <div class="message-body">
                                                        <textarea class="textarea" rows="5" name="zi10"><?= $form['data']['zi10'] ?></textarea>
                                                    </div>
                                                </article>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Include optional progressbar HTML -->
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="action" value="submit_form_evagara">
                <input id="responden" type="hidden" name="responden" value="<?= $form['responden'] ?>">
                <input type="hidden" name="pelatihan" value="<?= $pelatihan->display('ID') ?>">
                <input type="hidden" name="form" value="<?= get_the_id() ?>">
                <div id="submit_button" class="has-text-centered mt-5"><input type="submit" id="submit_form_evagara" class="button is-info" value="Submit"></div>
            </form>
        </section>
    </div>.

    <script>
        $(document).ready(function($) {
            var ajaxurl = '<?= get_site_url() . '/wp-admin/admin-ajax.php' ?>';

            $("#form_evagara").on("submit", function(e) {

                if ($("#nip").val() == '') {
                    var result = names.filter(obj => {
                        return obj.label === $(this).val()
                    })

                    if (result.length > 0) {
                        $("#nip").val(result[0].nip);
                        $("#responden").val(result[0].responden);
                        $("#unit").val(result[0].unit);
                    } else {
                        alert('Maaf, nama yang anda masukkan tidak terdaftar sebagai peserta PJJ ini!')
                        $("#nama").val('');
                        $("#nama").focus();
                        $("#nip").val('');
                        $("#unit").val('');
                    }
                }
                
                e.preventDefault();

                var data = $('#form_evagara').serialize();

                $('#submit_form_evagara').html('<i class="fa fa-spinner fa-spin"></i>');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: data,

                    success: function(response) {
                        $('#submit_button').html('Data berhasil disimpan! Terima kasih.');
                        tata.success(response.message, '', {
                            position: 'tm',
                            duration: 5000
                        });
                    }
                });                
            });

            $("input[name='zi9']").change(function(){
                if ($("input[name='zi9']:checked").val() == 1) {                    
                    alert('Pelayanan di Pusdiklat KNPK tidak dipungut biaya. Jika anda mengetahui adanya pemungutan biaya, mohon tuliskan di kolom saran, dan mohon kesedian Saudara untuk kami hubungi untuk konfirmasi.');
                }
            });

            /*
             $('#submit_form_evagara').click(function() {
                 var data = $('#form_evagara').serialize();
                 $(this).addClass('is-loading');

                 $.ajax({
                     url: ajaxurl,
                     type: 'POST',
                     data: data,

                     success: function(response) {
                         $('#submit_form_evagara').removeClass('is-loading');
                         tata.success(response.message, '', {
                             position: 'tm',
                             duration: 5000
                         });
                     }
                 });
             });
             */
        });

        
    </script>
</body>

</html>