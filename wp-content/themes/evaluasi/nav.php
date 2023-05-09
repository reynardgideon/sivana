<?php
$userid = get_current_user_id();
$user = pods('user', $userid);
$image = $userid !== 0 && !empty($user->display('foto')) ? $user->display('foto') : 'https://evaluasi.knpk.xyz/wp-content/uploads/2023/01/dummy.jpg';
?>

<nav class="navbar header-navbar pcoded-header navbar-dark text-light">
  <div class="navbar-wrapper">
    <div class="navbar-logo">
      <a class="mobile-menu waves-effect waves-light" id="mobile-collapse" href="#!">
        <i class="ti-menu"></i>
      </a>
      <div class="mobile-search waves-effect waves-light">
        <div class="header-search">
          <div class="main-search morphsearch-search">
            <div class="input-group">
              <span class="input-group-addon search-close"><i class="ti-close"></i></span>
              <input type="text" class="form-control" placeholder="Enter Keyword">
              <span class="input-group-addon search-btn"><i class="ti-search"></i></span>
            </div>
          </div>
        </div>
      </div>
      <a href="<?= get_site_url() ?>">
        <h4>SIVANA</h4>
      </a>
      <a class="mobile-options waves-effect waves-light">
        <i class="ti-more"></i>
      </a>
    </div>

    <div class="navbar-container container-fluid">
      <ul class="nav-left">
        <li>
          <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu"></i></a></div>
        </li>
        <li class="header-search">
          <div class="main-search morphsearch-search">
            <div class="input-group">
              <span class="input-group-addon search-close"><i class="ti-close"></i></span>
              <input type="text" class="form-control">
              <span class="input-group-addon search-btn"><i class="ti-search"></i></span>
            </div>
          </div>
        </li>
        <li>
          <a href="#" onclick="javascript:toggleFullScreen()" class="waves-effect waves-light">
            <i class="ti-fullscreen"></i>
          </a>
        </li>
      </ul>
      <ul class="nav-right">
        <?php if (get_current_user_id() !== 0) : ?>
          <li class="header-notification">
            <a href="#!" class="waves-effect waves-light">
              <i class="ti-bell"></i>
              <span class="badge bg-c-red"></span>
            </a>
            <ul class="show-notification">
              <li>
                <h6>Notifications</h6>
                <label class="label label-danger">New</label>
              </li>
              <li class="waves-effect waves-light">
                <div class="media">
                  <img class="d-flex align-self-center img-radius" src="https://evaluasi.knpk.xyz/wp-content/uploads/2023/01/dummy.jpg" alt="Generic placeholder image">
                  <div class="media-body">

                  </div>
                </div>
              </li>
            </ul>
          </li>

          <li class="user-profile header-notification">
            <a href="#!" class="waves-effect waves-light">
              <img src="<?= $image ?>" class="img-radius" alt="User-Profile-Image">
              <span><?= $user->display('nama_lengkap') ?></span>
              <i class="ti-angle-down"></i>
            </a>
            <ul class="show-notification profile-notification">
              <li class="waves-effect waves-light">
                <a href="/settings">
                  <i class="ti-settings"></i> Settings
                </a>
              </li>
              <li class="waves-effect waves-light">
                <a href="<?= get_author_posts_url($user->field('ID')) ?>">
                  <i class="ti-user"></i> Profile
                </a>
              </li>
              <li class="waves-effect waves-light">
                <a href="<?= wp_logout_url() ?>">
                  <i class="ti-power-off"></i> Logout
                </a>
              </li>
            </ul>
          </li>
        <?php else : ?>
          <li>
            <a href="<?= wp_login_url() ?>"><i class="ti-user"></i>  Login</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>