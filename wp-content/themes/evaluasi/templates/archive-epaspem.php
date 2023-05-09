<?php
include_once(get_template_directory() . '/getters/helpers.php');

$params = array(
    'limit' => -1,
    'where' => "progres.meta_value <> 'selesai'"
);

$ep = pods('epaspem', $params);

$data = [];
$i = 1;
while ($ep->fetch()) {
    $item = array(
        $i,
        str_replace('Epaspem ', '', $ep->display('post_title')),
        $ep->field('level'),
        $ep->display('progres'),
        Helpers::tanggal($ep->field('target_penyelesaian_laporan'))
    );

    $data[] = $item;

    $i++;
}

?>

<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">

<div class="card">
    <div class="card-header has-background-kmk-mix has-text-centered">
        <h5>EVALUASI PASCA PEMBELAJARAN</h5>
        <div class="card-header-right">
            <a href="?action=tambah_epaspem"><i class="fa fa-plus" title="Tambah"></i></a>
            <a id="ubah_epaspem" href="#"><i class="fa fa-pencil" title="Ubah"></i></a>
            <i class="hapus fa fa-trash" title="Hapus" id="hapus"></i>
        </div>
    </div>
    <div class="card-content p-4">
        <table id="epaspem" class="display" width="100%"></table>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#epaspem').DataTable({
            data: <?= json_encode($data) ?>,
            columns: [{
                    title: 'No'
                },
                {
                    title: 'Nama Pelatihan'
                },
                {
                    title: 'Level'
                },
                {
                    title: 'Progres'
                },
                {
                    title: 'Target Penyelesaian Laporan'
                },
            ],
            columnDefs: [
                {
                    className: "dt-head-center",
                    targets: "all"
                },
                {
                    className: "dt-body-center",
                    targets: [0, 2, 3, 4]
                },
            ]
        });
    });
</script>