<?php
/**
* Template Name: Profile
*
* @package WordPress
*/

?>

<!DOCTYPE html>
<html lang="en" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">
<?php get_header(); ?>
<body>
<div id="app">
  <?php get_template_part('nav'); ?>
  <?php get_sidebar(); ?>
  <section class="section">
    <h4 class="title is-4">Change Username and Password</h4>
    <table>
        <tr><td>Username</td><td><input type="text"></input><td></tr>
        <tr><td>Old Password</td><td><input type="text"></input></td></tr>
        <tr><td>Username</td><td><input type="text"></input></td></tr>
    </table>
  </section>
</div>
<?php get_footer(); ?>
</body>
</html>