<?php
include_once(get_template_directory() . '/getters/helpers.php');

function page_edit_rekap_evaluasi_penyelenggaraan($qs)
{
    $data = [];

    $pod = pods('pelatihan', get_the_ID());

    $rekap = $pod->field('rekap_evaluasi_level_1') == '' ? [] : (array) json_decode($pod->field('rekap_evaluasi_level_1'));

    $ev = (array)$rekap['penyelenggaraan'];

    if (count($ev) > 0) {
        for ($i = 0; $i < count($ev); $i++) {
            if ($i == count($ev) - 1) {
                $aspek = 'Rata-rata';
                $index = 'rerata';
            } else {
                $aspek = $qs[$i];
                $index = 'q' . $i + 1;
            }

            $data[] = array(
                $aspek,
                $ev[$index]->h,
                $ev[$index]->k,
                $ev[$index]->p,
                $ev[$index]->ku
            );
        }
    } else {
        foreach ($qs as $q) {
            $data[] = array($q, '', '', '', '');
        }
        $data[] = array('Rata-rata', '', '', '', '');
    }    
?>
    <script src="https://bossanova.uk/jspreadsheet/v4/jexcel.js"></script>
    <link rel="stylesheet" href="https://bossanova.uk/jspreadsheet/v4/jexcel.css" type="text/css" />

    <script src="https://jsuites.net/v4/jsuites.js"></script>
    <link rel="stylesheet" href="https://jsuites.net/v4/jsuites.css" type="text/css" />

    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">
    <div class="card">
        <div class="card-header has-background-kmk-mix has-text-centered">
            <h5>EDIT REKAP EVALUASI PENYELENGGARAAN</h5>
            <div class="card-header-right">
                <i id="back" class="ti ti-angle-double-left" title="Kembali"></i>
            </div>
        </div>
        <div class="card-content p-4">
            <div id="spreadsheet"></div>
            <div class="text-center mt-3">
                <button style="width:100px;" id="cancel" type="button" class="btn btn-danger mx-2">Cancel</button>
                <button style="width:100px;" id="submit_button" type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>

    <script>
        let sheet = jspreadsheet(document.getElementById('spreadsheet'), {
            data: <?= json_encode($data) ?>,
            tableOverflow: true,
            minDimensions: [5, 8],
            columns: [{
                    type: 'text',
                    title: 'Aspek Penilaian',
                    width: ($('.card-content').width()) * 0.6,
                    align: 'left'
                },
                {
                    type: 'text',
                    title: 'Kepentingan',
                    width: ($('.card-content').width()) * 0.12,
                },
                {
                    type: 'text',
                    title: 'Kenyataan',
                    width: ($('.card-content').width()) * 0.12,
                },
                {
                    type: 'dropdown',
                    source: ['Sangat Baik', 'Baik', 'Cukup', 'Kurang Baik', 'tidak Baik'],
                    title: 'Kategori',
                    width: ($('.card-content').width()) * 0.12
                },
                {
                    type: 'dropdown',
                    source: ['Kuadran I', 'Kuadran II', 'Kuadran III', 'Kuadran IV'],
                    title: 'Kuadran',
                    width: ($('.card-content').width()) * 0.12
                }
            ],
            rowResize: true,
        });


        var ajaxurl = '<?= admin_url('admin-ajax.php') ?>';

        $('#submit_button').click(function() {
            $(this).html('<i class="fa fa-spinner fa-spin"></i>');
            var data = {
                action: 'edit_evaluasi_level_1',
                jenis: 'penyelenggaraan',
                json: sheet.getData(),
                id_pelatihan: <?= get_the_id() ?>,
            };
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.status == 1) {
                        tata.success(response.message, '', {
                            position: 'tm',
                            duration: 2000
                        });
                        location.href = '<?= get_the_permalink() ?>?section=rekap_evaluasi_penyelenggaraan';
                    } else {
                        tata.error(response.message, '', {
                            position: 'tm',
                            duration: 2000
                        });
                    }
                    $('#submit_button').html('Submit');
                }
            });
        });
        $('#cancel').click(function() {
            $(this).html('<i class="fa fa-spinner fa-spin"></i>');
            window.location.href = document.referrer;
        });

        $('#back').click(function() {
            window.location.href = document.referrer;
        });
    </script>
    <?php
}

