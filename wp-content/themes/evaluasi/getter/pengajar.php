<?php
function get_pengajar($args)
{
    $data = array();
    $params = array(
        'limit' => -1,
        'where' => "groups.meta_value = 'pengajar'"
    );

    $pod = pods('user', $params);

    switch ($args['type']) {
        case 'dataraw':
            while ($pod->fetch()) {
                $item = array();
                $item['id'] = $pod->display('ID');
                $item['nama_lengkap'] = $pod->display('nama_lengkap');
                $data[] = $item;
            }
            break;
        case 'datatable':
            $i = 1;
            while ($pod->fetch()) {
                $fields = array(
                    'Nama Lengkap',
                    'NIP',
                    'Unit Kerja',
                    'Nomor HP'
                );

                $item = array();
                $first_slug = str_replace(' ', '_', strtolower($fields[0]));
                $item = array();
                $item[] = $pod->display('ID');
                $item[] = '<input type="checkbox" data-' . $first_slug . '="' . $pod->display($first_slug) . '" class="check_item" value="' . $pod->display('ID') . '">';
                $item[] = $i;

                foreach ($fields as $f) {
                    $slug = str_replace(' ', '_', strtolower($f));
                    $item[] = $pod->display($slug);
                }
                $data[] = $item;
                $i++;
            }
            break;
        case 'dataselect':
            while ($pod->fetch()) {

            }

    }
}
