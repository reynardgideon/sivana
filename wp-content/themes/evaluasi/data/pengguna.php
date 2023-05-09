<?php
require_once("/var/www/evaluasi.knpk.xyz/wp-load.php");

$fields = constant('FIELDS_PENGGUNA');
$data = array();
$params = array(
    'limit' => -1,
    'where' => "groups.meta_value <> 'peserta' AND groups.meta_value <> 'pengajar'"
);

$pod = pods('user', $params);

if (isset($_GET['type']) && $_GET['type'] == 'dropdown') {
    if (0 < $pod->total()) {
        while ($pod->fetch()) {
            $item = array();
            $item['id'] = $pod->display('ID');
            $item['name'] = $pod->display('nama_lengkap');

            $data[] = $item;
        }
    }
    echo json_encode($data);
} else {
    $i = 1;
    if (0 < $pod->total()) {
        while ($pod->fetch()) {
            $item = array();
            $first_slug = str_replace(' ', '_', strtolower($fields[0]['title']));
            $item = array();
            $item[] = $pod->display('ID');
            $item[] = '<input type="checkbox" data-' . $first_slug . '="' . $pod->display($first_slug) . '" class="check_item" value="' . $pod->display('ID') . '">';
            $item[] = $i;

            $nama = '';
            foreach ($fields as $f) {
                $slug = str_replace(' ', '_', strtolower($f['title']));

                if ($slug !== 'nip') {
                    if ($slug == 'nama_lengkap') {
                        $nama .= '<a href="' . get_site_url() . '/author/' . $pod->display('nip') . '/">' . $pod->display($slug) . '</a><br/>'.$pod->display('nip');
                        $item[] = $nama;
                    } else {
                        $r = ($pod->display($slug)) ? $pod->display($slug) : '-';
                        $item[] = $r;
                    }
                }
            }
            $data[] = $item;
            $i++;
        }
    }

    echo json_encode(array(
        'data' => $data
    ));
}
