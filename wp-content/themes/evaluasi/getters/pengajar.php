<?php
class Pengajar
{
    public static function get_data($id_pelatihan = null)
    {
        $params = array(
            'limit' => -1,
            'where' => "groups.meta_value = 'pengajar'"
        );
        $pengajar = pods('user', $params);
        $data = [];
        while ($pengajar->fetch()) {
            $data[$pengajar->field('ID')] = $pengajar->field('nama_lengkap');
        }
        return $data;
    }

    public static function get_options_list($id_pelatihan = null, $selected = [])
    {
        $params = array(
            'limit' => -1,
            'where' => "groups.meta_value = 'pengajar'"
        );
        $pengajar = pods('user', $params);
        $options = '';
        while ($pengajar->fetch()) {
            $is_selected = in_array($pengajar->field('ID'), $selected) ? ' selected' : '';
            $options .= '<option value="'.$pengajar->field('ID').'"'.$is_selected.'>'.$pengajar->field('nama_lengkap').'</option>';
        }
        return $options;
    }

    public static function get_by_pelatihan($id_pelatihan = null)
    {       
        $pod = pods('pelatihan', $id_pelatihan);
        $data = $pod->field('mata_pelajaran.pengajar.ID');
        return $data ? $data : [];
    }
}
