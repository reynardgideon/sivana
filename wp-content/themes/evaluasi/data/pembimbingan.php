<?php

require_once("/var/www/sikad.knpk.xyz/wp-load.php");


$params = array(
    'limit' => -1,
    'where' => 'kajian_akademis.ID = '.$_GET["id"]
);


$pem = pods("pembimbingan", $params);

$data = array();
if (0 < $pem->total()) {
    $i = 1;
    while ($pem->fetch()) {        
        $item = array(            
            $i,
            $pem->display('tanggal_kegiatan'),
            '<a href="'.get_permalink($pem->display('ID')).'" class="lihat_detail_pembimbingan">'.$pem->display('kegiatan').'</a>',          
        );
        $data[] = $item;
        $i++;
    }
}

$data = array(
  "data" => $data
);


echo json_encode($data);