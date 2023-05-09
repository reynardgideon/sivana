<?php

include_once(get_template_directory() . '/getters/helpers.php');

$pod = pods('pelatihan', $_GET['id_pelatihan']);

$data = [];
if ($pod->exists()) {    
    $peserta = $pod->field('peserta.nama_lengkap');

    foreach ($peserta as $p) {
        $data[] = array(
            "NAMA PELATIHAN" => $p,
            "LULUS" => 1
        );
    }
    echo json_encode($data);
} else {
    echo 'ERROR';
}
