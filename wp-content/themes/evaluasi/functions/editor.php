<?php
include_once(get_template_directory() . '/getters/pengajar.php');

function editor_single($pod_name, $id = null, $id_pelatihan = null)
{
  $form = '<form id="form_' . $pod_name . '" action="" method="POST" class="needs-validation">';

  $pod = $pod_name == 'peserta' || $pod_name == 'pengajar' || $pod_name == 'pengguna' ? pods('user', $id) : pods($pod_name, $id);

  foreach (constant('FIELDS_' . strtoupper($pod_name)) as $f) {
    $name = str_replace(' ', '_', strtolower($f['title']));

    $val = null;

    if ($id !== null) {
      if ($name == 'pengajar') {
        $val = $pod->field(array(
          'name' => 'pengajar.ID',
          'single' => false
        ));
      } else if($name == 'group') {
        $val = $pod->field(array(
          'name' => 'group.ID',
          'single' => false
        ));
      } else if ($name == 'jadwal') {
        $dates = $pod->field(array(
          'name' => 'jadwal',
          'single' => false
        ));
        
        foreach ($dates as $d) {
          $d = date("d-m-Y", strtotime($d));
          $val[$d] = $d;
        }
      } else if ($name == 'mulai' || $name == 'selesai') {
        $val = date("d-m-Y", strtotime($pod->field($name)));
      } else {
        $val = $pod->field($name);
      }
    }

    $list = null;
    if (in_array($f['type'], array('radio', 'select', 'checkbox', 'dropdown'))) {
      if ($name == 'jadwal') {
        $mp = pods('pelatihan', $id_pelatihan);
        $dates = get_dates($mp->field('mulai'), $mp->field('selesai'));

        foreach ($dates as $d) {
          $list[$d] = $d;
        }
      } else if ($name == 'jenis_mp') {
        $list['pokok'] = 'Pokok';
        $list['penunjang'] = 'Penunjang';
      } else if ($name == 'pengajar') {
        $list = Pengajar::get_data($id_pelatihan);
      } else {
        $list = $pod->fields($name, 'data');
      }
    }
    $form .= editor_input($f['type'], $f['title'], $val, $list);
  }

  


  $form .= '<input type="hidden" name="action" value="action_' . $pod_name . '_single">';
  $form .= '<input type="hidden" name="pelatihan" value="' . $id_pelatihan . '">';
  $form .= '<input type="hidden" name="pod_id" value="' . $id . '">';
  $form .= '<button style="width:100px;" id="cancel" type="button" class="btn btn-danger mx-2">Cancel</button>';
  $form .= '<button style="width:100px;" id="submit_button" type="submit" class="btn btn-primary">Submit</button>';
  $form .= '</form>';  

  editor_page($pod_name, $form);
  editor_script($pod_name);
}

function editor_bulk($pod, $id_related)
{
  $obj = $pod;
  $content = '<div id="spreadsheet"></div>';
  $content .= '<div class="text-center mt-3">';
  $content .= '<button style="width:100px;" id="cancel" type="button" class="btn btn-danger mx-2">Cancel</button>';
  $content .= '<button style="width:100px;" id="submit_button" type="submit" class="btn btn-primary">Submit</button>';
  $content .= '</div>';

  $fields = constant('FIELDS_' . strtoupper($obj));

  if ($pod == 'mata_pelajaran') {
    $mp = pods('pelatihan', $id_related);
    $fields[5]['source'] = get_dates($mp->field('mulai'), $mp->field('selesai'));
  }

  editor_page($pod, $content);
  bulk_editor_script($obj, $fields, $id_related);
}



function editor_page($pod, $content)
{
?>
  <div class="card">
    <div class="card-header has-background-kmk-mix has-text-centered">
      <h5><?= 'TAMBAH ' . strtoupper(str_replace('_', ' ', $pod)) ?></h5>
      <div class="card-header-right">
          <i id="back" class="ti ti-angle-double-left" title="Kembali"></i>        
      </div>
    </div>
    <div class="card-content p-4">
      <?= $content ?>
    </div>
  </div>
<?php
}

