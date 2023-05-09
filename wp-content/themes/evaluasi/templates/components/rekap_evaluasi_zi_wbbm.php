<?php

function page_rekap_evaluasi_zi_wbbm()
{
    $zi = array(
        'Bagaimana pendapat Saudara tentang kesesuaian persyaratan pelayanan dengan jenis pelayanannya yang disediakan oleh Pusdiklat Kekayaan Negara dan Perimbangan Keuangan?',
        'Bagaimana pemahaman Saudara tentang tentang kemudahan prosedur pelayanan di Pusdiklat Kekayaan Negara dan Perimbangan Keuangan?',
        'Bagaimana pendapat Saudara tentang kecepatan waktu Pusdiklat Kekayaan Negara dan Perimbangan Keuangan dalam memberikan pelayanan?',
        'Bagaimana pendapat Saudara tentang kesesuaian layanan antara yang tercantum dalam standar pelayanan dengan hasil yang diberikan?',
        'Bagaimana pendapat Saudara tentang kompetensi/kemampuan petugas dalam memberi pelayanan?',
        'Bagaimana pendapat Saudara, perilaku petugas dalam memberikan pelayanan terkait kesopanan dan keramahan?',
        'Bagaimana pendapat Saudara tentang kualitas sarana dan prasarana?',
        'Bagaimana pendapat Saudara tentang penanganan pengaduan pengguna layanan di Pusdiklat Kekayaan Negara dan Perimbangan Keuangan?',
        'Apakah anda dipungut biaya dalam menerima layanan Pusdiklat Kekayaan Negara dan Perimbangan Keuangan?',
        'Saran dan masukan terkait kepuasan Layanan pelatihan di Pusdiklat KNPK'
    );

    $pod = pods('pelatihan', get_the_id());

    $ev_1 = (array)json_decode($pod->field('rekap_evaluasi_level_1'));

?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/af-2.4.0/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/fc-4.1.0/fh-3.2.4/sc-2.0.7/sr-1.1.1/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/af-2.4.0/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/fc-4.1.0/fh-3.2.4/sc-2.0.7/sr-1.1.1/datatables.min.js"></script>
    <style>
        .text-wrap {
            white-space: normal;
        }
    </style>

    <div class="card">
        <div class="card-header has-background-kmk-mix has-text-centered">
            <h5>REKAPITULASI EVALUASI ZI WBBM</h5>
        </div>
        <div class="card-content p-4">
            <div class="container">
                <table id="evazi" class="display" style="width:100%; font-size:12pt;">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Aspek Penilaian</th>
                            <th>Rata-Rata</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $i = 1;
                        ?>
                        <?php foreach ((array) $ev_1['zi'] as $q => $v) : ?>
                            <?php if ($q !== 'zi10') : ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $zi[substr($q, -1) - 1] ?></td>
                                    <td><?= $v ?></td>
                                </tr>
                                <?php $i++; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header has-background-kmk-mix has-text-centered">
            <h5>SARAN DAN MASUKAN</h5>
        </div>
        <div class="card-content p-4">
            <div class="container">
                <table id="saran" class="display" style="width:100%; font-size:12pt;">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Responden</th>
                            <th>Saran dan Masukan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $i = 1;
                        ?>
                        <?php foreach ((array) $ev_1['zi']->zi10 as $id => $v) : ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><a href="<?= get_author_posts_url($id) ?>"><?= get_user_meta($id, 'nama_lengkap', true) ?></td>
                                <td><?= $v ?></td>
                            </tr>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#evazi').DataTable({
                pageLength: 20,
                order: [],
                columnDefs: [{
                    render: function(data, type, full, meta) {
                        return "<div class='text-wrap width-c'>" + data + "</div>";
                    },
                    targets: 1
                }],
                dom: 'Bfrtip',
                buttons: ['copy',
                    'excel',
                ],
                scrollX: true,
            });

            $('#saran').DataTable({
                pageLength: 20,
                order: [],
                columnDefs: [{
                    render: function(data, type, full, meta) {
                        return "<div class='text-wrap width-c'>" + data + "</div>";
                    },
                    targets: 2
                }],
                dom: 'Bfrtip',
                buttons: ['copy',
                    'excel',
                ],
                scrollX: true,
            });
        });
    </script>

<?php }
