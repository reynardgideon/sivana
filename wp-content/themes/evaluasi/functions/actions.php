<?php

include_once(get_template_directory() . '/class/ujian.php');

include_once(get_template_directory() . '/getters/pengolahan.php');

require_once(get_template_directory() . '/libs/phpspreadsheet/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;


add_action('wp_ajax_submit_form_evagara', 'submit_form_evagara');

add_action('wp_ajax_save_sheet', 'save_sheet');

add_action('wp_ajax_update_profile', 'update_profile');

add_action('wp_ajax_submit_form', 'submit_form');

add_action('wp_ajax_generate_daftar_nilai', 'generate_daftar_nilai');

add_action('wp_ajax_remove_peserta', 'remove_peserta');

add_action('wp_ajax_get_link_daftar_nilai', 'get_link_daftar_nilai');

add_action('wp_ajax_remove_mata_pelajaran', 'remove_mata_pelajaran');

add_action('wp_ajax_delete_pengajar', 'delete_pengajar');

add_action('wp_ajax_delete_pod', 'delete_pod');

add_action('wp_ajax_tambah_peserta_bulk', 'tambah_peserta_bulk');

add_action('wp_ajax_action_pelatihan_single', 'action_pelatihan_single');

add_action('wp_ajax_tambah_pengajar_bulk', 'tambah_pengajar_bulk');

add_action('wp_ajax_tambah_pengguna_bulk', 'tambah_pengguna_bulk');

add_action('wp_ajax_tambah_pelatihan_bulk', 'tambah_pelatihan_bulk');

add_action('wp_ajax_tambah_mata_pelajaran_bulk', 'tambah_mata_pelajaran_bulk');

add_action('wp_ajax_action_peserta_single', 'action_peserta_single');

add_action('wp_ajax_action_pengajar_single', 'action_pengajar_single');

add_action('wp_ajax_action_pengguna_single', 'action_pengguna_single');

add_action('wp_ajax_action_mata_pelajaran_single', 'action_mata_pelajaran_single');

add_action('wp_ajax_nopriv_submit_form_evagara', 'submit_form_evagara');

add_action('wp_ajax_nopriv_submit_daftar_nilai', 'submit_daftar_nilai');

add_action('wp_ajax_submit_daftar_nilai', 'submit_daftar_nilai');

add_action('wp_ajax_nopriv_save_daftar_nilai', 'save_daftar_nilai');

add_action('wp_ajax_save_daftar_nilai', 'save_daftar_nilai');

add_action('wp_ajax_edit_daftar_nilai', 'edit_daftar_nilai');

add_action('wp_ajax_remove_daftar_nilai', 'remove_daftar_nilai');

add_action('wp_ajax_update_status', 'update_status');

add_action('wp_ajax_masuk_ujian', 'masuk_ujian');

add_action('wp_ajax_import_excel', 'import_excel');

add_action('wp_ajax_rekap_evaluasi_level_1', 'rekap_evaluasi_level_1');

add_action('wp_ajax_get_saran', 'get_saran');

add_action('wp_ajax_update_saran', 'update_saran');

add_action('wp_ajax_update_rekomendasi', 'update_rekomendasi');

add_action('wp_ajax_remove_saran', 'remove_saran');

add_action('wp_ajax_edit_evaluasi_level_1', 'edit_evaluasi_level_1');

add_action('wp_ajax_nopriv_submit_pakta_integritas', 'submit_pakta_integritas');

add_action('wp_ajax_submit_pakta_integritas', 'submit_pakta_integritas');

function submit_pakta_integritas()
{    
    $post = array(
        'form' => $_POST['form'],
        'pelatihan' => $_POST['pelatihan'],
        'responden' => $_POST['responden']
    );

    $remove = ['action', 'form', 'responden', 'pelatihan'];
    foreach ($remove as $re) {
        if (isset($_POST[$re])) {
            unset($_POST[$re]);
        }
    }

    $post['data'] = json_encode($_POST);

    $data_form = pods('data_form');
    $r = $data_form->add($post);
  
    $message = '';
    if (is_int($r)) {
        $message = 'Pakta Integritas berhasil dikirim.';
    } else {
        $message = 'Maaf, anda harus melengkapi semua data.';
    }
    
    wp_send_json(array('message' => $message));
}

function edit_evaluasi_level_1()
{
    $pod = pods('pelatihan', $_POST['id_pelatihan']);

    $rekap = $pod->field('rekap_evaluasi_level_1') == '' ? [] : (array) json_decode($pod->field('rekap_evaluasi_level_1'));

    $json = $_POST['json'];

    if ($_POST['jenis'] == 'penyelenggaraan') {

        $ev =  (array) $rekap['penyelenggaraan'];

        for ($i = 0; $i < count($json); $i++) {
            if ($i == count($json) - 1) {
                $key = 'rerata';
            } else {
                $key = 'q' . $i + 1;
            }
            $item = array(
                'h' => $json[$i][1],
                'k' => $json[$i][2],
                'p' => $json[$i][3],
                'ku' => $json[$i][4]
            );
            $ev[$key] = $item;
        }

        $rekap['penyelenggaraan'] = $ev;
    } else {

        $ev =  (array) $rekap['pengajar'];

        for ($i = 0; $i < count($json); $i++) {
            if ($i == count($json) - 1) {
                $ev['rerata'] = array(
                    'h' => $json[$i][2],
                    'k' => $json[$i][3],
                    'p' => $json[$i][4],
                    'ku' => $json[$i][5],
                );
            } else {
                $key = $json[$i][6] . '-' . $json[$i][7];
                $item = array(
                    $json[$i][2],
                    $json[$i][3]
                );
                $ev[$key] = $item;
            }
        }

        $rekap['pengajar'] = $ev;

        foreach ($ev as $key => $xue) {
            if ($key !== 'rerata') {

                $ids = explode('-', $key);

                $data_evajar = array(
                    'pelatihan' => $_POST['id_pelatihan'],
                    'pengajar' => $ids[0],
                    'mata_pelajaran' => $ids[1],
                    'nilai' => json_encode(array(
                        'h' => $xue[0],
                        'k' => $xue[1],
                        'p' => Helpers::predikat($xue[1]),
                        'ku' => Helpers::kuadran($xue[0], $xue[1])
                    )),
                    'post_title' => $_POST['id_pelatihan'] . '-' . $ids[1] . '-' . $ids[0]
                );

                $evajar = pods('evaluasi_pengajar', $data_evajar['post_title']);

                if ($evajar->exists()) {
                    $evajar->save($data_evajar);
                } else {
                    $evajar->add($data_evajar);
                }
            }
        }
    }

    $pod->save(
        array(
            'rekap_evaluasi_level_1' => json_encode($rekap)
        )
    );

    wp_send_json(array(
        'status' => 1,
        'message' => 'data berhasil disimpan!'
    ));
}
function remove_saran()
{
    foreach ($_POST['ids'] as $id) {
        $saran = pods('saran', $id);

        $saran->save(array(
            'aktif' => 0
        ));
    }

    $params = array(
        'limit' => -1,
        'where' => "pelatihan.ID = '" . $_POST['pelatihan'] . "'"
    );

    $data_form = pods('data_form', $params);

    if ($data_form->total() == 0) {
        $params = array(
            'limit' => -1,
            'where' => "pelatihan.ID = '" . $_POST['pelatihan'] . "' AND kategori.meta_value='" . $_POST['kategori'] . "'"
        );

        $all_saran = pods('saran', $params);

        $total = 0;
        if ($all_saran->total() > 0) {
            while ($all_saran->fetch()) {
                if ($all_saran->field('aktif') == 1) {
                    $total += $all_saran->field('frekuensi');
                }
            }
        }

        $pod = pods('pelatihan', $_POST['pelatihan']);

        $ev_1 = (array)json_decode($pod->field('rekap_evaluasi_level_1'));

        $ev_1['responden'] = $total + $_POST['frekuensi'];

        $pod->save(array(
            'rekap_evaluasi_level_1' => json_encode($ev_1)
        ));
    }

    wp_send_json(array(
        'status' => 1,
        'message' => 'Data berhasil dihapus!'
    ));
}

function update_saran()
{
    if (isset($_POST['action'])) {
        unset($_POST['action']);
    }


    //$params = array(
    //    'limit' => -1,
    //    'where' => "pelatihan.ID = '" . $_POST['pelatihan'] . "'"
    //);

    //$data = pods('data_form', $params);



    $params = array(
        'limit' => -1,
        'where' => "pelatihan.ID = '" . $_POST['pelatihan'] . "' AND kategori.meta_value='" . $_POST['kategori'] . "'"
    );

    $all_saran = pods('saran', $params);

    $total = 0;
    if ($all_saran->total() > 0) {
        while ($all_saran->fetch()) {
            if ($all_saran->field('aktif') == 1) {
                $total += $all_saran->field('frekuensi');
            }
        }
    }

    //$total = 3;

    
    $_POST['matriks_rekomendasi'] = $_POST['matriks_rekomendasi'] == 'on' ? 1 : 0;
    

    if (!empty($_POST['id'])) {
        $saran = pods('saran', $_POST['id']);

        $_POST['persen'] = ($_POST['frekuensi'] / ($total + $_POST['frekuensi'])) * 100;

        if ($saran->exists()) {
            $saran->save($_POST);
        }
    } else {
        $saran = pods('saran');

        $_POST['post_title'] = $_POST['pelatihan'];
        $_POST['post_status'] = 'publish';
        $_POST['aktif'] = 1;
        $_POST['persen'] = ($_POST['frekuensi'] / ($total + $_POST['frekuensi'])) * 100;
        $saran->add($_POST);
    }

    $params = array(
        'limit' => -1,
        'where' => "pelatihan.ID = '" . $_POST['pelatihan'] . "'"
    );

    $data_form = pods('data_form', $params);

    if ($data_form->total() == 0) {
        $pod = pods('pelatihan', $_POST['pelatihan']);

        $ev_1 = (array)json_decode($pod->field('rekap_evaluasi_level_1'));

        $ev_1['responden'] = $total + $_POST['frekuensi'];

        $pod->save(array(
            'rekap_evaluasi_level_1' => json_encode($ev_1)
        ));
    }

    wp_send_json(array(
        'status' => 1,
        'message' => 'Data berhasil disimpan!'
    ));
}

function update_rekomendasi()
{
    if (isset($_POST['action'])) {
        unset($_POST['action']);
    }    

    if (!empty($_POST['id'])) {
        $saran = pods('saran', $_POST['id']);

        $_POST['persen'] =  (float)$_POST['persen'];

        if ($saran->exists()) {
            $saran->save($_POST);
        }

        wp_send_json(array(
            'status' => 1,
            'message' => 'Data berhasil disimpan!'
        ));
    } else {
        wp_send_json(array(
            'status' => 0,
            'message' => 'ID rekomendasi tidak ditemukan!'
        ));
    }    
}

function get_saran()
{
    $saran = pods('saran', $_POST["id"]);

    wp_send_json(array(
        'frekuensi' => $saran->field('frekuensi'),
        'isi' => $saran->field('isi'),
        'responden' => $saran->field('responden.ID'),
        'id' => $saran->field('ID'),
        'matriks_rekomendasi' => $saran->field('matriks_rekomendasi')
    ));
}

function rekap_evaluasi_level_1()
{
    $message = Pengolahan::rekap_evaluasi_level_1($_POST['id_pelatihan']);
    wp_send_json(array(
        'status' => 1,
        'message' => $message,
    ));
}

function import_excel()
{
    $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    if (isset($_FILES['select_excel']['name']) && in_array($_FILES['select_excel']['type'], $file_mimes)) {

        $arr_file = explode('.', $_FILES['select_excel']['name']);
        $extension = end($arr_file);

        if ('csv' == $extension) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }

        $spreadsheet = $reader->load($_FILES['select_excel']['tmp_name']);

        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        wp_send_json(array(
            'status' => 1,
            'message' => $sheetData[0][1],
        ));
    } else {
        wp_send_json(array(
            'status' => 1,
            'message' => 'Fail',
        ));
    }
}

