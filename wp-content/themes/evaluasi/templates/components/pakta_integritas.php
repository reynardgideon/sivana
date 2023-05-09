<?php
include_once(get_template_directory() . '/getters/helpers.php');
include_once(get_template_directory() . '/getters/pengajar.php');

function page_pakta_integritas()
{
    $id =  get_the_id();
    show_data_pakta_integritas($id);
}


function show_data_pakta_integritas($id)
{
    $params = array(
        'limit' => -1,
        'where' => "pelatihan.ID = '" . $id . "' AND form.ID = '7248'"
    );

    $data = pods('data_form', $params);

    $submit = [];
    while ($data->fetch()) {        
        $submit[$data->field('responden.ID')] = $data->field('ID');
    }

    $pod = pods('pelatihan', $id);

    $id_peserta = $pod->field('peserta.ID');

    $nama_peserta = $pod->field('peserta.nama_lengkap');

    $data_table = [];

    for ($i = 0; $i < count($id_peserta); $i++) {
        $sudah = array_key_exists($id_peserta[$i], $submit) ? '<i class="fa-sharp fa-solid fa-check text-success" style="font-size: 22px;"></i>' : '';

        $nama = $sudah == true ? '<a href="' . get_site_url() . '/form/pakta-integritas/?id_data=' . $submit[$id_peserta[$i]] . '&id_pelatihan=' . $id . '">' . $nama_peserta[$i] . '</a>' : $nama_peserta[$i];
        $data_table[] = array(
            $id_peserta[$i],
            '<input type="checkbox" data-nama="' . $nama_peserta[$i] . '" class="check_item" value="' . $id_peserta[$i] . '">',
            $i + 1,
            $nama,
            $sudah
        );
    }

?>
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <div class="card">
        <div class="card-header has-background-kmk-mix has-text-centered">
            <h5>DATA RESPON PAKTA INTEGRITAS</h5>            
        </div>
        <div class="card-content p-4">
            <table id="list_mengisi" class="display nowrap" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th><input type="checkbox" class="check_all"></th>
                        <th>#</th>
                        <th>Nama Peserta</th>
                        <th>Sudah Mengisi</i></th>
                    </tr>
                </thead>
            </table>            

            <style>
                table,
                table a {
                    font-size: 16px;
                }
            </style>

            <script>
                $(document).ready(function() {
                    var table = $('#list_mengisi').DataTable({
                        order: [],
                        pageLength: 50,
                        data: <?= json_encode($data_table) ?>,
                        columnDefs: [{
                                targets: [0],
                                visible: false,
                                searchable: false,
                            },
                            {
                                targets: [2],
                                orderable: false
                            },
                            {
                                targets: [0, 1, 2, 4],
                                className: "dt-body-center"
                            },
                            {
                                targets: [0, 1, 2, 4],
                                className: "dt-head-center"
                            }
                        ],
                        order: [
                            [2, 'asc']
                        ],
                    });                    
                });
            </script>
        </div>
    </div>
<?php
}
