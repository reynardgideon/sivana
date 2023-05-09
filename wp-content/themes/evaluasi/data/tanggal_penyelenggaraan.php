<?php
require_once("/var/www/evaluasi.knpk.xyz/wp-load.php");

$data = array();

$pelatihan = pods('pelatihan', $_GET['id_pelatihan']);

$mulai = $pelatihan->field('mulai');

$selesai = $pelatihan->field('selesai');

echo json_encode(array(
  'data' => array($mulai, $selesai)
));

?>
