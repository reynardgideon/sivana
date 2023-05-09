<?php
include_once(get_template_directory() . '/templates/components/table.php');
include_once(get_template_directory() . '/getters/pengolahan.php');
include_once(get_template_directory() . '/getters/mata_pelajaran.php');

function page_npr()
{
    $mps = Mata_Pelajaran::get_data_indexed(get_the_id());

    $list = '';
    $index = '';
    $content_only = isset($_GET['content_only']) && $_GET['content_only'] == 'true' ? '&content_only=true' : '';

    for ($i=0; $i<count($mps); $i++) {
        if ($mps[$i]['id'] == $_GET['id_mata_pelajaran']) {
            $index = $i+1;
        }
        $no = $i+1;
        $label = 'MP '.$no.' - '.$mps[$i]['judul'].' - '.Mata_Pelajaran::get_pengajar($mps[$i]['id']);
        $slabel = strlen($label) > 100 ? substr($label, 0, 100).'...' : $label;
        $list .= '<a class="dropdown-item" href="'.get_the_permalink().'?section=npr&id_mata_pelajaran='.$mps[$i]['id'].$content_only.'" title="'.$label.'">'.$slabel.'</a>';
    }    
?>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link href="https://cdn.datatables.net/scroller/2.0.5/css/scroller.dataTables.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">

    <?php if (isset($_GET['id_mata_pelajaran'])) : ?>
        <style>
            .btn-square-md {
                width: 70px !important;
                height: 70px !important;
            }
        </style>
        <div class="card">
            <div class="card-header text-center">
                <div class="dropdown show float-left mr-3">
                    <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        MP <?= $index ?>                        
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <?= $list ?>
                    </div>
                </div>
                <div>
                    <h4><?= Mata_Pelajaran::get_judul($_GET['id_mata_pelajaran']) ?></h4>
                    <?= Mata_Pelajaran::get_pengajar($_GET['id_mata_pelajaran']) ?>
                </div>
            </div>
            <div class="card-content p-4">
                <?= Mata_Pelajaran::npr_table_single($_GET['id_mata_pelajaran']) ?>
            </div>
        </div>
    <?php else : ?>
        <div class="card">
            <div class="card-header has-background-kmk-mix has-text-centered text-dark">
                <h5>NPR</h5>
            </div>
            <div class="card-content p-4">
                <?php Pengolahan::get_npr_table(get_the_id()) ?>
            </div>
        </div>
    <?php endif; ?>
<?php
}
?>