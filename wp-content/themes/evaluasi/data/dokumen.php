<?php

require_once("/var/www/sikad.knpk.xyz/wp-load.php");

$ka = pods('kajian_akademis', $_GET["id"]);
$fields = array(
    'Proposal Kajian Akademis' => 'file_proposal',
    'Hasil Seleksi Administrasi' => 'file_nd_hasil_seleksi_administrasi',
    'SK Seminar Proposal' => "file_sk_seminar_proposal",
    'Rekomendasi Perbaikan Proposal' => 'file_rekomendasi_perbaikan_proposal',
    'Revisi Proposal' => 'file_revisi_proposal',
    'SK Penelitian' => 'file_sk_penelitian',
    'ST Penelitian' => 'file_st_penelitian',
    'Draft Laporan Penelitian' => 'file_draft_laporan_penelitian',
    'Laporan Hasil Penelitian' => 'file_laporan_penelitian',
    'SK Seminar Hasil Penelitian' => 'file_sk_seminar_hasil_penelitian',
    'Rekomendasi Perbaikan Hasil Penelitian' => 'file_rekomendasi_perbaikan_hasil_penelitian',
    'Revisi Hasil Penelitian' => 'file_revisi_hasil_penelitian',
    'Sinopsis' => 'file_sinopsis',
    'Publikasi Kajian Akademis' => 'file_publikasi_kajian_akademis'
);

$doks = array();
$i = 1;
foreach ($fields as $l => $f) {
    if (!empty($ka->field($f . '.guid'))) {
        $doks[] = array(
            $i,
            '<a href="' . $ka->field($f . '.guid') . '" target="_blank">' . $l . '</a>'
        );
        $i++;
    }
}

foreach ($ka->field('dokumen.file') as $dok) {
    $doks[] = array(
        $i,
        '<a href="' . $dok['guid'] . '" target="_blank">' . $dok['nama_dokumen'] . '</a>'
    );
    $i++;
}

$data = array(
    "data" => $doks
);


echo json_encode($data);
