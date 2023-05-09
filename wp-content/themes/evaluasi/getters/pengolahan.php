<?php
include_once(get_template_directory() . '/getters/helpers.php');
include_once(get_template_directory() . '/getters/pengajar.php');
include_once(get_template_directory() . '/getters/mata-pelajaran.php');

class Pengolahan
{
    public static function get_npr_table($id_pelatihan)
    {
        $data = [];
        $pelatihan = pods('pelatihan', $id_pelatihan);

        $ids = $pelatihan->field('mata_pelajaran.ID');

        $i = 1;
        foreach ($ids as $id) {
            $item = [];
            $mp = pods('mata_pelajaran', $id);

            $item[] = $i;
            $item[] = '<a href="' . get_site_url() . '/pelatihan/' . $id_pelatihan . '/?section=npr&id_mata_pelajaran=' . $id . '">' . $mp->display('judul') . '</a>';
            $item[] = empty($mp->display('pengajar')) ? '-' : $mp->display('pengajar');

            $data[] = $item;
            $i++;
        }

        $data = json_encode($data);
?>
        <table id="daftar_npr" class="display nowrap" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Mata Pelajaran</th>
                    <th>Pengajar</th>
                </tr>
            </thead>
        </table>

        <script>
            $(document).ready(function() {
                var table = $('#daftar_npr').DataTable({
                    scrollX: true,
                    columnDefs: [{
                        target: 0,
                        width: '10%'
                    }],
                    order: [],
                    data: <?= $data ?>,
                    scrollX: true,
                    scrollCollapse: true
                });

                table.on('order.dt search.dt', function() {
                    let i = 1;

                    table.cells(null, 0, {
                        search: 'applied',
                        order: 'applied'
                    }).every(function(cell) {
                        this.data(i++);
                    });
                }).draw();

                $('li[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                    $($.fn.dataTable.tables(true)).css('width', '100%');
                });
            });
        </script>
    <?php

    }

    public static function get_rekap_table($id_pelatihan)
    {
    ?>
        <table id="rekap" class="display cell-border" style="width:100%">
            <thead>
                <tr>
                    <th rowspan="3">No.</th>
                    <th rowspan="3">Nama</th>
                    <th colspan="9">Mata Pelajaran Pokok</th>
                    <th colspan="3">Mata Pelajaran Penunjang</th>
                </tr>
                <tr>
                    <th colspan="3">MP1</th>
                    <th colspan="3">MP2</th>
                    <th colspan="3">MP3</th>
                    <th colspan="3">MP4</th>
                </tr>
                <tr>
                    <th>NPR</th>
                    <th>NP</th>
                    <th>NT</th>
                    <th>NPR</th>
                    <th>NP</th>
                    <th>NT</th>
                    <th>NPR</th>
                    <th>NP</th>
                    <th>NT</th>
                    <th>NPR</th>
                    <th>NP</th>
                    <th>NT</th>
                </tr>
            </thead>
        </table>

        <script>
            $(document).ready(function() {
                var table = $('#rekap').DataTable({
                    scrollX: true,
                    columnDefs: [{
                            target: 0,
                            width: 200
                        },
                        {
                            target: 1,
                            width: 400,
                        },
                        {
                            target: [3, 4, 5, 6, 7, 8, 9, 10, 11],
                            width: 200,
                        }
                    ],
                    order: [],
                    data: [],
                    scrollX: true,
                    scrollCollapse: true,
                    fixedColumns: true
                });

                table.on('order.dt search.dt', function() {
                    let i = 1;

                    table.cells(null, 0, {
                        search: 'applied',
                        order: 'applied'
                    }).every(function(cell) {
                        this.data(i++);
                    });
                }).draw();

                $('li[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                    $($.fn.dataTable.tables(true)).css('width', '100%');
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#example').DataTable({
                    scrollX: true,
                });
            });
        </script>
        <style>
            #rekap th {
                border-top: 1px solid #dddddd;
                border-bottom: 1px solid #dddddd;
                border-right: 1px solid #dddddd;
                text-align: center;
            }
        </style>