function masuk_ujian()
{
    $ujian = pods('ujian', $_POST['id']);
    $peserta = $ujian->field('mata_pelajaran.pelatihan.peserta.nip');

    $fail = false;
    $message = '';

    if (!in_array($_POST['nip'], $peserta)) {
        $fail = true;
        $message .= 'NIP tidak terdaftar pada ujian ini';
    }

    if ($_POST['token'] !== $ujian->display('token')) {
        $fail = true;
        $message .= $message !== '' ? ' dan ' : '';
        $message .= 'Token yang anda masukkan tidak sesuai';
    }

    if ($fail == true) {
        wp_send_json(array(
            'status' => 0,
            'message' => 'Maaf, ' . $message . '!'
        ));
    } else {
        wp_send_json(array(
            'status' => 1,
            'nip' => $_POST['nip'],
        ));
    }
}

function edit_daftar_nilai()
{
    $pengajar = [];
    foreach ($_POST['id_pengajar'] as $id) {
        $p = pods('user', $id);
        $pengajar[] = array(
            'id' => $p->field('ID'),
            'nama_lengkap' => $p->field('nama_lengkap'),
        );
    }

    $_POST['data']['pengajar'] = $pengajar;
    $_POST['data'] = json_encode($_POST['data']);

    $message = '';

    if ($_POST['pod_id'] == null) {
        $dn = pods('daftar_nilai');
        clean_data($_POST);
        $dn->add($_POST);
        $message = 'Data berhasil ditambahkan!';
    } else {
        $dn = pods('daftar_nilai', (int) $_POST['pod_id']);
        clean_data($_POST);
        $dn->save($_POST);
        $message = 'Data berhasil diubah!';
    }


    wp_send_json(array(
        'status' => 1,
        'message' => $message
    ));
}

