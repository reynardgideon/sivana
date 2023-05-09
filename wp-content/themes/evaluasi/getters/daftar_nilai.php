<?php

function get_daftar_nilai($args)
{

    $data = array();
    $params = array(
        'limit' => -1,
        'where' => "mata_pelajaran.pelatihan.ID='" . $args['id_pelatihan'] . "'"
    );

    $dn = pods('daftar_nilai', $params);
    switch ($args['type']) {
        case 'datatable':
            if (0 < $dn->total()) {
                $fields = [
                    'Mata Pelajaran',
                    'Jenis Nilai',
                    'Submitted'
                ];
                $i = 0;
                while ($dn->fetch()) {
                    $item = array();
                    $first_slug = str_replace(' ', '_', strtolower($fields[0]));
                    $item = array();
                    $item[] = $dn->display('ID');
                    $item[] = '<input type="checkbox" data-' . $first_slug . '="' . $dn->display($first_slug) . '" class="check_item" value="' . $dn->display('ID') . '">';
                    $item[] = $i;

                    foreach ($fields as $f) {
                        $slug = str_replace(' ', '_', strtolower($f));
                        if ($slug == 'mata_pelajaran') {
                            $item[] = '<a href="' . get_the_permalink($dn->field('ID')) . '">' . $dn->display('post_title') . '</a>';
                        } else {
                            $item[] = $dn->display($slug);
                        }
                    }
                    $item[] = '<span class="get_link" id="pop_' . $dn->display('ID') . '" data-content="Link Copied" data-placement="left"><button style="width:70px;" id="' . $dn->display('ID') . '" type="button" class="btn btn-primary btn-sm">Get Link</button></span>';
                    $data[] = $item;
                    $i++;
                }
                
            }

            echo json_encode(array(
                'data' => $data
            ));

            break;

            case 'field_jenis_nilai':

                break;
    }
}
