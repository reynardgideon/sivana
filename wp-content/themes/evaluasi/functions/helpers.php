<?php

function show_error($case)
{
    if ($case == 'login') {
        echo "You need to login";
    }
}

function get_data_table2($id, $fields, $data)
{
?>
    <table id="<?= $id ?>" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th><input type="checkbox" class="check_all"></th>
                <th>#</th>
                <?php foreach ($fields as $f) : ?>
                    <th><?= $f ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>

    </table>

    <script>
        $(document).ready(function() {
            var table = $('#<?= $id ?>').DataTable({
                pageLength: 50,
                columnDefs: [{
                    target: 0,
                    visible: false,
                    searchable: false,
                }],
                order: [],
                ajax: '<?= $data ?>',
                deferRender: true,
                scrollX: true,
                scrollY: 200,
                scrollCollapse: true,
                scroller: true,
            });


        });
    </script>
<?php
}

function get_data_table($pod, $q)
{
    $data = 'https://evaluasi.knpk.xyz/wp-content/themes/evaluasi/data/' . $pod . '.php?id_' . $q[0] . '=' . $q[1];
?>
    <table id="<?= $pod ?>" class="display nowrap" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th><input type="checkbox" class="check_all"></th>
                <th>#</th>
                <?php foreach (constant('FIELDS_' . strtoupper($pod)) as $f) : ?>
                    <th><?= $f['title'] ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>

    </table>

    <script>
        $(document).ready(function() {
            var table = $('#<?= $pod ?>').DataTable({
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

function tabel_pelatihan($tahun)
{
    $data = get_template_directory_uri() . '/data/pelatihan.php?tahun=' . $tahun;
?>
    <table id="pelatihan" class="display nowrap" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th><input type="checkbox" class="check_all"></th>
                <th>#</th>
                <?php foreach (constant('FIELDS_PELATIHAN') as $f) : ?>
                    <th><?= $f['title'] ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>

    </table>

    <script>
        $(document).ready(function() {
            var table = $('#pelatihan').DataTable({
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

function get_datatable($obj, $q = '')
{
    $data_ajax = get_template_directory_uri() . '/data/' . $obj . '.php' . $q;

    $fields = constant('FIELDS_' . strtoupper($obj));

    $pod_name = $obj;
    $data = array();
    $params = array(
        'limit' => -1
    );

    if ($obj == 'pengajar') {
        $pod_name = 'user';
        $params['limit'] = -1;
        $params['where'] = "groups.meta_value = 'pengajar'";
    }

    if ($obj == 'pengguna') {
        $params['limit'] = 1;
        $pod_name = 'user';
        $params['where'] = "groups.meta_value = 'peserta'";
    }

    $pod = pods($pod_name, $params);

    $i = 1;
    if (0 < $pod->total()) {
        while ($pod->fetch()) {
            $first_slug = str_replace(' ', '_', strtolower($fields[0]['title']));
            $item = array();
            $item[] = $pod->display('ID');
            $item[] = '<input type="checkbox" data-' . $first_slug . '="' . $pod->display($first_slug) . '" class="check_item" value="' . $pod->display('ID') . '">';
            $item[] = $i;

            foreach ($fields as $f) {
                $slug = str_replace(' ', '_', strtolower($f['title']));
                $item[] = $pod->display($slug);
            }
            $data[] = $item;
            $i++;
        }
    }
?>

    <table id="<?= $obj ?>" class="display nowrap" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th><input type="checkbox" class="check_all"></th>
                <th>#</th>
                <?php foreach ($fields as $f) : ?>
                    <?php if ($f['title'] !== 'NIP' || ($f['title'] == 'NIP' && $obj !== 'pengguna')) : ?>
                        <th><?= $f['title'] ?></th>
                    <?php endif ?>
                <?php endforeach; ?>
                <?= $obj == 'daftar_nilai' ? '<th>Actions</th>' : '' ?>
            </tr>
        </thead>

    </table>

    <script>
        $(document).ready(function() {
            var table = $('#<?= $obj ?>').DataTable({
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
                        className: "dt-left",
                        target: 1
                    },
                    {
                        className: "dt-head-center",
                        targets: "_all"
                    },

                    <?php if ($obj == 'daftar_nilai') : ?> {
                            target: 5,
                            visible: false,
                        },
                    <?php endif; ?>

                    <?php if ($obj == 'pengguna') : ?> {
                        render: function(data, type, full, meta) {
                            return "<div class='text-justify text-wrap width-c'>" + data + "</div>";
                        },
                        targets: 4
                    }
                    <?php endif; ?>
                ],
                order: [],
                ajax: '<?= $data_ajax ?>',
                createdRow: function(row, data, dataIndex) {
                    if (data[5] == 'Ya') {
                        $(row).addClass('table-info');
                    }
                }

            });

        });
    </script>
<?php
}

function tanggal($date)
{
    $bulan = [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];

    $split = explode('-', $date);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

function get_dates($start, $end, $format = 'd-m-Y')
{

    // Declare an empty array
    $array = array();

    // Variable that store the date interval
    // of period 1 day
    $interval = new DateInterval('P1D');

    $realEnd = new DateTime($end);
    $realEnd->add($interval);

    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

    // Use loop to store date into array
    foreach ($period as $date) {
        $array[] = $date->format($format);
    }

    // Return the array elements
    return $array;
}
