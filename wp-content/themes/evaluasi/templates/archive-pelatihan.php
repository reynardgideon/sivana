<?php

include_once(get_template_directory() . '/getters/pelatihan.php');

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'tambah_pelatihan':
            if (isset($_GET['editor']) && $_GET['editor'] == 'bulk') {
                editor_bulk('pelatihan', null);
            } else {
                Pelatihan::editor();
            }
            break;
        case 'ubah_pelatihan':
            Pelatihan::editor($_GET['id_pelatihan']);
            break;
    }
} else {
    halaman_pelatihan();
}

function halaman_pelatihan()
{
?>
    <div class="card">
        <div class="card-header has-background-kmk-mix has-text-centered">
            <h5>PELATIHAN</h5>
            <div class="card-header-right">
                <a href="?action=tambah_pelatihan"><i class="fa fa-plus" title="Tambah"></i></a>
                <a class="bulk_editor" id="bulk_editor" href="#"><i class="fa fa-table" title="Bulk Editor"></i></a>
                <a id="ubah_pelatihan" href="#"><i class="fa fa-pencil" title="Ubah"></i></a>
                <i class="hapus fa fa-trash" title="Hapus" id="hapus"></i>
                <i class="fa fa-cog" title="Pengaturan"></i>
            </div>
        </div>
        <div class="card-content p-4">
            <?= Pelatihan::get_table($_GET['tahun']) ?>
        </div>
    </div>

    <style>
        table,
        table a {
            font-size: 14px;
        }
    </style>
<?php     
}
