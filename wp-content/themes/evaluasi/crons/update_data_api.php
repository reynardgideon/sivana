<?php
    require_once("/var/www/evaluasi.knpk.xyz/wp-load.php");

    $args = array(
        'limit' => -1,
        'where' => "YEAR(mulai.meta_value) = '" . $_GET['tahun'] . "' AND selesai.meta_value < CURRENT_DATE()"
    );

    $pod = pods('pelatihan', $args);

    $output = [];

    while($pod->fetch()) {
        $kelulusan = (array) json_decode($pod->field('rapat_kelulusan'));
        $rekap = (array) json_decode($pod->field('rapat_kelulusan'));
        if (count($kelulusan) > 0 && $kelulusan['Pelaksanaan Rapat Kelulusan']->selesai == 1) {
            $hp = (array)json_decode($pod->field('hasil_pelatihan'));

            $peringkat = [];
            $terbaik = (array) $hp['peserta_terbaik'];
            foreach ($terbaik as $key => $ranks) {
                foreach ($ranks as $p) {
                    $peringkat[$p->nip] = $key;
                }
            }

            $l = $hp['lulus'];
            $tl = $hp['tidak_lulus'];

            foreach ($l as $nip => $pre) {      
                $item = [];          
                $params = array(
                    'limit' => 1,
                    'where' => "nip.meta_value = '".$nip."'"
                );
                $peserta = pods('user', $params);

                $item = array(
                    'PELATIHAN' => str_replace('PJJ', 'Pelatihan Jarak Jauh', $pod->display('judul')),
                    'NAMA LENGKAP' => $peserta->display('nama_lengkap'),
                    'NIP' => $nip,
                    'STATUS UJIAN' => 1,
                    'PREDIKAT' => $pre,
                    'LULUS ' => 'Lulus',
                    'UJIAN ULANGAN 1' => '',
                    'UJIAN ULANGAN 2' => '',
                    'PERINGKAT' => array_key_exists($nip, $peringkat) ? $peringkat[$nip] : ' '
                );
                $output[] = $item;
            }           
        }
    }

    $data_api = pods('data_api', '6186');

    view_array($output);

    $data_api->save(array(
        'data' => json_encode($output)
    ));
?>