<?php
require_once("/var/www/evaluasi.knpk.xyz/wp-load.php");

$data = array();

$pelatihan = pods('pelatihan', $_GET['id_pelatihan']);

if ($pelatihan->exists()) {
    $i = 1;
    foreach ($pelatihan->field('mata_pelajaran') as $p) {
        $mp = pods('mata_pelajaran', $p['ID']);
        $judul_slug = str_replace(' ', '_', strtolower(FIELDS_MATA_PELAJARAN[0]['title']));
        $judul = $mp->display($judul_slug);
        $judul_mp = strlen($judul) > 60 ? '<span title="'.$judul.'">'.substr($judul, 0, 60) . "...</span>" : '<span title="'.$judul.'">'.$judul."</span>";

        $item = [];
        $item = [
            $p['ID'],
            '<input type="checkbox" class="check_item" value="' . $p['ID'] . '" data-judul="' . $judul . '">',
            $i,
            $judul_mp          
        ];

        for ($j = 1; $j < count(FIELDS_MATA_PELAJARAN); $j++) {
            $slug = str_replace(' ', '_', strtolower(FIELDS_MATA_PELAJARAN[$j]['title']));
            
            $item[] = str_replace('and', 'dan', $mp->display($slug));
        }

        $data[] = $item;
        $i++;
    }
}

echo json_encode(array(
    'data' => $data
));
