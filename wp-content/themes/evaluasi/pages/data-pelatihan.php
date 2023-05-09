<?php
function data_pelatihan($tahun) {
    ?>
    <div class="card">
    <div class="card-header has-background-kmk-mix">
        <h5>Data Pelatihan</h5>
        <div class="card-header-right">
            <ul class="list-unstyled card-option">
                <li><i class="ti-plus"></i></li>
                <li><i class="ti-pencil"></i></li>
                <li><i class="ti-eraser"></i></li>
            </ul>
        </div>
    </div>
    <div class="card-block">
        <?= $tahun ?>
    </div>
</div>
<?php
}

