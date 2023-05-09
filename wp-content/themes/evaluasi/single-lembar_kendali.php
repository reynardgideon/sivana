<?php
/*
Template Name: Document
Template Post Type: page, form
*/

include_once(get_template_directory() . '/getters/component.php');
include_once(get_template_directory() . '/templates/components/lembar_kendali_penyerahan_lju.php');
include_once(get_template_directory() . '/getters/helpers.php');

$pod = pods('lembar_kendali', get_the_id());
$data = (array) json_decode($pod->field('data'));
$action = isset($_GET['action']) ? $_GET['action'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= get_the_title() ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <style>
        @page {
            size: A4;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 25pt;
        }

        input {
            border-top-style: hidden;
            border-right-style: hidden;
            border-left-style: hidden;
            border-bottom-style: dotted;
            border-bottom-color: #ddd;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            width: 100%;
        }

        .no-outline:focus {
            outline: none;
        }
    </style>
    <script src="<?php echo get_template_directory_uri(); ?>/libs/signaturepad/assets/numeric-1.2.6.min.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/libs/signaturepad/assets/bezier.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/libs/signaturepad/jquery.signaturepad.js"></script>
</head>

<body>
    <div class="page mb-4" id="page">
        <?= Component::document_header() ?>
        <?= lembar_kendali_penyerahan_lju($data, $action) ?>
    </div>
</body>

</html>