function remove_daftar_nilai()
{
    foreach ($_POST['ids'] as $id) {
        $dn = pods('daftar_nilai');
        $dn->delete($id);
    }

    wp_send_json(array(
        'status' => 1,
        'message' => 'Data berhasil dihapus!'
    ));
}
function generate_daftar_nilai()
{
    $pelatihan = pods('pelatihan', $_POST['id_pelatihan']);

    $level2 = $pelatihan->field('jenis_evaluasi_level_2');

    $id_pelatihan = $pelatihan->field('ID');

    $komponen_na = (array)json_decode($pelatihan->field('komponen_na'));

    $jenis_pelatihan = $pelatihan->field('jenis_pelatihan');

    $dn = pods('daftar_nilai');

    $code = range(101, count($pelatihan->field('peserta.ID')) + 100);

    shuffle($code);

    $list_code = array();
    foreach ($pelatihan->field('peserta.nip') as $k => $nip) {
        $list_code[$nip] = $code[$k] . '-KNPK-' . $_POST['id_pelatihan'] . '/' . date("Y", strtotime($pelatihan->field('mulai')));;
    }

    $pelatihan->save(array(
        'coding' => json_encode($list_code)
    ));

    $peserta = array_map(function ($id) {
        $user = pods('user', $id);
        $item = array(
            'nama_lengkap' => $user->display('nama_lengkap'),
            'nip' => $user->display('nip'),
            'nilai' => ''
        );

        return $item;
    }, $pelatihan->field('peserta.ID'));

    $peserta_nilai = array_map(function ($id) {
        $user = pods('user', $id);
        $item = array(
            'nama_lengkap' => $user->display('nama_lengkap'),
            'nip' => $user->display('nip'),
            'nilai' => 100
        );

        return $item;
    }, $pelatihan->field('peserta.ID'));

    $code = (array) json_decode($pelatihan->field('coding'));

    $peserta_coded = array();
    foreach ($pelatihan->field('peserta.ID') as $id) {
        $item = [];

        $user = pods('user', $id);
        $nip = (string) $user->display('nip');
        $item['nama_lengkap'] = $user->display('nama_lengkap');
        $item['nip'] = $user->display('nip');
        $item['nilai'] = '';
        $item['code'] = $code[$nip];
        $peserta_coded[] = $item;
    }

    $peserta_coded = array_map(
        function ($id, $code) {
            $user = pods('user', $id);
            $nip = (string) $user->display('nip');
            $item = array(
                'nama_lengkap' => $user->display('nama_lengkap'),
                'nip' => $nip,
                'nilai' => '',
                'code' => $code
            );

            return $item;
        },
        $pelatihan->field('peserta.ID'),
        $code
    );

    $generate = array();

    foreach ($pelatihan->field('mata_pelajaran.ID') as $id) {
        $mp = pods('mata_pelajaran', $id);

        foreach (array('p', 'q', 'r') as $jenis) {
            $ids = !empty($mp->field('pengajar.ID')) ? $mp->field('pengajar.ID') : [];
            $names = !empty($mp->field('pengajar.nama_lengkap')) ? $mp->field('pengajar.nama_lengkap') : [];

            $pengajar = array();

            if (count($ids) > 0) {
                for ($i = 0; $i < count($ids); $i++) {
                    $pengajar[] = array(
                        'nama_lengkap' => $names[$i],
                        'id' => $ids[$i]
                    );
                }
            }


            if ($jenis == 'p' || $jenis == 'q') {

                $data = array(
                    'pelatihan' => $id_pelatihan,
                    'mata_pelajaran' => $id,
                    'jenis_nilai' => $jenis,
                    'data' => json_encode(
                        array(
                            'pengajar' => count($pengajar) == 0 ? [] : $pengajar,
                            'peserta' => ($jenis == 'p') ? $peserta_nilai : $peserta
                        )
                    )
                );

                $generate[] = $data;
            }


            if ($jenis == 'r' && $mp->field('diujikan') == 1 && $level2 == 'ujian') {
                $data = array(
                    'pelatihan' => $id_pelatihan,
                    'mata_pelajaran' => $id,
                    'jenis_nilai' => $jenis,
                    'coding' => 1,
                    'data' => json_encode(
                        array(
                            'pengajar' => count($pengajar) == 0 ? [] : $pengajar,
                            'peserta' => $jenis_pelatihan == 'e-learning' ? $peserta : $peserta_coded,
                        )
                    )
                );

                $generate[] = $data;
            }
        }
    }


    if (floatval($komponen_na['nk']) > 0) {
        $data = array(
            'pelatihan' => $id_pelatihan,
            'jenis_nilai' => 'k',
            'coding' => $jenis_pelatihan == 'e-learning' ? 0 : 1,
            'data' => json_encode(
                array(
                    'pengajar' => [],
                    'peserta' => $jenis_pelatihan == 'e-learning' ? $peserta : $peserta_coded,
                )
            )
        );

        $generate[] = $data;
    }

    if (floatval($komponen_na['npkl']) > 0) {
        $data = array(
            'pelatihan' => $id_pelatihan,
            'jenis_nilai' => 'pkl',
            'format' => 2,
            'data' => json_encode(
                array(
                    'pengajar' => [],
                    'peserta' => $peserta_coded,
                )
            )
        );

        $generate[] = $data;
    }


    foreach ($generate as $g) {
        $dn->add($g);
    }

    wp_send_json(array(
        'status' => 1,
        'message' => 'Daftar Nilai berhasil digenerate!'
    ));
}

