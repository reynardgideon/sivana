<?php
include_once(get_template_directory() . '/getters/helpers.php');

function page_monitoring()
{
    $pod = pods('pelatihan', get_the_id());

    $tasks = array(
        'rapat_kelulusan' => array(
            'Pelaksanaan Rapat Kelulusan',
            'Nomor BA Rapat Kelulusan',
            'DS BA Rapat Kelulusan'
        ),
        'rapat_rekomendasi' => array(
            'Pelaksanaan Rapat Rekomendasi',
            'Nomor BA Rapat Rekomendasi',
            'DS BA Rapat Rekomendasi'
        ),
        'pengumuman' => array(
            'Nomor Pengumuman',
            'DS Pengumuman',
        ),
        'sertifikat' => array(
            'Nomor Sertifikat',
            'DS Sertifikat'
        )
    );

    $monitoring = pods('monitoring', 6535);

    $tasks2 = $monitoring->field('tasks.ID');

    $data2 = [];

    $j = 1;
    foreach ($tasks2 as $id) {

        $item = [];
        $task = pods('task', $id);

        $params = array(
            'limit' => 1,
            'where' => "pelatihan.ID = " . get_the_ID() . " AND task.ID=" . $id
        );

        $item = array(
            $j,
            $task->display('judul'),
            $task->display('task_category'),
            '<i class="fa fa-square-o status" aria-hidden="true" data-toggle="modal" data-target="#updateStatusModal" data-group="' . $task->display('task_category') . '" data-task="' . $id . '"></i>',
            '',
            ''
        );

        $record = pods('task_record', get_the_ID() . '-' . $id);

        $icon = $record->field('selesai') == 1 ? 'fa-check-square-o' : 'fa-square-o';

        if ($record->exists()) {
            $item[3] = '<i class="fa ' . $icon . ' status" aria-hidden="true" data-toggle="modal" data-target="#updateStatusModal" data-group="' . $task->display('task_category') . '" data-task="' . $id . '"></i>';
            $item[4] = $record->display('tanggal');
            $item[5] = $record->display('catatan');
        }
        $j++;

        $data2[] = $item;
    }

    $data = [];
    $i = 1;

    $all_state = [];

    foreach ($tasks as $k => $v) {
        $state = (array) json_decode($pod->field($k));

        $all_state[$k] = $state;

        foreach ($v as $task) {

            $icon = isset($state[$task]) && $state[$task]->selesai == 1 ? 'fa-check-square-o' : 'fa-square-o';
            $item = array(
                $i,
                $task,
                ucwords(str_replace('_', ' ', $k)),
                '<i class="fa ' . $icon . ' status" aria-hidden="true" data-toggle="modal" data-target="#updateStatusModal" data-group="' . $k . '" data-task="' . $task . '"></i>',
                $state[$task]->catatan
            );
            $data[] = $item;

            $i++;
        }
    }

?>

    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link href="https://cdn.datatables.net/scroller/2.0.5/css/scroller.dataTables.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.0/css/rowGroup.dataTables.min.css">
    <script src="https://cdn.datatables.net/rowgroup/1.3.0/js/dataTables.rowGroup.min.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
    <script>
        $(document).ready(function() {
            $('#monitoring').DataTable({
                data: <?= json_encode($data2) ?>,
                order: [],
                columns: [{
                        title: 'No'
                    },
                    {
                        title: 'Uraian'
                    },
                    {
                        title: 'Group'
                    },
                    {
                        title: 'Status'
                    },
                    {
                        title: 'Tanggal'
                    },
                    {
                        title: 'Catatan'
                    }
                ],

                rowGroup: {
                    dataSrc: 2
                },

                columnDefs: [{
                        className: "dt-head-center",
                        targets: "_all"
                    },
                    {
                        className: "dt-center",
                        targets: [0, 3, 4, 5]
                    },
                    {
                        visible: false,
                        targets: 2
                    },
                    {
                        width: "5%",
                        targets: 0
                    },
                    {
                        width: "15%",
                        targets: 1
                    },
                    {
                        width: "5%",
                        targets: 3
                    },
                    {
                        width: "10%",
                        targets: 4
                    },
                    {
                        width: "30%",
                        targets: 4
                    },


                ],
            });

            var ajaxurl = '<?= get_site_url() ?>/wp-admin/admin-ajax.php';

            $('#update_status').click(function() {
                $(this).html('<i class="fa fa-spinner fa-spin"></i>');

                var data = $('#updateForm').serialize();

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        tata.success(response.message, '', {
                            position: 'tm',
                            duration: 5000
                        });
                        location.reload();
                    }
                });
            });

            $('.status').click(function() {
                $('#task').val($(this).data('task'));
                $('#group').val($(this).data('group'));

                let state = <?= json_encode($all_state) ?>;

                if (state[$(this).data('group')][$(this).data('task')].selesai == 1) {
                    //let dat = new Date('Y-m-d');
                    alert(state[$(this).data('group')][$(this).data('task')].tanggal);
                    $('#inputTanggal').val(dat);
                    $('#inputSelesai').prop('checked', true);
                    //$('#inputTanggal').val(state[$(this).data('group')][$(this).data('task')].tanggal);
                    $('#inputCatatan').val(state[$(this).data('group')][$(this).data('task')].catatan);
                }
            });
        });
    </script>

    <div class="card">
        <div class="card-header has-background-kmk-mix has-text-centered text-dark">
            <h5>Monitoring Pekerjaan</h5>
        </div>
        <div class="card-content p-4">
            <table id="monitoring" class="display" style="width:100%; font-size:11pt;"></table>
        </div>
    </div>

    <div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog" aria-labelledby="updateStatusModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body px-4">
                    <form id="updateForm">
                        <div class="form-group">
                            <label for="inputAddress"><b>Status</b></label>
                            <div class="form-check ml-4">
                                <input id="selesai" name="selesai" class="form-check-input" type="checkbox" id="inputSelesai">
                                <label class="form-check-label" for="gridCheck">
                                    Selesai
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputTanggal"><b>Tanggal</b></label>
                            <input type="date" name="tanggal" class="form-control" id="inputTanggal" placeholder="Tanggal Dilaksanakan" value="2014-02-09">
                        </div>
                        <div class="form-group">
                            <label for="inputCatatan"><b>Catatan</b></label>
                            <input type="text" name="catatan" class="form-control" id="inputCatatan" placeholder="Catatan bila diperlukan">
                        </div>
                        <input type="hidden" class="form-control" name="action" value="update_status">
                        <input id="task" type="hidden" class="form-control" name="task" value="">
                        <input id="group" type="hidden" class="form-control" name="group" value="">
                        <input type="hidden" class="form-control" name="id_pelatihan" value="<?= get_the_id() ?>">
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" style="width:100px;" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" style="width:100px;" id="update_status">Save</button>
                </div>
            </div>
        </div>
    </div>

<?php } ?>