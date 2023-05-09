$(document).ready(function () {

  $('#kajian_akademis').DataTable({
    order: [],
    lengthMenu: [[20, -1], [20, 'All']],
    columnDefs: [
      {
        targets: 0,
        width: "5%"
      },
      {
        targets: 1,
        width: "70%"
      },
      {
        targets: [0, 2],
        className: 'dt-body-center'
      },
      {
        targets: [0, 1, 2],
        className: 'dt-head-center'
      },
    ],
    deferRender: true,
    scrollCollapse: true,
    scrollX: true,
    columns: [{
      title: 'No'
    },
    {
      title: 'Judul'
    },
    {
      title: 'Tahapan'
    },
    {
      title: 'Progres'
    }
    ],
    ajax: 'https://sikad.knpk.xyz/wp-content/themes/sikad/data/kajian-akademis.php?userid=' + $('#kajian_akademis').data('id'),
  });

  $('#tabel_pembimbingan').DataTable({
    order: [],
    columnDefs: [

      {
        targets: [0],
        className: 'dt-body-center'
      },
      {
        targets: [0],
        className: 'dt-head-center'
      },
      {
        targets: 2,
        className: "dt-nowrap",
      }
    ],
    columns: [
      {
        title: 'No'
      },
      {
        title: 'Tanggal'
      },
      {
        title: 'Kegiatan'
      }],
    ajax: {
      url: 'https://sikad.knpk.xyz/wp-content/themes/sikad/data/pembimbingan.php?id=' + $('#tabel_pembimbingan').data('id')
    }
  });

  $('#tabel_dokumen').DataTable({
    order: [],
    columnDefs: [

      {
        targets: [0],
        width: "30",
        className: 'dt-body-center dt-head-center'
      },
      {
        targets: 1,
        className: "dt-nowrap",
      }
    ],
    columns: [
      {
        title: 'No'
      },
      {
        title: 'Nama Dokumen'
      }],
    ajax: {
      url: 'https://sikad.knpk.xyz/wp-content/themes/sikad/data/dokumen.php?id=' + $('#tabel_dokumen').data('id')
    }
  });

  $("table:first").attr("width", "100%");
  $("th").attr("width", "30%");

  var ajaxurl = 'https://sikad.knpk.xyz/wp-admin/admin-ajax.php';

  $("input[class='pods-submit-button']").addClass('is-success');
  $('.delete').click(function () {
    $('.modal').removeClass('is-active');
  });

  $('#tambah_profile').click(function (event) {
    $('#message').remove();
    $('#tambah_profile_modal').addClass('is-active');
  });

  $('#tambah_pembimbing').click(function () {
    $(this).addClass('is-loading');
    var data = $('#form_tambah_pembimbing').serialize();
    $.ajax({
      url: ajaxurl,
      type: 'POST',
      data: data,

      success: function (response) {
        if (response.status == 1) {
          $('#tambah_pembimbing').removeClass('is-loading');
          window.scrollTo({
            top: 0,
            behavior: 'smooth'
          });
        } else {
          $('#submit_button').removeClass('is-loading');
        }

      }
    });
  });

  $('#submit_button').click(function () {
    var data = $('#next_progress_form').serialize();
    $(this).addClass('is-loading');
    $.ajax({
      url: ajaxurl,
      type: 'POST',
      data: data,

      success: function (response) {
        $('#submit_button').removeClass('is-loading');
        if (response) {
          $('#next_progress_modal').removeClass('is-active');
          location.reload();
          /*
          window.scrollTo({
            top: 0,
            behavior: 'smooth'
          });
          */
          tata.success('Data berhasil disimpan.', '', {
            position: 'tm',
            duration: 2000
          });
        } else {
          tata.log('Maaf, permintaan anda tidak dapat kami proses.', '', {
            position: 'tm',
            duration: 2000
          });
        }
      }
    });
  });

  $('#submit_kegiatan').click(function () {
    var data = $('#pembimbingan_form').serialize();
    $(this).addClass('is-loading');
    $.ajax({
      url: ajaxurl,
      type: 'POST',
      data: data,

      success: function (response) {
        $('#submit_kegiatan').removeClass('is-loading');
        if (response) {
          $('#tambah_kegiatan').removeClass('is-active');
          location.reload();
          /*
          window.scrollTo({
            top: 0,
            behavior: 'smooth'
          });
          */
          tata.success('Data berhasil disimpan.', '', {
            position: 'tm',
            duration: 2000
          });
        } else {
          tata.log('Maaf, permintaan anda tidak dapat kami proses.', '', {
            position: 'tm',
            duration: 2000
          });
        }
      }
    });
  });


  $('#next_progress').click(function (event) {
    $('#next_progress_modal').addClass('is-active');
  });

  $('#pengajuan_proposal').click(function (event) {
    $('#pengajuan_proposal_modal').addClass('is-active');
  });

  $('#tambah_kegiatan').click(function (event) {
    $('#tambah_kegiatan_modal').addClass('is-active');
  });

  $('#back').click(function () {
    $(this).addClass('is-loading');
    window.location.href = '/kajian-akademis';
  });

  $('li.tab-menu').click(function (id) {
    $('li.tab-menu').removeClass('is-active');
    $(this).addClass('is-active');
    $('#bread-current').html($(this).html());

    $('.tab-content').addClass('is-hidden');
    $('#' + $(this).text().trim()).removeClass('is-hidden');
  });

  $("a.lihat_detail_pembimbingan").click(function (event) {
    event.preventDefault();
  });
  //$('#pembimbingan-page').load('https://sikad.knpk.xyz/pembimbingan/penyusunan-pertanyaan-kuesioner-dan-pengukuran-validitas-dan-reliabilitas-kuesioner/');

});

function lihat_detail(url) {
  //$('#pembimbingan-page').load(url + ' #kegiatan-pembimbingan');
}

