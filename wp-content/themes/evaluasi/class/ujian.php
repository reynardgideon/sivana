<?php

add_action('wp_ajax_create_session', 'Ujian::create_session');
add_action('wp_ajax_delete_session', array('Ujian', 'delete_session'));

class Ujian
{
    public static function create_session()
    {
        session_start();
        $_SESSION["peserta"] = $_POST['nip'];
    }

    public static function delete_session()
    {
        session_destroy();
    }
}
