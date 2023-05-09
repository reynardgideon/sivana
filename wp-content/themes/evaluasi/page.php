<?php

/**
 * Template Name: Data Pelatihan
 *
 * @package WordPress
 */

?>
<!DOCTYPE html>
<html lang="en">
<?php get_header(); ?>

<body>
	<?php get_template_part('loader'); ?>
	<div id="pcoded" class="pcoded" vertical-nav-type="offcanvas">
		<div class="pcoded-overlay-box"></div>
		<div class="pcoded-container navbar-wrapper">
			<?php get_template_part('nav'); ?>
			<div class="pcoded-main-container">
				<div class="pcoded-wrapper">
					<?php get_sidebar(); ?>
					<div class="pcoded-content">
						<?php get_template_part('breadcrumb'); ?>
						<div class="pcoded-inner-content">
							<!-- Main-body start -->
							<div class="main-body">
								<div class="page-wrapper">
									<!-- Page-body start -->
									<div class="page-body">
										<?php
										global $post;
										$post_slug = $post->post_name;

										if (str_contains($post_slug, 'data-pelatihan')) {
											$file = get_template_directory() . '/pages/data-pelatihan.php';
											include_once($file);
											echo data_pelatihan(str_replace('data-pelatihan-tahun-', '', $post_slug));
										} else {
											$file = get_template_directory() . '/pages/' . $post_slug . '.php';
											if (file_exists($file)) {
												include_once($file);
											} else {
												the_content();
											}
										}
										?>
									</div>
									<!-- Page-body end -->
								</div>
								<div id="styleSelector"> </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php get_footer(); ?>
	<script>
		$(document).ready(function() {
			if (localStorage.getItem("showSidebar") == 'false') {
				$("#pcoded").attr("vertical-nav-type", "offcanvas");
				$($.fn.dataTable.tables(true)).DataTable()
					.columns.adjust();
			}
			if (localStorage.getItem("showBreadcrumb") == 'false') {
				$('.page-header').remove();
			}
		});
	</script>
</body>

</html>