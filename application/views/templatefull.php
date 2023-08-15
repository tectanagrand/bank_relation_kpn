<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<base href="<?php echo base_url(); ?>">
	<meta charset="utf-8" />
	<?php if($this->session->userdata('FCCODE') != 'ERPKPN'){ ?>
        <title>KPN-Corp Management System</title>
        <?php } else{ ?>
        <title><?php echo ($this->uri->segment(2) == null) ? $this->uri->segment(1) : $this->uri->segment(2); }?></title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	<link href="./assets/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
	<link href="./assets/plugins/bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet" />
	<link href="./assets/plugins/font-awesome/5.0/css/fontawesome-all.min.css" rel="stylesheet" />
	<link href="./assets/plugins/animate/animate.min.css" rel="stylesheet" />
	<link href="./assets/css/default/style.min.css" rel="stylesheet" />
	<link href="./assets/css/default/style-responsive.min.css" rel="stylesheet" />
	<link href="./assets/css/default/theme/default.css" rel="stylesheet" id="theme" />
	<!-- ================== END BASE CSS STYLE ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
	<link href="./assets/plugins/jquery-jvectormap/jquery-jvectormap.css" rel="stylesheet" />
	<link href="./assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
	<link href="./assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" />
	<link href="./assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
	<link href="./assets/plugins/bootstrap-combobox/css/bootstrap-combobox.css" rel="stylesheet" />
	<link href="./assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
    <link href="./assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
	<!-- ================== END PAGE LEVEL STYLE ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
    <link href="./assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
	<link href="./assets/plugins/DataTables/extensions/Buttons/css/buttons.bootstrap.min.css" rel="stylesheet" />
    <link href="./assets/plugins/DataTables/extensions/AutoFill/css/autoFill.bootstrap.min.css" rel="stylesheet" />
    <link href="./assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
	 <link href="./assets/plugins/nvd3/build/nv.d3.css" rel="stylesheet" />
	<!-- ================== END PAGE LEVEL STYLE ================== -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="./assets/plugins/pace/pace.min.js"></script>
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<script src="./assets/plugins/jquery-ui/jquery-ui.min.js"></script>
	<script src="./assets/plugins/bootstrap/4.1.0/js/bootstrap.bundle.min.js"></script>
	<!--[if lt IE 9]>
		<script src="./assets/crossbrowserjs/html5shiv.js"></script>
		<script src="./assets/crossbrowserjs/respond.min.js"></script>
		<script src="./assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="./assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="./assets/plugins/js-cookie/js.cookie.js"></script>
	<script src="./assets/js/theme/default.min.js"></script>
	<script src="./assets/js/apps.min.js"></script>
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="./assets/plugins/gritter/js/jquery.gritter.js"></script>
	<script src="./assets/plugins/bootstrap-sweetalert/sweetalert.min.js"></script>
	<script src="./assets/plugins/flot/jquery.flot.min.js"></script>
	<script src="./assets/plugins/flot/jquery.flot.time.min.js"></script>
	<script src="./assets/plugins/flot/jquery.flot.resize.min.js"></script>
	<script src="./assets/plugins/flot/jquery.flot.pie.min.js"></script>
	<script src="./assets/plugins/sparkline/jquery.sparkline.js"></script>
	<script src="./assets/plugins/jquery-jvectormap/jquery-jvectormap.min.js"></script>
	<script src="./assets/plugins/jquery-jvectormap/jquery-jvectormap-world-mill-en.js"></script>
	<script src="./assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="./assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
	<script src="./assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
	<script src="./assets/plugins/bootstrap-combobox/js/bootstrap-combobox.js"></script>
	<script src="./assets/plugins/select2/dist/js/select2.min.js"></script>
	<script src="./assets/js/demo/dashboard.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->	
	<!-- <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
	<script src="//cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="//cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
	<link href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet"/> -->
	
	<script src="./assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
	<script src="./assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
	<script src="./assets/plugins/DataTables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
	<script src="./assets/plugins/DataTables/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
	<script src="./assets/plugins/DataTables/extensions/Buttons/js/buttons.flash.min.js"></script>
	<script src="./assets/plugins/DataTables/extensions/Buttons/js/jszip.min.js"></script>
	<script src="./assets/plugins/DataTables/extensions/Buttons/js/vfs_fonts.min.js"></script>
	<script src="./assets/plugins/DataTables/extensions/Buttons/js/buttons.html5.min.js"></script>
	<script src="./assets/plugins/DataTables/extensions/Buttons/js/buttons.print.min.js"></script>
	<script src="./assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
	<script>
            $(document).ready(function () {
                App.init();
            });
        </script>
</head>
<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade show"><span class="spinner"></span></div>
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
		<!-- begin #header -->
		<div id="header" class="header navbar-default">
			<!-- begin navbar-header -->
			<div class="navbar-header">
				<a href="<?php echo site_url()?>/">
                        <img src="./assets/img/logo/kpn-logo-2.png" class="media-object" alt="" style="width: 150px; height: 34px; margin-top: 8px; margin-left: 20px;" />
				</a>
				<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<!-- end navbar-header -->
			
			<!-- begin header-nav -->
			<ul class="navbar-nav navbar-right">
				<li>
					<!-- <form class="navbar-form">
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Enter keyword" />
							<button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
						</div>
					</form> -->
				</li>
				<li class="dropdown">
					<a href="javascript:;" data-toggle="dropdown" class="dropdown-toggle f-s-14">
						<i class="fa fa-bell"></i>
						<span class="label">5</span>
					</a>
					<ul class="dropdown-menu media-list dropdown-menu-right">
						<li class="dropdown-header">NOTIFICATIONS (2)</li>
						<li class="media">
							<a href="javascript:;">
								<div class="media-left">
									<i class="fa fa-bug media-object bg-silver-darker"></i>
								</div>
								<div class="media-body">
									<h6 class="media-heading">Server Error Reports <i class="fa fa-exclamation-circle text-danger"></i></h6>
									<div class="text-muted f-s-11">3 minutes ago</div>
								</div>
							</a>
						</li>
						<li class="media">
							<a href="javascript:;">
								<div class="media-left">
									<i class="fa fa-plus media-object bg-silver-darker"></i>
								</div>
								<div class="media-body">
									<h6 class="media-heading"> New User Registered</h6>
									<div class="text-muted f-s-11">1 hour ago</div>
								</div>
							</a>
						</li>
						<li class="dropdown-footer text-center">
							<a href="javascript:;">View more</a>
						</li>
					</ul>
				</li>
				<li class="dropdown navbar-user">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
						<img src="./assets/img/user/user-13.jpg" alt="" /> 
						<span class="d-none d-md-inline"><?php echo $this->session->userdata('username'); ?></span> <b class="caret"></b>
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a href="javascript:;" class="dropdown-item">Edit Profile</a>
						<a href="javascript:;" class="dropdown-item"><span class="badge badge-danger pull-right">2</span> Inbox</a>
						<a href="javascript:;" class="dropdown-item">Calendar</a>
						<a href="javascript:;" class="dropdown-item">Setting</a>
						<div class="dropdown-divider"></div>
						<a href="<?php echo site_url()?>/Login/logout" class="dropdown-item">Log Out</a>
					</div>
				</li>
			</ul>
			<!-- end header navigation right -->
		</div>
		<!-- end #header -->
		
		<?php $this->load->view($content); ?>
		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
</body>
</html>