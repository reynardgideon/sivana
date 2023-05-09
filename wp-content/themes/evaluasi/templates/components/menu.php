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
                'Rekap Evaluasi Level 1' => array(
                    'url' => get_the_permalink() . '?section=rekap_evaluasi_level_1&content_only=true',
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
                'Rekap Evaluasi ZI WBBM' => array(
                    'url' => get_the_permalink() . '?section=rekap_evaluasi_zi_wbbm&content_only=true',
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
        'Ujian' => array(
            'Form Pakta Integritas' => array(
                'url' => get_site_url() . '/form/pakta-integritas/?id_pelatihan=' . get_the_id(),
                'target' => ''
            ),
            'Rekap Pakta Integritas' => array(
                'url' => get_the_permalink() . '?section=pakta_integritas',
                'target' => ''
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
            'Daftar Peserta Mengulang' => array(
                'url' => get_the_permalink() . '?section=daftar_peserta_mengulang',
                'target' => ''
            ),
        ),
        'Monitoring' => array(
            'url' => get_the_permalink() . '?section=monitoring',
            'target' => ''
        ),
        'Konsep ND' => array(
            'Undangan Rapat Kelulusan' => array(
                'Undangan' => array(
                    'url' => get_site_url() . '/konsep-nd/undangan-rapat-kelulusan?id_pelatihan=' . get_the_id(),
                    'target' => ''
                ),
                'Lampiran' => array(
                    'url' => get_site_url() . '/konsep-nd/lampiran-undangan-rapat-kelulusan?id_pelatihan=' . get_the_id(),
                    'target' => ''
                ),
            ),
            'Pengumuman Hasil Pelatihan' => array(
                'Pengumuman' => array(
                    'url' => get_site_url() . '/konsep-nd/pengumuman?id_pelatihan=' . get_the_id(),
                    'target' => ''
                ),
                'Lampiran 1' => array(
                    'url' => get_site_url() . '/konsep-nd/pengumuman-lampiran-1?id_pelatihan=' . get_the_id(),
                    'target' => ''
                ),
                'Lampiran 2' => array(
                    'url' => get_site_url() . '/konsep-nd/pengumuman-lampiran-2?id_pelatihan=' . get_the_id(),
                    'target' => ''
                ),
                'Lampiran 3' => array(
                    'url' => get_site_url() . '/konsep-nd/pengumuman-lampiran-3?id_pelatihan=' . get_the_id(),
                    'target' => ''
                )
            ),
            'Matriks Rekomendasi' => array(
                'url' => get_site_url() . '/konsep-nd/matriks-rekomendasi?id_pelatihan=' . get_the_id(),
                'target' => ''
            )
        )

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
                    <?php foreach ($menu as $tab => $menu1) : ?>
                        <?php if (isset($menu1['url'])) : ?>
                            <li class="nav-item mr-4"> <a class="nav-link" href="<?= $menu1['url'] ?>"><?= $tab ?> </a> </li>
                        <?php else : ?>
                            <li class="nav-item dropdown mr-4">
                                <a class="nav-link  dropdown-toggle" href="#" data-bs-toggle="dropdown"><?= $tab ?></a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <?php $i = 1; ?>
                                    <?php foreach ($menu1 as $row => $menu2) : ?>
                                        <?php if (isset($menu2['url'])) : ?>
                                            <li class="list-item"><a class="dropdown-item" href="<?= $menu2['url'] ?>"><?= $i . '. ' . $row ?></a></li>
                                        <?php else : ?>
                                            <li class="list-item has-megasubmenu">
                                                <a class="dropdown-item" href="#"><?= $i . '. ' . $row ?> &raquo; </a>
                                                <div class="megasubmenu dropdown-menu">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <ul>
                                                                <?php $j = 1; ?>
                                                                <?php foreach ($menu2 as $row2 => $menu3) : ?>
                                                                    <li class="list-item"><a class="dropdown-item" href="<?= $menu3['url'] ?>"><?= $j . '. ' . $row2 ?></a></li>
                                                                    <?php $j++; ?>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endif; ?>
                                        <?php $i++; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div> <!-- navbar-collapse.// -->
        </div> <!-- container-fluid.// -->
    </nav>
<?php
}
?>