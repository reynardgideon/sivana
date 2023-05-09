<?php
/*
Template Name: Slide
Template Post Type: page, slide
*/

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <title>reveal.js - Slide Transitions</title>
    <link rel="stylesheet" href="<?= get_template_directory_uri() ?>/libs/reveal/reveal.css">
    <link rel="stylesheet" href="<?= get_template_directory_uri() ?>/libs/reveal/theme/simple.css" id="theme">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>    
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
        <section data-transition="zoom" data-background="https://evaluasi.knpk.xyz/wp-content/uploads/2023/01/gettyimages-1202900062-612x612-1.jpg" data-background-size="contain">
       
</section>
            <section data-background-iframe="https://evaluasi.knpk.xyz/pelatihan/625/?section=npr&id_mata_pelajaran=665&content_only=true"
          data-background-interactive>
          
                       </section> 
      
            
        </div>

    </div>

    <audio id="audio" loop hidden src="https://zi.knpk.xyz/wp-content/uploads/2022/11/Background-Music-for-Presentation_256k.mp3" type="audio/mpeg">

    </audio>

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