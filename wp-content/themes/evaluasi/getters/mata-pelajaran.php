<?php
class Mata_Pelajaran
{
    public static function get_data($id_pelatihan = null)
    {
        $pelatihan = pods('pelatihan', $id_pelatihan);
        $data = [];
        $ids = $pelatihan->field('mata_pelajaran.ID');
        $juduls = $pelatihan->field('mata_pelajaran.judul');

        $data[0] = 'Tidak Ada';
        for ($i = 0; $i < count($pelatihan->field('mata_pelajaran.ID')); $i++) {
            $data[$ids[$i]] = $juduls[$i];
        }
        return $data;
    }

    public static function get_data_indexed($id_pelatihan = null)
    {
        $pelatihan = pods('pelatihan', $id_pelatihan);
        $data = [];
        $ids = $pelatihan->field('mata_pelajaran.ID');
        $juduls = $pelatihan->field('mata_pelajaran.judul');

        for ($i = 0; $i < count($pelatihan->field('mata_pelajaran.ID')); $i++) {
            $data[$i] = array(
                'id' => $ids[$i],
                'judul' => $juduls[$i]
            );
        }
        return $data;
    }

    public static function get_pengajar($id_mata_pelajaran)
    {
        $mp = pods('mata_pelajaran', $id_mata_pelajaran);
        return $mp->display('pengajar');
    }

    public static function get_judul($id_mata_pelajaran)
    {
        $mp = pods('mata_pelajaran', $id_mata_pelajaran);
        return $mp->display('judul');
    }

    public static function calculate_np($id_pelatihan)
    {
        $params = array(
            'limit' => -1,
            //'where' => "diujikan.meta_value != '0' AND pelatihan.ID = '" . $id_pelatihan . "'"
            'where' => "pelatihan.ID = '" . $id_pelatihan . "'"
        );

        $mp = pods('mata_pelajaran', $params);

        $list = [];

        if ($mp->total() > 0) {
            while ($mp->fetch()) {
                $id = $mp->field('ID');
                $list[$id] = $mp->field('diujikan') == 1 ? $mp->field('jp') : 0;
            }
        }

        $total = array_sum($list);
        foreach ($list as $id => $jp) {
            $list[$id] = ($jp / $total) * 100;
        }
        return $list;
    }

    public static function get_mp_by_jenis($id, $jenis)
    {
        $params = array(
            'limit' => -1,
            'where' => "jenis_mp.meta_value = '" . $jenis . "' AND pelatihan.ID = '" . $id . "'"
        );

        $mp = pods('mata_pelajaran', $params);

        $ids = [];
        if ($mp->total() > 0) {
            while ($mp->fetch()) {
                $ids[] = $mp->field('ID');
            }
            //$ids[90];
        }
        return $ids;
    }

    public static function get_options_list($id_pelatihan, $selected = '')
    {
        $pelatihan = pods('pelatihan', $id_pelatihan);
        $options = '<option value="">Nothing Selected</option>';
        $ids = $pelatihan->field('mata_pelajaran.ID');
        $juduls = $pelatihan->field('mata_pelajaran.judul');
        for ($i = 0; $i < count($pelatihan->field('mata_pelajaran.ID')); $i++) {
            $is_selected = $ids[$i] == $selected ? ' selected' : '';
            $options .= "<option value='{$ids[$i]}'" . $is_selected . ">{$juduls[$i]}</option>";
        }
        return $options;
    }

    public static function get_npr($id)
    {
        $params = array(
            'limit' => -1,
            'where' => "mata_pelajaran.ID ='" . $id . "'"
        );

        $mp = pods('mata_pelajaran', $id);

        $nips = $mp->field('pelatihan.peserta.nip');

        $dn = pods('daftar_nilai', $params);

        $nilai = [];

        while ($dn->fetch()) {
            $data = (array) json_decode($dn->field('data'));
            foreach ($data['peserta'] as $p) {
                $nilai[$p->nip][$dn->field('jenis_nilai')] = $p->nilai == '' ? '-' : $p->nilai;
            }
        }

        $data = [];
        
        $npr = [];
        for ($i = 0; $i < count($nips); $i++) {
            $nip = $nips[$i];
            if ($mp->field('pelatihan.jenis_evaluasi_level_2') == 'ujian' && $mp->field('diujikan') == 1) {
                $p = $nilai[$nip]['p'] == '-' ? 0 : $nilai[$nip]['p'];
                $q = $nilai[$nip]['q'] == '-' ? 0 : $nilai[$nip]['q'];
                $r = $nilai[$nip]['r'] == '-' ? 0 : $nilai[$nip]['r'];
                $nilai_npr = (0.1 * $p) + (0.2 * $q) + (0.7 * $r);
                $npr[$nip] = sprintf('%0.2f', $nilai_npr);
            } else {
                $p = $nilai[$nip]['p'] == '-' ? 0 : $nilai[$nip]['p'];
                $q = $nilai[$nip]['q'] == '-' ? 0 : $nilai[$nip]['q'];
                $nilai_npr = (0.3 * $p) + (0.7 * $q);
                $npr[$nip] = sprintf('%0.2f', $nilai_npr);
            }
            
        }
        return $npr;
    }

