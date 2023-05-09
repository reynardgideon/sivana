<?php
require_once('C:\xampp\htdocs\evaluasi\wp-load.php');

$data = array();
$params = array(
  'limit' => -1
);
if (isset($_GET['tahun'])) {
  //$params['where'] = "YEAR(mulai.meta_value)='" . $_GET['tahun'] . "'";
}

$fields = constant('FIELDS_PELATIHAN');

$pelatihan = pods('pelatihan', $params);

$i = 1;
if (0 < $pelatihan->total()) {
  while ($pelatihan->fetch()) {
    $item = array();
    $first_slug = str_replace(' ', '_', strtolower($fields[0]['title']));
    $item = array();
    $item[] = $pelatihan->display('ID');
    $item[] = '<input type="checkbox" data-' . $first_slug . '="' . $pelatihan->display($first_slug) . '" class="check_item" value="' . $pelatihan->display('ID') . '">';
    $item[] = $i;

    foreach ($fields as $f) {
      $slug = str_replace(' ', '_', strtolower($f['title']));
      if ($slug == 'judul') {
        $item[] = '<a href="'.get_the_permalink($pelatihan->field('ID')).'">'.$pelatihan->display($slug).'</a>';
      } elseif ($slug == 'mulai' || $slug == 'selesai') {
        $item[] = tanggal($pelatihan->display($slug));
      } else {
        $item[] = $pelatihan->display($slug);
      }
    }
    $data[] = $item;
    $i++;
  }
}

echo json_encode(array(
  'data' => $data
));
