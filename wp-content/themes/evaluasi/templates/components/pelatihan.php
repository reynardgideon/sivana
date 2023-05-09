<?php

include_once(get_template_directory() . '/getters/helpers.php');
include_once(get_template_directory() . '/templates/components/menu.php');

class Pelatihan_P
{
    public static function get_page()
    {
        $pelatihan = pods('pelatihan', get_the_id());
?>
        <div class="card">
            <div class="card-header has-text-centered">
                <h4>Info Pelatihan</h4>
            </div>
            <div class="card-content p-4">
            <div class="table-responsive">
                <table class="table table-striped table-responsive">
                    <tbody>
                        <?php foreach (FIELDS_PELATIHAN as $f) : ?>
                            <tr>
                                <th scope="row"><?= $f['title'] ?></th>
                                <td class="text-wrap">
                                    <?php
                                    $slug = strtolower(str_replace(' ', '_', $f['title']));
                                    if ($slug == 'mulai' || $slug == 'selesai') {
                                        echo Helpers::tanggal($pelatihan->display($slug, true));
                                    } else {
                                        echo $pelatihan->display($slug, true);
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                                </div>
            </div>
        </div>
        </div>
<?php }
}