function submit_daftar_nilai()
{
    $dn = pods('daftar_nilai', $_POST['id']);
    if ($_POST['token'] == $dn->field('token')) {

        $data = (array) json_decode($dn->field('data'));

        $data['peserta'] = $_POST['nilai'];

        $dn->save(array(
            'data' => json_encode($data),
            'tanda_tangan' => $_POST['sign'],
            'submitted' => 1,
            'token' => ''
        ));

        wp_send_json(array(
            'status' => 1,
            'message' => 'Daftar Nilai berhasil diinput!'
        ));
    } else {
        wp_send_json(array(
            'status' => 1,
            'message' => 'Maaf, token tidak sesuai! Silahkan menghubungi PIC Evaluasi.'
        ));
    }
}

function save_daftar_nilai()
{
    $dn = pods('daftar_nilai', $_POST['id']);
    if ($_POST['token'] == $dn->field('token')) {

        $data = (array) json_decode($dn->field('data'));

        $data['peserta'] = $_POST['nilai'];

        $dn->save(array(
            'data' => json_encode($data)
        ));

        wp_send_json(array(
            'status' => 1,
            'message' => 'Saved'
        ));
    } else {
        wp_send_json(array(
            'status' => 1,
            'message' => 'Maaf, token tidak sesuai! Silahkan menghubungi PIC Evaluasi.'
        ));
    }
}

