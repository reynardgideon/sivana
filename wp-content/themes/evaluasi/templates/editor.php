<?php

function editor_buttons($buttons) {
  $b_class = array(
    'create' => 'mx-1 is-primary',
    'edit' => 'mx-1 is-warning must-select',
    'delete' => 'mx-1 is-danger must-select',
    'duplicate' => 'mx-1 is-link must-select',
    'remove' => 'mx-1 is-danger is-light must-select',
    'revise' => 'mx-1 is-warning is-light must-select',
    'back' => 'is-success'  
  );

  $all = '';
  foreach ($buttons as $b) {
    $all .= '<button id="'.$b.'" class="button '.$b_class[$b].'">'.ucwords($b).'</button>';
  }
  ?>
  <div class="container has-text-right">
    <p class="mb-3">
      <?php echo $all; ?>
    </p>
  </div>
  <?php
}

function create_forms($pod_name, $id=null) {
  $fields = array (
    'program' => array(
      'nama_program', 
      'status_kurikulum',
      'jp',
      'hari_kalender',
      'metodologi',
      'durasi',
      'pembelajaran_terintegrasi',
      'deskripsi',
      'tujuan',
      'sasaran_peserta',
      'mata_pelajaran',
      'persyaratan_peserta',
      'evaluasi',
      'kap'
    ),
    'diklat'  => array(
      'nama_diklat',
      'angkatan',
      'program',
      'tanggal_mulai',
      'tanggal_selesai',
      'rincian_waktu_penyelenggaraan',
      'lokasi',
      'pic_diklat',
      'keterangan',
      'rencana_djp',
      'rencana_bppk',
      'rencana_djbc',
      'rencana_djpb',
      'rencana_djkn',
      'rencana_dja',
      'rencana_bkf',
      'rencana_itjen',
      'rencana_setjen',
      'rencana_djpk',
      'rencana_djppr',
      'rencana_lnsw',
      'rencana_non_asn',
      'realisasi_djp',
      'realisasi_bppk',
      'realisasi_djbc',
      'realisasi_djpb',
      'realisasi_djkn',
      'realisasi_dja',
      'realisasi_bkf',
      'realisasi_itjen',
      'realisasi_setjen',
      'realisasi_djpk',
      'realisasi_djppr',
      'realisasi_lnsw',
      'realisasi_non_asn'
    ),
    'kalpem' => array(
      'tahun',
      'revisi',
      'nama_kalpem',
      'tanggal_penetapan',
      'nd_penyampaian'
    ),
    'laporan_akp' => array(
      'unit_pengguna',
      'tahun',
      'tautan_laporan'
    ),
    'akp' => array(
      'program',
      'unit_pengguna',
      'tanggal_disposisi',
      'tanggal_telaahan_ditandatangani',
      'nomor_nd_telaahan',
      'pic',
      'tindak_lanjut',
      'tanggal_mulai',
      'tanggal_selesai'
    ),
    'kap' => array(
      'program',
      'wali_program',
      'pic_kurikulum',
      'status_kap',
      'tautan_kap'
    )
  );

  $params = array(
    'fields_only' => true,
    'fields' => $fields[$pod_name],
    'output_type' => 'table'
  );

  $pod = ($id == null) ? pods($pod_name) : pods($pod_name, $id);
  
  echo $pod->form($params);
}

function show_buttons($back, $diklat=false) {
  $remove = ($diklat) ? '<button id="remove" class="button is-danger must-select">Remove</button>' : '<button id="delete" class="button is-danger must-select">Delete</button>';
  ?>
    <div class="container">
      <p class="mb-3">
        <?php if (isset($_GET['action'])) { ?>
          <a id="create" class="button is-success" href="<?php echo $back; ?>">Back</a>
        <?php } else { ?>
          <a id="create" class="button is-primary" href="?action=create">Create</a>
          <a id="edit" href="#" class="button is-link must-select">Edit</a>
          <?php echo $remove; ?>
        <?php } ?>
      </p>              
    </div>
  <?php
}

