<?php
require_once("/var/www/evaluasi.knpk.xyz/wp-load.php");

$params = array(
    'limit' => -1
);

$pod = pods('group', $params);

if (isset($_GET['type']) && $_GET['type'] == 'dropdown') {
    if (0 < $pod->total()) {
        while ($pod->fetch()) {
            $item = array();
            $item['id'] = $pod->display('ID');
            $item['name'] = $pod->display('nama_pendek');

            $data[] = $item;
        }
    }
    echo json_encode($data);
}