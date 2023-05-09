<?php

include_once(get_template_directory() . '/getters/helpers.php');

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
    'klasikal' => $klasikal
);

$pod = pods('pelatihan', $_GET['id_pelatihan']);

if ($pod->exists()) {
    $ev_1 = (array)json_decode($pod->field('rekap_evaluasi_level_1'));

    $mulai = get_post_meta($_GET['id_pelatihan'], 'mulai', true);
    $selesai = get_post_meta($_GET['id_pelatihan'], 'selesai', true);

    $tanggal = Helpers::range_tanggal($pod->field('mulai'), $pod->field('selesai'));

    $jenis = $pod->field('jenis_pelatihan');

    $penye = [];

    $i = 0;
    foreach ($ev_1['penyelenggaraan'] as $key => $v) {
        $item = array(
            $key == 'rerata' ? $key : $qs[$jenis][$i],
            $v->h,
            $v->k,
            $v->p,
            $v->ku
        );

        $penye[] = $item;
        $i++;
    }

    $data = array(
        'Penyelenggaraan' => $penye,
        'lainnya' => $ev_1['lainnya'],
        "info_pelatihan" => array(
            "judul_pelatihan" => $pod->display('judul'),
            "tanggal_pelatihan" => $tanggal,
            "lokasi_pelatihan" => $pod->display('lokasi') !== '' ? $pod->display('lokasi') : 'Pusdiklat KNPK'
        ),
        'responden' => $ev_1['responden'],
        'total_peserta' => count($pod->field('peserta.ID'))
    );

    $params = array(
        'limit' => -1,
        'where' => "pelatihan.ID = '" . $_GET['id_pelatihan'] . "'"
    );

    $evajar = pods('evaluasi_pengajar', $params);

    $i = 1;

    $pengajar = [];

    while ($evajar->fetch()) {
        $nilai = (array) json_decode($evajar->field('nilai'));
        $item = array(
            $evajar->display('pengajar'),
            $evajar->display('mata_pelajaran'),
            $nilai['h'],
            $nilai['k'],
            $nilai['p'],
            $nilai['ku'],
            $evajar->display('mata_pelajaran.jp')
        );
        $pengajar[] = $item;
        $i++;
    }

    $pengajar[] = array(
        'rerata',
        $ev_1['pengajar']->rerata->h,
        $ev_1['pengajar']->rerata->k,
        $ev_1['pengajar']->rerata->p,
        $ev_1['pengajar']->rerata->ku
    );

    $data['Pengajar'] = $pengajar;

    $params = array(
        'limit' => -1,
        'where' => "pelatihan.ID = '" . $_GET['id_pelatihan'] . "' AND aktif.meta_value = '1'"
    );

    $saran = pods('saran', $params);

    $saran_penyelenggaraan = [];
    $saran_pengajar = [];

    while ($saran->fetch()) {
        if ($saran->field('kategori') == 'penyelenggaraan') {
            $saran_penyelenggaraan[] = array(
                $saran->display('frekuensi'),
                (string) number_format($saran->field('frekuensi')/$ev_1['responden']*100, 1, ",", ".").'%',
                strip_tags($saran->display('isi'))
            );
        }

        if ($saran->field('kategori') == 'pengajar') {
            $saran_pengajar[] = array(
                $saran->display('frekuensi'),
                (string) number_format($saran->field('frekuensi')/$ev_1['responden']*100, 1, ",", ".").'%',
                strip_tags($saran->display('isi'))
            );
        }        
    }

    $data['Saran'] = array(
        'penyelenggaraan' => $saran_penyelenggaraan,
        'pengajar' => $saran_pengajar
    );

    echo json_encode($data);
} else {
    echo 'ERROR';
}
