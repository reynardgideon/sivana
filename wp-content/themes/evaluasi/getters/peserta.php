<?php

class Peserta
{
    public static function get_data($id_pelatihan = null)
    {
        $pelatihan = pods('pelatihan', $id_pelatihan);
        $data = [];
        $ids = $pelatihan->field('peserta.nip');
        $namas = $pelatihan->field('peserta.nama_lengkap');
        $coding = (array) json_decode($pelatihan->field('coding'));
        for ($i = 0; $i < count($pelatihan->field('peserta.ID')); $i++) {
            $data[] = array(
                'nama_lengkap' => $namas[$i],
                'nip' => $ids[$i],
                'code' => $coding[$ids[$i]],
                'nilai' => ''
            );
        }
        return $data;
    }


    public static function get_table($id_pelatihan)
    {
        $data = 'https://evaluasi.knpk.xyz/wp-content/themes/evaluasi/data/peserta.php?id_pelatihan=' . $id_pelatihan;
?>
        <table id="peserta" class="display nowrap" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><input type="checkbox" class="check_all"></th>
                    <th>#</th>
                    <?php foreach (constant('FIELDS_' . strtoupper('peserta')) as $f) : ?>
                        <th><?= $f['title'] ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>

        </table>

        <script>
            $(document).ready(function() {
                var table = $('#peserta').DataTable({
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
}
