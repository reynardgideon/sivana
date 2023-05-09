<?php
$program = pods('program', get_the_id());
?>

<div class="card">
    <div class="card-header text-center">
        <h3><?= get_the_title() ?></h3>        
    </div>
    <div class="card-content p-4">
        <?= $program->display('kompetensi_dasar') ?>
    </div>
</div>