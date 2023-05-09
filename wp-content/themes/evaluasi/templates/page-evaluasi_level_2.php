<?php

/**
 * Template Name: Evaluasi Level 2
 *
 * @package WordPress
 */
include_once(get_template_directory() . '/templates/components/daftar_nilai.php');
?>

<!DOCTYPE html>
<html lang="en">
<?php get_header(); ?>

<head>
    <style type="text/css">
        .redback {
            background-color: red;
        }
    </style>
</head>

<body>
    <?php get_template_part('loader'); ?>
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            <?php get_template_part('nav'); ?>
            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <?php get_sidebar(); ?>
                    <div class="pcoded-content">
                        <?php get_template_part('breadcrumb'); ?>
                        <div class="pcoded-inner-content">
                            <!-- Main-body start -->
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <!-- Page-body start -->
                                    <div class="page-body">
                                        <div class="card">
                                            <div class="card-header has-text-centered">
                                                <nav>
                                                    <ul class="nav nav-pills nav-fill" id="nav-tab" role="tablist">
                                                        <li class="nav-item nav-link active" id="daftar_nilai-tab" data-toggle="tab" href="#nav-daftar_nilai" role="tab" aria-controls="nav-daftar_nilai" aria-selected="true">
                                                            <a href="#" class="red_back">
                                                                <h4>Daftar Nilai</h4>

                                                            </a>
                                                        </li>
                                                        <li class="nav-item nav-link" id="rekap_per_mp-tab" data-toggle="tab" href="#nav-rekap_per_mp" role="tab" aria-controls="nav-rekap_per_mp" aria-selected="false">
                                                            <a href="#">
                                                                <h4>Rekap Per MP</h4>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item nav-link" id="mata_pelajaran-tab" data-toggle="tab" href="#nav-pelajaran" role="tab" aria-controls="nav-pelajaran" aria-selected="false">
                                                            <a href="#">
                                                                <h4>Rekap Keseluruhan</h4>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </nav>

                                            </div>
                                            <div class="card-content p-4">
                                                <div class="tab-content" id="nav-tabContent">
                                                    <div class="tab-pane fade show active" id="nav-daftar_nilai" role="tabpanel" aria-labelledby="daftar_nilai-tab">
                                                        <?= daftar_nilai_page() ?>
                                                    </div>
                                                    <div class="tab-pane fade" id="nav-rekap_per_mp" role="tabpanel" aria-labelledby="rekap_per_mp-tab">
                                                        <div class="card-header has-background-kmk-mix has-text-centered">
                                                            <h5>PESERTA</h5>
                                                            <div class="card-header-right">
                                                                <a href="?action=tambah_peserta"><i class="fa fa-plus" title="Tambah"></i></a>
                                                                <a class="bulk_editor" id="bulk_editor" href="#"><i class="fa fa-table" title="Bulk Editor"></i></a>
                                                                <a class="ubah" id="ubah_peserta" href="#"><i class="fa fa-pencil" title="Ubah"></i></a>
                                                                <i class="hapus fa fa-trash" title="Hapus"></i>
                                                                <i class="fa fa-cog" title="Pengaturan"></i>
                                                            </div>
                                                        </div>
                                                        <div class="card-content p-4">
                                                            ZC
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="nav-pelajaran" role="tabpanel" aria-labelledby="pelajaran-tab">
                                                        <div class="card-header has-background-kmk-mix has-text-centered">
                                                            <h5>MATA PELAJARAN</h5>
                                                            <div class="card-header-right">
                                                                <a href="?action=tambah_mata_pelajaran"><i class="fa fa-plus" title="Tambah"></i></a>
                                                                <a class="bulk_editor" id="bulk_editor" href="#"><i class="fa fa-table" title="Bulk Editor"></i></a>
                                                                <a class="ubah" id="ubah_mata_pelajaran" href="#"><i class="fa fa-pencil" title="Ubah"></i></a>
                                                                <i class="hapus fa fa-trash" title="Hapus"></i>
                                                                <i class="fa fa-cog" title="Pengaturan"></i>
                                                            </div>
                                                        </div>
                                                        <div class="card-content p-4">
                                                            AWF
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- Page-body end -->
                                </div>
                                <div id="styleSelector"> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php get_footer(); ?>

    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">

    <script>
        $(document).ready(function() {
            var ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>';
            $('table').on('click', '.get_link', function(e) {

                var data = {
                    action: 'get_link_daftar_nilai',
                    id: e.target.id
                };

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        navigator.clipboard.writeText(response.message);
                        $('#get_link').html('Get Link');
                        let pop = 'pop_' + e.target.id;
                        $('#' + pop).popover('show');

                        setTimeout(function() {
                            $('#' + pop).popover('hide');
                        }, 1000);
                    }
                });
            });

            $('#generate_daftar_nilai').click(function(e) {
                
                $(this).html('<i class="fa fa-spinner fa-spin"></i>');

                var data = {
                    action: 'generate_daftar_nilai',
                    id_pelatihan: <?= $_GET['id_pelatihan'] ?>
                };

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        alert (response.message);
                        $('#' + e.target.id).html('Generate Daftar Nilai');
                    }
                });
            });
        });
    </script>
</body>

</html>