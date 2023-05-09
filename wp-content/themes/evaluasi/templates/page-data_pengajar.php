<?php
/**
* Template Name: Data Pengajar
*
* @package WordPress
*/

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
						<?php get_template_part('breadcrumb'); ?>
						<div class="pcoded-inner-content">
							<!-- Main-body start -->
							<div class="main-body">
								<div class="page-wrapper">
									<!-- Page-body start -->
									<div class="page-body">
										<?= get_the_title() ?>
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
</body>

</html>