<?php

function get_mata_pelajaran(array $args)
{

    $pelatihan = pods('pelatihan', $args['id_pelatihan']);

    if ($pelatihan->exists()) {
        switch ($args['type']) {
            case 'datatable':
                $fields = array(
                    'Judul',
                    'JP',
                    'Jenis MP',
                    'Diujikan',
                    'Pengajar',
                    'Jadwal'
                );
                $data = array();
                $i = 1;
                foreach ($pelatihan->field('mata_pelajaran') as $p) {
                    $mp = pods('mata_pelajaran', $p['ID']);
                    $judul_slug = str_replace(' ', '_', strtolower($fields[0]));
                    $judul = $mp->display($judul_slug);

                    $item = [];
                    $item = [
                        $p['ID'],
                        '<input type="checkbox" class="check_item" value="' . $p['ID'] . '" data-judul="' . $judul . '">',
                        $i,
                        $judul
                    ];

                    for ($j = 1; $j < count($fields); $j++) {
                        $slug = str_replace(' ', '_', strtolower($fields[$j]));

                        $item[] = str_replace('and', 'dan', $mp->display($slug));
                    }

                    $data[] = $item;
                    $i++;
                }
                echo json_encode(array(
                    'data' => $data
                ));
                break;

            case 'dataraw':
                $data = [];
                $ids = $pelatihan->field('mata_pelajaran.ID');
                $juduls = $pelatihan->field('mata_pelajaran.judul');
                for ($i = 0; $i < count($pelatihan->field('mata_pelajaran.ID')); $i++) {
                    $item = [];
                    $item['id'] = $ids[$i];
                    $item['judul'] = $juduls[$i];
                    $data[] = $item;
                }
                return $data;
                break;

            case 'dataoptions':
                $options = '';
                $ids = $pelatihan->field('mata_pelajaran.ID');
                $juduls = $pelatihan->field('mata_pelajaran.judul');
                for ($i = 0; $i < count($pelatihan->field('mata_pelajaran.ID')); $i++) {                
                    $options .= "<option value='{$ids[$i]}'>{$juduls[$i]}</option>";
                }
                return $options;
            default:
                return 'No Data';
        }
    }
}