function page_rekap_evaluasi_penyelenggaraan()
{
    $qs = array(
        'pjj' => ASPEK_PJJ,
        'e-learning' => ASPEK_ELEARNING,
        'klasikal' => ASPEK_KLASIKAL,
        'blended' => ASPEK_BLENDED,
        'blended_non_asrama' => ASPEK_BLENDED_NON_ASRAMA,
        'klasikal_non_asrama' => ASPEK_KLASIKAL_NON_ASRAMA,
    );

    $questions = array(
        'q1' => 'Kesesuaian materi pembelajaran dengan harapan/kebutuhan peserta',
        'q2' => 'Bahan ajar mudah dipahami',
        'q3' => 'Kesesuaian metode pembelajaran dengan materi Pengajar Jarak Jauh',
        'q4' => 'Ketercukupan waktu penyelenggaraan Pelatihan Jarak Jauh dengan jumlah materi yang diberikan',
        'q5' => 'Kesigapan penyelenggara dalam melayani peserta selama proses Pelatihan Jarak Jauh',
        'q6' => 'Ketercukupan waktu dalam mengerjakan penugasan, kuis, atau ujian',
        'q7' => 'Fasilitas Pelatihan Jarak Jauh mudah diakses',
        'q8' => 'Fasilitas Pelatihan Jarak Jauh mudah digunakan'
    );

    $pod = pods('pelatihan', get_the_id());

    $jenis_pelatihan = $pod->field('jenis_pelatihan');
    $q_index = ($jenis_pelatihan == 'klasikal' || $jenis_pelatihan == 'blended') && $pod->display('diasramakan') !== 'Ya' ? $jenis_pelatihan.'_non_asrama' : $jenis_pelatihan;
   

    if (isset($_GET['action']) && $_GET['action'] == 'edit') {
        page_edit_rekap_evaluasi_penyelenggaraan($qs[$pod->field('jenis_pelatihan')]);
    } else {


        /*
    foreach ($data as $key => $value) {
        if (substr($key, 0, 2) == 'zi') {
            if ($key == 'zi9') {
                $counts = array_count_values($value);
                $percent = array_sum($value) / count($value) * 100;
                $rekap['zi'][$key] = (string)round($percent, 2) . '%';
            } else if ($key == 'zi10') {
                $rekap['zi'][$key] = $value;
            } else {
                $average = array_sum($value) / count($value);
                $rekap['zi'][$key] = round($average, 2);
            }
        } else {
            if ($key == 'q9' || $key == 'q10') {
                $counts = array_count_values($value);
                $percent = $counts['ya'] / count($value) * 100;
                $rekap['lainnya'][$key] = (string)round($percent, 2) . '%';
            } else if (in_array($key, array('q11', 'q12'))) {
                $rekap['saran'][$key] = $value;
            } else {
                $average = array_sum($value) / count($value);
                if (substr($key, 0, 1) == 'p') {
                    $rekap['pengajar'][$key] = round($average, 2);
                } else {
                    $rekap['penyelenggaraan'][$key] = round($average, 2);
                }
            }
        }
    }
    */
    ?>
        <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">

        <div class="card" id="evagara">
            <div class="card-header has-background-kmk-mix has-text-centered">
                <h5>REKAPITULASI EVALUASI PENYELENGGARAAN</h5>
                <div class="card-header-right">
                    <a class="bulk_editor" id="bulk_editor" href="?section=<?= $_GET['section'] ?>&action=edit"><i class="fa fa-table" title="Bulk Editor"></i></a>
                </div>
            </div>
            <div class="card-content p-4">
                <div class="container">
                    <table id="tabel_evagara" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th rowspan="2">No.</th>
                                <th rowspan="2">Aspek Penilaian</th>
                                <th rowspan="2">Rata-Rata<br />Kepentingan (Y)</th>
                                <th colspan="2">Kenyataan (X)</th>
                                <th rowspan="2">Kuadran</th>
                            </tr>
                            <tr>
                                <th>Rata-Rata</th>
                                <th>Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ev_1 = (array)json_decode($pod->field('rekap_evaluasi_level_1'));
                            $i = 0;
                            ?>
                            <?php foreach ($ev_1['penyelenggaraan'] as $q => $v) : ?>
                                <?php if ($q !== 'rerata') : ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= $qs[$q_index][$i] ?></td>
                                        <td><?= $v->h ?></td>
                                        <td><?= $v->k ?></td>
                                        <td><?= $v->p ?></td>
                                        <td><?= $v->ku ?></td>
                                    </tr>
                                    <?php $i++; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-center">Rata - Rata</th>
                                <th class="text-center">
                                    <h5><?= $ev_1['penyelenggaraan']->rerata->h ?></h5>
                                </th>
                                <th class="text-center">
                                    <h5><?= $ev_1['penyelenggaraan']->rerata->k ?></h5>
                                </th>
                                <th class="text-center"><?= Helpers::predikat($ev_1['penyelenggaraan']->rerata->k) ?></th>
                                <th class="text-center"><?= Helpers::kuadran($ev_1['penyelenggaraan']->rerata->k, $ev_1['penyelenggaraan']->rerata->k) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $('#tabel_evagara').DataTable({
                    columnDefs: [
                        // Center align the header content of column 1
                        {
                            className: "dt-head-center",
                            targets: "all"
                        },
                        // Center align the body content of columns 2, 3, & 4
                        {
                            className: "dt-body-center",
                            targets: [0, 2, 3, 4]
                        },
                        {
                            render: function(data, type, full, meta) {
                                return "<div class='text-justify text-wrap width-c'>" + data + "</div>";
                            },
                            targets: 1
                        }
                    ],
                    pageLength: 20,
                    order: [],
                    scrollX: true
                });
            });
        </script>
<?php }
}
