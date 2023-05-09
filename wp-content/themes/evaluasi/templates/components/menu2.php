<?php

function get_menu()
{
    $base = get_the_permalink();


    $menu = array(
        'Home' => array(
            'url' => get_the_permalink(),
            'target' => ''
        ),
        'Data Pelatihan' => array(
            'Data Peserta' => array(
                'url' => get_the_permalink() . '?section=peserta',
                'target' => ''
            ),
            'Mata Pelajaran' => array(
                'url' => get_the_permalink() . '?section=mata_pelajaran',
                'target' => ''
            )
        ),
        'Evaluasi' => array(
            'Level 1' => array(
                'Form Evaluasi Penyelenggaraan' => array(
                    'url' => get_site_url() . '/form/evaluasi-penyelenggaraan/?id_pelatihan=' . get_the_id(),
                    'target' => '_blank'
                ),
                'Raw Data dan Pengolahan' => array(
                    'url' => get_the_permalink() . '?section=pengolahan_evaluasi_level_1',
                    'target' => ''
                ),
                'Rekap Evaluasi Penyelenggaraan' => array(
                    'url' => get_the_permalink() . '?section=rekap_evaluasi_penyelenggaraan&content_only=true',
                    'target' => ''
                ),
                'Rekap Evaluasi Pengajar' => array(
                    'url' => get_the_permalink() . '?section=rekap_evaluasi_pengajar&content_only=true',
                    'target' => ''
                ),
                'Rekap Evaluasi Lainnya' => array(
                    'url' => get_the_permalink() . '?section=rekap_evaluasi_lainnya&content_only=true',
                    'target' => ''
                ),
                'Saran Terkait Penyelenggaraan' => array(
                    'url' => get_the_permalink() . '?section=saran_masukan&content_only=true',
                    'target' => ''
                ),
                'Saran Terkait Pengajar' => array(
                    'url' => get_the_permalink() . '?section=saran_masukan_terkait_pengajar&content_only=true',
                    'target' => ''
                ),
                'Matriks Rekomendasi' => array(
                    'url' => get_the_permalink() . '?section=matriks_rekomendasi&content_only=true',
                    'target' => ''
                ),
            ),
            'Level 2' => array(
                'Daftar Nilai' => array(
                    'url' => get_the_permalink() . '?section=daftar_nilai&content_only=true',
                    'target' => ''
                ),
                'NPR' => array(
                    'url' => get_the_permalink() . '?section=npr&content_only=true',
                    'target' => ''
                ),
                'Rekap Nilai' => array(
                    'url' => get_the_permalink() . '?section=rekap_nilai&content_only=true',
                    'target' => ''
                ),
            )
        ),
        'Kelulusan' => array(
            'Bahan Rapat Kelulusan' => array(
                'url' => get_the_permalink() . '?section=bahan_rapat_kelulusan',
                'target' => ''
            ),
            'Hasil Pelatihan' => array(
                'url' => '#',
                'target' => ''
            ),
        ),
        'Monitoring' => array(
            'url' => get_the_permalink() . '?section=monitoring',
            'target' => ''
        ),
        'Konsep ND' => array(
            'Undangan Rapat Kelulusan' => array(
                'url' => get_site_url() . '/konsep-nd/undangan-rapat-kelulusan?id_pelatihan='.get_the_id(),
                'target' => ''
            ),
            'Lampiran Undangan Rapat Kelulusan' => array(
                'url' => get_site_url() . '/konsep-nd/lampiran-undangan-rapat-kelulusan?id_pelatihan='.get_the_id(),
                'target' => ''
            ),
        ),

    );


?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <style type="text/css">
        .megasubmenu {
            padding: 1rem;
        }

        .list-item {
            border-bottom: solid 1px #ddd;
        }

        /* ============ desktop view ============ */
        @media all and (min-width: 992px) {
            .megasubmenu {
                left: 100%;
                top: 0;
                min-height: 100%;
                min-width: 300px;
            }

            .dropdown-menu>li:hover .megasubmenu {
                display: block;
            }
        }

        /* ============ desktop view .end// ============ */
    </style>

    <script type="text/javascript">
        window.addEventListener("resize", function() {
            "use strict";
            //window.location.reload();
        });


        document.addEventListener("DOMContentLoaded", function() {

            /////// Prevent closing from click inside dropdown
            document.querySelectorAll('.dropdown-menu').forEach(function(element) {
                element.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });

            // make it as accordion for smaller screens
            if (window.innerWidth < 992) {

                // close all inner dropdowns when parent is closed
                document.querySelectorAll('.navbar .dropdown').forEach(function(everydropdown) {
                    everydropdown.addEventListener('hidden.bs.dropdown', function() {
                        // after dropdown is hidden, then find all submenus
                        this.querySelectorAll('.megasubmenu').forEach(function(everysubmenu) {
                            // hide every submenu as well
                            everysubmenu.style.display = 'none';
                        });
                    })
                });

                document.querySelectorAll('.has-megasubmenu a').forEach(function(element) {
                    element.addEventListener('click', function(e) {

                        let nextEl = this.nextElementSibling;
                        if (nextEl && nextEl.classList.contains('megasubmenu')) {
                            // prevent opening link if link needs to open dropdown
                            e.preventDefault();

                            if (nextEl.style.display == 'block') {
                                nextEl.style.display = 'none';
                            } else {
                                nextEl.style.display = 'block';
                            }

                        }
                    });
                })
            }
            // end if innerWidth
        });
        // DOMContentLoaded  end
    </script>


    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="main_nav">
                <ul class="navbar-nav">
                    <li class="nav-item mr-4"> <a class="nav-link" href="<?= $base ?>">Home </a> </li>
                    <li class="nav-item dropdown mr-4">
                        <a class="nav-link  dropdown-toggle" href="#" data-bs-toggle="dropdown">Data Pelatihan</a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= $base ?>?section=peserta">Peserta</a></li>
                            <li><a class="dropdown-item" href="<?= $base ?>?section=mata_pelajaran">Mata Pelajaran</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown mr-4">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Evaluasi</a>
                        <ul class="dropdown-menu">
                            <li class="has-megasubmenu">
                                <a class="dropdown-item" href="#">Evaluasi Level 1 &raquo; </a>
                                <div class="megasubmenu dropdown-menu">
                                    <div class="row">
                                        <div class="col-12">
                                            <ol>
                                                <li class="list-item"><a href="<?= get_site_url() ?>/form/evaluasi-penyelenggaraan/?id_pelatihan=<?= get_the_id() ?>" target="_blank">Form Evaluasi Penyelenggaraan</a></li>
                                                <li class="list-item"><a href="<?= $base ?>?section=pengolahan_evaluasi_level_1">Raw Data dan Pengolahan</a></li>
                                                <li class="list-item"><a href="<?= $base ?>?section=rekap_evaluasi_level_1&content_only=true">Rekap Evaluasi Level 1</a></li>
                                                <li class="list-item"><a href="<?= $base ?>?section=rekap_evaluasi_penyelenggaraan&content_only=true">Rekap Evaluasi Penyelenggaraan</a></li>
                                                <li class="list-item"><a href="<?= $base ?>?section=rekap_evaluasi_pengajar&content_only=true">Rekap Evaluasi Pengajar</a></li>
                                                <li class="list-item"><a href="<?= $base ?>?section=rekap_evaluasi_lainnya&content_only=true">Rekap Evaluasi Lainnya</a></li>
                                                <li class="list-item"><a href="<?= $base ?>?section=rekap_evaluasi_zi_wbbm">Rekap Evaluasi ZI WBBM</a></li>
                                                <li class="list-item"><a href="<?= $base ?>?section=rekap_evaluasi_pengajar_dan_lainnya&content_only=true">Rekap Ev. Pengajar dan Ev. Lainnya</a></li>
                                                <li class="list-item"><a href="<?= $base ?>?section=saran_masukan&content_only=true">Saran Terkait Penyelenggaran</a></li>
                                                <li class="list-item"><a href="<?= $base ?>?section=saran_masukan_terkait_pengajar&content_only=true">Saran Terkait Pengajar</a></li>
                                                <li class="list-item"><a href="<?= $base ?>?section=matriks_rekomendasi&content_only=true">Matriks Rekomendasi</a></li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            <li class="has-megasubmenu">
                                <a class="dropdown-item" href="#">Evaluasi Level 2 &raquo; </a>
                                <div class="megasubmenu dropdown-menu">
                                    <div class="row">
                                        <div class="col-6">
                                            <h6 class="title">Nilai</h6>
                                            <ul class="list-unstyled">
                                                <li><a href="<?= $base ?>?section=daftar_nilai">Daftar Nilai</a></li>
                                                <li><a href="<?= $base ?>?section=npr">NPR</a></li>
                                                <li><a href="<?= $base ?>?section=rekap_nilai">Rekap Nilai</a></li>
                                            </ul>
                                        </div><!-- end col-3 -->
                                        <div class="col-6">
                                            <h6 class="title">Rapat Kelulusan</h6>
                                            <ul class="list-unstyled">
                                                <li><a href="#">Input Data Rapat Kelulusan</a></li>
                                                <li><a href="#">Generate Undangan</a></li>
                                                <li><a href="#">Generate BA</a></li>
                                                <li><a href="#">Generate Pengumuman</a></li>
                                            </ul>
                                        </div><!-- end col-3 -->
                                    </div><!-- end row -->
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown mr-4 dropdown-menu-end">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Kelulusan</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= $base ?>?section=bahan_rapat_kelulusan">Bahan Rapat Kelulusan</a></li>
                            <li class="has-megasubmenu">
                                <a class="dropdown-item" href="#">Hasil Pelatihan &raquo; </a>
                                <div class="megasubmenu dropdown-menu">
                                    <div class="row">
                                        <div class="col-12">
                                            <ul class="list-unstyled">
                                                <li><a href="<?= $base ?>?section=peserta_terbaik">Peserta Terbaik</a></li>
                                                <li><a href="<?= $base ?>?section=peserta_tidak_lulus">Peserta Tidak Lulus</a></li>
                                                <li><a href="<?= get_site_url() ?>/dokumen/pengumuman-hasil-pelatihan?id_pelatihan=<?= get_the_ID() ?>">Konsep Pengumuman</a></li>
                                            </ul>
                                        </div><!-- end col-3 -->
                                    </div><!-- end row -->
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item mr-4"> <a class="nav-link" href="<?= $base ?>?section=monitoring">Monitoring </a> </li>
                    <li class="nav-item dropdown mr-4 dropdown-menu-end">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Konsep ND</a>
                        <ol class="dropdown-menu">
                            <li class="list-item"><a class="dropdown-item" target="_blank" href="<?= get_site_url() ?>/konsep-nd/undangan-rapat-kelulusan?id_pelatihan=<?= get_the_ID() ?>">Undangan Rapat Kelulusan</a></li>
                            <li class="list-item"><a class="dropdown-item" target="_blank" href="<?= get_site_url() ?>/konsep-nd/penyampaian-evagara?id_pelatihan=<?= get_the_ID() ?>">Penyampaian Evaluasi Penyelenggaraan</a></li>
                            <li class="list-item"><a class="dropdown-item" target="_blank" href="<?= get_site_url() ?>/konsep-nd/penyampaian-evajar?id_pelatihan=<?= get_the_ID() ?>">Penyampaian Evaluasi Pengajar</a></li>
                            <li class="list-item"><a class="dropdown-item" target="_blank" href="<?= get_site_url() ?>/konsep-nd/pengumuman?id_pelatihan=<?= get_the_ID() ?>">Pengumuman Hasil Pelatihan</a></li>
                            <li class="list-item"><a class="dropdown-item" target="_blank" href="<?= get_site_url() ?>/konsep-nd/matriks-rekomendasi?id_pelatihan=<?= get_the_ID() ?>">Matriks Rekomendasi</a></li>
                        </ol>
                    </li>
                </ul>
            </div> <!-- navbar-collapse.// -->
        </div> <!-- container-fluid.// -->
    </nav>
<?php
}
?>