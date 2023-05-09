<?php

/**
 * Template Name: Form Daftar Nilai
 *
 * @package WordPress
 */

$pod_name = 'daftar_nilai';
$form = '<form id="form_' . $pod_name . '" action="" method="POST" class="needs-validation">';

$form .= <<<FORM
          <div class="form-group">
            <label for="jenis_nilai">Jenis Nilai</label>
            <select id="jenis_nilai" name="jenis_nilai" class="form-control selectpicker" data-live-search="true" data-dropup-auto="false">                
                <option value="p">Nilai Kehadiran</option>
                <option value="q">Nilai Aktivitas</option>
                <option value="r">Ujian</option>
                <option value="k">Nilai Komprehensif</option>
                <option value="k2b1">Nilai Komprehensif (Form 2B-1)</option>
                <option value="pkl">Nilai PKL</option>
            </select>
          </div>
          FORM;

