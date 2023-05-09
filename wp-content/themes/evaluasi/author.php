<?php
include_once('templates/parts.php');
add_action('wp_head', 'add_script');
function add_script()
{
?>
	<link rel="stylesheet" href="<?= get_template_directory_uri() ?>/css/author.css">
<?php
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
						global $wp_query;
						$curauth = $wp_query->get_queried_object();
						$userid = get_current_user_id();

						if (!empty($curauth->foto)) {
							$attach = wp_get_attachment_image_src($curauth->foto, 'thumbnail');
							$image = $attach[0];
						} else {
							$image = 'https://evaluasi.knpk.xyz/wp-content/uploads/2023/01/dummy.jpg';
						}

						$fields = array(
							'nama_lengkap',
							'nama_panggilan',
							'nip',
							'unit_kerja',
							'jabatan',
							'user_email',
							'nomor_hp',
							'foto'
						);
						?>
						<div class="main-body">
							<div class="page-wrapper">
								<div class="page-body">
									<div class="card">
										<div class="card-body">
											<div class="container">
												<div class="row">
													<div class="col-4 profile-image-box">
														<div class="d-flex flex-column align-items-center text-center p-3 py-5">
															<img class="rounded-circle mt-5" width="150px" src="<?= $image ?>"><h5 class="mt-3 text-white"><?= $curauth->nama_lengkap ?></h5><span class="mt-2 text-black-50 text-white"><?= $curauth->nip ?></span><span> </span>
														</div>
													</div>
													<div class="col-8">
														<div class="level">
															<h5 class="subtitle is-5">Biodata</h5>
															<?php if ($curauth->ID == $userid) : ?>
																<button type="button" class="my-2 btn btn-primary btn-sm" data-toggle="modal" data-target="#editProfileModal">
																	Edit
																</button>
															<?php endif; ?>
														</div>
														<table class="table">
															<?php foreach ($fields as $f) : ?>
																<?php if ($f !== 'foto') : ?>
																	<tr>
																		<th>
																			<?= ucwords(str_replace('_', ' ', $f)) ?>
																		</th>
																		<td>
																			<?= empty($curauth->$f) ? '-' : $curauth->$f;  ?>
																		</td>
																	</tr>
																<?php endif; ?>
															<?php endforeach; ?>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Modal -->
						<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<section id="confirm_message" class="modal-card-body">
											<?php
											$user = pods("user", $userid);
											$params = array(
												'fields_only' => true,
												'fields' => $fields,
												'output_type' => 'div'
											);
											$form = $user->form($params);
											if (strpos($form, 'Error')) {
												echo 'Maaf Permintaan Anda tidak dapat kami proses. Silahkan hubungi Admin.';
											} else {
												echo '<form id="edit_profile_form" class="has-text-left" action="" method="POST">';
												echo $form;
												echo '<input type="hidden" name="action" value="update_profile">';
												echo '<input type="hidden" name="pod_id" value="' . $userid . '">';
												echo '</form>';
											}
											?>
										</section>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										<button type="button" id='submit_button' class="btn btn-primary">Save</button>
									</div>
								</div>
							</div>
						</div>

						<?php
						?>
						<script>
							$(document).ready(function() {
								var ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>';

								$('#submit_button').click(function() {
									$(this).text('Loading...');
									var data = $('#edit_profile_form').serialize();
									$.ajax({
										url: ajaxurl,
										type: 'POST',
										data: data,

										success: function(response) {
											$('#submit_button').text('Save');
											if (response) {
												location.reload();
												window.scrollTo({
													top: 0,
													behavior: 'smooth'
												});
												tata.success('Data berhasil diubah!', '', {
													position: 'tm',
													duration: 20000
												});
											} else {
												tata.log('Maaf, permintaan anda tidak dapat kami proses!', '', {
													position: 'tm',
													duration: 2000
												});
											}
										}
									});
								});

							});
						</script>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Required Jquery -->
	<link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
	<?php get_footer(); ?>
</body>

</html>