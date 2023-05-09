<?php

include_once(get_template_directory() . '/templates/components/peserta.php');
include_once(get_template_directory() . '/templates/components/pelatihan.php');
include_once(get_template_directory() . '/getters/daftar-nilai.php');
include_once(get_template_directory() . '/templates/components/menu.php');

if (isset($_GET['section'])) {
    include_once(get_template_directory() . '/templates/components/' . $_GET['section'] . '.php');
}

if (isset($_GET['section'])) {
    call_user_func('page_'.$_GET['section']);
} else {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'tambah_peserta':
                if (isset($_GET['editor']) && $_GET['editor'] == 'bulk') {
                    editor_bulk('peserta', get_the_id());
                } else {
                    editor_single('peserta', null, get_the_id());
                }
                break;
            case 'ubah_peserta':
                if (isset($_GET['editor']) && $_GET['editor'] == 'bulk') {
                    //ubah_peserta_bulk($_GET['ids']);
                } else {
                    editor_single('peserta', $_GET['id_peserta'], get_the_id());
                }
                break;
            case 'tambah_mata_pelajaran':
                if (isset($_GET['editor']) && $_GET['editor'] == 'bulk') {
                    editor_bulk('mata_pelajaran', get_the_id());
                } else {
                    editor_single('mata_pelajaran', null, get_the_id());
                }
                break;
            case 'ubah_mata_pelajaran':
                editor_single('mata_pelajaran', $_GET['id_mata_pelajaran'], get_the_id());
                break;
            case 'tambah_daftar_nilai':
                Daftar_Nilai::editor(get_the_id());
                break;

            case 'ubah_daftar_nilai':
                Daftar_Nilai::editor(get_the_id(), $_GET['id_daftar_nilai']);
                break;
        }
    } else {
        Pelatihan_P::get_page();
    }
}
