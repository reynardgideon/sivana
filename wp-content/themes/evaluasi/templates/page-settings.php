<?php

/**
 * Template Name: Settings
 *
 * @package WordPress
 */

function change_password_form()
{ ?>
    <form action="" method="post">
        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input name="current_password" type="password" class="form-control" id="current_password" placeholder="Password">
            <small id="emailHelp" class="form-text text-muted">Masukkan password saat ini.</small>
        </div>
        <div class="form-group">
            <label for="new_password">New Password</label>
            <input name="new_password" type="password" class="form-control" id="new_password" placeholder="Password">
            <small id="emailHelp" class="form-text text-muted">Masukkan password baru.</small>
        </div>
        <div class="form-group">
            <label for="confirm_new_password">Confirm New Password</label>
            <input name="confirm_new_password" type="password" class="form-control" id="confirm_new_password" placeholder="Password">
            <small id="emailHelp" class="form-text text-muted">Masukkan kembali password baru.</small>
        </div>
        <input class="btn btn-primary" type="submit" value="Submit">
    </form>
<?php }

function change_password()
{
    if (isset($_POST['current_password'])) {
        $_POST = array_map('stripslashes_deep', $_POST);
        $current_password = sanitize_text_field($_POST['current_password']);
        $new_password = sanitize_text_field($_POST['new_password']);
        $confirm_new_password = sanitize_text_field($_POST['confirm_new_password']);
        $user_id = get_current_user_id();
        $errors = array();
        $current_user = get_user_by('id', $user_id);
        // Check for errors
        if (empty($current_password) && empty($new_password) && empty($confirm_new_password)) {
            $errors[] = 'All fields are required';
        }
        if ($current_user && wp_check_password($current_password, $current_user->data->user_pass, $current_user->ID)) {
            //match
        } else {
            $errors[] = 'Password is incorrect';
        }
        if ($new_password != $confirm_new_password) {
            $errors[] = 'Password does not match';
        }
        if (strlen($new_password) < 6) {
            $errors[] = 'Password is too short, minimum of 6 characters';
        }
        if (empty($errors)) {
            wp_set_password($new_password, $current_user->ID);
            echo '<h2>Password successfully changed!</h2>';
        } else {
            // Echo Errors
            echo '<h3>Errors:</h3>';
            foreach ($errors as $error) {
                echo '<p>';
                echo "<strong>$error</strong>";
                echo '</p>';
            }
        }
    }
}

function cp_form_shortcode()
{
    change_password();
    change_password_form();
}

?>

<!DOCTYPE html>
<html lang="en">
<?php get_header(); ?>

<body>
    <?php get_template_part('loader'); ?>
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            <?php get_template_part('nav'); ?>
            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <?php get_sidebar(); ?>
                    <div class="pcoded-content">
                        <?php
                        get_template_part('breadcrumb');
                        ?>
                        <div class="pcoded-inner-content">
                            <!-- Main-body start -->
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <div id="main-content" class="card">
                                        <div class="card-header">
                                            <h4>SETTINGS</h4>
                                        </div>
                                        <div class="card-body">
                                        <div id="tes"></div>
                                            <div class="row px-4">
                                                <div class="col-lg-6">
                                                    <h5 class="my-3">Pengaturan</h5>
                                                    <div class="form-check px-4">
                                                        <input class="form-check-input" type="checkbox" value="" id="showSidebar" checked>
                                                        <label class="form-check-label" for="showSidebar">
                                                            Show Sidebar
                                                        </label>
                                                    </div>
                                                    <div class="form-check px-4">
                                                        <input class="form-check-input" type="checkbox" value="" id="showBreadcrumb" checked>
                                                        <label class="form-check-label" for="showBreadcrumb">
                                                            Show Breadcrumb
                                                        </label>
                                                    </div>                                                  
                                                </div>
                                                <div class="col-lg-6">
                                                    <h5 class="my-3">Ubah Password</h5>
                                                    <?= cp_form_shortcode() ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Jquery -->
    <?php get_footer(); ?>
    <script>
        $(document).ready(function() {
            if (localStorage.getItem("showSidebar") == 'false') {
                $("#pcoded").attr("vertical-nav-type", "offcanvas");
                $('#showSidebar').prop('checked', false);
            }
            if (localStorage.getItem("showBreadcrumb") == 'false') {
                $('#showBreadcrumb').prop('checked', false);
				$('.page-header').remove();
			}
            $('#showSidebar').change(function() {
                localStorage.setItem("showSidebar", this.checked);
                location.reload();
            });
            $('#showBreadcrumb').change(function() {
                localStorage.setItem("showBreadcrumb", this.checked);
                location.reload();
            });
        });        
    </script>
</body>

</html>