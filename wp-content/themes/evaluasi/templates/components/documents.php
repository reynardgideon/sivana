<?php

function get_documents_page()
{    
?>
    <div class="card">
        <header class="card-header has-background-kmk-mix has-text-centered">
            <p class="card-header-title has-text-grey-dark">
                DAFTAR DOKUMEN
            </p>
        </header>
        <div class="card-content">
        <table id="tabel_dokumen" data-id="<?= get_the_id() ?>" class="display" width="100%"></table>
        </div>
    </div>
<?php
}
?>