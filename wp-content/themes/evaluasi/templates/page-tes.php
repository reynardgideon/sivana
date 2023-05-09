<?php

/**
 * Template Name: Tes
 *
 * @package WordPress
 * Template Post Type: page, dokumen
 */


?>

<!DOCTYPE html>
<html lang="en" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">

<head>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
</head>


<body>
  <form method="post" id="load_excel_form" enctype="multipart/form-data">
    <table class="table">
      <tr>
        <td width="25%">Select Excel File</td>
        <td width="50%"><input type="file" name="select_excel" /><input type="hidden" name="action" value="import_excel"></td>
        <td width="25%"><input type="submit" name="load" class="btn btn-primary" /></td>
      </tr>
    </table>
  </form>

  <script>
    $(document).ready(function() {
      $('#load_excel_form').on('submit', function(event) {
        event.preventDefault();
        var ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>';
        
        $.ajax({
          url: ajaxurl,
          type: 'POST',
          data: new FormData(this),
          contentType: false,
          cache: false,
          processData: false,
          success: function(response) {
            alert(response.message);
          }
        });
      });
    });
  </script>
</body>

</html>