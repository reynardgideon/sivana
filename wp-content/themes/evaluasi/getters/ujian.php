<?php
class Ujian2
{
    public $daftar_nama = array();
    public $daftar_nip = array();
    public $submitted = array();
    public $id_ujian;
    public $tanggal;
    public $mata_pelajaran;
    public $pelatihan;
    public $pelatihan_id;
    public $mata_pelajaran_id;
    public $is_seb;
    public $status_lju = array();
    public $status_pakta = array();
    public $user_lju;
    public $user_pakta = '';
    public $pod;
    public $pengawas;
    public $pengamat;
    public $tes;
    public $siblings;
    public $durasi;
    public $links;

    function __construct($id)
    {
        $ujian = pods('ujian', $id);
        $this->id_ujian = $id;
        $this->pod = $ujian;

        $this->tanggal = $ujian->display('tanggal');
        $this->pelatihan = $ujian->display('pelatihan');
        $this->mata_pelajaran = $ujian->display('mata_pelajaran');
        $this->pelatihan_id = $ujian->field('pelatihan.ID');
        $this->mata_pelajaran_id = $ujian->field('mata_pelajaran.ID');
        $this->pengamat = $ujian->field('pengamat.ID');
        $this->pengawas = $ujian->field('pengawas.ID');
        $this->durasi = $ujian->field('durasi');

        $daftar_link = explode(PHP_EOL, $ujian->field('daftar_link'));

        foreach ($daftar_link as $link) {
            $this->links[] = explode('=', $link);
        }

        $nama = $ujian->field('pelatihan.peserta.nama_lengkap');
        $nip = $ujian->field('pelatihan.peserta.nip');

        for ($i = 0; $i < count($nip); $i++) {
            $this->daftar_nip[] = trim(preg_replace('/\s\s+/', ' ', $nip[$i]));
            $this->daftar_nama[] = trim(preg_replace('/\s\s+/', ' ', $nama[$i]));
        }

        if (!get_option('ujian-' . $ujian->field('ID'))) {
            $data = array();
            foreach ($nip as $i => $n) {
                $data[trim($n)] = '';
            }
            update_option('ujian-' . $ujian->field('ID'), $data);
        }
    }


    public function link_tree()
    {
?>
        <div class="mx-5 column is-half-desktop is-inline-block">
            <button id="kirim_jawaban" class="button is-fullwidth is-info is-rounded">
                <a href="<?= site_url() ?>/form/pakta-integritas/?id_pelatihan=<?= get_the_id() ?>">PAKTA INTEGRITAS</a>
            </button>
        </div>
        <?php foreach ($this->links as $link) : ?>
            <div class="mx-5 column is-half-desktop is-inline-block">
                <button id="kirim_jawaban" class="button is-fullwidth is-info is-rounded">
                    <a href="<?= trim($link[1]) ?>"><?= trim($link[0]) ?></a>
                </button>
            </div>
        <?php endforeach; ?>
        <div class="mx-5 column is-half-desktop is-inline-block">
            <button id="kirim_jawaban" class="button is-fullwidth is-info is-rounded">
                KIRIM JAWABAN
            </button>
        </div>
    <?php
    }


    public function main_page()
    {
    ?>
        <div id="main" class="columns is-centered">
            <div class="column is-three-quarters-desktop has-text-centered">
                <figure class="image mx-5 mb-10 is-inline-block">
                    <img src="https://eps.knpk.xyz/wp-content/uploads/2022/06/corpu2.png">
                </figure>
                <br />
                <h1 class="title mt-5 mx-5 is-uppercase">
                    <?= $this->pelatihan ?>
                </h1>
                <p class="title is-uppercase is-4 mx-5">
                    <?= $this->mata_pelajaran; ?>
                </p>
                <p class="subtitle mt-4 mx-5">
                    <?= $this->tanggal; ?>
                </p>

                <p class="subtitle mt-0 pt-0 mx-5">
                <p id="timer" class="has-text-dark my-0 py-0" style="font-size:90px;font-weight:bold;"><?= $this->durasi; ?></p>
                <p>menit</p>
                </p>
                <hr />
                <div class="px-6">
                    <div class="tabs is-boxed is-fullwidth is-medium mt-4">
                        <ul>
                            <li class="is-active tab-menu" data-id="Status_Peserta">
                                <a>
                                    <span class="icon is-small"><i class="mdi mdi-view-list" aria-hidden="true"></i></span>
                                    <span>Status Peserta</span>
                                </a>
                            </li>
                            <li class="tab-menu" data-id="Link_Tree">
                                <a>
                                    <span class="icon is-small"><i class="mdi mdi-file-document" aria-hidden="true"></i></span>
                                    <span>Link Tree</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div id="content pb-5">
                        
                        <div id="Link_Tree" class="tab-content is-hidden has-text-centered">
                            <?= $this->link_tree() ?>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
<?php
    }
}
