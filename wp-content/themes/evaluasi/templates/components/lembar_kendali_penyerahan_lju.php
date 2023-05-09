<?php
include_once(get_template_directory() . '/getters/helpers.php');

function lembar_kendali_penyerahan_lju($data, $action='')
{
    if ($action !== 'view') {
        $data['tanggal'] = '<input class="no-outline" type="text" name="tanggal" value="">';
        $data['tempat'] = '<input class="no-outline" type="text" name="tempat" value="">';
    }
?>
    <div>
        <div class="has-text-centered has-text-weight-bold mb-6 mt-4">
            <h3 class="has-text-weight-bold">LEMBAR KENDALI</h3>
            <h3 class="has-text-weight-bold">PENYERAHAN HASIL UJIAN DARI PEMERIKSA HASIL UJIAN</h3>
        </div>
        <p class="mt-4" style="text-indent: 50px;">Saya yang bertanda tangan di bawah ini menyatakan dengan sesungguhnya bahwa pada:</p>
        <div class="pl-6 py-2">
            <table style="border:none; width:100%;">
                <tbody>
                    <tr>
                        <td style="width:150px;">Hari/tanggal</td>
                        <td>:</td>
                        <td style="padding-left:5px;"><?= $data['tanggal'] ?></td>
                    </tr>
                    <tr>
                        <td>Tempat/via**)</td>
                        <td>:</td>
                        <td style="padding-left:5px;"><?= $data['tempat'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p>Telah diserahterimakan hasil ujian:</p>
        <div class="pl-6 py-2">
            <table style="border:none">
                <tbody>
                    <tr>
                        <td style="width:150px;">Pelatihan</td>
                        <td>:</td>
                        <td style="padding-left:5px;"><?= ' ' . $data['pelatihan'] ?></td>
                    </tr>
                    <tr>
                        <td>Mata pelajaran</td>
                        <td>:</td>
                        <td style="padding-left:5px;"><?= $data['mata_pelajaran'] ?></td>
                    </tr>
                    <tr>
                        <td>Jumlah</td>
                        <td>:</td>
                        <td style="padding-left:5px;"><?= $data['jumlah'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p>dari pengajar/pemeriksa kepada Kepala Bidang Penjaminan Mutu Pembelajaran dan Sertifikasi.</p>
        <p class="mt-2" style="text-indent: 50px;">Demikian Lembar Kendali serah terima ini dibuat.</p>
        <br />
        <p class="mt-6">Yang menyerahkan,<br />Pengajar/Pemeriksa Hasil Ujian</p>
        <form id="sign-form" method="POST" action="">
            <canvas id="sign-pad" class="pad" height="150" width="225" style="border:0px solid #ddd;"></canvas>
        </form>
        <p><?= $data['penanda_tangan'][0]->nama_lengkap ?></p>
    </div>
    <script>
        var sign = $('#sign-form').signaturePad({
            drawBezierCurves: true,
            drawOnly: true,
            displayOnly: true
        });

        sign.regenerate('<?= Helpers::get_specimen($data['penanda_tangan'][0]->id) ?>').off();
    </script>
<?php
}
