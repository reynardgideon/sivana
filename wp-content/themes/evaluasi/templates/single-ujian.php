<?php
require_once(get_template_directory() . '/getters/ujian.php');

?>
<!DOCTYPE html>
<html class="has-navbar-fixed-top">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= get_the_title() ?></title>
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
    <style>
        .right {
            text-align: right;
            margin-right: 1em;
        }

        .left {
            text-align: left;
            vertical-align: middle;
            margin-left: 1em;
        }
    </style>
    <?php wp_head(); ?>
</head>

<body>
xxx
</body>

</html>
?>