function editor_input($type, $label, $val = null, $list = null)
{
  $name = str_replace(' ', '_', strtolower($label));
  $input_name = $name == 'pengajar' || $name == 'jadwal' || $name == 'group' ? $name . '[]' : $name;
  $picker = $name == 'pengajar' || $name == 'jadwal' || $name == 'group' ? ' selectpicker" data-live-search="true" multiple' : '"';
  $r = '';
  switch ($type) {
    case 'text':
      $r = '
            <div class="form-group">
                <label for="' . $name . '">' . $label . '</label>
                <input name="' . $name . '" value="' . $val . '" type="' . $type . '" class="form-control" id="' . $name . '" required>                
            </div>';
      break;
    case 'calendar':
      $r = '
          <div class="form-group">
            <label for="' . $name . '">' . $label . '</label>
            <input autocomplete="off" name="' . $name . '" value="' . $val . '" type="' . $type . '" class="datepicker form-control" id="' . $name . '" required>                
          </div>';
      break;
    case 'dropdown':
      $option = '';
      foreach ($list as $slug => $title) {
        $selected = '';
        if (is_array($val)) {
          if (in_array($slug, $val)) {
            $selected = ' selected';
          }
        }
        $option .= '<option value="' . $slug . '"' . $selected . '>' . $title . '</option>';
      }
      $r = '      
            <div class="form-group">
              <label for="' . $name . '">' . $label . '</label>
              <select id="' . $name . '" name="' . $input_name . '" class="form-control' . $picker . '>                
                ' . $option . '
              </select>
            </div>';
      break;
  }
  return $r;
}

function editor_script($pod)
{
?>
  <script type='text/javascript'>
    $(document).ready(function() {
      var ajaxurl = '<?= admin_url('admin-ajax.php') ?>';
      $('#form_<?= $pod ?>').on('submit', function(e) {
        e.preventDefault();
        $('#submit_button').html('<i class="fa fa-spinner fa-spin"></i>');
        var data = $('#form_<?= $pod ?>').serialize();
        $.ajax({
          url: ajaxurl,
          type: 'POST',
          data: data,
          success: function(response) {
            if (response.status == 1) {
              tata.success(response.message, '', {
                position: 'tm',
                duration: 2000
              });

              if (response.reset == 1) {
                $('#form_<?= $pod ?>').get(0).reset();
              }
            } else {
              tata.error(response.message, '', {
                position: 'tm',
                duration: 2000
              });
            }
            $('#submit_button').html('Submit');
          }
        });
      });

      $('#back').click(function() {
        window.location.href = document.referrer;
      });

      $('#cancel').click(function() {
        $(this).html('<i class="fa fa-spinner fa-spin"></i>');
        window.location.href = document.referrer;
      });


      $(".datepicker").datepicker({
        dateFormat: "dd-mm-yy",
        dayNames: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
        dayNamesMin: ["Mi", "Se", "Sel", "Ra", "Ka", "Ju", "Sa"],
        monthNames: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"]
      });
    });
  </script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">
  <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<?php
}

function bulk_editor_script($obj, $fields, $id_related)
{
?>
  <script>
    $(document).ready(function() {
      //var data = [];
      var ajaxurl = '<?= admin_url('admin-ajax.php') ?>';
      var fields = <?= json_encode($fields) ?>

      fields.map(function(item) {
        item['width'] = ($('.card-content').width() - 100) * item['width'];
        return item;
      });

      let sheet = jspreadsheet(document.getElementById('spreadsheet'), {
        data: null,
        tableOverflow: true,
        minDimensions: [3, 10],
        columns: fields,
        rowResize: true,
      });

      $('#submit_button').click(function() {
        $(this).html('<i class="fa fa-spinner fa-spin"></i>');
        var data = {
          action: 'tambah_<?= $obj ?>_bulk',
          json: sheet.getData(),
          id_pelatihan: <?= get_the_id() ?>,
        };
        $.ajax({
          url: ajaxurl,
          type: 'POST',
          data: data,
          success: function(response) {
            if (response.status == 1) {
              tata.success(response.message, '', {
                position: 'tm',
                duration: 2000
              });
              sheet.setData([]);
            } else {
              tata.error(response.message, '', {
                position: 'tm',
                duration: 2000
              });
            }
            $('#submit_button').html('Submit');
          }
        });
      });
      $('#cancel').click(function() {
        $(this).html('<i class="fa fa-spinner fa-spin"></i>');
        window.location.href = document.referrer;
      });

      $('#back').click(function() {
        window.location.href = document.referrer;
      });
    });
  </script>

  <script src="https://bossanova.uk/jspreadsheet/v4/jexcel.js"></script>
  <link rel="stylesheet" href="https://bossanova.uk/jspreadsheet/v4/jexcel.css" type="text/css" />

  <script src="https://jsuites.net/v4/jsuites.js"></script>
  <link rel="stylesheet" href="https://jsuites.net/v4/jsuites.css" type="text/css" />

  <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">
  <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
<?php
}



?>