function get_link_daftar_nilai()
{
    $dn = pods('daftar_nilai', $_POST['id']);

    $token = base64_encode($_POST['id'] . time());

    $dn->save(array(
        'submitted' => 0,
        'token' => $token
    ));

    $link = get_the_permalink($_POST['id']) . '?action=submit&token=' . $token;

    wp_send_json(array(
        'status' => 1,
        'message' => $link
    ));
}

function submit_form_evagara()
{
    $remove = ['action'];
    foreach ($remove as $re) {
        if (isset($_POST[$re])) {
            unset($_POST[$re]);
        }
    }

    $data = $_POST;

    foreach (array('nama', 'nip', 'unit') as $f) {
        if (isset($data[$f])) {
            unset($data[$f]);
        }
    }

    $_POST['data'] = json_encode($data);

    $params = array(
        'limit' => 1,
        'where' => "pelatihan.ID = '" . $_POST['pelatihan'] . "' AND responden.ID = '" . $_POST['responden'] . "'"
    );

    $data_form = pods('data_form', $params);

    if ($data_form->total() > 0) {
        $data_form->save($_POST);
        wp_send_json(array(
            'status' => 1,
            'message' => "Data berhasil diubah!"
        ));
    } else {
        $data_form->add($_POST);
        wp_send_json(array(
            'status' => 1,
            'message' => "Data berhasil disimpan!"
        ));
    }


    //$data->add($_POST);
    //$post['data'] = json_encode($_POST);

    /*
    if (isset($_POST['pakta_id']) && $_POST['pakta_id'] !== '') {
        $pakta = pods('pakta_integritas', $_POST['pakta_id']);
        unset($_POST['pakta_id']);
        $r = $pakta->save($_POST);
    } else {
        $pakta = pods('pakta_integritas');
        $r = $pakta->add($post);
    }
    */
}

function save_sheet()
{
    $sheet = pods('sheet', $_POST['sheet']);
    $sheet->save(array('data' => json_encode($_POST['json_data'])));

    //if (isset($_POST['callback'])) {
    //    call_user_func($_POST['cb'], $_POST['json_data']);
    //}
}

add_action('wp_ajax_submit_form', 'submit_form');
//add_action( 'wp_ajax_delete_pod', 'delete_pod' );


function submit_form()
{
    $action_function = isset($_POST['id']) ? 'ubah_' . $_POST['pod_name'] : 'tambah_' . $_POST['pod_name'];

    $r = $action_function($_POST);
    if (is_int($r) == 1) {
        wp_send_json(array(
            'status' => 1,
            'message' => 'Data berhasil diinput!'
        ));
    } else {
        wp_send_json(array(
            'status' => 0,
            'message' => 'Maaf, data gagal diinput!'
        ));
    }
}

