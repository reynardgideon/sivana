<?php
include_once(get_template_directory() . '/getters/mata-pelajaran.php');
$mp = pods('mata_pelajaran', get_the_id());
?>

<div class="card">
    <div class="card-header text-center">
        <h3><?= get_the_title() ?></h3>
        <h4 class="mt-4 text-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?= $mp->display('pengajar') ?></h4>
        <div class="dropdown show">
            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Dropdown link
            </a>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <a class="dropdown-item" href="#">Something else here</a>
            </div>
        </div>
    </div>
    <div class="card-content p-4">
        <?= Mata_Pelajaran::npr_table_single(get_the_id()) ?>
    </div>
</div>