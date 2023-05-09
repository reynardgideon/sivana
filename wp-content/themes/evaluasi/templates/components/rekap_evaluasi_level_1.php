<?php
include_once(get_template_directory() . '/getters/helpers.php');
include_once(get_template_directory() . '/getters/pengajar.php');

function page_rekap_evaluasi_level_1()
{
    $id = get_the_id();
    rekap_evaluasi_penyelenggaraan($id);
    rekap_evaluasi_pengajar($id);
    saran_penyelenggaraan($id);
    saran_pengajar($id);
}
function rekap_evaluasi_penyelenggaraan($id)
{
    $qs = array(
        'pjj' => ASPEK_PJJ,
        'e-learning' => ASPEK_ELEARNING,
        'klasikal' => ASPEK_KLASIKAL,
        'blended' => ASPEK_BLENDED,
        'blended_non_asrama' => ASPEK_BLENDED_NON_ASRAMA,
        'klasikal_non_asrama' => ASPEK_KLASIKAL_NON_ASRAMA,
    );

    $pod = pods('pelatihan', $id);

    $jenis_pelatihan = $pod->field('jenis_pelatihan');
    $q_index = ($jenis_pelatihan == 'klasikal' || $jenis_pelatihan == 'blended') && $pod->display('diasramakan') !== 'Ya' ? $jenis_pelatihan . '_non_asrama' : $jenis_pelatihan;

?>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">

    <div class="card" id="evagara">
        <div class="card-header has-background-kmk-mix has-text-centered">
            <h5>REKAPITULASI EVALUASI PENYELENGGARAAN</h5>
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

function rekap_evaluasi_pengajar($id)
{
    $pod = pods('pelatihan', $id);
    $ev_1 = (array)json_decode($pod->field('rekap_evaluasi_level_1'));

    $params = array(
        'limit' => -1,
        'where' => "pelatihan.ID = '" . $id . "'"
    );

    $evajar = pods('evaluasi_pengajar', $params);

    $data = [];
    $i = 1;

    while ($evajar->fetch()) {
        $nilai = (array) json_decode($evajar->field('nilai'));
        $item = array(
            $i,
            $evajar->display('pengajar.nama_lengkap'),
            $evajar->display('mata_pelajaran'),
            $nilai['h'],
            $nilai['k'],
            $nilai['p'],
            $nilai['ku']
        );
        $data[] = $item;
        $i++;
    }
?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/af-2.4.0/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/fc-4.1.0/fh-3.2.4/sc-2.0.7/sr-1.1.1/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/af-2.4.0/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/fc-4.1.0/fh-3.2.4/sc-2.0.7/sr-1.1.1/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">

    <div class="card" id="evajar">
        <div class="card-header has-background-kmk-mix has-text-centered">
            <h5>REKAPITULASI EVALUASI PENGAJAR</h5>            
        </div>
        <div class="card-content p-4">
            <div class="container">
                <table id="tabel_evajar" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th rowspan="2">No.</th>
                            <th rowspan="2">Nama Pengajar</th>
                            <th rowspan="2">Mata Pelajaran</th>
                            <th colspan="4" style="white-space: normal;text-align:center;">Kemampuan Pengajar dalam Memberikan<br />Bimbingan secara Jarak Jauh</th>

                        </tr>
                        <tr>
                            <th>Ekspektasi/Harapan</th>
                            <th>Persepsi/Kenyataan</th>
                            <th>Kategori</th>
                            <th>Kuadran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $d) : ?>
                            <tr>
                                <td><?= $d[0] ?></td>
                                <td><?= $d[1] ?></td>
                                <td style="white-space: normal;"><?= $d[2] ?></td>
                                <td><?= $d[3] ?></td>
                                <td><?= $d[4] ?></td>
                                <td><?= $d[5] ?></td>
                                <td><?= $d[6] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-center">Rata - Rata</th>
                            <th class="text-center">
                                <h5><?= $ev_1['pengajar']->rerata->h ?></h5>
                            </th>
                            <th class="text-center">
                                <h5><?= $ev_1['pengajar']->rerata->k ?></h5>
                            </th>
                            <th class="text-center"><?= $ev_1['pengajar']->rerata->p ?></th>
                            <th class="text-center"><?= $ev_1['pengajar']->rerata->ku ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#tabel_evajar').DataTable({
                order: [],
                columnDefs: [
                    // Center align the header content of column 1
                    {
                        className: "dt-head-center",
                        targets: "all"
                    },
                    // Center align the body content of columns 2, 3, & 4
                    {
                        className: "dt-body-center",
                        targets: [0, 3, 4, 5, 6]
                    }
                ]                
            });
        });
    </script>
<?php
}