function tambah_peserta($post)
{
    $account = array(
        'user_login' => $post['nip'],
        'user_pass' => $post['nip'],
        'display_name' => $post['nama_lengkap'],
        'first_name' => $post['nama_lengkap'],
        'role'  => 'subscriber'
    );

    $data = array(
        'nama_lengkap' => $post['nama_lengkap'],
        'nip' =>  $post['nip'],
        'unit_kerja' => $post['unit_kerja']
    );
    clean_data($post);

    $r = tambah_user($account, $data, array('peserta'));

    if (is_int($r) == 1) {
        $pelatihan = pods('pelatihan', $post['pelatihan']);
        $pelatihan->add_to('peserta', $r);

        return $r;
    }
}

function action_peserta_single()
{
    if (isset($_POST['pod_id']) && !empty($_POST['pod_id'])) {
        $user = pods("user", $_POST['pod_id']);
        clean_data($_POST);
        $user->save($_POST);

        wp_send_json(array(
            'status' => 1,
            'message' => 'Data berhasil diubah!'
        ));
    } else {
        $user = array(
            'user_login' => $_POST['nip'],
            'user_pass' => $_POST['nip'],
            'display_name' => $_POST['nama_lengkap'],
            'first_name' => $_POST['nama_lengkap'],
            'role'  => 'subscriber'
        );

        $data = array(
            'nama_lengkap' => $_POST['nama_lengkap'],
            'nip' =>  $_POST['nip'],
            'unit_kerja' => $_POST['unit_kerja']
        );

        clean_data($_POST);

        $r = tambah_user($user, $data, array('peserta'));

        if (is_int($r) == 1) {
            $pelatihan = pods('pelatihan', $_POST['pelatihan']);
            $pelatihan->add_to('peserta', $r);

            wp_send_json(array(
                'status' => 1,
                'message' => $r,
                'reset' => 1
            ));
        } else {
            wp_send_json(array(
                'status' => 0,
                'message' => 'Data gagal ditambahkan!'
            ));
        }
    }
}

function action_pengajar_single()
{
    if (isset($_POST['pod_id']) && !empty($_POST['pod_id'])) {
        $user = pods("user", $_POST['pod_id']);
        clean_data($_POST);
        $user->save($_POST);

        wp_send_json(array(
            'status' => 1,
            'message' => 'Data berhasil diubah!'
        ));
    } else {
        $user = array(
            'user_login' => $_POST['nip'],
            'user_pass' => $_POST['nip'],
            'display_name' => $_POST['nama_lengkap'],
            'first_name' => $_POST['nama_lengkap'],
            'role'  => 'subscriber'
        );

        $data = array(
            'nama_lengkap' => $_POST['nama_lengkap'],
            'nip' =>  $_POST['nip'],
            'unit_kerja' => $_POST['unit_kerja'],
            'nomor_hp' => $_POST['nomor_hp']
        );

        clean_data($_POST);

        $r = tambah_user($user, $data, array('pengajar'));

        if (is_int($r) == 1) {

            wp_send_json(array(
                'status' => 1,
                'message' => 'Data berhasil ditambahkan!',
                'reset' => 1
            ));
        } else {
            wp_send_json(array(
                'status' => 0,
                'message' => 'Data gagal ditambahkan!'
            ));
        }
    }
}

function action_pengguna_single()
{
    if (isset($_POST['pod_id']) && !empty($_POST['pod_id'])) {
        $user = pods("user", $_POST['pod_id']);
        clean_data($_POST);
        $user->save($_POST);

        wp_send_json(array(
            'status' => 1,
            'message' => 'Data berhasil diubah!'
        ));
    } else {
        $user = array(
            'user_login' => $_POST['nip'],
            'user_pass' => $_POST['nip'],
            'display_name' => $_POST['nama_lengkap'],
            'first_name' => $_POST['nama_lengkap'],
            'role'  => 'subscriber'
        );

        $data = array(
            'nama_lengkap' => $_POST['nama_lengkap'],
            'nip' =>  $_POST['nip'],
            'unit_kerja' => $_POST['unit_kerja'],
            'nomor_hp' => $_POST['nomor_hp'],
            'group' => $_POST['group']
        );

        clean_data($_POST);

        $r = tambah_user($user, $data);

        if (is_int($r) == 1) {

            wp_send_json(array(
                'status' => 1,
                'message' => 'Data berhasil ditambahkan!',
                'reset' => 1
            ));
        } else {
            wp_send_json(array(
                'status' => 0,
                'message' => 'Data gagal ditambahkan!'
            ));
        }
    }
}

