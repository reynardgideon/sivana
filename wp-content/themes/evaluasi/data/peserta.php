<?php
require_once("/var/www/evaluasi.knpk.xyz/wp-load.php");

$data = array();

$pelatihan = pods('pelatihan', $_GET['id_pelatihan']);

if ($pelatihan->exists()) {
    $i = 1;
    foreach ($pelatihan->field('peserta') as $p) {      
        $nama = get_user_meta($p['ID'], 'nama_lengkap', true);  
        $data[] = array(
            $p['ID'],
            '<input type="checkbox" class="check_item" value="' . $p['ID'] . '" data-nama_lengkap="'.$nama.'">',
            $i,
            $nama,
            get_user_meta($p['ID'], 'nip', true),
            get_user_meta($p['ID'], 'unit_kerja', true),
        );
        $i++;
    }
}

echo json_encode(array(
    'data' => $data
));
