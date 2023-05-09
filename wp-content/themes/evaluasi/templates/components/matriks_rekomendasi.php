<?php

function page_matriks_rekomendasi()
{
    $pod = pods('pelatihan', get_the_ID());

    $ev_1 = (array)json_decode($pod->field('rekap_evaluasi_level_1'));

    $params = array(
        'limit' => -1,
        'where' => "pelatihan.ID = '" . get_the_ID() . "' AND matriks_rekomendasi.meta_value=1",
        'orderby' => 'tujuan ASC'
    );

    $saran = pods('saran', $params);

    $total = $ev_1['responden'];

    $i = 1;
    $data = [];
    if ($total > 0) {
        while ($saran->fetch()) {

            if ($saran->field('aktif') == 1) {
                $index = $i - 1;
                $item = array(
                    $saran->field('ID'),
                    $saran->field('tujuan'),
                    '<input type="checkbox" data-isi="' . $saran->field('isi') . '" class="check_item" value="' . $saran->field('ID') . '" data-indeks="' . $index . '">',
                    $i . '.',
                    $saran->field('frekuensi'),
                    (string) number_format($saran->field('frekuensi') / $total * 100, 1, ",", ".") . '%',
                    //number_format($saran->field('persen'), 1, ",", ".") . '%',
                    '<a href="#" class="lihat_detail" data-indeks="' . $index . '">' . $saran->field('isi') . '</a>',
                    $saran->field('analisis') == '' ? '-' : $saran->field('analisis'),
                    $saran->field('tanggapan') == '' ? '-' : $saran->field('tanggapan'),
                    $saran->field('uraian_rekomendasi') == '' ? '-' : $saran->field('uraian_rekomendasi'),
                    $saran->field('tindak_lanjut') == '' ? '-' : $saran->field('tindak_lanjut'),
                    $saran->field('target_waktu_penyelesaian') == '' ? '-' : $saran->field('target_waktu_penyelesaian')
                );
                $data[] = $item;
                $i++;
            }
        }
    }

    $form = '<form>
    <div class="form-group">
      <label for="recipient-name" class="col-form-label">Recipient:</label>
      <input type="text" class="form-control" id="recipient-name">
    </div>
    <div class="form-group">
      <label for="message-text" class="col-form-label">Message:</label>
      <textarea class="form-control" id="message-text"></textarea>
    </div>
  </form>';

?>

    <style>
        .tabel_matriks,
        .tabel_matriks a {
            width: 100%;
            font-size: 20px;
        }

        .text-wrap {
            white-space: normal;
        }

        .form-control {
            font-size: 20px;
        }

        .form-group label {
            font-size: 20px;
            font-weight: bold;
        }

        .mlabel {
            font-size: 20px;
            font-weight: bold;
            margin: 0px;
        }

        .mtext {
            font-size: 20px;
            margin: 0px;
            text-align: justify;
        }

        .modal-content {
            padding: 20px;
        }

        @media screen and (min-width: 676px) {
            .modal-vlg {
                max-width: 80%;
                /* New width for default modal */
            }
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/af-2.4.0/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/fc-4.1.0/fh-3.2.4/sc-2.0.7/sr-1.1.1/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/af-2.4.0/b-2.2.3/b-html5-2.2.3/b-print-2.2.3/fc-4.1.0/fh-3.2.4/sc-2.0.7/sr-1.1.1/datatables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.1/css/fixedHeader.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.3.1/js/dataTables.fixedHeader.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.0/css/rowGroup.dataTables.min.css">
    <script src="https://cdn.datatables.net/rowgroup/1.3.0/js/dataTables.rowGroup.min.js"></script>
    <div class="card" id="sagara">
        <div class="card-header has-background-kmk-mix has-text-centered">
            <h5>MATRIKS REKOMENDASI</h5>
            <div class="card-header-right">
                <i id="ubah_saran" class="fa fa-pencil" title="Ubah"></i>
                <i id="hapus_saran" class="fa fa-trash" title="Hapus"></i>
            </div>
        </div>
        <div class="card-content p-4">
            <div class="container">
                <table id="tabel_sagara" class="display nowrap tabel_matriks"></table>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalTitle" aria-hidden="true">
        <div class="modal-dialog .modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalTitle">ERROR</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="errorModalContent" class="modal-body">
                    Maaf, anda belum memilih item!
                </div>
                <div class="modal-footer" id="modalButtons">
                    <button type="button" class="cancelButton btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="hapusModal" tabindex="-1" role="dialog" aria-labelledby="hapusModalTitle" aria-hidden="true">
        <div class="modal-dialog .modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hapusModalTitle">HAPUS ITEM</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="hapusModalContent" class="modal-body">
                </div>
                <div class="modal-footer" id="modalButtons">
                    <button data-confirm="cancel" type="button" class="cancelButton btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button id="confirm_hapus" style="width:120px;" type="button" class="btn btn-primary confirmButton" data-dismiss="modal">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalTitle" aria-hidden="true">
        <div class="modal-dialog .modal-dialog-centered modal-vlg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="formModalTitle">UBAH MATRIKS REKOMENDASI</h5> 
                    <h5 id="headerIndex" class="text-warning">1</h5>                   
                    <div>
                        <i style="cursor: pointer;" id="edit_rekomendasi" class="button fa fa-pencil mr-3" title="Ubah" data-indeks=""></i>
                        <i style="cursor: pointer;" class="cancelButton fa fa-times" title="Tutup"></i>
                    </div>
                </div>
                <div id="modal_matriks"></div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var data = <?= json_encode($data) ?>;
            var field = [{
                    title: 'Frekuensi',
                    slug: 'frekuensi',
                    indeks: 4
                },
                {
                    title: 'Persen',
                    slug: 'persen',
                    indeks: 5
                },
                {
                    title: 'Tujuan',
                    slug: 'tujuan',
                    indeks: 1
                },
                {
                    title: 'Uraian',
                    slug: 'uraian',
                    indeks: 6
                },
                {
                    title: 'Analisis Masukan',
                    slug: 'analisis',
                    indeks: 7
                },
                {
                    title: 'Masukan Saat Rapat Kelulusan',
                    slug: 'tanggapan',
                    indeks: 8
                },
                {
                    title: 'Uraian Rekomendasi',
                    slug: 'uraian_rekomendasi',
                    indeks: 9
                },
                {
                    title: 'Tindak Lanjut',
                    slug: 'tindak_lanjut',
                    indeks: 10
                },
                {
                    title: 'Target Waktu Penyelesaian',
                    slug: 'target_waktu_penyelesaian',
                    indeks: 11
                }
            ];            

            $('#tabel_sagara').DataTable({
                data: data,
                fixedHeader: {
                    header: true,
                    footer: true,
                },
                columns: [{
                        title: 'ID'
                    },
                    {
                        title: 'Tujuan'
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
                    },
                    {
                        title: 'Analisis Masukan'
                    },
                    {
                        title: 'Masukan Saat Rapat Kelulusan'
                    },
                    {
                        title: 'Uraian Rekomendasi'
                    },
                    {
                        title: 'Tindak Lanjut'
                    },
                    {
                        title: 'Target Waktu Penyelesaian'
                    }
                ],
                columnDefs: [{
                        render: function(data, type, full, meta) {
                            return "<div class='text-justify text-wrap pr-3'>" + data + "</div>";
                        },
                        targets: [6, 7, 8, 9, 10, 11]
                    },
                    {
                        targets: [0],
                        visible: false,
                        searchable: false,
                    },
                    {
                        visible: false,
                        targets: 1
                    },
                    {
                        className: "dt-center",
                        targets: "_all"
                    },
                    {
                        width: 800,
                        targets: 6
                    },
                    {
                        width: 800,
                        targets: [6, 7]
                    },
                ],
                pageLength: 50,
                order: [
                    [1, 'asc']
                ],
                dom: 'Bfrtip',
                buttons: ['copy',
                    'excel',
                ],
                scrollX: true,
                scrollY: '70vh',
                scrollCollapse: true,
                rowGroup: {
                    dataSrc: 1
                },
            });

            var checked_ids = [];
            var ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>';

            $('table').on('change', '.check_item', function() {
                checked_ids = [];
                $(".check_item:checked").each(function() {
                    checked_ids.push($(this).val());
                });
            });

            $(".check_all").change(function() {
                if (this.checked) {
                    $('.check_item').prop('checked', true);
                    checked_ids = [];
                    $(".check_item:checked").each(function() {
                        checked_ids.push($(this).val());
                    });
                } else {
                    $('.check_item').prop('checked', false);
                }
            });

            $.fn.viewRekomendasi = function(i) {
                $('#formModalTitle').text('Matriks Rekomendasi');
                $('#edit_rekomendasi').data('indeks', i);
                $('#headerIndex').text(i + 1);
                let content_f = '';
                let content_l = '';
                field.forEach(function(v) {
                    if (v.slug == 'tujuan' || v.slug == 'frekuensi' || v.slug == 'persen') {
                        content_f += '<div class="col-sm p-0">';
                        content_f += '<p class="mlabel">' + v.title + '</p> <p class="mtext">' + data[i][v.indeks] + '</p>';
                        content_f += '</div>';
                    } else {
                        let text = '';
                        if (v.slug == 'uraian') {
                            text = $(data[i][v.indeks]).text();
                        } else {
                            text = data[i][v.indeks] == '' ? '-' : data[i][v.indeks];
                        }
                        content_l += '<div class="mt-3">';
                        content_l += '<p class="mlabel">' + v.title + '</p>';
                        content_l += '<p class="mtext" style="white-space: pre-wrap;">' + text + '</p>';
                        content_l += '</div>';
                    }
                });

                let disable_next = i == data.length - 1 ? ' disabled' : '';
                let disable_prev = i == 0 ? ' disabled' : '';

                let next = i == data.length? i : i + 1;
                let prev = i == 0 ? 0 : i - 1;

                let content = '<div class="container">';

                let j = i + 1;
                content += '<div class="row mx-1 mt-3">' + content_f + '</div>';
                content += content_l;
                content += '<hr/><div class="text-center">'
                content += '<button data-indeks="'+prev+'" type="button" class="btn btn-dark next_prev"'+disable_prev+'>Previous</button>';
                content += '<span style="font-size:20px; font-weight: bold; margin: 0 20px;">'+j+'</span>';
                content += '<button data-indeks="'+next+'" type="button" class="btn btn-dark next_prev"'+disable_next+'>Next</button>';
                content += '</div>'
                content += '</div>';

                $('#modal_matriks').html(content);

                $('#formModal').modal('show');
            }

            $('.lihat_detail').click(function() {
                let i = $(this).data("indeks");
                $.fn.viewRekomendasi(i);
            });

            $('#modal_matriks').on('click', '.next_prev', function() {            
                let i = $(this).data("indeks");
                $.fn.viewRekomendasi(i);
            });

            $("#hapus_saran").click(function() {
                if (checked_ids.length == 0) {
                    $('#errorModal').modal('show');
                } else {
                    let list = '<ol>';
                    $(".check_item:checked").each(function() {
                        list = list + '<li>' + $(this).data('isi') + '</li>';
                    });
                    list = list + '</ol>';
                    $('#hapusModalContent').html('Apakah anda yakin ingin menghapus item berikut: ' + list);
                    $('#hapusModal').modal('show');
                }
            });

            $.fn.editRekomendasi = function(j) {
                $('#formModalTitle').text('Ubah Matriks Rekomendasi');
                $('#edit_rekomendasi').hide();
                $('#headerIndex').text(j + 1);
                    let content_f = '';
                    let content_l = '';
                    field.forEach(function(v) {
                        if (v.slug == 'tujuan' || v.slug == 'frekuensi' || v.slug == 'persen') {

                            input = '<input type="text" class="form-control" name="' + v.slug + '" value="' + data[j][v.indeks] + '">';

                            content_f += '<div class="col-sm form-group p-0">';
                            content_f += '<p class="mlabel">' + v.title + '</p><p class="mtext">' + input + '</p>';
                            content_f += '</div>';
                        } else {
                            let text = '';
                            if (v.slug == 'uraian') {
                                text = '<textarea class="form-control" rows="5" name="isi">' + $(data[j][v.indeks]).text() + '</textarea>';
                            } else {
                                text = data[j][v.indeks] == '-' ? '' : data[j][v.indeks];
                                text = '<textarea class="form-control" rows="5" name="' + v.slug + '">' + text + '</textarea>';
                            }
                            content_l += '<div class="mt-3 form-group">';
                            content_l += '<p class="mlabel">' + v.title + '</p>';
                            content_l += '<p class="mtext">' + text + '</p>';
                            content_l += '</div>';
                        }
                    });

                    let content = '<div id="form_saran" class="container">';
                    content += '<form id="update_saran">';
                    content += '<div class="row mx-1 mt-3">' + content_f + '</div>';
                    content += content_l;
                    content += '<input type="hidden" name="action" value="update_rekomendasi">';
                    content += '<input id="id" type="hidden" name="id" value="' + data[j][0] + '">';
                    content += '</form>';
                    content += '<div class="text-center">';
                    content += '<button data-confirm="cancel" type="button" class="cancelButton btn btn-danger mx-1" data-dismiss="modal">Cancel</button>';
                    content += '<button id="confirm_form" data-confirm="ok" type="button" style="width:120px;" class="confirmButton btn btn-primary mx-1" data-dismiss="modal">Submit</button>';
                    content += '</div></div>';

                    $('#modal_matriks').html(content);

                    $('#formModal').modal('show');
            }

            $('#edit_rekomendasi').click(function() {
                $.fn.editRekomendasi($(this).data('indeks'));
            });

            $("#ubah_saran").click(function() {
                if (checked_ids.length == 0) {
                    $('#errorModal').modal('show');
                } else {
                    let j = $(".check_item:checked").data('indeks');
                    $.fn.editRekomendasi(j);
                }
            });

            $("#tambah_saran").click(function() {
                $('#frekuensi').val('');
                $('#saran').val('');
                $('#formModal').modal('show');
            });

            $(".close").click(function() {
                $('.modal').modal('hide');
            });

            $(".cancelButton").click(function() {
                $('.modal').modal('hide');
            });

            $('#modal_matriks').on('click', '.cancelButton', function() {
                $('.modal').modal('hide');
            });

            $('#modal_matriks').on('click', '#confirm_form', function() {
                $(this).html('<i class="fa fa-spinner fa-spin"></i>');

                var data = $('#update_saran').serialize();

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.status == 1) {
                            $("#confirm_form").html('Submit');

                            $('.modal').modal('hide');

                            tata.success(response.message, '', {
                                position: 'tm',
                                duration: 2000
                            });

                            location.reload();

                        } else {
                            tata.error(response.message, '', {
                                position: 'tm',
                                duration: 2000
                            });
                        }
                    }
                });
            });

            $("#confirm_hapus").click(function() {

                $(this).html('<i class="fa fa-spinner fa-spin"></i>');

                var data = {
                    action: 'remove_saran',
                    kategori: 'penyelenggaraan',
                    pelatihan: <?= get_the_ID() ?>,
                    ids: checked_ids
                };

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        if (response.status == 1) {
                            $("#confirm_hapus").html('Submit');

                            $('.modal').modal('hide');

                            tata.success(response.message, '', {
                                position: 'tm',
                                duration: 2000
                            });

                            location.reload();

                        } else {
                            tata.error(response.message, '', {
                                position: 'tm',
                                duration: 2000
                            });
                        }
                    }
                });
            });

        });
    </script>
    <!--
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/4.2.1/js/dataTables.fixedColumns.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.colVis.min.js"></script>
<style>
    table {
        table-layout: fixed;
    }

    table td {
        word-wrap: break-word;
        max-width: 400px;
    }

    #evagara td {
        white-space: inherit;
    }
