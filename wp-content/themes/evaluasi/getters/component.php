<?php

class Component
{
    public static function document_header()
    {
?>
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/paper-a4.css">
        <div class="columns" style="border-bottom: solid 2px #000;">
            <div class="column is-2">
                <figure class="image is-96x96">
                    <img src="http://zi.knpk.xyz/wp-content/uploads/2022/11/logo-kementerian-keuangan-356-1.png">
                </figure>
            </div>
            <div class="column is-10">
                <div class="has-text-centered has-text-weight-bold has-text-black" style="line-height:12pt;">
                    <p style="font-size:13pt;margin: 0 auto">KEMENTERIAN KEUANGAN REPUBLIK INDONESIA</p>
                    <p style="font-size:11pt;margin: 0 auto">BADAN PENDIDIKAN DAN PELATIHAN KEUANGAN</p>
                    <p style="font-size:11pt;margin: 0 auto">PUSAT PENDIDIKAN DAN PELATIHAN KEKAYAAN NEGARA DAN PERIMBANGAN KEUANGAN</p>
                </div>
                <div class="has-text-centered" style="line-height:9pt;margin-top:5pt;">
                    <p style="font-size:7pt;margin: 0 auto">GEDUNG SUDONO PURWODIHARDJO LANTAI 2, JALAN PURNAWARMAN NOMOR 99, KEBAYORAN BARU</p>
                    <p style="font-size:7pt;margin: 0 auto">JAKARTA, 12110; TELEPON (021) 7394696 EXT 8201, FAKSIMILE (021) 7244846, SITUS</p>
                    <p style="font-size:7pt;margin: 0 auto">http://www.bppk.kemenkeu.go.id</p>
                </div>
            </div>
        </div>
<?php
    }
}
