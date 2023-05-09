<?php

/**
 * Template Name: Rekapitulasi Evaluasi Level 1
 *
 * @package WordPress
 */

?>
<!DOCTYPE html>
<html lang="en">

<head>    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <script>
        $(function() {
            var pelatihan = [{
                    value: "www.foo.com",
                    label: "Spencer Kline"
                },
                {
                    value: "www.example.com",
                    label: "James Bond"
                }
            ];
            $("#pelatihan").autocomplete({
                source: pelatihan,
                select: function(event, ui) {
                    window.location.href = ui.item.value;
                }
            });
        });
    </script>
</head>
<?php get_header(); ?>

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
                                    <div class="page-body has-text-centered columns">
                                        <div class="column is-8 mx-auto mt-6">
                                            <div class="ui-widget">
                                                <label for="pelatihan">
                                                    <h4 class="title is-4">Cari Pelatihan</h4>
                                                </label>
                                                <div class="field is-grouped">
                                                    <input id="pelatihan" class="input is-link" type="text" placeholder="Masukkan nama pelatihan">
                                                    <div class="select ml-2">
                                                        <select>
                                                            <option>2021</option>
                                                            <option>2022</option>
                                                        </select>
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

    <!-- Required Jquery -->
    <?php get_footer(); ?>
</body>

</html>

?>