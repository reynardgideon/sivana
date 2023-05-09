<?php
include_once(get_template_directory() . '/getters/helpers.php');

function page_rekap_evaluasi_lainnya()
{
    /*
    

    $params = array(
        'limit' => -1,
        'where' => "pelatihan.ID = '" . get_the_id() . "'"
    );

    $data_form = pods('data_form', $params);

    $data = [];

    while ($data_form->fetch()) {
        $this_data = json_decode($data_form->field('data'));
        foreach ($this_data as $k => $v) {
            if (substr($k, 0, 1) == 'p' || $k == 'q9' || $k == 'q10') {
                $data[$k][] = $v;
            }
        }
    }

    unset($data['form']);
    unset($data['responden']);
    unset($data['pelatihan']);

    $rekap = [];

    foreach ($data as $key => $value) {
        if (substr($key, 0, 1) == 'p') {
            $average = array_sum($value) / count($value);
            $rekap[$key] = number_format((float)round($average, 2), 2, '.', '');
        } else {
            $counts = array_count_values($value);
            $percent = $counts['ya'] / count($value) * 100;
            $rekap[$key] = (string)round($percent, 2) . '%';
        }
    }

    $harapan = 0;
    $kenyataan = 0;

    $stored = [];
    $lainnya = [];

    foreach ($rekap as $key => $v) {
        if (substr($key, 0, 1) == 'p') {
            $ids = substr($key, 1, -1);

            if (substr($key, -1) == 'h') {
                $harapan += $v;
                $stored[$ids]['h'] = $v;
            }

            if (substr($key, -1) == 'k') {
                $kenyataan += $v;
                $stored[$ids]['k'] = $v;
            }
        } else {
            $lainnya[$key] = $v;
        }
    }

    $har = number_format((float)round($harapan / ((count($rekap) - 2) / 2), 2), 2, '.', '');
    $ken = number_format((float)round($kenyataan / ((count($rekap) - 2) / 2), 2), 2, '.', '');

    $row = array(
        'h' => $har,
        'k' => $ken,
        //'p' => Helpers::predikat($har),
        //'ku' => Helpers::kuadran($har, $ken)
    );

    $stored['rerata'] = $row;

    $rekap_ev1 = (array)json_decode($pod->field('rekap_evaluasi_level_1'));

    $rekap_ev1['pengajar'] = $stored;
    $rekap_ev1['lainnya'] = $lainnya;

    $pod->save(array(
        'rekap_evaluasi_level_1' => json_encode($rekap_ev1)
    ));
*/

    $pod = pods('pelatihan', get_the_id());
    $ev_1 = (array)json_decode($pod->field('rekap_evaluasi_level_1'));

    $q9 = $ev_1['lainnya']->q9;
    $q10 = $ev_1['lainnya']->q10;

?>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <div class="card">
        <div class="card-header has-background-kmk-mix has-text-centered">
            <h5>REKAPITULASI EVALUASI LAINNYA</h5>
        </div>
        <div class="card-content p-4">
            <div class="container">
                <div class="row mt-5">
                    <div class="col mx-5" style="border: 1px solid #999;border-radius: 10px; padding:20px;">
                        <div class="panel panel-default">
                            <div class="panel-body text-center text-primary mb-2">
                                <h5>Saya mengetahui tujuan pembelajaran sebelum pembelajaran dimulai</h5>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <h3><?= $q9 ?></h3>
                        </div>
                    </div>
                    <div class="col mx-6" style="border: 1px solid #999;border-radius: 10px; padding:20px;">
                        <div class="panel panel-default">
                            <div class="panel-body text-center text-primary mb-2">
                                <h5>Saya akan merekomendasikan pembelajaran ini kepada orang lain</h5>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <h3><?= $q10 ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } ?>