function action_pelatihan_single()
{
    $komponen_na = json_encode(array(
        'nt' => $_POST['nt'],
        'nk' => $_POST['nk'],
        'npkl' => $_POST['npkl']
    ));

    $_POST['komponen_na'] = $komponen_na;

    if (isset($_POST['pod_id']) && !empty($_POST['pod_id'])) {
        $pelatihan = pods("pelatihan", $_POST['pod_id']);
        clean_data($_POST);

        $pelatihan->save($_POST);

        wp_send_json(array(
            'status' => 1,
            'message' => 'Data berhasil diubah!'
        ));
    } else {
        clean_data($_POST);

        $pelatihan = pods("pelatihan");

        $r = $pelatihan->add($_POST);

        if (is_int($r) == 1) {

            wp_send_json(array(
                'status' => 1,
                'message' => 'Data berhasil ditambahkan!',
                'reset' => 1
            ));
        } else {
            wp_send_json(array(
                'status' => 0,
                'message' => 'Data gagal ditambahkan!'
            ));
        }
    }
}

function action_mata_pelajaran_single()
{
    if (isset($_POST['pod_id']) && !empty($_POST['pod_id'])) {
        $mp = pods("mata_pelajaran", $_POST['pod_id']);
        clean_data($_POST);

        $mp->save($_POST);

        wp_send_json(array(
            'status' => 1,
            'message' => 'Data berhasil diubah!'
        ));
    } else {
        $mp = pods("mata_pelajaran");
        clean_data($_POST);
        $r = $mp->add($_POST);

        if (is_int($r) == 1) {
            $pelatihan = pods('pelatihan', $_POST['pelatihan']);
            $pelatihan->add_to('mata_pelajaran', $r);

            wp_send_json(array(
                'status' => 1,
                'message' => 'Data berhasil ditambahkan!',
                'reset' => 1
            ));
        } else {
            wp_send_json(array(
                'status' => 0,
                'message' => 'Data gagal ditambahkan!'
            ));
        }
    }
}

function tambah_pelatihan_bulk()
{
    $pelatihan = pods('pelatihan');
    foreach ($_POST['json'] as $row) {
        if (!empty($row[0])) {
            $data = array();

            $i = 0;
            foreach (FIELDS_PELATIHAN as $f) {
                $slug = str_replace(' ', '_', strtolower($f['title']));
                $data[$slug] = $row[$i];
                $i++;
            }
            $pelatihan->add($data);
        }
    }

    wp_send_json(array(
        'status' => 1,
        'message' => 'Data berhasil ditambahkan!'
    ));
}

function tambah_peserta_bulk()
{
    foreach ($_POST['json'] as $row) {
        if (!in_array('', $row, true)) {
            $data = array();

            $i = 0;
            foreach (FIELDS_PESERTA as $f) {
                $slug = str_replace(' ', '_', strtolower($f['title']));

                if ($slug == 'nip') {
                    $row[$i] = trim(str_replace(' ', '', $row[$i]));
                }
                $data[$slug] = $row[$i];
                $i++;
            }

            $user = array(
                'user_login' => trim($row[1]),
                'user_pass' => trim($row[1]),
                'display_name' => trim($row[0]),
                'first_name' => trim($row[0]),
                'role'  => 'subscriber'
            );

            $r = tambah_user($user, $data, array('peserta'));

            if (is_int($r) == 1) {
                $pelatihan = pods('pelatihan', $_POST['id_pelatihan']);
                $pelatihan->add_to('peserta', $r);
            }
        }
    }
    wp_send_json(array(
        'status' => 1,
        'message' => 'Data berhasil ditambahkan!'
    ));
}

function tambah_pengajar_bulk()
{
    foreach ($_POST['json'] as $row) {
        if (!empty($row[0])) {
            $data = array();

            $i = 0;
            foreach (FIELDS_PENGAJAR as $f) {
                $slug = str_replace(' ', '_', strtolower($f['title']));
                //$index[$slug] = array_search($l, FIELDS_PENGAJAR);

                if ($slug == 'nip') {
                    $row[$i] = trim(str_replace(' ', '', $row[$i]));
                }

                $data[$slug] = $row[$i];
                $i++;
            }

            $user = array(
                'user_login' => trim($row[1]),
                'user_pass' => trim($row[1]),
                'display_name' => trim($row[0]),
                'first_name' => trim($row[0]),
                'role'  => 'subscriber'
            );

            $r = tambah_user($user, $data, array('pengajar'));
        }
    }
    wp_send_json(array(
        'status' => 1,
        'message' => 'Data berhasil ditambahkan!'
    ));
}

function tambah_pengguna_bulk()
{
    foreach ($_POST['json'] as $row) {
        if (!empty($row[0])) {
            $data = array();

            $i = 0;
            foreach (FIELDS_PENGGUNA as $f) {
                $slug = str_replace(' ', '_', strtolower($f['title']));
                //$index[$slug] = array_search($l, FIELDS_PENGAJAR);

                if ($slug == 'nip') {
                    $row[$i] = trim(str_replace(' ', '', $row[$i]));
                }

                if ($slug !== 'group') {
                    $data[$slug] = $row[$i];
                }
                $i++;
            }

            $ids = explode(';', $row[4]);
            $data['group'] = array_filter($ids);

            $user = array(
                'user_login' => trim($row[1]),
                'user_pass' => trim($row[1]),
                'display_name' => trim($row[0]),
                'first_name' => trim($row[0]),
                'role'  => 'subscriber'
            );

            $r = tambah_user($user, $data);
        }
    }
    wp_send_json(array(
        'status' => 1,
        'message' => 'Data berhasil ditambahkan!'
    ));
}

