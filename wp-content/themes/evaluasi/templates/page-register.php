<?php

/**
 * Template Name: Register
 *
 * @package WordPress
 */

?>



<!DOCTYPE html>
<html>

<head>
    <title>Registration Form</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300i,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.0/css/bulma.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/tata.js"></script>
    <style type="text/css">
        body {
            margin: 0;
            font-family: 'Nunito Sans';
        }

        .field:not(:last-child) {
            margin-bottom: 0px;
        }

        .columns {
            margin: 20px !important;
        }

        .custom-color {
            background-color: #005FAC;
            padding: 35px;
            border-radius: 4px;
        }

        label,
        a {
            color: #fff !important;
        }

        a {
            text-decoration: underline;
            font-size: 14px;
        }

        .submit {
            background-color: #FCB813;
            color: #000;
            border: 0px;
        }

        .submit:hover {
            background-color: #303952;
            color: #fff;
        }

        .error {
            color: red !important;
        }
    </style>
</head>

<body>

    <div class="columns is-tablet">
        <div class="column is-one-quarter "></div>
        <div class="column">
            <div class="has-text-centered">
                <figure class="image is-128x128 is-inline-block">
                    <img src="http://sikad.knpk.xyz/wp-content/uploads/corpu.png">
                </figure>
                <h1 class="title is-2 mt-5 py-5">Registration Form</h1>
            </div>
            <form id="registration_form" action="" method="POST">
                <div class="custom-color">
                    <div class="field">
                        <label class="label">Nama Lengkap</label>
                        <div class="control ">
                            <input class="input" id="nama_lengkap" name="nama_lengkap" type="text" placeholder="Nama Lengkap" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-user"></i>
                            </span>
                            <span class="icon is-small is-right">
                                <i class="fas fa-check"></i>
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">NIP</label>
                        <div class="control ">
                            <input class="input" id="nip" name="nip" type="text" placeholder="NIP" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-user"></i>
                            </span>
                            <span class="icon is-small is-right">
                                <i class="fas fa-check"></i>
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Email</label>
                        <div class="control ">
                            <input class="input " id="user_email" name="user_email" type="email" placeholder="Email" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <span class="icon is-small is-right">
                                <i class="fas fa-exclamation-triangle"></i>
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Password</label>
                        <div class="control ">
                            <input class="input " id="user_pass" name="user_pass" type="password" placeholder="Password" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <span class="icon is-small is-right">
                                <i class="fas fa-exclamation-triangle"></i>
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Confirm Password</label>
                        <div class="control ">
                            <input class="input " id="confirm_pass" name="confirm_pass" type="password" placeholder="Confirm Password" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <span class="icon is-small is-right">
                                <i class="fas fa-exclamation-triangle"></i>
                            </span>
                        </div>
                    </div>
                    <div class="field pt-3">
                        <div class="control">
                            <label class="checkbox">
                                <input name="term" id="term" type="checkbox" required>
                                Saya setuju dengan <a href="https://sikad.knpk.xyz/privacy-policy/" target="_blank">terms and conditions</a>
                            </label>
                        </div>
                    </div>
                    <div class="field is-grouped pt-5">
                        <div class="control has-text-centered">
                            <input id="submit" type="submit" name="submit" class="button submit" value="Register">
                        </div>
                    </div>
                    <input id="action" type="hidden" name="action" value="register_action">
            </form>
            <p class="mt-6 has-text-white">OR</p>
            <a href="https://sikad.knpk.xyz/wp-login.php?loginSocial=google" rel="nofollow" aria-label="Continue with <b>Google</b>" data-plugin="nsl" data-action="connect" data-provider="google" data-popupwidth="600" data-popupheight="600">
                <figure class="image my-6 is-inline-block">
                    <img class="is-rounded" src="https://sikad.knpk.xyz/wp-content/uploads/login-1.png">
                </figure>
            </a>
        </div>
    </div>
    <div class="column is-one-quarter"></div>
    </div>

    <div id="modal" class="modal">
        <div class="modal-background"></div>
        <div class="modal-content">
            <div class="container has-text-centered">
                <div id="box" class="box">
                    <div>
                        <span class="icon is-large"><i id="icon" class="mdi mdi-48px"></i>
                    </div>
                    <p id="status" class="is-size-2"></p>
                    <p id="message" class="is-size-5 my-4 has-text-dark"></p>
                    <div id="button">
                        <a href="/dashboard/" id="next" class="button is-success">Continue</a>
                        <a href="#" id="back" class="button is-success">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
    <script type='text/javascript'>
        var ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>';
        jQuery(document).ready(function($) {
            $('#back').click(function() {
                $('#modal').removeClass('is-active');
            });

            $("#registration_form").validate({
                errorClass: 'error',
                rules: {
                    nip: "required",
                    nama_lengkap: "required",
                    user_email: {
                        required: true,
                        email: true
                    },
                    user_pass: {
                        required: true,
                        minlength: 6
                    },
                    confirm_pass: {
                        required: true,
                        minlength: 6,
                        equalTo: "#user_pass"
                    },
                    term: "required"
                },
                messages: {
                    nama_lengkap: "Masukkan nama lengkap anda!",
                    nip: "Masukkan NIP anda!",
                    term: 'Anda harus menyetujui "Term and Condition!"',
                    user_pass: {
                        required: "Masukkan password anda!",
                        minlength: "Password minimal menggunakan 6 karakter!"
                    },
                    confirm_pass: {
                        minlength: "Password minimal menggunakan 6 karakter!",
                        required: "Masukkan kembali password anda!",
                        equalTo: "Password yang anda masukkan tidak sama!"
                    },
                    user_email: {
                        required: "Masukkan email anda!",
                        email: "Masukkan alamat email yang valid",
                    }
                },
                submitHandler: function(form) {
                    var ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>';
                    var data = $('#registration_form').serialize();

                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            if (response.status == 1) {
                                tata.success(response.message, '', {
                                    position: 'tm',
                                    duration: 2000
                                });
                                window.location.href = '/profile';
                            } else {
                                tata.log('Maaf, permintaan anda tidak dapat kami proses.', '', {
                                    position: 'tm',
                                    duration: 2000
                                });
                            }
                        }
                    });

                }
            });

        });
    </script>
</body>

</html>