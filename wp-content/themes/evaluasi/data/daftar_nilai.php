<?php
require_once("/var/www/evaluasi.knpk.xyz/wp-load.php");

$data = array();
$params = array(
  'limit' => -1
);
if (isset($_GET['id_pelatihan'])) {
  $params['where'] = "mata_pelajaran.pelatihan.ID='" . $_GET['id_pelatihan'] . "'";
}

$fields = constant('FIELDS_DAFTAR_NILAI');
$dn = pods('daftar_nilai', $params);

$i = 1;
if (0 < $dn->total()) {
  while ($dn->fetch()) {
    $item = array();
    $first_slug = str_replace(' ', '_', strtolower($fields[0]['title']));
    $item = array();
    $item[] = $dn->display('ID');
    $item[] = '<input type="checkbox" data-judul="' . $dn->display($first_slug) . '" class="check_item" value="' . $dn->display('ID') . '">';
    $item[] = $i;

    foreach ($fields as $f) {
      $slug = str_replace(' ', '_', strtolower($f['title']));
      if ($slug == 'judul') {
        $item[] = '<a href="'.get_the_permalink($dn->field('ID')).'">'.$dn->display('post_title').'</a>';
      } else {
        $item[] = $dn->display($slug);
      }
    }
    $item[] = '<span class="get_link" id="pop_'.$dn->display('ID').'" data-content="Link Copied" data-placement="left"><button style="width:70px;" id="'.$dn->display('ID').'" type="button" class="btn btn-primary btn-sm">Get Link</button></span>';
    $data[] = $item;
    $i++;
  }
}

echo json_encode(array(
  'data' => $data
));

?>
