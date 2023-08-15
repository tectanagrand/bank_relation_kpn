<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
    <meta charset="utf-8" />
    <title>iPlas | Login Page</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />

    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="<?php echo base_url()?>/assets/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
    <link href="<?php echo base_url()?>/assets/plugins/bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?php echo base_url()?>/assets/plugins/font-awesome/5.0/css/fontawesome-all.min.css" rel="stylesheet" />
    <link href="<?php echo base_url()?>/assets/plugins/animate/animate.min.css" rel="stylesheet" />
    <link href="<?php echo base_url()?>/assets/css/default/style.min.css" rel="stylesheet" />
    <link href="<?php echo base_url()?>/assets/css/default/style-responsive.min.css" rel="stylesheet" />
    <link href="<?php echo base_url()?>/assets/css/default/theme/default.css" rel="stylesheet" id="theme" />
    <!-- ================== END BASE CSS STYLE ================== -->
    <!-- ================== BEGIN BASE JS ================== -->
    <script src="<?php echo base_url()?>/assets/plugins/pace/pace.min.js"></script>
    <!-- ================== END BASE JS ================== -->
</head>
<body class="pace-top bg-white">
    <!-- begin #page-loader -->
    <div id="page-loader" class="fade show"><span class="spinner"></span></div>
    <!-- end #page-loader -->
    
	<div class="login-cover">
	    <div class="login-cover-image" style="background-image: url(<?php echo base_url()?>/assets/img/login-bg/xx.jpg)" data-id="login-cover-image"></div>
	    <div class="login-cover-bg"></div>
	</div>
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade">
	    <!-- begin login -->
        <div class="login login-v2" data-pageload-addclass="animated fadeIn">
            <!-- begin brand -->
            <div class="login-header">
                <div class="brand">
                    <span class="logo"></span> <b>iPlas</b>
                    <small>Integrated Plantation System</small>
                </div>
                <div class="icon">
                    <i class="fa fa-lock"></i>
                </div>
            </div>
            <!-- end brand -->
            <!-- begin login-content -->
            <div class="login-content">
                <form action="<?php echo site_url('login/aksi_login'); ?>" method="post">
                    <div class="form-group m-b-20">
                        <input type="username" type="text" name="username" class="form-control" placeholder="Username">
                    </div>
                    <div class="form-group m-b-20">
                        <input type="password" class="form-control" name="password" placeholder="Password">
                    </div>
                    <div class="checkbox checkbox-css m-b-20">
                        <input type="checkbox" id="remember_checkbox" /> 
                        <label for="remember_checkbox">
                        	Remember Me
                        </label>
                    </div>
                    <div class="login-buttons">
                        <button type="submit" class="btn btn-success btn-block btn-lg">Login</button>
                    </div>
                    <div class="m-t-20">
                        Not a member yet? Click <a href="javascript:;">here</a> to register.
                    </div>
                </form>
            </div>
            <!-- end login-content -->
        </div>
        <!-- end login -->
        
        <ul class="login-bg-list clearfix">
            <li class="active"><a href="javascript:;" data-click="change-bg" data-img="<?php echo base_url()?>/assets/img/login-bg/xx.jpg" style="background-image: url(<?php echo base_url()?>/assets/img/login-bg/xx.jpg)"></a></li>
            <li><a href="javascript:;" data-click="change-bg" data-img="<?php echo base_url()?>/assets/img/login-bg/x.jpg" style="background-image: url(<?php echo base_url()?>/assets/img/login-bg/x.jpg)"></a></li>
			<li><a href="javascript:;" data-click="change-bg" data-img="<?php echo base_url()?>/assets/img/login-bg/5.jpg" style="background-image: url(<?php echo base_url()?>/assets/img/login-bg/5.jpg)"></a></li>
            <li><a href="javascript:;" data-click="change-bg" data-img="<?php echo base_url()?>/assets/img/login-bg/6.jpg" style="background-image: url(<?php echo base_url()?>/assets/img/login-bg/6.jpg)"></a></li>
            <li><a href="javascript:;" data-click="change-bg" data-img="<?php echo base_url()?>/assets/img/login-bg/xxxxx.jpg" style="background-image: url(<?php echo base_url()?>/assets/img/login-bg/xxxxx.jpg)"></a></li>			
            <li><a href="javascript:;" data-click="change-bg" data-img="<?php echo base_url()?>/assets/img/login-bg/xxx.jpg" style="background-image: url(<?php echo base_url()?>/assets/img/login-bg/xxx.jpg)"></a></li>
        </ul>
        
        <!-- begin theme-panel -->
        <div class="theme-panel theme-panel-lg">
            <a href="javascript:;" data-click="theme-panel-expand" class="theme-collapse-btn"><i class="fa fa-cog"></i></a>
            <div class="theme-panel-content">
                <h5 class="m-t-0">Color Theme</h5>
                <ul class="theme-list clearfix">
                    <li><a href="javascript:;" class="bg-red" data-theme="red" data-theme-file="<?php echo base_url()?>/assets/css/default/theme/red.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Red">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-pink" data-theme="pink" data-theme-file="<?php echo base_url()?>/assets/css/default/theme/pink.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Pink">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-orange" data-theme="orange" data-theme-file="<?php echo base_url()?>/assets/css/default/theme/orange.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Orange">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-yellow" data-theme="yellow" data-theme-file="<?php echo base_url()?>/assets/css/default/theme/yellow.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Yellow">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-lime" data-theme="lime" data-theme-file="<?php echo base_url()?>/assets/css/default/theme/lime.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Lime">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-green" data-theme="green" data-theme-file="<?php echo base_url()?>/assets/css/default/theme/green.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Green">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-teal" data-theme="default" data-theme-file="<?php echo base_url()?>/assets/css/default/theme/default.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Default">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-aqua" data-theme="aqua" data-theme-file="<?php echo base_url()?>/assets/css/default/theme/aqua.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Aqua">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-blue" data-theme="blue" data-theme-file="<?php echo base_url()?>/assets/css/default/theme/blue.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Blue">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-purple" data-theme="purple" data-theme-file="<?php echo base_url()?>/assets/css/default/theme/purple.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Purple">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-indigo" data-theme="indigo" data-theme-file="<?php echo base_url()?>/assets/css/default/theme/indigo.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Indigo">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-black" data-theme="black" data-theme-file="<?php echo base_url()?>/assets/css/default/theme/black.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Black">&nbsp;</a></li>
                </ul>
                <div class="divider"></div>
                <div class="row m-t-10">
                    <div class="col-md-6 control-label text-inverse f-w-600">Header Styling</div>
                    <div class="col-md-6">
                        <select name="header-styling" class="form-control form-control-sm">
                            <option value="1">default</option>
                            <option value="2">inverse</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-6 control-label text-inverse f-w-600">Header</div>
                    <div class="col-md-6">
                        <select name="header-fixed" class="form-control form-control-sm">
                            <option value="1">fixed</option>
                            <option value="2">default</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-6 control-label text-inverse f-w-600">Sidebar Styling</div>
                    <div class="col-md-6">
                        <select name="sidebar-styling" class="form-control form-control-sm">
                            <option value="1">default</option>
                            <option value="2">grid</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-6 control-label text-inverse f-w-600">Sidebar</div>
                    <div class="col-md-6">
                        <select name="sidebar-fixed" class="form-control form-control-sm">
                            <option value="1">fixed</option>
                            <option value="2">default</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-6 control-label text-inverse f-w-600">Sidebar Gradient</div>
                    <div class="col-md-6">
                        <select name="content-gradient" class="form-control form-control-sm">
                            <option value="1">disabled</option>
                            <option value="2">enabled</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-6 control-label text-inverse f-w-600">Content Styling</div>
                    <div class="col-md-6">
                        <select name="content-styling" class="form-control form-control-sm">
                            <option value="1">default</option>
                            <option value="2">black</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-6 control-label text-inverse f-w-600">Direction</div>
                    <div class="col-md-6">
                        <select name="direction" class="form-control form-control-sm">
                            <option value="1">LTR</option>
                            <option value="2">RTL</option>
                        </select>
                    </div>
                </div>
                <div class="divider"></div>
                <h5>THEME VERSION</h5>
                <div class="theme-version">
                	<a href="<?php echo base_url()?>/template_html/index_v2.html" class="active">
                		<span style="background-image: url(<?php echo base_url()?>/assets/img/theme/default.jpg);"></span>
                	</a>
                	<a href="<?php echo base_url()?>/template_transparent/index_v2.html">
                		<span style="background-image: url(<?php echo base_url()?>/assets/img/theme/transparent.jpg);"></span>
                	</a>
                </div>
                <div class="theme-version">
                	<a href="<?php echo base_url()?>/template_apple/index_v2.html">
                		<span style="background-image: url(<?php echo base_url()?>/assets/img/theme/apple.jpg);"></span>
                	</a>
                	<a href="<?php echo base_url()?>/template_material/index_v2.html">
                		<span style="background-image: url(<?php echo base_url()?>/assets/img/theme/material.jpg);"></span>
                	</a>
                </div>
                <div class="divider"></div>
                <div class="row m-t-10">
                    <div class="col-md-12">
                        <a href="javascript:;" class="btn btn-inverse btn-block btn-rounded" data-click="reset-local-storage"><b>Reset Local Storage</b></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end theme-panel -->
	</div>
	<!-- end page container -->
    <!-- ================== BEGIN BASE JS ================== -->
    <script src="<?php echo base_url()?>/assets/plugins/jquery/jquery-3.2.1.min.js"></script>
    <script src="<?php echo base_url()?>/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="<?php echo base_url()?>/assets/plugins/bootstrap/4.1.0/js/bootstrap.bundle.min.js"></script>
   <!-- @*[if lt IE 9]>
            <script src="~/assets/crossbrowserjs/html5shiv.js"></script>
            <script src="~/assets/crossbrowserjs/respond.min.js"></script>
            <script src="~/assets/crossbrowserjs/excanvas.min.js"></script>
        <![endif]*@-->
    <script src="<?php echo base_url()?>/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?php echo base_url()?>/assets/plugins/js-cookie/js.cookie.js"></script>
    <script src="<?php echo base_url()?>/assets/js/theme/default.min.js"></script>
    <script src="<?php echo base_url()?>/assets/js/apps.min.js"></script>
    <!-- ================== END BASE JS ================== -->
	<script src="<?php echo base_url()?>/assets/js/demo/login-v2.demo.min.js"></script>
	
    <script>
        $(document).ready(function () {
            App.init();
			LoginV2.init();
        });
    </script>
</body>
</html>
