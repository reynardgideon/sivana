<?php

include_once(get_template_directory() . '/getters/helpers.php');

if (isset($_GET['action']) && $_GET['action'] == 'generate') {
    call_user_func('generate_' . strtolower(str_replace(' ', '_', get_the_title())), $_GET['year']);
} else {
    $pod = pods('data_api', get_the_ID());
    echo $pod->field('data');
}

function generate_rekap_pelatihan($year)
{
    $args = array(
        'limit' => -1,
        'where' => "YEAR(mulai.meta_value) = '" . $year . "' AND selesai.meta_value < CURRENT_DATE()"
    );

    $pod = pods('pelatihan', $args);     

    $output = [];    
    
    while ($pod->fetch()) {

        $higher = 0;

        $ev_1 = (array) json_decode($pod->field('rekap_evaluasi_level_1'));

        foreach ($ev_1['pengajar'] as $key => $val) {
            $higher += $key !== 'rerata' && $val[1] >  $val[0] ? 1 : 0;
        }

        echo $higher.' - '.(count((array)$ev_1['pengajar']) - 1).'<br/>';

        $output[] = array(
            $pod->display('judul'),
            strtoupper($pod->display('judul')),
            Helpers::range_tanggal($pod->field('mulai'), $pod->field('selesai'))
        );
    }

    $data_api = pods('data_api', get_the_ID());
    $data_api->save(array(
        'data' => json_encode($output)
    ));

    echo $data_api->field('data');
}
