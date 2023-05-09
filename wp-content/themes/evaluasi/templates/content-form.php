<?php
$id = get_the_id();
$id_pelatihan = isset($_GET['id_pelatihan']) ? $_GET['id_pelatihan'] : null;
$form = pods('form', $id);
include_once(get_template_directory() . '/getters/daftar-nilai.php');
include_once(get_template_directory() . '/getters/mata-pelajaran.php');
include_once(get_template_directory() . '/getters/pengajar.php');
include_once(get_template_directory() . '/getters/peserta.php');

$pod_name = str_replace('-', '_', $form->display('post_name'));

?>

<div class="card">
    <div class="card-header has-background-kmk-mix has-text-centered">
        <h5>DAFTAR NILAI</h5>
        <div class="card-header-right">
            <a href="">
                <i class="ti ti-angle-double-left" title="Kembali"></i>
            </a>
        </div>
    </div>
    <div class="card-content p-4">
        <form id="form_<?= $pod_name ?>" action="" method="POST" class="needs-validation">
            <?php foreach ($form->field('fields.ID') as $f) : ?>
                <?php
                $field = pods('form_field', $f);
                $name = $field->field('post_name');
                $label = $field->field('judul');
                $config = json_decode($field->field('konfigurasi') == '' ? '' : $field->field('konfigurasi'));
                ?>
                <div class="form-group">
                    <label for="<?= $name ?>"><?= $label ?></label>
                    <?php if ($field->field('tipe') == 'short') : ?>
                        <input name="<?= $name ?>" value="" type="text" class="form-control" id="<?= $name ?>" required>
                    <?php elseif ($field->field('tipe') == 'dropdown' || $field->field('tipe') == 'multiple_choice') : ?>
                        <select id="<?= $name ?>" name="<?= $name ?>" class="form-control selectpicker" data-live-search="true" data-dropup-auto="false" required<?= $field->field('tipe') == 'multiple_choice' ? ' multiple' : '' ?>>
                            <?php foreach (call_user_func($config->source, $id_pelatihan) as $k => $v) : ?>
                                <option value="<?= $k ?>"><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php elseif ($field->field('tipe') == 'checkbox') : ?>
                        <div class="form-check ml-4">
                            <input class="form-check-input" type="checkbox" id="<?= $name ?>" name="<?= $name ?>" value="1">
                            <label class="form-check-label">Ya</label>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <div class="form-group">
                <label for="peserta">Peserta</label>
            </div>
            <?php if ($pod_name == 'daftar_nilai') : ?>
                <div id="peserta" class="mb-5"></div>
            <?php endif; ?>
            <button style="width:100px;" id="back" type="button" class="btn btn-danger mx-2">Cancel</button>
            <button style="width:100px;" id="submit_button" type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>

<?php if ($pod_name == 'daftar_nilai') : ?>
    <script src="https://cdn.jsdelivr.net/npm/handsontable@12.1/dist/handsontable.full.min.js"></script>
    <link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@12.1/dist/handsontable.full.min.css" />

    <style>
        .handsontable span.colHeader {
            font-weight: bold;
            font-size: 14px;
            line-height: 30px;
        }

        .handsontable table {
            font-size: 14px;
        }

        .handsontable .middle {
            vertical-align: middle;
        }

        .table .left {
            text-align: left;
        }
    </style>

    <script>
        const container = document.querySelector('#peserta');

        const hot = new Handsontable(container, {
            data: <?= json_encode(Peserta::get_data($id_pelatihan)) ?>,
            width: "80%",
            columns: [{
                    data: 'nama_lengkap',
                    className: "htMiddle htLeft nama_lengkap",
                    readOnly: true,
                }, {
                    data: 'id',
                    readOnly: true
                },
                {
                    data: 'code',
                    readOnly: true
                }
            ],
            className: 'htMiddle htCenter',
            colHeaders: ['Nama', 'NIP', 'Code'],
            rowHeights: 40,
            manualRowResize: true,
            rowHeaders: true,
            columnSorting: true,
            height: 'auto',
            contextMenu: true,
            afterGetRowHeader: function(col, TH) {
                TH.className = 'middle'
            },
            licenseKey: 'non-commercial-and-evaluation'
        });

        $(document).ready(function() {
            var ajaxurl = '<?= get_site_url() . '/wp-admin/admin-ajax.php' ?>';

            $('#form_<?= $pod_name ?>').on('submit', function(e) {
                e.preventDefault();
                var data = {
                    id: <?= $id ?>,
                    id_pelatihan: <?= $id_pelatihan ?>,
                    action: '<?= $pod_name ?>_editor'
                };
                data = data.concat($('#form_<?= $pod_name ?>').serializeArray());
                alert(data.serialize());
            });
        });
    </script>

<?php endif; ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>