function kalpem_button($back) {
  ?>
    <div class="container">
      <p class="mb-3">
        <?php if (isset($_GET['action'])) { ?>
          <a id="create" class="button is-success" href="<?php echo $back; ?>">Back</a>
        <?php } else { ?>
          <a id="create" class="button is-primary" href="?action=create">Create</a>
          <a id="edit" href="#" class="button is-link must-select">Edit</a>
          <button id="duplicate" class="button is-success must-select">Duplicate</button>
          <button id="delete" class="button is-danger must-select">Delete</button>
        <?php } ?>        
      </p>              
    </div>
  <?php
}

function get_form($name, $id=null) {
  $fields = array (
    'program' => array(
      'nama_program', 
      'status_kurikulum',
      'jp',
      'hari_kalender',
      'metodologi',
      'durasi',
      'pembelajaran_terintegrasi',
      'deskripsi',
      'tujuan',
      'sasaran_peserta',
      'mata_pelajaran',
      'persyaratan_peserta',
      'evaluasi',
      'kap'
    ),
    'diklat'  => array(
      'nama_diklat',
      'angkatan',
      'program',
      'tanggal_mulai',
      'tanggal_selesai',
      'rincian_waktu_penyelenggaraan',
      'lokasi',
      'pic_diklat',
      'keterangan',
      'rencana_djp',
      'rencana_bppk',
      'rencana_djbc',
      'rencana_djpb',
      'rencana_djkn',
      'rencana_dja',
      'rencana_bkf',
      'rencana_itjen',
      'rencana_setjen',
      'rencana_djpk',
      'rencana_djppr',
      'rencana_lnsw',
      'rencana_non_asn',
      'realisasi_djp',
      'realisasi_bppk',
      'realisasi_djbc',
      'realisasi_djpb',
      'realisasi_djkn',
      'realisasi_dja',
      'realisasi_bkf',
      'realisasi_itjen',
      'realisasi_setjen',
      'realisasi_djpk',
      'realisasi_djppr',
      'realisasi_lnsw',
      'realisasi_non_asn'
    ),
    'kalpem' => array(
      'tahun',
      'revisi',
      'nama_kalpem',      
      'tanggal_penetapan',
      'nd_perubahan'
    )
  );
  $action = ($id == null) ? 'create' : 'edit';
  $params = array(
    'fields_only' => false,
    'fields' => $fields[$name],
    'output_type' => 'table',
    'thank_you' => '?action='.$action.'&id=X_ID_X&result=1'
  );

  $pod = ($id == null) ? pods($name) : pods($name, $id);
  
  return $pod->form($params);
}

function action_page($name) {
  if (is_user_logged_in()) {
    $id = isset($_GET['id']) ? $_GET['id'] : null;    ?>
    <section class="section pt-3">
      <div id="response_message" class="notification is-success is-light my-3 is-hidden"></div>
      <?php editor_buttons(array('back')); ?>
      <form id="pod_form" action="" method="POST">
        <?php create_form($name, $id); ?>
        <input type="hidden" name="action" value="submit_form">
        <input type="hidden" name="pod_action" value="<?php echo $_GET['action'].'_pod'; ?>">
        <input type="hidden" name="pod_name" value="<?php echo $name; ?>">
        <input type="hidden" name="pod_id" value="<?php echo $id; ?>">
      </form>
      <button id="submit_button" class="is-success mt-3 button">Submit</button>      
    </section>

    <script type='text/javascript'>
      $(document).ready(function() {
        $( "table:first" ).attr("width","100%");
        $( "th" ).attr("width","30%");
      });

      var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';
        
      $('#submit_button').click(function() {
        $(this).addClass('is-loading');
        var data = $('#pod_form').serialize();
        $.ajax({
          url: ajaxurl,
          type: 'POST',
          data: data,
          success: function (response) {
            $('#submit_button').removeClass('is-loading');
            window.scrollTo({ top: 0, behavior: 'smooth' });
            $('#response_message').text('Data berhasil disimpan!');
            $('#response_message').removeClass('is-hidden');
          } 
        });       
      });

      $('#back').click(function() {
        $(this).addClass('is-loading');
        window.location.href = '/<?php echo str_replace('_', '-', $name); ?>';
      });      
    </script>
    <?php
  } else {
    ?>
      <div id="response_message" class="notification is-danger is-light my-3 is-hidden">Anda tidak bisa mengedit halaman ini!</div>
    <?php
  }
}

?>
