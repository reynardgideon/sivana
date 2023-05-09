<?php
$menus = wp_get_nav_menus();
$tree = array();
$html = '';
$current = get_the_permalink();

$userid = get_current_user_id();

$user = pods('user', $userid);

$image = $userid !== 0 && !empty($user->display('foto')) ? $user->display('foto') : 'https://evaluasi.knpk.xyz/wp-content/uploads/2023/01/dummy.jpg';

foreach ($menus as $menu) {

  $html .= '<div class="pcoded-navigation-label" data-i18n="nav.category.navigation">' . $menu->name . '</div>';

  $navs = wp_get_nav_menu_items($menu);

  $items = build_tree($navs);

  $html .= '<ul class="pcoded-item pcoded-left-item">';

  foreach ($items as $item) {

    $icon = isset($item->classes[0]) ? $item->classes[0] : 'mdi-square-small';
    if (property_exists($item, 'wpse_children')) {
      $html .= '
      <li class="pcoded-hasmenu">
        <a href="javascript:void(0)" class="waves-effect waves-dark">
          <span class="pcoded-micon"><i class="' . $icon . '"></i></span>
          <span class="pcoded-mtext" data-i18n="nav.basic-components.main">' . $item->title . '</span>
          <span class="pcoded-mcaret"></span>
        </a>
          <ul class="pcoded-submenu">';

      foreach ($item->wpse_children as $i) {
        $class = ' ';
        if ($current == $i->url) {
          $class = 'active';
        }

        $html .= '
        <li class="' . $class . '">
            <a href="' . $i->url . '" class="waves-effect waves-dark">
              <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
              <span class="pcoded-mtext" data-i18n="nav.basic-components.alert">' . $i->title . '</span>
              <span class="pcoded-mcaret"></span>
            </a>
          </li>';
      }
      $html .= '</ul>';
      $html .= '</li>';
    } else {
      $class = ' ';
      if ($current == $item->url) {
        $class = 'active';
      }
      $html .= '      
      <li class="' . $class . '">
        <a href="' . $item->url . '" class="waves-effect waves-dark">
          <span class="pcoded-micon"><i class="' . $icon . '"></i><b>D</b></span>
          <span class="pcoded-mtext" data-i18n="nav.dash.main">' . $item->title . '</span>
          <span class="pcoded-mcaret"></span>
        </a>
      </li>';
    }
  }
  $html .= '</ul>';
}

?>

<nav class="pcoded-navbar">
  <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
  <div class="pcoded-inner-navbar main-menu">
    <div class="">
      <?php if ($userid !== 0) : ?>
        <div class="main-menu-header">
          <img class="img-80 img-radius" src="<?= $image ?>" alt="User-Profile-Image">
          <div class="user-details">
            <span id="more-details"><?= $user->display('nama_lengkap') ?></span>
          </div>
        </div>
      <?php endif; ?>
      <div class="main-menu-content">
        <ul>
          <li class="more-details">
            <a href="user-profile.html"><i class="ti-user"></i>View Profile</a>
            <a href="#!"><i class="ti-settings"></i>Settings</a>
            <a href="<?= wp_logout_url() ?>"><i class="ti-layout-sidebar-left"></i>Logout</a>
          </li>
        </ul>
      </div>
    </div>
    <?= $html ?>
  </div>
</nav>