<?php
    }

    public static function save_rekap_nilai($id_pelatihan, $data)
    {
        $pod = pods('pelatihan', $id_pelatihan);

        $i_na = count($data[0]) - 5;

        $data_sorted = $data;
        usort($data_sorted, function ($a, $b) {
            $j = count($a) - 5;
            return $a[$j] < $b[$j];
        });

        $mpel = [];
        $mu = [];
        $lulus = [];

        foreach ($data as $d) {
            $i_tl = count($d) - 2;
            $i_ket = count($d) - 1;
            $i_pre = count($d) - 3;
            $item = [];

            if ($d[$i_tl] == 'Tidak Lulus') {
                $item['nip'] = $d[2];
                $item['ket'] = $d[$i_ket];

                if ($d[$i_ket] == 'Mengulang Pelatihan') {
                    $mpel[] = $item;
                } else {
                    $mu[] = $item;
                }
            } else {
                $lulus[$d[2]] = $d[$i_pre];
            }
        }


        /*

        $mpel = array_filter($data_sorted, function ($a) {
            $i_tl = count($a) - 2;
            $i_ket = count($a) - 1;
            return $a[$i_tl] == 'Tidak Lulus' && $a[$i_ket] == 'Mengulang Pelatihan';
        });

        $mu = array_filter($data_sorted, function ($a) {
            $i_tl = count($a) - 2;
            $i_ket = count($a) - 1;
            return $a[$i_tl] == 'Tidak Lulus' && !str_contains($a[$i_ket], 'Pelatihan');
        });
        */

        /*
        $lulus = array_filter($data, function ($a) {
            $i_tl = count($a) - 2;
            return $a[$i_tl] == 'Lulus';
        });
        */

        $ab = array_filter($data_sorted, function ($a) {
            $i_lu = count($a) - 2;
            $i_p = count($a) - 3;
            return $a[$i_lu] == 'Lulus' && $a[$i_p] == 'Amat Baik';
        });

        $b = array_filter($data_sorted, function ($a) {
            $i_lu = count($a) - 2;
            $i_p = count($a) - 3;
            return $a[$i_lu] == 'Lulus' && $a[$i_p] == 'Baik';
        });

        $c = array_filter($data_sorted, function ($a) {
            $i_lu = count($a) - 2;
            $i_p = count($a) - 3;
            return $a[$i_lu] == 'Lulus' && $a[$i_p] == 'Cukup';
        });

        $rank = 0;
        $terbaik = [];
        $last = 0;

        foreach ($data_sorted as $k => $v) {
            if ($v[$i_na] !== $last) {
                $rank++;
            }
            if ($rank < 4) {
                $p = [];
                $p['nip'] = $v[2];
                $p['na'] = $v[$i_na];

                $terbaik[$rank][] = $p;

                $last = $v[$i_na];
            }
        }

        $tl = count($mpel) + count($mu);

        $tidak_lulus = [];
        if ($tl > 0) {
            $tidak_lulus['Mengulang Pelatihan'] = $mpel;

            if ($pod->field('jenis_evaluasi_level_2') == 'ujian') {
                $all_mp = $pod->field('mata_pelajaran.judul');

                foreach ($mu as $k => $meng) {
                    $ex = explode(', ', str_replace('Mengulang ', '', $meng['ket']));

                    $new = 'Mengulang MP ';
                    foreach ($ex as $x) {
                        $i = str_replace('MP ', '', $x) - 1;
                        $new .= $all_mp[$i] . ', ';
                    }
                    $meng['ket'] = trim($new, ', ');

                    $mu[$k] = $meng;
                }                
            }
            
            $tidak_lulus['Mengulang Ujian'] = $mu;
        }

        $hasil = array(
            'peserta_terbaik' => $terbaik,
            'predikat' => array(
                'Amat Baik' => count($ab),
                'Baik' => count($b),
                'Cukup' => count($c),
                'Tidak Lulus' => $tl
            ),
            'tidak_lulus' => $tidak_lulus,
            'lulus' => $lulus
        );

        $pod->save(array(
            'rekap_nilai' => json_encode($data),
            'hasil_pelatihan' => json_encode($hasil)
        ));
    }

    public static function rekap_evaluasi_level_1($id)
    {
        $pod = pods('pelatihan', $id);

        $jenis_pelatihan = $pod->field('jenis_pelatihan');

        $params = array(
            'limit' => -1,
            'where' => "pelatihan.ID = '" . $id . "' AND responden.ID <> ''"
        );

        $data_form = pods('data_form', $params);

        if ($data_form->total() > 0) {
            $data = [];

            while ($data_form->fetch()) {
                $this_data = json_decode($data_form->field('data'));
                foreach ($this_data as $k => $v) {
                    if ($k == 'q11' || $k == 'q12' || $k == 'zi10') {
                        $data[$k][$data_form->field('responden.ID')] = $v;
                    } else {
                        $data[$k][] = $v;
                    }
                }
            }

            unset($data['form']);
            unset($data['responden']);
            unset($data['pelatihan']);

            $rekap_ev1 = [];

            $rekap_ev1 = (array)json_decode($pod->field('rekap_evaluasi_level_1'));

            $rekap_ev1['responden'] = $data_form->total();

            $jenis_array = array('penyelenggaraan', 'lainnya', 'saran', 'zi');

            if (count(Pengajar::get_by_pelatihan($id)) > 0) {
                $jenis_array[] = 'pengajar';
            }

            foreach ($jenis_array as $jenis) {

                $rekap_ev1[$jenis] = call_user_func(array('Pengolahan', 'calculate_evaluasi_' . $jenis), $data);

                if ($jenis == 'saran' || $jenis == 'zi') {

                    if ($jenis == 'saran') {
                        foreach ($rekap_ev1['saran'] as $key => $val) {
                            foreach ($val as $res => $saran) {
                                $data_saran = array(
                                    'pelatihan' => $id,
                                    'frekuensi' => 1,
                                    'persen' => (1 / $data_form->total()) * 100,
                                    'responden' => $res,
                                    'post_title' => $id . '-' . $res . '-' . $key,
                                    'kategori' => $key == 'q11' ? 'penyelenggaraan' : 'pengajar'
                                );

                                if (trim($saran, ' -') == '') {
                                    $data_saran['isi'] = '-';
                                } else {
                                    $data_saran['isi'] = $saran;
                                }

                                $pod_saran = pods('saran', $data_saran['post_title']);

                                if ($pod_saran->exists()) {
                                    $pod_saran->save($data_saran);
                                } else {
                                    $pod_saran->add($data_saran);
                                }
                            }
                        }
                    } else {
                        foreach ($rekap_ev1['zi']['z10'] as $res_zi => $saran_zi) {
                            $data_saran_zi = array(
                                'pelatihan' => $id,
                                'frekuensi' => 1,
                                'persen' => (1 / $data_form->total()) * 100,
                                'responden' => $res_zi,
                                'post_title' => $id . '-' . $res_zi . '-' . 'zi',
                                'kategori' => 'zi'
                            );

                            if (trim($saran_zi, ' -') == '') {
                                $data_saran_zi['isi'] = '-';
                            } else {
                                $data_saran_zi['isi'] = $saran_zi;
                            }

                            $pod_saran = pods('saran', $data_saran_zi['post_title']);

                            if ($pod_saran->exists()) {
                                $pod_saran->save($data_saran_zi);
                            } else {
                                $pod_saran->add($data_saran_zi);
                            }
                        }
                    }
                }

                if (count(Pengajar::get_by_pelatihan($id)) > 0) {

                    foreach ($rekap_ev1['pengajar'] as $key => $xue) {
                        if ($key !== 'rerata') {

                            $ids = explode('-', $key);

                            $data_evajar = array(
                                'pelatihan' => $id,
                                'pengajar' => $ids[0],
                                'mata_pelajaran' => $ids[1],
                                'nilai' => json_encode(array(
                                    'h' => $xue[0],
                                    'k' => $xue[1],
                                    'p' => Helpers::predikat($xue[1]),
                                    'ku' => Helpers::kuadran($xue[0], $xue[1])
                                )),
                                'post_title' => $id . '-' . $ids[1] . '-' . $ids[0]
                            );

                            $evajar = pods('evaluasi_pengajar', $data_evajar['post_title']);

                            if ($evajar->exists()) {
                                $evajar->save($data_evajar);
                            } else {
                                $evajar->add($data_evajar);
                            }
                        }
                    }
                }
            }

            $pod->save(array(
                'rekap_evaluasi_level_1' => json_encode($rekap_ev1)
            ));

            return 'Data berhasil direkap!';
        }
    }

    public static function rekap_evaluasi_level_2($id)
    {
        $pod = pods('pelatihan', $id);

        $params = array(
            'limit' => -1,
            'where' => "pelatihan.ID = '" . $id . "'"
        );

        $data_form = pods('data_form', $params);

        if ($data_form->total() > 0) {

            $data = [];

            while ($data_form->fetch()) {
                $this_data = json_decode($data_form->field('data'));
                foreach ($this_data as $k => $v) {
                    if ($k == 'q11' || $k == 'q12') {
                        $data[$k][$data_form->field('responden.ID')] = $v;
                    } else {
                        $data[$k][] = $v;
                    }
                }
            }

            unset($data['form']);
            unset($data['responden']);
            unset($data['pelatihan']);

            $rekap_ev1 = [];
            $rekap_ev1 = (array)json_decode($pod->field('rekap_evaluasi_level_1'));

            foreach (array('penyelenggaraan', 'lainnya', 'saran', 'zi') as $jenis) {

                $rekap_ev1[$jenis] = call_user_func('Helpers::calculate_evaluasi_' . $jenis, $data);

                if ($jenis == 'saran') {

                    foreach ($rekap_ev1['saran'] as $key => $val) {
                        foreach ($val as $res => $saran) {
                            $data = array(
                                'pelatihan' => $id,
                                'frekuensi' => 1,
                                'persen' => 1 / $data_form->total(),
                                'responden' => $res,
                                'post_title' => $id . '-' . $res
                            );

                            if (trim($saran, ' -') == '') {
                                $data['isi'] = 'Tidak memberikan masukan';
                            } else {
                                $data['isi'] = $saran;
                            }

                            $pod_saran = pods('saran', $data['post_title']);

                            if ($pod_saran->exists()) {
                                $pod_saran->save($data);
                            } else {
                                $pod_saran->add($data);
                            }
                        }
                    }
                }
            }

            if ($pod->field('jenis_pelatihan' !== 'e-learning')) {
                $rekap_ev1['pengajar'] = self::calculate_evaluasi_pengajar($data);

                /*
                foreach ($rekap_ev1['pengajar'] as $key => $val) {
                    if ($key !== 'rerata') {
                        $ids = explode('-', $key);

                        $data_evajar = array(
                            'pelatihan' => $id,
                            'pengajar' => $ids[0],
                            'mata_pelajaran' => $ids[1],
                            'nilai' => json_encode(array(
                                'h' => $val['h'],
                                'k' => $val['k'],
                                'p' => Helpers::predikat($val['k']),
                                'ku' => Helpers::kuadran($val['h'], $val['k'])
                            )),
                            'post_title' => $id . '-' . $ids[1] . '-' . $ids[0]
                        );

                        $evagara = pods('evaluasi_pengajar', $data_evajar['post_title']);

                        if ($evagara->exists()) {
                            $evagara->save($data_evajar);
                        } else {
                            $evagara->add($data_evajar);
                        }
                    }
                }
                */
            }

            $rekap_ev1['responden'] = $data_form->total();

            /*
            echo $pod->save(array(
                'rekap_evaluasi_level_1' => json_encode($rekap_ev1)
            ));
            */
        }
    }

    public static function calculate_evaluasi_penyelenggaraan($data)
    {

        /*
    $pod = pods('pelatihan', $id);

    $params = array(
        'limit' => -1,
        'where' => "pelatihan.ID = '" . $id . "'"
    );

    $data_form = pods('data_form', $params);

    $data = [];

    while ($data_form->fetch()) {
        $this_data = json_decode($data_form->field('data'));
        foreach ($this_data as $k => $v) {
            if (substr($k, 0, 1) == 'q' && !in_array($k, array('q9', 'q10', 'q11', 'q12'))) {
                $data[$k][] = $v;
            }
        }
    }

    unset($data['form']);
    unset($data['responden']);
    unset($data['pelatihan']);

    */

        $rekap = [];

        foreach ($data as $key => $value) {
            if (substr($key, 0, 1) == 'q' && !in_array($key, array('q9', 'q10', 'q11', 'q12'))) {
                $average = array_sum($value) / count($value);
                $rekap[$key] = number_format((float)round($average, 2), 2, '.', '');
            }
        }

        $harapan = 0;
        $kenyataan = 0;

        $stored = [];


        for ($i = 1; $i <= count($rekap) / 2; $i++) {
            $row = [];

            $harapan += $rekap['q' . $i . 'h'];
            $kenyataan += $rekap['q' . $i . 'k'];

            //$predikat = Helpers::predikat($rekap['q' . $i . 'k']);
            // $kuadran = Helpers::kuadran($rekap['q' . $i . 'h'], $rekap['q' . $i . 'k']);

            //$rekap['q' . $i . 'p'] = $predikat;
            //$rekap['q' . $i . 'q'] = $kuadran;

            $row = array(
                'h' => $rekap['q' . $i . 'h'],
                'k' => $rekap['q' . $i . 'k'],
                'p' => Helpers::predikat($rekap['q' . $i . 'k']),
                'ku' => Helpers::kuadran($rekap['q' . $i . 'h'], $rekap['q' . $i . 'k'])
                //'p' => $predikat,
                //'ku' => $kuadran
            );

            $stored['q' . $i] = $row;
        }

        $stored['rerata'] = array(
            'h' => number_format((float)round($harapan / (count($rekap) / 2), 2), 2, '.', ''),
            'k' => number_format((float)round($kenyataan / (count($rekap) / 2), 2), 2, '.', ''),
            'p' => Helpers::predikat($kenyataan / (count($rekap) / 2)),
            'ku' => Helpers::kuadran($harapan / (count($rekap) / 2), $kenyataan / (count($rekap) / 2))
        );

        return $stored;
    }

    public static function calculate_evaluasi_lainnya($data)
    {
        $rekap = [];
        foreach ($data as $key => $value) {
            if ($key == "q9" || $key == "q10") {
                $counts = array_count_values($value);
                $percent = $counts['ya'] / count($value) * 100;
                $rekap[$key] = (string)round($percent, 2) . '%';
            }
        }
        return $rekap;
    }

    public static function calculate_evaluasi_pengajar($data)
    {
        $rekap = [];

        foreach ($data as $key => $value) {
            if (substr($key, 0, 1) == 'p') {
                $average = array_sum($value) / count($value);
                $rekap[$key] = number_format((float)round($average, 2), 2, '.', '');
            }
        }

        $harapan = 0;
        $kenyataan = 0;

        $stored = [];

        foreach ($rekap as $key => $v) {
            $ids = substr($key, 1, -1);

            if (substr($key, -1) == 'h') {
                $harapan += $v;
                $stored[$ids][] = $v;
            }

            if (substr($key, -1) == 'k') {
                $kenyataan += $v;
                $stored[$ids][] = $v;
            }
        };

        $har = number_format((float)round($harapan / ((count($rekap)) / 2), 2), 2, '.', '');
        $ken = number_format((float)round($kenyataan / ((count($rekap)) / 2), 2), 2, '.', '');


        $row = array(
            'h' => $har,
            'k' => $ken,
            'p' => Helpers::predikat($ken),
            'ku' => Helpers::kuadran($har, $ken)
        );

        $stored['rerata'] = $row;

        return $stored;
    }

    public static function calculate_evaluasi_saran($data)
    {
        $stored = [];
        foreach ($data as $k => $v) {
            if ($k == 'q11' || $k == 'q12') {
                $stored[$k] = $v;
            }
        }
        return $stored;
    }

    public static function calculate_evaluasi_zi($data)
    {
        $stored = [];
        foreach ($data as $key => $value) {
            if (substr($key, 0, 1) == 'z') {
                if ($key == 'zi9') {
                    $percent = array_sum($value) / count($value) * 100;
                    $stored[$key] = (string)round($percent, 2) . '%';
                } else if ($key == 'zi10') {
                    $stored[$key] = $value;
                } else {
                    $average = array_sum($value) / count($value);
                    $stored[$key] = number_format((float)round($average, 2), 2, '.', '');
                }
            }
        }

        return $stored;
    }
}