    public static function npr_table_single($id)
    {
        $params = array(
            'limit' => -1,
            'where' => "mata_pelajaran.ID ='" . $id . "'"
        );

        $mp = pods('mata_pelajaran', $id);

        $nips = $mp->field('pelatihan.peserta.nip');

        $names = $mp->field('pelatihan.peserta.nama_lengkap');

        $dn = pods('daftar_nilai', $params);

        $nilai = [];

        while ($dn->fetch()) {
            $data = (array) json_decode($dn->field('data'));
            foreach ($data['peserta'] as $p) {
                $nilai[$p->nip][$dn->field('jenis_nilai')] = $p->nilai == '' ? '-' : $p->nilai;
            }
        }

        $data = [];

        $bp = $mp->field('diujikan') == 1 && $mp->field('pelatihan.jenis_evaluasi_level_2') == 'ujian' ? 0.1 : 0.3;
        $bq = $mp->field('diujikan') == 1 && $mp->field('pelatihan.jenis_evaluasi_level_2') == 'ujian' ? 0.2 : 0.7;
        $br = $mp->field('diujikan') == 1 && $mp->field('pelatihan.jenis_evaluasi_level_2') == 'ujian' ? 0.7 : 0;

        for ($i = 0; $i < count($nips); $i++) {
            $nip = $nips[$i];
            $name = $names[$i];
            $p = $nilai[$nip]['p'] == '-' ? 0 : $nilai[$nip]['p'];
            $q = $nilai[$nip]['q'] == '-' ? 0 : $nilai[$nip]['q'];
            $r = $nilai[$nip]['r'] == '-' ? 0 : $nilai[$nip]['r'];
            $npr = ($bp * $p) + ($bq * $q) + ($br * $r);
            $nilai[$nip]['npr'] = $npr;

            $item = [];
            $item[] = $i + 1;
            $item[] = $name;
            $item[] = $nilai[$nip]['p'] == '-' ? '-' : sprintf('%0.2f', $nilai[$nip]['p']);
            $item[] = $nilai[$nip]['q'] == '-' ? '-' : sprintf('%0.2f', $nilai[$nip]['q']);

            if ($mp->field('pelatihan.jenis_evaluasi_level_2') == 'ujian' && $mp->field('diujikan') == 1) {
                $item[] = $nilai[$nip]['r'] == '-' ? '-' : sprintf('%0.2f', $nilai[$nip]['r']);
            }

            $item[] = sprintf('%0.2f', $nilai[$nip]['npr']);

            $data[] = $item;
        }

        $data = json_encode($data);
?>
        <table id="nilai_npr" class="display nowrap" width="100%">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>P</th>
                    <th>Q</th>
                    <?php if ($mp->field('pelatihan.jenis_evaluasi_level_2') == 'ujian' && $mp->field('diujikan') == 1) : ?>
                        <th>R</th>
                    <?php endif; ?>
                    <th>NPR</th>
                </tr>
            </thead>
        </table>

        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

        <style>
            table {
                font-size: 16px;
            }
        </style>
        <script>
            $(document).ready(function() {
                var t = $('#nilai_npr').DataTable({
                    pageLength: 50,
                    scrollX: true,
                    order: [],
                    data: <?= $data ?>,
                    scrollX: true,
                    scrollCollapse: true,
                    columnDefs: [{
                        searchable: false,
                        orderable: false,
                        targets: 0,
                    }, ],
                    rowCallback: function(row, data, index) {
                        let col = <?= $mp->field('diujikan') ?> == 1 ? 6 : 5;
                        let min = '<?= $mp->field('jenis_mp') ?>' == 'pokok' ? 65 : 60;
                        for (let i = 2; i < col; i++) {
                            if (data[i] < min) {
                                $(row).find('td:eq(' + i + ')').css('color', 'red');
                            }
                        }
                    }
                });

                t.on('order.dt search.dt', function() {
                    let i = 1;

                    t.cells(null, 0, {
                        search: 'applied',
                        order: 'applied'
                    }).every(function(cell) {
                        this.data(i++);
                    });
                }).draw();
            });
        </script>
    <?php
    }

    public static function get_table($id_pelatihan)
    {
        $data = 'https://evaluasi.knpk.xyz/wp-content/themes/evaluasi/data/mata_pelajaran.php?id_pelatihan=' . $id_pelatihan;
    ?>
        <table id="mata_pelajaran" class="display nowrap" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><input type="checkbox" class="check_all"></th>
                    <th>#</th>
                    <?php foreach (constant('FIELDS_' . strtoupper('mata_pelajaran')) as $f) : ?>
                        <th><?= $f['title'] ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>

        </table>

        <script>
            $(document).ready(function() {
                var table = $('#mata_pelajaran').DataTable({
                    pageLength: 20,
                    scrollX: true,
                    columnDefs: [{
                            target: 0,
                            visible: false,
                            searchable: false,
                        }, {
                            target: 1,
                            width: 20
                        },
                        {
                            target: 2,
                            width: 20
                        },
                        {
                            target: 8,
                            visible: false,
                        }
                    ],
                    order: [],
                    ajax: '<?= $data ?>',
                    scrollX: true,
                    scrollCollapse: true,
                });

            });
        </script>
<?php
    }
}
