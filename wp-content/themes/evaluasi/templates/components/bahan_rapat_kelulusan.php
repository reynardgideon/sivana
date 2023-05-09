<?php
include_once(get_template_directory() . '/getters/pengajar.php');
function page_bahan_rapat_kelulusan()
{    
    $pod = pods('pelatihan', get_the_id());

    $mps = $pod->field('mata_pelajaran.ID');
    
    $sections = array(
        'mata_pelajaran',
        'syarat_kelulusan',
        'npr',
        'rekap_nilai',
        'rekap_kelulusan',
        'mengisi_kuesioner',
        'rekap_evaluasi_penyelenggaraan',
        'rekap_evaluasi_pengajar_dan_lainnya',
        'saran_masukan'
    );

    $links = [];

    foreach ($sections as $s) {
        if ($s == 'npr') {
            $links[] = get_the_permalink().'?section='.$s.'&id_mata_pelajaran='.$mps[0].'&content_only=true';
        } else {
            $links[] = get_the_permalink().'?section='.$s.'&content_only=true';
        }       
    }

    if (count(Pengajar::get_by_pelatihan(get_the_id())) > 0 ) {
        $links[] = get_the_permalink().'?section=saran_masukan_terkait_pengajar&content_only=true';
    }
    
    if (empty($pod->field('bahan_rapat_kelulusan'))) {
        $slide = pods('slide');
        $id = $slide->add(array(
            'pelatihan' => get_the_ID(),
            'slides' => $links
        ));
        echo '<a href="'.get_the_permalink($id).'"><h3>Bahan Rapat Kelulusan</h3></a>';
    } else {
        $slide = pods('slide', $pod->field('bahan_rapat_kelulusan.ID'));
        $slide->save(array(
            'pelatihan' => get_the_ID(),
            'slides' => $links
        ));
        echo '<a href="'.get_the_permalink($pod->field('bahan_rapat_kelulusan.ID')).'"><h3>Bahan Rapat Kelulusan</h3></a>';
    }
    
}

