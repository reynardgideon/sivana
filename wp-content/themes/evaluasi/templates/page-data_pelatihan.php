<?php

/**
 * Template Name: Data Pelatihan
 *
 * @package WordPress
 */

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Data Pelatihan</title>
	<!-- HTML5 Shim and Respond.js IE10 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 10]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
	<!-- Meta -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description" content="Mega Able Bootstrap admin template made using Bootstrap 4 and it has huge amount of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
	<meta name="keywords" content="bootstrap, bootstrap admin template, admin theme, admin dashboard, dashboard template, admin template, responsive" />
	<meta name="author" content="codedthemes" />

	<!-- Google font-->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500" rel="stylesheet">


	<!-- Required Fremwork -->
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/css/bootstrap/css/bootstrap.min.css">
	<!-- themify icon -->
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/themify-icons/themify-icons.css">
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/icon/font-awesome/css/font-awesome.min.css">
	<!-- Style.css -->
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/assets/css/style.css">

	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/style.css">

	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery-ui/jquery-ui.min.js "></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/popper.js/popper.min.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/bootstrap/js/bootstrap.min.js "></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/pages/widget/excanvas.js "></script>
	<!-- modernizr js -->
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/modernizr/modernizr.js "></script>

	<!-- menu js -->
	<script src="<?php echo get_template_directory_uri(); ?>/assets/js/pcoded.min.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/assets/js/vertical-layout.min.js "></script>
	<!-- custom js -->
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/pages/dashboard/custom-dashboard.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/script.js "></script>

	<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
	<script>
		$(document).ready(function() {
			$('#data_pelatihan').DataTable({
				order: [],
				ajax: 'https://evaluasi.knpk.xyz/wp-content/themes/evaluasi/data/pelatihan.php'
			});
		});
	</script>
	<?php wp_head(); ?>
</head>

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
										<div class="card">
											<div class="card-header has-background-kmk-mix has-text-centered">
												<h5>DAFTAR PELATIHAN</h5>
												<div class="card-header-right">
													<i class="fa fa-plus" title="Tambah" data-toggle="modal" data-target="#tambah_pelatihan"></i>
													<i class="fa fa-table" title="Tambah Beberapa"></i>
													<i class="fa fa-pencil" title="Ubah"></i>
													<i class="fa fa-trash" title="Hapus"></i>
													<i class="fa fa-cog" title="Pengaturan"></i>
												</div>
											</div>
											<div class="card-content p-4">

												<table id="data_pelatihan" class="display" style="width:100%">
													<thead>
														<tr>
															<th><input type="checkbox"></th>
															<th>#</th>
															<th>Nama Pelatihan</th>
															<th>Mulai</th>
															<th>Selesai</th>
															<th>PIC PHD</th>
															<th>PIC Ujian</th>
														</tr>
													</thead>
													<tfoot>
														<tr>
															<th><input type="checkbox"></th>
															<th>#</th>
															<th>Nama Pelatihan</th>
															<th>Mulai</th>
															<th>Selesai</th>
															<th>PIC PHD</th>
															<th>PIC Ujian</th>
														</tr>
													</tfoot>
												</table>
											</div>

											<!-- Modal -->
											<div class="modal fade" id="tambah_pelatihan" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
												<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">Tambah Pelatihan</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<div class="modal-body px-4">
															<?php
															$params = array(
																'fields' => array(
																	'judul',
																	'jenis_pelatihan',
																	'mulai',
																	'selesai',
																	'pic_phd',
																	'pic_ujian'
																),
																'output_type' => 'p'
															);
															$pod = pods('pelatihan');
															echo $pod->form($params);
															?>
														</div>
													</div>
												</div>
											</div>
										</div>

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