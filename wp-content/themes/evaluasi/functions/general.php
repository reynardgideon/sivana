<?php
@ini_set('upload_max_size', '64M');
@ini_set('post_max_size', '64M');
@ini_set('max_execution_time', '300');

add_theme_support('wp-block-styles');
add_theme_support('menus');
add_filter('show_admin_bar', '__return_false');

add_action('login_head', 'custom_logologin');

add_filter('show_admin_bar', '__return_false');


function view_array($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

function build_tree(array &$elements, $parentId = 0)
{
    $branch = array();
    foreach ($elements as &$element) {
        if ($element->menu_item_parent == $parentId) {
            $children = build_tree($elements, $element->ID);
            if ($children)
                $element->wpse_children = $children;

            $branch[$element->ID] = $element;
            unset($element);
        }
    }
    return $branch;
}

function custom_logologin()
{
?>
    <style type="text/css">
        h1 a {
            background-image: url(<?php echo get_template_directory_uri() . '/logo.png'; ?>) !important;
        }
    </style>
<?php
}

add_action('pods_api_post_save_pod_item', 'after_pod_save', 10, 3);

function after_pod_save($pieces, $is_new_item, $id)
{
    remove_action('pods_api_post_save_pod_item', 'after_pod_save', 10);

    update_title($pieces['params']->pod, $id);

    add_action('pods_api_post_save_pod_item', 'after_pod_save', 10, 3);
}

function update_title($type, $id)
{
    $all_types = array(
        'pelatihan',
        'mata_pelajaran',
        'sheet',
        'evagara',
        'daftar_nilai',
        'api',
        'form_field',
        'lembar_kendali',
        'program',
        'rapat_kelulusan',
        'group',
        'soal',
        'ujian',
        'form',
        'data_form',
        'slide',
        'data_api',
        'task',
        'monitoring',
        'task_record',
        'konsep_nd',
        'menu_pelatihan',
        'epaspem',
        'links'
    );
    $pod = pods($type, $id);

    if (in_array($type, $all_types)) {

        $title = '';
        switch (true) {
            case ($type == 'pelatihan'):
                $title = $pod->display('judul') . ' Tahun ' . date('Y', strtotime($pod->display('mulai')));
                break;
            case ($type == 'epaspem'):
                $title = 'Epaspem '.$pod->display('pelatihan.judul') . ' Tahun ' . date('Y', strtotime($pod->display('pelatihan.mulai')));
                break;
            case ($type == 'task_record'):
                $title = $pod->field('pelatihan.ID') . '-' . $pod->field('task.ID');
                break;
            case ($type == 'evagara'):
                $title = $pod->display('judul');
                break;
            case ($type == 'group'):
                $title = $pod->display('nama_panjang');
                break;
            case ($type == 'data_api'):
                $title = $pod->display('key');
                break;
            case ($type == 'task'):
                $title = $pod->display('judul');
                break;
            case ($type == 'soal' || $type == 'ujian'):
                $title = $pod->display('ID');
                break;
            case ($type == 'program'):
                $title = $pod->display('nama_program');
                break;
            case ($type == 'rapat_kelulusan'):
                $title = 'Rapat Kelulusan ' . $pod->display('pelatihan.judul');
                break;
            case ($type == 'slide'):
                $title = $pod->display('pelatihan.judul');
                break;
            case ($type == 'daftar_nilai'):
                $title .= empty($pod->display('mata_pelajaran')) ? $pod->display('jenis_nilai') : $pod->display('mata_pelajaran');
                break;
            case ($type == 'data_form'):
                $title .= $pod->field('form.ID') . '-' . $pod->field('pelatihan.ID') . '-' . $pod->display('responden.ID');
                break;
            default:
                $title = $pod->display('judul');
        }

        if ($type == 'api' || $type == 'form' || $type == 'form_field' || $type == 'konsep_nd') {
            $arr = array(
                'post_title' => $title,
                'post_name' => str_replace(' ', '_', strtolower($title))
            );
        } else if ($type == 'task_record') {
            $arr = array(
                'post_title' => $title,
                'post_name' => $title
            );
        } else {
            $arr = array(
                'post_title' => $title,
                'post_name' => $pod->display('ID')
            );
        }

        $pod->save($arr);
    }
}

//add_filter('login_redirect', 'my_login_redirect', 10, 3);


function is_mobile()
{
    $ua = strtolower($_SERVER["HTTP_USER_AGENT"]);
    $isMob = is_numeric(strpos($ua, "mobile"));

    return $isMob;
}
/*
add_action( 'admin_init', function () {
    $user = wp_get_current_user();
    $allowed_roles = array('administrator');
    if (!array_intersect($allowed_roles, $user->roles) ) {        
        wp_redirect(get_author_posts_url($user->ID));
    } 
});
*/


function my_login_redirect($redirect_to, $request, $user)
{
    if (isset($user->roles) && is_array($user->roles)) {
        if (in_array('administrator', $user->roles)) {
            return $redirect_to;
        } else {
            return home_url();
        }
    } else {
        return $redirect_to;
    }
}

add_filter('login_redirect', 'my_login_redirect', 10, 3);

add_action('template_redirect', 'redirect_to_login');

function redirect_to_login()
{
    $iframe = isset($_SERVER['HTTP_SEC_FETCH_DEST']) && $_SERVER['HTTP_SEC_FETCH_DEST'] == 'iframe' ? true : false;
    $isevaluasi = isset($_GET['section']) && ($_GET['section'] == 'rekap_evaluasi_level_1' || $_GET['section'] == 'saran_masukan' || $_GET['section'] == 'saran_masukan_terkait_pengajar' || $_GET['section'] == 'rekap_evaluasi_penyelenggaraan' || $_GET['section'] == 'rekap_evaluasi_pengajar' || $_GET['section'] == 'matriks_rekomendasi')  ? true : false;
    if (!is_user_logged_in() && !is_singular('links') && !is_singular('form') && !is_singular('daftar_nilai') && !is_singular('api') && !is_singular('data_api')  && (!is_singular('slide') && !$iframe && !$isevaluasi)) {
        auth_redirect();
    }
}

function update_users_with_pengajar_group() {
    // Replace [PENGAJAR_GROUP_ID] with the actual ID of the pengajar group
    $pengajar_group_id = [4236];
    
    // Query all users
    $args = array(
        'fields' => 'all',
        'where' => "groups.meta_value = 'pengajar'"
    );
    $users = get_users($args);

    // Loop through all users
    foreach ($users as $user) {
        // Get the user's 'groups' field value
        $user_groups = get_user_meta($user->ID, 'groups', true);
        
        $user_groups_array = explode(',', $user_groups);
        // Check if the 'pengajar' value is checked in the 'groups' field
        if (in_array('pengajar', $user_groups_array)) {
            // Update the 'group' field with the related 'pengajar' group
            update_user_meta($user->ID, 'group', $pengajar_group_id);
        }
    }
}

?>