</style>
<div class="card">
    <div class="card-body">
        <h4 class="text-center">MATRIKS REKOMENDASI</h4><br />
        <h4 class="text-center"><?= strtoupper(get_the_title()) ?></h4>
        <div class="container">
            <table id="evagara" class="display nowrap" style="width:100%; font-size:20px;">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Bidang</th>
                        <th>Frek.</th>
                        <th>Persen</th>
                        <th>Masukan Peserta</th>
                        <th>Analisis</th>
                        <th>Masukan Saat Rapat Kelulusan</th>
                        <th>Uraian Rekomendasi</th>
                        <th>Tindak Lanjut</th>
                        <th>Target Penyelesaian</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Renbang</td>
                        <td>4</td>
                        <td>14%</td>
                        <td><span style="white-space:normal">Waktu PKL harap diperpanjang</span></td>
                        <td><span style="white-space:normal">Agar menjadi catatan dan didiskusikan saat rapat desain pengembangan program</span></td>
                        <td></td>
                        <td>Tidak Ada</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Renbang</td>
                        <td>3</td>
                        <td>11%</td>
                        <td><span style="white-space:normal;width:200px;">Diharapkan ada pelatihan lanjutan</span></td>
                        <td><span style="white-space:normal;width:200px;">Agar menjadi catatan dan didiskusikan saat rapat desain pengembangan program</span></td>
                        <td><b>Nugroho</b>: Mungkin perlu diklat khusus untuk struktural</td>
                        <td>Tidak Ada</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Renbang</td>
                        <td>2</td>
                        <td>7%</td>
                        <td><span style="white-space:normal">Ke depan kalau pandemi sudah hilang dari indonesia, saran saya pelatihan di adakan secara luring agar peserta lebih fokus</span></td>
                        <td><span style="white-space:normal">Agar menjadi catatan dan didiskusikan saat rapat desain pengembangan program</span></td>
                        <td>
                            <b>Nugroho</b>: Perlu dipilah kembali materi-materi yang perlu dilaksanakan secara luring, terutama yang bersifat teknis<br />
                            <b>Puspa</b>: Diskusi akan menjadi lebih baik bila bisa dilaksanakan secara luring<br />
                            <b>Jaja</b>: Peserta terkendala penugasan di kantor ketika pelatihan dilaksanakan secara daring
                        </td>
                        <td>Tidak Ada</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Penyelenggaraan</td>
                        <td>1</td>
                        <td>4%</td>
                        <td><span style="white-space:normal">Dalam PKL agar ditambahkan materi fun game untuk merilekskan peserta</span></td>
                        <td><span style="white-space:normal">Agar menjadi catatan disampaikan kepada pengajar</span></td>
                        <td></td>
                        <td>Tidak Ada</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Penyelenggaraan</td>
                        <td>1</td>
                        <td>4%</td>
                        <td><span style="white-space:normal">Dalam pelaksanaan PKL saya sarankan agar koordinator lapangan dapat menjelaskan ulang antara teori dengan praktek di lapangan. Sehingga peserta tidak kebingungan yang harus dikerjakan dilapangan</span></td>
                        <td><span style="white-space:normal">Agar manjadi catatan untuk didiskusikan kembali terkait teknis pelaksanaan PKL untuk perbaikan kedepannya</span></td>
                        <td>
                            <b>Umardani</b>: Bisa direview kembali poin-poin yang terkait langsung dengan PKL.<br />
                            <b>Koko</b>: Perlu pemantapan materi 1 hari di awal PKL.
                        </td>
                        <td>Tidak Ada</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Penyelenggaraan</td>
                        <td>1</td>
                        <td>4%</td>
                        <td><span style="white-space:normal">Agar dapat di informasikan ke daerah sebelum penyusunan anggaran, sehingga dapat di akomodir dalam DPA awal</span></td>
                        <td><span style="white-space:normal">Agar menjadi catatan dan didiskusikan saat penyusunan rencana program maupun saat rapat persiapan</span></td>
                        <td><b>Jaja</b>: Sebaiknya memang informasi juga bisa disampaikan langsung ke daerah karena perlu persiapan terkait anggaran, dan menyesuaikan dengan volume pekerjaan</td>
                        <td>Tidak Ada</td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>7</td>
                        <td>Penyelenggaraan</td>
                        <td>1</td>
                        <td>4%</td>
                        <td><span style="white-space:normal">Untuk penyelenggara sebaiknya memberikan informasi sampai ke pemerintah daerah</span></td>
                        <td><span style="white-space:normal">Agar menjadi catatan dan didiskusikan saat penyusunan rencana program maupun saat rapat persiapan</span></td>
                        <td></td>
                        <td>Tidak Ada</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>Penyelenggaraan</td>
                        <td>1</td>
                        <td>4%</td>
                        <td><span style="white-space:normal">Pengajar strategi optimalisasi pengelolaan BMD kiranya dapat memahami masalah-masalah yang dialami oleh pengelola BMD di daerah sehingga dapat memberikan lebih banyak trik/strategi pada peserta terkait masalah yang dialami</span></td>
                        <td><span style="white-space:normal">Agar menjadi catatan untuk disampaikan kepada pengajar dan menjadi bahan referensi untuk pemilihan pengajar pada pelatihan selanjutnya.</span></td>
                        <td></td>
                        <td>Tidak Ada</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td>Penyelenggaraan</td>
                        <td>1</td>
                        <td>4%</td>
                        <td><span style="white-space:normal">Pemateri dari kemendagri harus lebih berbobot lagi, dan materinya lebih aplikatif</span></td>
                        <td><span style="white-space:normal">Agar menjadi catatan dan disampaikan kepada pengajar</span></td>
                        <td></td>
                        <td>Tidak Ada</td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#evagara').DataTable({
            pageLength: 50,
            order: [],
            scrollX: true,
            scrollY: 450,
            columnDefs: [{
                width: 650,
                targets: [4, 5]
            }],
            dom: 'Bfrtip',
            buttons: [
                'colvis'
            ]
        });
    });
</script>
-->

<?php } ?>