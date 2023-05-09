<?php
  $sheet = pods('sheet', get_the_id());

  $data = '{}';

  if (!empty($sheet->display('data'))) {
    $data = $sheet->display('data');
  }
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>Spreadsheet</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/x-data-spreadsheet@1.1.5/dist/xspreadsheet.css">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
  <style>
    .icon:hover {
      background-color: #ddd;
    }
  </style>
</head>

<body>
  <div class="pl-5 has-background-light">
    <span id="save" class="icon is-medium button" title="Save Document">
      <i class="mdi mdi-24px mdi-content-save"></i>
    </span>
    <span class="pl-2"><?= get_the_title() ?></span>
  </div>
  <div id="x-spreadsheet"></div>
  <script>
    $(document).ready(function($) {
      var xs = x_spreadsheet('#x-spreadsheet', {
        showToolbar: false,
        showGrid: true,
        row: {
          len: 30,
          height: 30,
        },
        col: {
          len: 2,
        }
      });

      var data = JSON.parse('<?= $data ?>');
      xs.loadData(data);

      xs.on('cell-edited', (text, ri, ci) => {alert(text)});
      
      $('#save').click(function() {
        
        var ajaxurl = 'https://evaluasi.knpk.xyz/wp-admin/admin-ajax.php';
        $(this).addClass('is-loading');

        var data = {
          action: 'save_sheet',
          json_data: xs.getData(),
          sheet: <?= get_the_id() ?>
        };
        $.ajax({
          url: ajaxurl,
          type: 'POST',
          data: data,

          success: function(response) {
            console.log(response);
            $('#save').removeClass('is-loading');
          }
        });
      });
    });
  </script>
  <script src="https://unpkg.com/x-data-spreadsheet@1.1.5/dist/xspreadsheet.js"></script>
</body>

</html>