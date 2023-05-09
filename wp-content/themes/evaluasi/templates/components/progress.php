<?php

function get_progress_page()
{
    $ka = pods('kajian_akademis', get_the_id());

    $group = pods('_pods_group', $ka->display('progres_terakhir'));

    if (get_next_action($group->display('menu_order')) !== $group->display('menu_order')) {
        $next = get_next_action($group->display('menu_order'));
    } else {
        $next = 'selesai';
    }

    if ($group->exists()) {
        $total = get_next_action($group->display('menu_order'), true);

        $args = array(
            'limit' => $total,
            'where' => 'post_parent = 75',
            'offset' => 1,
            'orderby' => 'menu_order ASC'
        );
        $pod = pods('_pods_group', $args);
        $skip = [6, 7, 8, 20, 21, 22];
        $i = 1;
        $j = 1;
        $actions = array();
        while ($pod->fetch()) {
            if (!in_array($pod->display('menu_order'), $skip)) {
                $split = explode("=>", $pod->display('post_title'));
                $date = json_decode($ka->field('tanggal_input'));

                $action = $pod->display('post_name');

                $fields = get_fields_by_group($pod->display('post_name'));

                $role = strtolower(trim($split[0]));

                $owner = array();

                if ($role == 'pengkaji') {
                    foreach (array($ka->field('pengkaji_1.ID'), $ka->field('pengkaji_2.ID')) as $pengkaji) {
                        if (!empty($pengkaji)) {
                            $owner[] = $pengkaji;
                        }
                    }
                } else if ($role == 'pembimbing') {
                    $owner = [$ka->field('pembimbing_metodologi.ID'), $ka->field('pembimbing_substansi.ID')];
                } else {
                    $owner = [41, 1];
                }

                if ($next == $pod->display('post_name')) {
                    $action_date = '';
                    $role = 'waiting';
                    if (in_array(get_current_user_id(), $owner)) {
                        $data = '<div class="has-text-centered"><a id="next_progress" class="button is-info my-5" href="#">PROSES</a></div>';
                    } else {
                        $data = "<div class='notification is-warning is-light my-5 subtitle is-5 has-text-centered'>" . STEP_MESSAGE[$j] . '</div>';
                    }
                } else {
                    $action_date = $date->$action;

                    $data = array();
                    foreach ($fields as $field) {
                        if (!empty($ka->field($field))) {
                            $label = ucwords(str_replace('_', ' ', $field));
                            if (strpos($label, 'File') !== false) {

                                //$files .= '<li><a class="documents" href="' . $file['guid'] . '" target="_new">' . $file['post_title'] . '</a></li>';
                                $char = is_mobile() ? 15 : 40;
                                $files = '
                                <div class="file has-name is-fullwidth">
                                    <label class="file-label">
                                        <span class="file-cta">
                                        <span class="file-icon">
                                            <i class="mdi mdi-24px mdi-file-download"></i>
                                        </span>                                            
                                        </span>
                                        <span class="file-name">
                                        <a class="documents" href="' . $ka->field($field . '.guid') . '" target="_new">' . substr($ka->display($field . '.post_title'), 0, $char) . '...</a>
                                        </span>
                                    </label>
                                </div>';

                                /*
                                foreach ($ka->field($field) as $file) {
                                    //$files .= '<li><a class="documents" href="' . $file['guid'] . '" target="_new">' . $file['post_title'] . '</a></li>';
                                    $char = is_mobile() ? 15 : 40;
                                    $files .= '
                                    <div class="file has-name is-fullwidth">
                                        <label class="file-label">
                                            <span class="file-cta">
                                            <span class="file-icon">
                                                <i class="mdi mdi-24px mdi-file-download"></i>
                                            </span>                                            
                                            </span>
                                            <span class="file-name">
                                            <a class="documents" href="' . $file['guid'] . '" target="_new">' . substr($file['post_title'], 0, $char) . '...</a>
                                            </span>
                                        </label>
                                    </div>';
                                }
                                */
                                $label = 'Document';
                                $value = $files;
                            } else if (strpos($label, 'Pengkaji') !== false || strpos($label, 'Pembimbing') !== false || strpos($label, 'Penguji') !== false) {
                                $id = $ka->field($field . '.ID');
                                $user = pods('user', $id);
                                $value = '<div class="file has-name is-fullwidth">
                                <label class="file-label">
                                    <span class="file-cta">
                                    <span class="file-icon">
                                        <i class="mdi mdi-24px mdi-account"></i>
                                    </span>
                                    
                                    </span>
                                    <span class="file-name">
                                    <a href="' . get_author_posts_url($user->display('ID')) . '" target="_blank">' . $user->display('nama_lengkap') . '</a>
                                    </span>
                                </label>
                            </div>';
                                //$value = '<a href="' . get_author_posts_url($user->display('ID')) . '" target="_blank">' . $user->display('nama_lengkap') . '</a>';
                            } else {
                                $value = $ka->display($field);
                            }
                            $data[$label] = $value;
                        }
                    }
                }

                $params = array(
                    'role'  => $role,
                    'order' => $j,
                    'title' => trim($split[1]),
                    'owner' => $owner,
                    'date'  => $action_date
                );
                $actions[] = array(
                    'params' => $params,
                    'data'  => $data
                );
                $j++;
            }
            $i++;
        }
    } else {
        echo ERROR_MESSAGE;
    }
?> <div class="card">
        <header class="card-header has-background-kmk-mix has-text-centered">
            <p class="card-header-title has-text-grey-dark">
                DETAIL KAJIAN AKADEMIS
            </p>
        </header>
        <div class="card-content">
            <table class="table is-striped">
                <tr>
                    <th>
                        Judul
                    </th>
                    <td>
                        <?= get_the_title() ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        Pengkaji
                    </th>
                    <td>
                        <?php
                        $i = 1;
                        foreach (array($ka->field('pengkaji_1.ID'), $ka->field('pengkaji_2.ID')) as $pengkaji) {
                            $user = pods('user', $pengkaji);
                            if ($user->exists()) {
                                if ($i == 2) {
                                    echo ' dan <a href="' . get_author_posts_url($pengkaji) . '">' . $user->display('nama_lengkap') . '</a>';
                                } else {
                                    echo '<a href="' . get_author_posts_url($pengkaji) . '">' . $user->display('nama_lengkap') . '</a>';
                                }
                                $i++;
                            }
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        Progres
                    </th>
                    <td>
                        <progress class="progress is-link is-small" value="<?= $j ?>" max="21"></progress>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="wrapper">
        <div class="center-line">
            <a href="#" class="scroll-icon"><span class="icon"><i class="mdi mdi-24px mdi-arrow-up-drop-circle-outline pt-2"></i></span></a>
        </div>
        <?php foreach ($actions as $act) : ?>
            <?= render_progress($act['params'], $act['data']) ?>
        <?php endforeach; ?>
    </div>

    <div id="next_progress_modal" class="modal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title"><b><?= ucwords(str_replace('_', ' ', $next)) ?></b></p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section id="confirm_message" class="modal-card-body">
                <?php
                $fields = get_fields_by_group($next);
                $params = array(
                    'fields_only' => true,
                    'fields' => $fields,
                    'output_type' => 'div'
                );
                $form = $ka->form($params);
                if (strpos($form, 'Error')) {
                    echo 'Maaf Permintaan Anda tidak dapat kami proses. Silahkan hubungi Admin.';
                } else {
                    echo '<form id="next_progress_form" class="has-text-left" action="" method="POST">';
                    echo $form;
                    echo '<input type="hidden" name="action" value="submit_form">';
                    echo '<input type="hidden" name="ka_action" value="' . $next . '">';
                    echo '<input type="hidden" name="pod_id" value="' . get_the_id() . '">';
                    echo '</form>';
                    echo '<button id="submit_button" class="is-success mt-3 button">Submit</button>';
                }
                ?>
            </section>
        </div>
    </div>
    </div>
<?php
}


function render_progress($params, $data)
{
    $i = $params['order'];
    $row_num = ($params['order'] % 2 == 0) ? 2 : 1;
    $colors = array(
        'sekretariat' => 'link',
        'pengkaji'    => 'success',
        'pembimbing'  => 'danger',
        'waiting'  => 'waiting'
    );
?>
    <div class="row row-<?= $row_num ?> <?= $params['role'] ?>">
        <section class="step">
            <span class="icon <?= $params['role'] ?>">
                <i class="mdi mdi-24px <?= STEP_ICON[$params['order']] ?> pt-2"></i>
            </span>
            <article class="message">
                <div class="message-header has-background-<?= $colors[$params['role']] ?>">
                    <div class="level details container">
                        <div class="level-left">
                            <div class="number-box level-item">
                                <?= strlen($params['order']) > 1 ? $params['order'] : '0' . $params['order'] ?>
                            </div>
                            <div class="level-item is-multiline"><?= $params['title'] ?></div>
                        </div>
                        <?php if (!is_mobile()) : ?>
                            <div style="float:right;" class="level-right">
                                <p class="level-item">
                                    <?php foreach ($params['owner'] as $pengkaji) : ?>
                                        <?php $user = pods('user', $pengkaji); ?>
                                        <?php $image = wp_get_attachment_image_src($user->field('image.ID'), 'thumbnail'); ?>
                                        <a href="<?= get_author_posts_url($pengkaji) ?>" target="_blank" title="<?= $user->display('nama_lengkap') ?>">
                                            <figure class="level-item image is-32x32">
                                                <img class="is-rounded" src="<?= $image[0] ?>">
                                            </figure>
                                        </a>
                                    <?php endforeach; ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="message-body step has-text-left py-0">
                    <?php if (!is_array($data)) : ?>
                        <?= $data ?>
                    <?php else : ?>
                        <table class="table is-bordered mt-3 is-fullwidth">
                            <?php foreach ($data as $k => $v) : ?>
                                <tr>
                                    <td>
                                        <label class="label"><?= $k ?></label>
                                        <div class="control">
                                            <?= $v ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                    <span class="is-size-6 is-grey px-3 pb-5 is-pulled-right"><?= $params['date'] ?></span>
                </div>
            </article>

        </section>
    </div>
<?php
}

?>