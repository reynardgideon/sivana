<?php
const FIELDS_PESERTA = [
    array(
        'title' => 'Nama Lengkap',
        'type' => 'text',
        'width' => '0.3'
    ),
    array(
        'title' => 'NIP',
        'type' => 'text',
        'width' => '0.2'
    ),
    array(
        'title' => 'Unit Kerja',
        'type' => 'text',
        'width' => '0.5'
    )
];

const FIELDS_DAFTAR_NILAI = [    
    array(
        'title' => 'Jenis Nilai',
        'type' => 'dropdown',
        'width' => '0.2',
        'source' => array(
            array(
                'id' => 'p',
                'name' => 'Nilai Kehadiran'
            ),
            array(
                'id' => 'q',
                'name' => 'Nilai Aktivitas'
            ),
            array(
                'id' => 'r',
                'name' => 'Nilai Ujian'
            ),
            array(
                'id' => 'k',
                'name' => 'Nilai Komprehensif'
            ),
            array(
                'id' => 'k2b1',
                'name' => 'Nilai Komprehensif (Form 2B-1)'
            ),
            array(
                'id' => 'pkl',
                'name' => 'Nilai PKL'
            )
        )
    ),

    array(
        'title' => 'Mata Pelajaran',
        'type' => 'dropdown',
    ),

    array(
        'title' => 'Pengajar',
        'type' => 'dropdown',
    ),

    array(
        'title' => 'Peserta',
        'type' => 'dropdown',
    )
    
];

const FIELDS_PELATIHAN = [
    array(
        'title' => 'Judul',
        'type' => 'text',
        'width' => '0.3'
    ),
    array(
        'title' => 'Jenis Pelatihan',
        'type' => 'dropdown',
        'width' => '0.1',
        'source' => array(
            array(
                'id' => 'pjj',
                'name' => 'PJJ'
            ),
            array(
                'id' => 'e-learning',
                'name' => 'E-Learning'
            ),
            array(
                'id' => 'klasikal',
                'name' => 'Klasikal'
            )
        )
    ),
    array(
        'title' => 'Mulai',
        'type' => 'calendar',
        'width' => '0.2',
        'options' => array(
            'format' => 'dd-mm-yyyy',
            'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            'weekdays' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
            'weekdays_short' => ['Sen', 'Sel', 'Ra', 'Ka', 'Ju', 'Sa', 'Mi']
        )
    ),
    array(
        'title' => 'Selesai',
        'type' => 'calendar',
        'width' => '0.2',
        'options' => array(
            'format' => 'dd-mm-yyyy',
            'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            'weekdays' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
            'weekdays_short' => ['Sen', 'Sel', 'Ra', 'Ka', 'Ju', 'Sa', 'Mi']
        )
    ),
    array(
        'title' => 'Jenis Evaluasi Level 2',
        'type' => 'dropdown',
        'width' => '0.2',
        'source' => array(
            array(
                'id' => 'ujian',
                'name' => 'Ujian'
            ),
            array(
                'id' => 'prepost',
                'name' => 'Pretest-Posttest'
            )
        )
    )
];

const FIELDS_PENGAJAR = [
    array(
        'title' => 'Nama Lengkap',
        'type' => 'text',
        'width' => '0.25'
    ),
    array(
        'title' => 'NIP',
        'type' => 'text',
        'width' => '0.15'
    ),
    array(
        'title' => 'Unit Kerja',
        'type' => 'text',
        'width' => '0.45'
    ),
    array(
        'title' => 'Nomor HP',
        'type' => 'text',
        'width' => '0.15'
    )
];

const FIELDS_PENGGUNA = [
    array(
        'title' => 'Nama Lengkap',
        'type' => 'text',
        'width' => '0.20'
    ),
    array(
        'title' => 'NIP',
        'type' => 'text',
        'width' => '0.15'
    ),
    array(
        'title' => 'Unit Kerja',
        'type' => 'text',
        'width' => '0.35'
    ),
    array(
        'title' => 'Nomor HP',
        'type' => 'text',
        'width' => '0.15'
    ),
    /*
    array(
        'title' => 'Group',
        'type' => 'dropdown',
        'width' => '0.15',
        'url' => 'https://evaluasi.knpk.xyz/wp-content/themes/evaluasi/data/group.php?type=dropdown',
        'autocomplete' => true,
        'multiple' => true
    ),
    */
];

const FIELDS_MATA_PELAJARAN = [
    array(
        'title' => 'Judul',
        'type' => 'text',
        'width' => '0.35'
    ),
    array(
        'title' => 'JP',
        'type' => 'text',
        'width' => '0.1'
    ),
    array(
        'title' => 'Jenis MP',
        'type' => 'dropdown',
        'width' => '0.15',
        'source' => array(
            array(
                'id' => 'pokok',
                'name' => 'Pokok'
            ),
            array(
                'id' => 'penunjang',
                'name' => 'Penunjang'
            ),
        )
    ),
    array(
        'title' => 'Diujikan',
        'type' => 'dropdown',
        'width' => '0.15',
        'source' => array(
            array(
                'id' => '0',
                'name' => 'Tidak'
            ),
            array(
                'id' => '1',
                'name' => 'Ya'
            )        
        )
    ),
    array(
        'title' => 'Pengajar',
        'type' => 'dropdown',
        'width' => '0.2',
        'url' => 'https://evaluasi.knpk.xyz/wp-content/themes/evaluasi/data/pengajar.php?type=dropdown',
        'autocomplete' => true,
        'multiple' => true
    ),
    array(
        'title' => 'Jadwal',
        'type' => 'dropdown',
        'width' => '0.1',
        'autocomplete' => true,
        'multiple' => true
    ),
];