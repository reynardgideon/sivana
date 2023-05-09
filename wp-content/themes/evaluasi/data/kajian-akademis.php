<?php

require_once("/var/www/sikad.knpk.xyz/wp-load.php");

if (get_ka_role() !== "sekretariat") {
  $params = array(
    'limit' => -1,
    'where' => 'pengkaji_1.ID = ' . $_GET['userid'].' OR pengkaji_2.ID = ' . $_GET['userid'] .' OR pembimbing_metodologi.ID = ' . $_GET['userid'] .' OR pembimbing_substansi.ID = ' . $_GET['userid']
  );
} else {
  $params = array(
    'limit' => -1
  );
}

$ka = pods("kajian_akademis", $params);
$data = array();
if (0 < $ka->total()) {
  $i = 1;
  while ($ka->fetch()) {
    $group = pods('_pods_group', $ka->display("progres_terakhir"));
    $title = explode('=>', $group->display('post_title'));
    $order = get_action_order($ka->display("progres_terakhir"));
    $item = array(
      $i,
      '<a href="'.get_permalink($ka->display("ID")).'">'.$ka->display("judul").'</a>',
      trim($title[1]),
      '<span style="display:none;">'.$order.'</span><progress class="progress is-link is-small" value="'.$order.'" max="21"></progress>'
    );
    $data[] = $item;
    $i++;
  }
}

$data = array(
  "data" => $data
);

echo json_encode($data);
