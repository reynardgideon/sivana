<?php
include_once(get_template_directory() . '/getters/helpers.php');
//include_once(get_template_directory() . '/getters/pelatihan.php');

function page_rk_judul()
{
    $pod = pods('pelatihan', get_the_ID());

    //echo Pelatihan::get_tanggal_rapat_kelulusan(get_the_ID());
?>
    <div class="card m-0">
        <div class="card-body text-center" style="margin:50px auto; width:100%">
            <div class="p-3 mx-5 text-warning" style="background-color:rgba(0, 0, 0, 0.5); border-radius: 10px;">
                <h3 style="font-family: 'Helvetica Neue', sans-serif; font-size: 30px; font-weight: bold; letter-spacing: -1px; line-height: 1; text-align: center;">RAPAT KELULUSAN</h3>
                <h3><?= strtoupper(get_the_title()) ?></h3>
                <h4>(<?= Helpers::range_tanggal($pod->display('mulai'), $pod->display('selesai')) ?>)</h4>
            </div>
            <br />                      
            <br />
            <div class="text-primary">
            <h4 style="text-shadow: 3px 3px #000;"><?= Helpers::range_tanggal($pod->display('mulai'), $pod->display('selesai')) ?></h4>
                <h3 style="text-shadow: 3px 3px #000;" class="text-primary">PUSAT PENDIDIKAN DAN PELATIHAN</h3>
                <h3 style="text-shadow: 3px 3px #000;">KEKAYAAN NEGARA DAN PERIMBANGAN KEUANGAN</h3>
            </div>
        </div>
    </div>
    <style>
        .card {
            background: url("https://evaluasi.knpk.xyz/wp-content/uploads/2023/01/gettyimages-1202900062-612x612-1.jpg") no-repeat;
            border: 1px solid;
            background-size: cover;
        }
    </style>
<?php
}

?>