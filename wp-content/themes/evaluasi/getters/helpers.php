<?php

class Helpers
{
    public static $months = [
        '',
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];

    public static function range_tanggal($mulai, $selesai = '')
    {
        $mulai = explode('-', $mulai);
        $selesai = explode('-', $selesai);

        $r = '';

        if ($selesai == '') {
            $r = $mulai[2] . ' ' . self::$months[$mulai[1]] . ' ' . $mulai[0];
        } else {
            if ($mulai[1] == $selesai[1]) {
                $r = $mulai[2] . ' s.d. ' . $selesai[2] . ' ' . self::$months[(int)$mulai[1]] . ' ' . $selesai[0];
            } else {
                $r = $mulai[2] . ' ' . self::$months[(int)$mulai[1]] . ' s.d. ' . $selesai[2] . ' ' . self::$months[(int)$selesai[1]] . ' ' . $selesai[0];
            }
            return $r;
        }
    }

    public static function tanggal($date)
    {
        $split = explode('-', $date);
        return $split[2] . ' ' . self::$months[(int)$split[1]] . ' ' . $split[0];
    }

    public static function get_specimen($id)
    {
        $user = pods('user', $id);
        if ($user->exists()) {
            return $user->field('tanda_tangan') == '' ? '' : $user->field('tanda_tangan');
        } else {
            return false;
        }
    }

    public static function is_iframe()
    {
        if (isset($_SERVER['HTTP_SEC_FETCH_DEST']) && $_SERVER['HTTP_SEC_FETCH_DEST'] == 'iframe') {
            return true;
        } else {
            return false;
        }
    }

    public static function get_fields_by_group($group)
    {
        $group = pods('_pods_group', $group);
        if ($group->exists()) {
            $args = array(
                'post_type'  => '_pods_field',
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                        'key'     => 'group',
                        'value'   => $group->field('ID'),
                        'compare' => '=',
                    ),
                ),
            );
            $query = new WP_Query($args);

            $fields = array();
            foreach ($query->posts as $f) {
                $fields[] = $f->post_name;
            }

            return $fields;
        } else {
            return false;
        }
    }

    public static function predikat($v)
    {
        if (is_string($v)) {
            $v = (float) str_replace(',', '.', $v);
        }        

        if ($v > 4.35) {
            return 'Sangat Baik';
        } else if ($v > 3.4) {
            return 'Baik';
        } else if ($v > 2.6) {
            return 'Cukup';
        } else if ($v > 1.8) {
            return 'Kurang Baik';
        } else {
            return 'Tidak Baik';
        }
    }

    public static function kuadran($h, $k)
    {
        $roman = ['', 'I', 'II', 'III', 'IV'];
        $min = 0;

        if (is_string($k)) {
            $k = (float) str_replace(',', '.', $k);
        }       

        if ($k >= 4.35) {
            $min += 1;
        }

        if ($h >= 4.35) {
            $min += 2;
        }

        $kuadran = 4 - $min;

        return 'Kuadran ' . $roman[$kuadran];
    }

    public static function is_mobile()
    {
        $ua = strtolower($_SERVER["HTTP_USER_AGENT"]);
        $isMob = is_numeric(strpos($ua, "mobile"));

        return $isMob;
    }
    public static function is_renbang() {
        $pod = pods('group', 4199);        
        return in_array(get_current_user_id(), $pod->field('anggota.ID'));
    }
}
