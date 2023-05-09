<?php

function page_rekap_kelulusan()
{
    $pod = pods('pelatihan', get_the_id());
    $hasil = json_decode($pod->field('hasil_pelatihan'));

    $terbaik = (array)$hasil->peserta_terbaik;

    $predikat = (array)$hasil->predikat;

    $colors = array(
        'Amat Baik' => 'rgb(75, 192, 192)',
        'Baik' => 'rgb(54, 162, 235)',
        'Cukup' => 'rgb(255, 205, 86)',
        'Tidak Lulus' => 'rgb(255, 99, 132)'
    );

    $chart = array_filter($predikat, function ($a) {
        return $a !== 0;
    });

    $label = [];
    $data = [];
    $cs = [];
    foreach ($chart as $l => $d) {
        $label[] = $l;
        $data[] = $d;
        $cs[] = $colors[$l];
    }

    $args = array(
        'limit' => -1,
        'where' => "nip.meta_value='198912102015031003'"
    );

    $user = pods('user', $args);

?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
    <div class="card">
        <div class="card-body text-center">
            <div class="container mx-auto">
                <div class="row h-100">
                    <div class="col-8">
                        <h4 class="mb-3">PESERTA TERBAIK</h4><br />
                        <ul class="list-unstyled">
                            <?php foreach ($terbaik as $r => $list) : ?>
                                <?php foreach ($list as $p) : ?>
                                    <?php
                                    $args = array(
                                        'limit' => -1,
                                        'where' => "nip.meta_value='" . $p->nip . "'"
                                    );

                                    $peserta = pods('user', $args);

                                    if (!empty($peserta->display('foto'))) {
                                        $image = $peserta->field('foto');
                                        $attach = wp_get_attachment_image_src($image['ID'], 'thumbnail');
                                        $image = $attach[0];
                                    } else {
                                        $image = 'https://evaluasi.knpk.xyz/wp-content/uploads/2023/01/dummy.jpg';
                                    }

                                    $badge = array(
                                        '',
                                        '#FFD700',
                                        '#AAA9AD',
                                        '#CD7F32'
                                    );
                                    ?>
                                    <li class="media mb-4">
                                        <img class="mr-3 rounded-circle" width="100px" height="100px" src="<?= $image ?>" alt="Generic placeholder image">
                                        <table style="width:100%;margin:auto;">
                                            <tr class="text-left">
                                                <td width="70%">
                                                    <h5 class="mb-2"><?= $peserta->display('nama_lengkap') ?></h5>
                                                </td>
                                                <td rowspan="3">
                                                    <h2><?= number_format((float)$p->na, 2, '.', '') ?></h2>
                                                </td>
                                                <td rowspan="3">
                                                    <h2><span class="icon is-large"><i id="icon" class="mdi mdi-medal mdi-48px" style="color:<?= $badge[$r] ?>;"></i></span></h2>
                                                </td>
                                            </tr>
                                            <tr class="text-left">
                                                <td>
                                                    <h6><?= $p->nip ?></h6>
                                                </td>
                                            </tr>
                                            <tr class="text-left">
                                                <td>
                                                    <h6><?= $peserta->display('unit_kerja') ?></h6>
                                                </td>
                                            </tr>
                                        </table>
                                    </li>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="col-4">
                        <h4>REKAP KELULUSAN</h4><br />
                        <canvas id="rekap_kelulusan" class="mt-3"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        const ctx = document.getElementById('rekap_kelulusan');

        const data = {
            labels: <?= json_encode($label) ?>,
            datasets: [{
                label: 'Predikat Kelulusan',
                data: <?= json_encode($data) ?>,
                backgroundColor: <?= json_encode($cs) ?>,
                hoverOffset: 4
            }]
        };

        new Chart(ctx, {
            type: 'pie',
            data: data,
            options: {
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            color: 'rgb(255, 99, 132)'
                        }
                    }
                }
            }
        });
    </script>
<?php
}
?>