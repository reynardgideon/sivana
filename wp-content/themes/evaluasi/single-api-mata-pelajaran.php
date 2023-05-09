<?php
$data = array();

if (isset($_GET['id_pelatihan'])) {
    $pelatihan = pods('pelatihan', $_GET['id_pelatihan']);

    if ($pelatihan->exists()) {
        if ($_GET['type'] == 'table') {
            $i = 1;
            foreach ($pelatihan->field('mata_pelajaran') as $p) {
                $mp = pods('mata_pelajaran', $p['ID']);
                $judul_slug = str_replace(' ', '_', strtolower(FIELDS_MATA_PELAJARAN[0]['title']));
                $judul = $mp->display($judul_slug);

                $item = [];
                $item = [
                    $p['ID'],
                    '<input type="checkbox" class="check_item" value="' . $p['ID'] . '" data-judul="' . $judul . '">',
                    $i,
                    $judul
                ];

                for ($j = 1; $j < count(FIELDS_MATA_PELAJARAN); $j++) {
                    $slug = str_replace(' ', '_', strtolower(FIELDS_MATA_PELAJARAN[$j]['title']));

                    $item[] = str_replace('and', 'dan', $mp->display($slug));
                }

                $data[] = $item;
                $i++;
            }
        } else {
            $ids = $pelatihan->field('mata_pelajaran.ID');
            $juduls = $pelatihan->field('mata_pelajaran.judul');

            for ($i = 0; $i < count($ids); $i++) {
                $data[] = array(
                    'id' => $ids[$i],
                    'judul' => $juduls[$i]
                );
            }
        }
    }
    echo json_encode(array(
        'data' => $data
    ));
}
