<?php
if (isset($_GET['action'])) {
    editor_single('daftar_nilai', null, $_GET['id_pelatihan']);
} else {
    echo 'daftar nilai';
}
?>