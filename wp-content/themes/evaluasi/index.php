<?php
include_once(get_template_directory() . '/templates/components/menu.php');
include_once(get_template_directory() . '/getters/helpers.php');
?>

<!DOCTYPE html>
<html lang="en">
<?php get_header(); ?>

<body>
	<?php get_template_part('loader'); ?>
	<div id="pcoded" class="pcoded">
		<div class="pcoded-overlay-box"></div>
		<div class="pcoded-container navbar-wrapper">
			<div id="navbar">
				<?php 
					/*
					if (!(isset($_GET['content_only']) && $_GET['content_only'] == 'true')) {
						get_template_part('nav');
					}
					*/
					if (Helpers::is_iframe() !== true || isset($_GET['content_only']) !== true) {
						get_template_part('nav');
					}
				?>
			</div>
			<div class="pcoded-main-container">
				<div class="pcoded-wrapper">
					<?php get_sidebar(); ?>
					<div class="pcoded-content">
						<?php
						if (Helpers::is_iframe() !== true) {
							get_template_part('breadcrumb');
						}
						if(is_user_logged_in()) {											
							get_menu();
						}
						?>
						<div class="pcoded-inner-content">
							<!-- Main-body start -->
							<div class="main-body">
								<div class="page-wrapper">
									<!-- Page-body start -->
									<div class="page-body">
										<?php
										if (have_posts()) :
											while (have_posts()) :
												the_post();
												get_template_part('templates/content', get_post_type());
											endwhile;
										else :
											get_template_part('templates/content', 'none');
										endif;
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

	<!-- Required Jquery -->
	<?php get_footer(); ?>

	<script>
		$(document).ready(function() {
			if (localStorage.getItem("showSidebar") == 'false' || <?= json_encode(Helpers::is_iframe()) ?> === true || <?= json_encode(isset($_GET['content_only']) && $_GET['content_only'] == true) ?> === true) {
				$("#pcoded").attr("vertical-nav-type", "offcanvas");
				if ($('table').length) {
					$($.fn.dataTable.tables(true)).DataTable()
						.columns.adjust();
				}
			}
			if (localStorage.getItem("showBreadcrumb") == 'false' || <?= json_encode(Helpers::is_iframe()) ?> === true) {
				$('.page-header').remove();
			}
		});
	</script>
</body>

</html>