function tambah_mata_pelajaran_bulk()
{
    $ids = array();
    foreach ($_POST['json'] as $row) {
        if (!empty($row[0]) && !empty($row[count($row) - 1])) {
            $data = array();
            $i = 0;
            $mp = pods('mata_pelajaran');

            foreach (FIELDS_MATA_PELAJARAN as $f) {
                $slug = str_replace(' ', '_', strtolower($f['title']));

                if ($slug !== 'pengajar' || $slug !== 'jadwal') {
                    $data[$slug] = $row[$i];
                }
                $i++;
            }
            $data['pelatihan'] = $_POST['id_pelatihan'];
            $ids = explode(';', $row[4]);
            $dates = explode(';', $row[5]);

            $data['jadwal'] = array_filter($dates);
            $data['pengajar'] = array_filter($ids);
            $id = $mp->add($data);
        }
    }

    wp_send_json(array(
        'status' => 1,
        'message' => 'Data berhasil ditambahkan!'
    ));
}


function ubah_peserta($post)
{
    $data = array(
        'nama_lengkap' => $post['nama_lengkap'],
        'nip' =>  $post['nip'],
        'unit_kerja' => $post['unit_kerja']
    );
    clean_data($post);
    $user = pods("user", $post['pod_id']);
    $user->save($data);

    return $user->display('ID');
}

function tambah_user($account, $data, $groups = null)
{
    $id = username_exists($account['user_login']);
    if (!$id) {
        $id = wp_insert_user($account);
        $user = pods("user", $id);
        $user->save($data);
        $user->add_to('groups', $groups);
    }

    return $id;
}

function remove_peserta()
{
    $pelatihan = pods('pelatihan', $_POST['id_pelatihan']);

    foreach ($_POST['ids'] as $id) {
        $pelatihan->remove_from('peserta', $id);
    }

    wp_send_json(array(
        'status' => 1,
        'message' => 'Data berhasil dihapus!'
    ));
}

function remove_mata_pelajaran()
{
    $pod = pods('mata_pelajaran');
    foreach ($_POST['ids'] as $id) {
        $pod->delete($id);
    }

    wp_send_json(array(
        'status' => 1,
        'message' => 'Mata Pelajaran berhasil dihapus!'
    ));
}

function delete_pengajar()
{
    foreach ($_POST['ids'] as $id) {
        $pengajar = pods('user');
        $pengajar->delete($id);
    }

    wp_send_json(array(
        'status' => 1,
        'message' => 'Data berhasil dihapus!'
    ));
}

function delete_pod()
{
    switch ($_POST['pod_name']) {
        case 'peserta':
            $pelatihan = pods('pelatihan', $_POST['id_pelatihan']);
            foreach ($_POST['ids'] as $id) {
                $pelatihan->remove_from('peserta', $id);
            }
            break;
        default:
            $pod = $_POST['pod_name'] == 'pengajar' || $_POST['pod_name'] == 'pengguna' ? pods('user') : pods($_POST['pod_name']);
            foreach ($_POST['ids'] as $id) {
                $pod->delete($id);
            }
            break;
    }

    wp_send_json(array(
        'status' => 1,
        'message' => 'Data berhasil dihapus!'
    ));
}

function clean_data($data)
{
    $fields = array(
        'action',
        'pod_name',
        'pod_id'
    );
    foreach ($fields as $field) {
        if (isset($data[$field])) {
            unset($data[$field]);
        }
    }
    return $data;
}

function update_profile()
{
    $ka = pods('user', $_POST['pod_id']);
    $fields = clean_data($_POST);
    $r = $ka->save($fields);
    wp_send_json($r);
}

function update_status()
{
    $pod = pods('pelatihan', $_POST['id_pelatihan']);

    $data = [];

    if (!empty($pod->field($_POST['group']))) {
        $data = (array) json_decode($pod->field($_POST['group']));
    }

    $data[$_POST['task']] = array(
        'selesai' => $_POST['selesai'] == 'on' ? 1 : 0,
        'tanggal' => date_format(date_create($_POST['tanggal']), 'd/m/Y'),
        'catatan' => $_POST['catatan']
    );


    $pod->save(array(
        $_POST['group'] =>
        json_encode($data)
    ));

    wp_send_json(array(
        'status' => 1,
        'message' => 'Data berhasil diupdate!'
    ));
}
