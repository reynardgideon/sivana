<?php
function get_pembimbingan_page()
{
  if (isset($_GET['id'])) {
    echo $_GET['id'];
  } else {

?>
    <div id="pembimbingan-page">
      <div class="card">
        <header class="card-header has-background-kmk-mix has-text-centered">
          <p class="card-header-title has-text-grey-dark">
            DAFTAR KEGIATAN PEMBIMBINGAN
          </p>
        </header>
        <div class="card-content">
          <div class="container">
            <?php if (is_ka_role('pengguna')) : ?>
              <p class="mb-3">
                <a id="tambah_kegiatan" class="button is-link" href="#">Tambah Kegiatan</a>
              </p>
            <?php endif; ?>
          </div>
          <table id="tabel_pembimbingan" data-id="<?= get_the_id() ?>" class="display" width="100%"></table>
        </div>
      </div>

      <div id="tambah_kegiatan_modal" class="modal">
        <div class="modal-background"></div>
        <div class="modal-card">
          <header class="modal-card-head">
            <p class="modal-card-title"><b>Tambah Kegiatan</b></p>
            <button class="delete" aria-label="close"></button>
          </header>
          <section id="confirm_message" class="modal-card-body">
            <?php
            $fields = array(
              'tanggal_kegiatan',
              'kegiatan',
              'output',
              'file',
              'keterangan'
            );
            $params = array(
              'fields_only' => true,
              'fields' => $fields,
              'output_type' => 'div'
            );

            $pemb = pods('pembimbingan');
            $form = $pemb->form($params);
            if (strpos($form, 'Error')) {
              echo 'Maaf Permintaan Anda tidak dapat kami proses. Silahkan hubungi Admin.';
            } else {
              echo '<form id="pembimbingan_form" class="has-text-left" action="" method="POST">';
              echo $form;
              echo '<input type="hidden" name="action" value="submit_form">';
              echo '<input type="hidden" name="kajian_akademis" value="' . get_the_id() . '">';
              echo '<input type="hidden" name="ka_action" value="tambah_kegiatan">';
              echo '</form>';
              echo '<button id="submit_kegiatan" class="is-success mt-3 button">Submit</button>';
            }
            ?>
          </section>
        </div>
      </div>
    </div>
<?php }
}
?>