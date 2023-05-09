<?php
function page_syarat_kelulusan()
{
    $pod = pods('pelatihan', get_the_id());

    $na = (array) json_decode($pod->field('komponen_na'));

    $bobot = '';
    if ((int)$na['nt'] == 100) {
        $bobot = 'NA = 100% ΣNT';
    } else {
        $bobot = 'NA = ' . $na['nt'] . ' ΣNT + ' . $na['nk'] . ' NK';

        if ((int)$na['npkl'] !== 0) {
            $bobot .= $na['npkl'] . ' NPKL';
        }
    }
?>
    <div class="card">
        <div class="card-body">
            <h3 class="text-center">SYARAT KELULUSAN DAN RUMUS KELULUSAN</h3>
            <div class="container">
                <div class="row">
                    <div class="col-8">
                        <div class="my-4" style="font-size:20px;">
                            <h5 class="text-primary">Syarat Kelulusan</h5>
                            <ol>
                                <li>Nilai Presentasi (NPR) mata diklat pokok serendah-rendahnya 65 (enam puluh lima)</li>
                                <li>Nilai Presentasi (NPR) mata diklat penunjang serendah-rendahnya 60 (enam puluh)</li>
                                <li>Total Nilai Tertimbang (ƩNT) serendah-rendahnya 65 (enam puluh lima)</li>
                                <li>Nilai Akhir (NA) serendah-rendahnya 65 (enam puluh lima)</li>
                                <?php if ((int)$na['nk'] !== 0) : ?>
                                    <li>Nilai Komprehensif (NK) serendah-rendahnya 60 (enam puluh)</li>
                                <?php endif; ?>
                            </ol>
                        </div>
                        <div class="my-4" style="font-size:20px;">
                            <h5 class="text-primary">Rumus Kelulusan</h5>
                            <ul>
                                <li>NPR MP Tidak Diujikan = (30% x P) + (70% x Q)</li>
                                <li>NPR MP Diujikan = (10% x P) + (20% x Q) + (70% x R)</li>
                                <li>NT = (NPR x NP) / 100</li>
                                <li><?= $bobot ?></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="my-4" style="font-size:20px;">
                            <h5 class="text-primary">Keterangan</h5>
                            <ul>
                                <li>P : Kehadiran</li>
                                <li>Q : Penyelesaian Tugas dan/atau Aktivitas</li>
                                <li>R : Nilai Ujian</li>
                                <li>K : Nilai Ujian Komprehensif</li>
                                <li>NPR : Nilai Presentasi</li>
                                <li>NP : Nilai Patokan</li>
                                <li>NT : Nilai tertimbang</li>
                                <li>NA : Nilai Akhir</li>
                                <?php if ((int)$na['nk'] !== 0) : ?>
                                    <li>NK : Nilai Komprehensif</li>
                                <?php endif; ?>                                
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>