function saran_penyelenggaraan($id)
{
    $pod = pods('pelatihan', get_the_ID());

    $ev_1 = (array)json_decode($pod->field('rekap_evaluasi_level_1'));

    $params = array(
        'limit' => -1,
        'where' => "pelatihan.ID = '" . $id . "' AND kategori.meta_value='penyelenggaraan'"
    );

    $saran = pods('saran', $params);

    $total = $ev_1['responden'];

    $i = 1;
    $data = [];
    if ($total > 0) {
        while ($saran->fetch()) {

            if ($saran->field('aktif') == 1) {

                $item = array(
                    $saran->field('ID'),
                    '<input type="checkbox" data-isi="' . $saran->field('isi') . '" class="check_item" value="' . $saran->field('ID') . '">',
                    $i . '.',
                    $saran->field('frekuensi'),
                    (string) number_format($saran->field('frekuensi') / $total * 100, 1, ",", ".") . '%',
                    //number_format($saran->field('persen'), 1, ",", ".") . '%',
                    $saran->field('isi')
                );
                $data[] = $item;
                $i++;
            }
        }
    }



?>
    <style>
        .text-wrap {
            white-space: normal;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/af-2.4.0/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/fc-4.1.0/fh-3.2.4/sc-2.0.7/sr-1.1.1/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/af-2.4.0/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/fc-4.1.0/fh-3.2.4/sc-2.0.7/sr-1.1.1/datatables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.1/css/fixedHeader.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.3.1/js/dataTables.fixedHeader.min.js"></script>
    <div class="card" id="sagara">
        <div class="card-header has-background-kmk-mix has-text-centered">
            <h5>SARAN DAN MASUKAN TERKAIT PENYELENGGARAAN</h5>            
        </div>
        <div class="card-content p-4">
            <div class="container">
                <table id="tabel_sagara" class="display" style="width:100%; font-size:20px;"></table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#tabel_sagara').DataTable({
                data: <?= json_encode($data) ?>,
                fixedHeader: {
                    header: true,
                    footer: true,
                },
                columns: [{
                        title: 'ID'
                    },
                    {
                        title: '<input type="checkbox" class="check_all">'
                    },
                    {
                        title: '#'
                    },
                    {
                        title: 'Frek'
                    },
                    {
                        title: 'Persen'
                    },
                    {
                        title: 'Uraian'
                    }
                ],
                columnDefs: [{
                        render: function(data, type, full, meta) {
                            return "<div class='text-justify text-wrap width-c'>" + data + "</div>";
                        },
                        targets: 5
                    },
                    {
                        targets: [0],
                        visible: false,
                        searchable: false,
                    },
                ],
                pageLength: 50,
                order: [],              
                scrollX: true,
            });          
        });
    </script>

<?php }

function saran_pengajar($id)
{
    $pod = pods('pelatihan', get_the_ID());

    $ev_1 = (array)json_decode($pod->field('rekap_evaluasi_level_1'));

    $params = array(
        'limit' => -1,
        'where' => "pelatihan.ID = '" . get_the_ID() . "' AND kategori.meta_value='pengajar'"
    );

    $saran = pods('saran', $params);

    $total = $ev_1['responden'];

    $i = 1;
    $data = [];
    if ($total > 0) {
        while ($saran->fetch()) {
            if ($saran->field('aktif') == 1) {
                $item = array(
                    $saran->field('ID'),
                    '<input type="checkbox" data-isi="' . $saran->field('isi') . '" class="check_item" value="' . $saran->field('ID') . '">',
                    $i . '.',
                    $saran->field('frekuensi'),
                    (string) number_format($saran->field('frekuensi') / $total * 100, 1, ",", ".") . '%',
                    //number_format($saran->field('persen'), 1, ",", ".") . '%',
                    $saran->field('isi')
                );
                $data[] = $item;
                $i++;
            }
        }
    }

?>
    <style>
        .text-wrap {
            white-space: normal;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/af-2.4.0/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/fc-4.1.0/fh-3.2.4/sc-2.0.7/sr-1.1.1/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/af-2.4.0/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/fc-4.1.0/fh-3.2.4/sc-2.0.7/sr-1.1.1/datatables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.1/css/fixedHeader.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.3.1/js/dataTables.fixedHeader.min.js"></script>
    <div class="card" id="sajar">
        <div class="card-header has-background-kmk-mix has-text-centered">
            <h5>SARAN DAN MASUKAN TERKAIT PENGAJAR</h5>            
        </div>
        <div class="card-content p-4">
            <div class="container">
                <table id="tabel_sajar" class="display" style="width:100%; font-size:20px;"></table>
            </div>
        </div>
    </div>
   
    <script>
        $(document).ready(function() {
            $('#tabel_sajar').DataTable({
                data: <?= json_encode($data) ?>,
                fixedHeader: {
                    header: true,
                    footer: true,
                },
                columns: [{
                        title: 'ID'
                    },
                    {
                        title: '<input type="checkbox" class="check_all">'
                    },
                    {
                        title: '#'
                    },
                    {
                        title: 'Frek'
                    },
                    {
                        title: 'Persen'
                    },
                    {
                        title: 'Uraian'
                    }
                ],
                columnDefs: [{
                        render: function(data, type, full, meta) {
                            return "<div class='text-justify text-wrap width-c'>" + data + "</div>";
                        },
                        targets: 5
                    },
                    {
                        targets: [0],
                        visible: false,
                        searchable: false,
                    },
                ],
                pageLength: 50,
                order: [],                
                scrollX: true,
            });          

        });
    </script>

<?php } ?>
