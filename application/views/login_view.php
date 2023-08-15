<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
    <meta charset="utf-8" />
    <title>KPN-Corp Management System</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
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

    <link href="<?= base_url()?>assets/fonts/aulianza-sans/aulianza-sans.css" rel="stylesheet">

    <!-- ================== BEGIN BASE JS ================== -->
    <script src="<?php echo base_url()?>/assets/plugins/pace/pace.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
    <!-- ================== END BASE JS ================== -->
    <style>
            .blink {
                animation: blinker 1.5s linear infinite;
                /*color: red;*/
                font-family: sans-serif;
            }
            @keyframes blinker {
                50% {
                    opacity: 0;
                }
            }
        </style>
</head>
<body class="pace-top bg-white">
    <!-- begin #page-loader -->
    <div id="page-loader" class="fade show"><span class="spinner"></span></div>
    <!-- end #page-loader -->
    
    <div class="login-cover">
        <div class="login-cover-image" style="background-image: url(<?php echo base_url()?>/assets/img/login-bg/gama-tower.jpg)" data-id="login-cover-image"></div>
        <div class="login-cover-bg"></div>
    </div>
    
    <!-- begin #page-container -->
    <div id="page-container" class="fade">
        <!-- begin login -->
        <div class="login login-v2" data-pageload-addclass="animated fadeIn">
            <!-- begin brand -->
            <div class="login-header">
                <div class="brand">
                    <img style="height:30px; width:30px;" src="<?= base_url()?>/assets/img/logo/kpn-logo.png" class="new-login-logo mb-1 mr-2"><b>KPN CORP</b>
                    <small>Cash Flow Web Applications</small>
                </div>
                <div class="icon">
                    <i class="fa fa-lock"></i>
                </div>
            </div>
            <!-- end brand -->
            <!-- begin login-content -->
            <div class="login-content">
                <form action="<?php echo site_url('login/aksi_login'); ?>" method="post">
                    <?php if($this->session->flashdata('error')){  ?>
                    <div class="alert alert-danger" id="error-alert">
                      <a href="" class="close" data-dismiss="alert">&times;</a>
                      <strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>
                    </div>
                  <?php } ?>
                    <div class="form-group m-b-20">
                        <input type="username" type="text" name="username" class="form-control" placeholder="Username">
                    </div>
                    <div class="form-group m-b-20">
                        <input type="password" class="form-control" name="password" placeholder="Password">
                    </div>
                    <div class="m-b-20">
                        <!-- <input type="checkbox" id="remember_checkbox" />  -->
                        <label for="remember_checkbox">
                            <span style="background-color: orange;padding: 3px;color: black;" class="blink">USE CAPITAL LETTER ON USERNAME TEXTBOX</span>
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
            <li  class="active"><a href="javascript:;" data-click="change-bg" data-img="<?php echo base_url()?>/assets/img/login-bg/gama-tower.jpg" style="background-image: url(<?php echo base_url()?>/assets/img/login-bg/gama-tower.jpg)"></a></li>
            <li><a href="javascript:;" data-click="change-bg" data-img="<?php echo base_url()?>/assets/img/login-bg/xx.jpg" style="background-image: url(<?php echo base_url()?>/assets/img/login-bg/xx.jpg)"></a></li>
            <li><a href="javascript:;" data-click="change-bg" data-img="<?php echo base_url()?>/assets/img/login-bg/x.jpg" style="background-image: url(<?php echo base_url()?>/assets/img/login-bg/x.jpg)"></a></li>           
            <li><a href="javascript:;" data-click="change-bg" data-img="<?php echo base_url()?>/assets/img/login-bg/cemindo.jpg" style="background-image: url(<?php echo base_url()?>/assets/img/login-bg/cemindo.jpg)"></a></li>
            <li><a href="javascript:;" data-click="change-bg" data-img="<?php echo base_url()?>/assets/img/login-bg/xxxxx.jpg" style="background-image: url(<?php echo base_url()?>/assets/img/login-bg/xxxxx.jpg)"></a></li>           
            <li><a href="javascript:;" data-click="change-bg" data-img="<?php echo base_url()?>/assets/img/login-bg/xxx.jpg" style="background-image: url(<?php echo base_url()?>/assets/img/login-bg/xxx.jpg)"></a></li>
        </ul>
        
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
            var closeInSeconds = 3, displayText = "this alert will disappear in #1 seconds.", timer;
            if (!localStorage.getItem("popup")) {
                Swal.fire({
                    title: "Please Use This Browser",
                    text: displayText.replace(/#1/, closeInSeconds),
                    // text: "This alert will disappear after 5 seconds.",
                    imageUrl: "http://172.27.7.193/assets/poplogin.jpg",
                    imageWidth: 646,
                    imageHeight: 284,
                    // position: "bottom",
                    backdrop: "linear-gradient(rgba(25,53,89), rgb(34,42,57))",
                    background: "white",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    showConfirmButton: false,
                    showCancelButton: false,
                    width: '800px',
                    timer: closeInSeconds * 1000,
                  });
                
                timer = setInterval(function(){
                    closeInSeconds--;
                    if (closeInSeconds < 0) {
                        clearInterval(timer);
                    }
                    $('.swal2-content').text(displayText.replace(/#1/, closeInSeconds));
                }, 1000);
                localStorage.setItem("popup", 'viewed');
            }
            // $(".swal2-modal").css('background-color', 'rgb(255 255 255 / 55%)');//Optional changes the color of the sweetalert 
            // $(".swal2-container.in").css('background-color', 'rgba(255,255,255,.8)');//changes the color of the overlay
        });

        
    </script>
</body>
</html>
