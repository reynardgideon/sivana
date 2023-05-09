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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <style>

    </style>
    <?php wp_head(); ?>
</head>

<body>
    <div id="main">
        <?php
        //$test_cookies = json_decode( stripslashes($_COOKIE['nip']));
        //view_array($test_cookies);
        ?>
        <?php if (!isset($_COOKIE['name'])) : ?>
            <?= get_halaman_login() ?>
        <?php else : ?>
            <?= get_halaman_ujian() ?>
        <?php endif; ?>
    </div>
</body>

</html>


<?php

function get_halaman_login()
{
?>
    <div class="hero is-fullheight">
        <div class="hero-body is-justify-content-center is-align-items-center">
            <div class="columns is-flex is-flex-direction-column box">
                <form id="masuk">
                    <div class="column">
                        <label for="nip">NIP</label>
                        <input class="input is-link" name="nip" type="text" placeholder="MasukKan NIP anda" required>
                    </div>
                    <div class="column">
                        <label for="token">Token Ujian</label>
                        <input class="input is-link" name="token" type="text" placeholder="Token Ujian" required>
                    </div>
                    <div class="column">
                        <button class="button is-link is-fullwidth" type="submit">Masuk</button>
                    </div>
                    <div class="has-text-centered">
                        <p class="is-size-7">Belum memiliki token ujian? Hubungi panitia ujian!
                        </p>
                    </div>
                    <input type="hidden" name="action" value="masuk_ujian">
                    <input type="hidden" name="id" value="<?= get_the_ID() ?>">
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function($) {
            var ajaxurl = '<?= get_site_url() ?>/wp-admin/admin-ajax.php';
            $('#masuk').submit(function(e) {
                event.preventDefault();
                if (window.innerHeight !== screen.height) {
                    alert('Maaf, aktifkan mode fullscreen pada browser anda sebelum masuk!')
                } else {
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            if (response.status == 1) {
                                document.cookie = "name = " + response.nip;
                                location.reload();
                            } else {
                                tata.error(response.message, '', {
                                    position: 'tm',
                                    duration: 3000
                                });
                            }
                        }
                    });
                }
            })
        });
    </script>
<?php }

function get_halaman_ujian()
{
    $time = 3600;
?>
    <nav class="navbar bg-dark fixed-top" data-bs-theme="dark">
        <div class="container-fluid">
            <button type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling"><span class="navbar-toggler-icon"></span></button>
            <span id="time" class="navbar-text text-white my-0 py-0" style="font-size:20pt;">
                120 : 00
            </span>
            <span class="navbar-text">
                Yusuf Indra
            </span>
        </div>
    </nav>
    <div class="offcanvas offcanvas-start" style="width:280px;" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title text-center" id="offcanvasScrollingLabel">Navigation</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="container">
                <?php for ($i = 1; $i < 50; $i++) : ?>
                    <button type="button" class="btn btn-outline-secondary my-1" style="width:40px;"><?= $i ?></button>
                <?php endfor; ?>

            </div>
        </div>
    </div>
    <div class="card m-4 p-4" style="font-size:16pt; line-height:30pt;">
        <div class="card-header mx-3"> Ibu Yulis membeli semangka sebanyak 3 buah dan melon 4 buah. Masing-masing semangka mempunyai bobot 2 kg dan masing-masing melon mempunyai bobot 15 Hg. Berapa total dari bobot kedua buah tersebut …</div>
        <div class="card-body">
            <!-- Include stylesheet -->
            <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

            <!-- Create the editor container -->
            <div id="editor" style="height:200px;font-size:14pt;"></div>

            <!-- Include the Quill library -->
            <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

            <!-- Initialize Quill editor -->
            <script>
                var toolbarOptions = [
                    ['bold', 'italic', 'underline', 'strike'], // toggled buttons
                    ['blockquote', 'code-block'],
                    [{
                        'header': 1
                    }, {
                        'header': 2
                    }], // custom button values
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'script': 'sub'
                    }, {
                        'script': 'super'
                    }], // superscript/subscript
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }], // outdent/indent                  


                ];

                var quill = new Quill('#editor', {
                    modules: {
                        toolbar: toolbarOptions
                    },
                    theme: 'snow'
                });
            </script>
            <div class="container-fluid">
                <span><button type="button" class="btn btn-primary">Tandai</button></span>
                <span><button type="button" class="btn btn-primary">Next</button></span>
                <span><button type="button" class="btn btn-primary">Previous</button></span>
            </div>
        </div>

        <!--
        <div class="card-header">Sebagai Pejabat Lelang apa yang anda lakukan jika Pejabat Penjual tidak bisa menandatangani Kaki Risalah Lelang karena pingsan akibat serangan jantung sesaat setelah penetapan pemenang</div>
        <div class="card-body">
        <button class="btn btn-light w-100" type="submit"><div class="container"><div class="row"><div class="col" style="width:40px;">A.</div><div class="col" style="text-align:justify;width:auto;" class="text-justify">Penjual tidak bisa menandatangani Kaki Risalah Lelang karena pingsan akibat serangan jantung sesaat setelah penetapan pemenang</div></div></div></button>
        <button class="btn btn-light w-100" type="submit"><div class="container"><div class="row"><div style="width:40px;">B.</div><div style="text-align:justify;width:auto;" class="text-justify">Penjual tidak bisa menandatangani Kaki Risalah Lelang karena pingsan akibat serangan jantung sesaat setelah penetapan pemenang Penjual tidak bisa menandatangani Kaki Risalah Lelang karena pingsan akibat serangan jantung sesaat setelah penetapan pemenang</div></div></div></button>
        
        <button class="btn btn-light w-100" type="submit"><div class="container"><div class="row"><div style="width:40px;">C.</div><div style="text-align:justify;width:auto;" class="text-justify">Penjual tidak bisa menandatangani</div></div></div></button>
        </div>
                -->
    </div>
    <script>
        $(document).ready(function($) {
            var ajaxurl = '<?= get_site_url() ?>/wp-admin/admin-ajax.php';
            $(window).resize(function() {
                if (window.innerHeight !== screen.height) {
                    /*
                    alert('no');
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'delete_session'
                        },
                        success: function(response) {
                            alert(response.message)
                            location.reload();
                        }
                    });
                    */
                    //document.cookie = "name= ; expires = Thu, 01 Jan 1970 00:00:00 GMT"
                    location.reload();
                }
            });
            display = document.querySelector('#time');
            startTimer(7200, display);
        });

        function startTimer(duration, display) {
            var timer = duration,
                minutes, seconds;
            setInterval(function() {
                minutes = parseInt(timer / 60, 10)
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + " : " + seconds;

                if (--timer < 0) {
                    timer = duration;
                }
            }, 1000);
        }
    </script>
<?php

}
?>