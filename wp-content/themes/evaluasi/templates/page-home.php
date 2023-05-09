<?php

/**
 * Template Name: Homepage
 *
 * @package WordPress
 */

?>

<!DOCTYPE html>
<html lang="en" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    * {
      box-sizing: border-box
    }

    body {
      font-family: Verdana, sans-serif;
      margin: 0
    }

    .mySlides {
      display: none
    }

    img {
      vertical-align: middle;
    }

    /* Slideshow container */
    .slideshow-container {
      
      position: relative;
      margin: auto;
    }

    /* Next & previous buttons */
    .prev,
    .next {
      cursor: pointer;
      position: absolute;
      top: 50%;
      width: auto;
      padding: 16px;
      margin-top: -22px;
      color: white;
      font-weight: bold;
      font-size: 18px;
      transition: 0.6s ease;
      border-radius: 0 3px 3px 0;
      user-select: none;
    }

    /* Position the "next button" to the right */
    .next {
      right: 0;
      border-radius: 3px 0 0 3px;
    }

    /* On hover, add a black background color with a little bit see-through */
    .prev:hover,
    .next:hover {
      background-color: rgba(0, 0, 0, 0.8);
    }

    /* Caption text */
    .text {
      color: #f2f2f2;
      font-size: 15px;
      padding: 8px 12px;
      position: absolute;
      bottom: 8px;
      width: 100%;
      text-align: center;
    }

    /* Number text (1/3 etc) */
    .numbertext {
      color: #f2f2f2;
      font-size: 12px;
      padding: 8px 12px;
      position: absolute;
      top: 0;
    }

    /* The dots/bullets/indicators */
    .dot {
      cursor: pointer;
      height: 15px;
      width: 15px;
      margin: 0 2px;
      background-color: #bbb;
      border-radius: 50%;
      display: inline-block;
      transition: background-color 0.6s ease;
    }

    .active,
    .dot:hover {
      background-color: #717171;
    }

    /* Fading animation */
    .fade {
      -webkit-animation-name: fade;
      -webkit-animation-duration: 1.5s;
      animation-name: fade;
      animation-duration: 1.5s;
    }

    @-webkit-keyframes fade {
      from {
        opacity: .4
      }

      to {
        opacity: 1
      }
    }

    @keyframes fade {
      from {
        opacity: .4
      }

      to {
        opacity: 1
      }
    }

    /* On smaller screens, decrease text size */
    @media only screen and (max-width: 300px) {

      .prev,
      .next,
      .text {
        font-size: 11px
      }
    }
  </style>
</head>
<?php get_header(); ?>

<body>
  <div id="app">
    <?php get_template_part('nav'); ?>
    <?php get_sidebar(); ?>
    <div class="container mx-5 my-4">
    <div class="slideshow-container">

      <?php

      $params = array(
        'limit' => -1,
        'where' => 'featured.meta_value = 1',
        'orderby' => 'post_date DESC'
      );
      $posts = pods('halaman', $params);

      if (0 < $posts->total()) {
        while ($posts->fetch()) {
          echo '<div class="mySlides fade">
                <a href="' . get_permalink($posts->display('ID')) . '"><img src="' . $posts->display('image') . '" style="width:100%; border-radius: 10px;"></a>
              </div>';
        }
        echo '<div style="text-align:center">';
        for ($i = 1; $i <= $posts->total(); $i++) {
          echo '<span class="dot" onclick="currentSlide(' . $i . ')"></span>';
        }
        echo '</div>';
      }

      ?>
      <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
      <a class="next" onclick="plusSlides(1)">&#10095;</a>
    </div>    
      <div class="card mt-4">
        <header class="card-header has-background-kmk-mix has-text-centered">
          <p class="card-header-title has-text-grey-dark">
            Kajian Akademis Tahun 2022
          </p>
        </header>
        <div class="card-content">
          <div class="columns">
            <div class="column">
              <div class="card">
                <div class="card-content">
                  <div class="level is-mobile">
                    <div class="level-item">
                      <div class="is-widget-label has-text-centered">
                        <h3 class="subtitle is-spaced">Total</h3>
                        <h1 class="title">6</h1>
                      </div>
                    </div>
                    <div class="level-item has-widget-icon">
                      <div class="is-widget-icon">
                        <span style="color:#f7b901;" class="icon is-large">
                          <i class="mdi mdi-book-variant-multiple mdi-48px"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="column">
              <div class="card">
                <div class="card-content">
                  <div class="level is-mobile">
                    <div class="level-item">
                      <div class="is-widget-label has-text-centered">
                        <h3 class="subtitle is-spaced">Published</h3>
                        <h1 class="title">10</h1>
                      </div>
                    </div>
                    <div class="level-item has-widget-icon">
                      <div class="is-widget-icon">
                        <span style="color:#3ca0e0;" class="icon is-large">
                          <i class="mdi mdi-bookshelf mdi-48px"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="column">
              <div class="card">
                <div class="card-content">
                  <div class="level is-mobile">
                    <div class="level-item">
                      <div class="is-widget-label has-text-centered">
                        <h3 class="subtitle is-spaced">Pengkaji</h3>
                        <h1 class="title">31</h1>
                      </div>
                    </div>
                    <div class="level-item has-widget-icon">
                      <div class="is-widget-icon">
                        <span style="color:#2dc42a;" class="icon is-large">
                          <i class="mdi mdi-account-multiple mdi-48px"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>



      <script>
        var slideIndex = 1;
        showSlides(slideIndex);

        var timeHandler;

        runTimer();

        function runTimer() {
          timeHandler = window.setTimeout(() => {
            plusSlides(1);
          }, 4000);
        }

        function plusSlides(n) {
          showSlides(slideIndex += n);
        }

        function currentSlide(n) {
          showSlides(slideIndex = n);
        }

        function showSlides(n) {
          var i;
          var slides = document.getElementsByClassName("mySlides");
          var dots = document.getElementsByClassName("dot");
          if (n > slides.length) {
            slideIndex = 1
          }
          if (n < 1) {
            slideIndex = slides.length
          }
          for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
          }
          for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
          }
          slides[slideIndex - 1].style.display = "block";
          dots[slideIndex - 1].className += " active";

          clearTimeout(timeHandler);
          runTimer();
        }
      </script>
      <?php get_footer(); ?>
    </div>
    <!-- Icons below are for demo only. Feel free to use any icon pack. Docs: https://bulma.io/documentation/elements/icon/ -->
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/main.min.js"></script>
</body>

</html>