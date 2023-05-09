<?php
include_once(get_template_directory() . '/getters/helpers.php');
$slide = pods('slide', get_the_id());

$cover = empty($slide->display('cover')) ? get_site_url().'/wp-content/uploads/2023/01/gettyimages-1202900062-612x612-1.jpg' : $slide->display('cover');

$effects = array(
    'zoom',
    'convex',
    'concave',
    'fade',
    'slide'
);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <title>Rapat Kelulusan</title>
    <link rel="stylesheet" href="<?= get_template_directory_uri() ?>/libs/reveal/reveal.css">
    <link rel="stylesheet" href="<?= get_template_directory_uri() ?>/libs/reveal/theme/simple.css" id="theme">
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/css/bootstrap/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="<?= get_template_directory_uri() ?>/libs/reveal/plugin/markdown/markdown.js"></script>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/bootstrap/js/bootstrap.min.js "></script>
    <style type="text/css" media="screen">
        .slides section.has-dark-background,
        .slides section.has-dark-background h3 {
            color: #fff;
        }

        .slides section.has-light-background,
        .slides section.has-light-background h3 {
            color: #222;
        }

        html,
        body {
            margin: 0;
            height: 100%;
            overflow: hidden;
        }

        img {
            min-height: 100%;
            min-width: 100%;
            height: auto;
            width: auto;
            position: absolute;
            top: -100%;
            bottom: -100%;
            left: -100%;
            right: -100%;
            margin: auto;
        }
    </style>
</head>

<body>
    <div class="reveal">
        <div class="slides">
            <section data-transition="zoom" data-background="<?= $cover ?>" data-background-size="contain">
                <div class="p-3 mx-5 mt-5 text-warning" style="background-color:rgba(0, 0, 0, 0.5); border-radius: 10px;">
                    <h3 style="font-family: 'Helvetica Neue', sans-serif; font-size: 30px; font-weight: bold; letter-spacing: -1px; line-height: 1; text-align: center;" class=" text-warning">RAPAT KELULUSAN</h3>
                    <h4 class=" text-warning" style="font-weight:bold;line-height:60px;"><?= $slide->display('pelatihan') ?></h4>
                    <h4 class=" text-danger">(<?= Helpers::range_tanggal($slide->display('pelatihan.mulai'), $slide->display('pelatihan.selesai')) ?>)</h4>
                </div>
                <br />
                <h4 style="text-shadow: 3px 3px #000;font-size:30px;" class="text-white"><?= Helpers::tanggal(date("Y-m-d", strtotime($slide->display('pelatihan.tanggal_rapat_kelulusan')))) ?></h4>
                <br />
                <div class="text-primary">
                    <h4 style="text-shadow: 3px 3px #000;font-size:30px;" class="text-primary">PUSAT PENDIDIKAN DAN PELATIHAN</h4>
                    <h4 style="text-shadow: 3px 3px #000;font-size:30px;" class="text-primary">KEKAYAAN NEGARA DAN PERIMBANGAN KEUANGAN</h4>
                </div>
            </section>
            <?php
                $i = 0;
                foreach ($slide->field('slides') as $s) {
                    ?>
                        <section data-transition="<?= $effects[$i] ?>" data-background-iframe="<?= $s ?>&content_only=true" data-background-interactive>
                        </section>
                    <?php
                    $i = $i == 4 ? 0 : $i+1;
                }
            ?>
            <section data-transition="concave" data-background="https://evaluasi.knpk.xyz/wp-content/uploads/2023/01/thank-you-gf4a113cc8_1920.jpg" data-background-size="contain">
            </section>
        </div>
    </div>

    <script src="<?= get_template_directory_uri() ?>/libs/reveal/reveal.js"></script>
    <script>
        Reveal.initialize({
            width: '100%',
            center: true,
            history: true,
            transitionSpeed: 'slow',
            embedded: false
        });
    </script>

    <script>
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>

</html>