<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html ng-app="salesapp">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Glaucus | SalesForce App</title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

<!--
<link rel="icon" href="https://lnsel.com/wp-content/uploads/2015/05/favicon.png" type="image/png" />
<link rel="shortcut icon" href="https://lnsel.com/wp-content/uploads/2015/05/favicon.png" type="image/png" />
-->
<!-- <link rel="SHORTCUT ICON" href="https://tiimg.tistatic.com/cimages/dc/84745/0/favicon/favicon.ico" type="image/x-icon"> -->
<link rel="SHORTCUT ICON" href="<?= base_url('assets/images/favicon.ico') ?>" type="image/x-icon">
    
<!-- Bootstrap 3.3.6 -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/bootstrap/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/dist/css/AdminLTE.min.css">
<!-- AdminLTE Skins. We have chosen the skin-blue for this starter
    page. However, you can choose any other skin. Make sure you
    apply the skin class to the body tag so the changes take effect.
-->
<link rel="stylesheet" href="<?php echo base_url();?>assets/dist/css/skins/_all-skins.min.css">
<!-- <link rel="stylesheet" href="<?php echo base_url();?>assets/dist/css/skins/all-skins.min.css"> -->
<!-- iCheck -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/iCheck/square/blue.css">

<!-- angular Toast -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/angulartoast/angular-toastr.min.css">

<!-- angular Zoom -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/angularZoom/imageviewer.css">

<!-- angular Multi Select -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/angularMultiSelect/isteven-multi-select.css">

<!-- angular Progress -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/angularProgress/ngprogress.css">


<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Angular JS -->
<script src="<?php echo base_url();?>assets/angular/angular.min.js"></script>
<script src="<?php echo base_url();?>assets/angularCtrl/appCtrls.js"></script>
<script src="<?php echo base_url();?>assets/angular/dirPagination.min.js"></script>
<script src="<?php echo base_url();?>assets/angularMap/ng-map.min.js"></script>

<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url();?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url();?>assets/bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>assets/dist/js/app.min.js"></script>
<!-- iCheck -->
<script src="<?php echo base_url();?>assets/plugins/iCheck/icheck.min.js"></script>

<!-- Angular Toast -->
<script src="<?php echo base_url();?>assets/angulartoast/angular-toastr.tpls.min.js"></script>

<!-- Angular Zoom -->
<script src="<?php echo base_url();?>assets/angularZoom/imageviewer.min.js"></script>

<!-- Angular Multi Select -->
<script src="<?php echo base_url();?>assets/angularMultiSelect/isteven-multi-select.js"></script>

<!-- Angular Progress -->
<script src="<?php echo base_url();?>assets/angularProgress/ngprogress.min.js"></script>

<!-- angularChart -->
<script src="//code.highcharts.com/highcharts.js"></script>
<script src="<?php echo base_url();?>assets/angularChart/chart.js"></script>

<!-- angularPDF -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jspdf/1.2.61/jspdf.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.3.2/jspdf.plugin.autotable.js"></script>

<!-- Angular NG-File-Upload -->
<script src="<?php echo base_url();?>assets/plugins/fileupload/ng-file-upload-shim.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/fileupload/ng-file-upload.min.js"></script>



<!-- Angular Timepicker -->


<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->	
</head>
<body class="hold-transition skin-blue layout-top-nav">
<script>
var main_url = '<?php echo base_url();?>';
</script>