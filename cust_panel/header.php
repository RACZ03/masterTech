<?php 
ob_start();
session_start();

include("../config.php");
include("../helper/common.php");

if(!isset($_SESSION['objCust']) || empty($_SESSION['objCust'])){
	header("Location: ".WEB_URL."logout.php");
	die();
}

// core init
$wms = new wms_core();

//menu seleciton
$page_name = '';
$page_name = pathinfo(curPageURL(),PATHINFO_FILENAME);
function curPageURL() {
 $pageURL = 'http';
 if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

$site_name = '';
$currency = '';
$email = '';
$address = '';
$estimate_data = array();
$website_logo = WEB_URL . 'img/no_image.jpg';
//
$result_settings = $wms->getWebsiteSettingsInformation($link);
if(!empty($result_settings)) {
	$site_name = $result_settings['site_name'];
	$currency = $result_settings['currency'];
	$email = $result_settings['email'];
	$address = $result_settings['address'];
	if($result_settings['site_logo'] != ''){
		$website_logo = WEB_URL.'img/'.$result_settings['site_logo'];
	}
}

//mini cart load
$cart_total = $wms->loadMiniCartHtml();
// apointment status
$apointment_list = $wms->get_all_waiting_apointment_list($link);
// contact us status
$contact_us_list = $wms->get_all_contact_us_list($link);
// car list status
$car_request_list = $wms->get_all_waiting_car_list($link);


$user_image = WEB_URL . 'img/no_image.jpg';
if(!empty($_SESSION['objCust']['image'])){
	$user_image = WEB_URL.'img/upload/'.$_SESSION['objCust']['image'];
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $site_name; ?></title>
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<!-- Bootstrap 3.3.4 -->
<link href="<?php echo WEB_URL; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- Font Awesome Icons -->
<link href="<?php echo WEB_URL; ?>dist/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<!-- Ionicons -->
<link href="<?php echo WEB_URL; ?>dist/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
<!-- Theme style -->
<link href="<?php echo WEB_URL; ?>dist/css/AdminLTE.css" rel="stylesheet" type="text/css" />
<!-- AdminLTE Skins. Choose a skin from the css/skins 
 folder instead of downloading all of them to reduce the load. -->
<link href="<?php echo WEB_URL; ?>dist/css/skins/_all-skins.css" rel="stylesheet" type="text/css" />
<!-- iCheck for checkboxes and radio inputs -->
<link href="<?php echo WEB_URL; ?>plugins/iCheck/all.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>dist/css/dataTables.responsive.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>dist/css/dataTables.tableTools.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>plugins/summernote/summernote.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- jQuery 2.1.4 -->
<script src="<?php echo WEB_URL; ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="<?php echo WEB_URL; ?>dist/js/printThis.js"></script>
<script src="<?php echo WEB_URL; ?>dist/js/common.js"></script>
<link href="<?php echo WEB_URL; ?>plugins/range_slider/nouislider.css" rel="stylesheet" type="text/css" />
<script src="<?php echo WEB_URL; ?>plugins/range_slider/nouislider.js"></script>
<link href="<?php echo WEB_URL; ?>plugins/jQuery/chosen.css" rel="stylesheet" type="text/css" />
<script src="<?php echo WEB_URL; ?>plugins/jQuery/chosen.jquery.js"></script>
<script src="<?php echo WEB_URL; ?>plugins/summernote/summernote.min.js"></script>
<script src="<?php echo WEB_URL; ?>plugins/chartjs/Chart.min.js" type="text/javascript"></script>
<script src="<?php echo WEB_URL; ?>plugins/select2/select2.min.js" type="text/javascript"></script>
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
<header class="main-header">
  <!-- Logo -->
  <a href="#" class="logo">
  <!-- mini logo for sidebar mini 50x50 pixels -->
  <span class="logo-mini"><i class="fa fa-car"></i></span>
  <!-- logo for regular state and mobile devices -->
  <span class="logo-lg"><img style="width:150px;" src="<?php echo $website_logo; ?>"></span> </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"> <span class="sr-only">Toggle navigation</span> </a>
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <li class="dropdown messages-menu"> <a href="<?php echo WEB_URL; ?>" target="_blank"> <i class="fa fa-home"></i></a></li>
        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <img src="<?php echo $user_image; ?>" class="user-image" alt="User Image"> <span class="hidden-xs"><?php echo !empty($_SESSION['objCust']['name']) ? $_SESSION['objCust']['name'] : ''; ?></span> </a>
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header"> <img src="<?php echo $user_image; ?>" class="img-circle" alt="User Image">
              <p style="text-transform:capitalize;"> <b style="color:#fff;font-weight:normal !important;"><?php echo !empty($_SESSION['objCust']['name']) ? $_SESSION['objCust']['name'] : ''; ?></b> <small style="color:#fff;font-weight:bold;"><?php echo !empty($_SESSION['login_type']) ? $_SESSION['login_type'] : ''; ?></small> </p>
            </li>
            <!-- Menu Footer-->
            <li class="user-footer">
              <div class="pull-left"> <a href="<?php echo WEB_URL; ?>cust_panel/profile.php" class="btn btn-success btn-flat"><b>Profile</b></a> </div>
              <div class="pull-right"> <a href="<?php echo WEB_URL; ?>logout.php" class="btn btn-danger btn-flat"><b>Sign out</b></a> </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>
<!-- =============================================== -->
<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar" style="margin-top:10px;">
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <div class="user-panel admin_header_border header_admin_border">
      <div class="pull-left image"> <img src="<?php echo $user_image; ?>" class="img-circle" alt="User Image"> </div>
      <div class="pull-left info">
        <p><?php echo !empty($_SESSION['objCust']['name']) ? $_SESSION['objCust']['name'] : ''; ?></p>
        <a href="<?php echo WEB_URL; ?>cust_panel/profile.php"><i class="fa fa-circle text-success text-profile"></i> Online</a> </div>
    </div>
    <ul class="sidebar-menu">
      <li class="header">MAIN NAVIGATION</li>
      <!-- <li class="tm10 <?php if($page_name != '' && $page_name == 'mech_dashboard'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>cust_panel/cust_dashboard.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a> </li> -->
      <li class="treeview <?php if($page_name != '' && $page_name == 'mycar' || $page_name == 'mycar'){echo 'active';}?>"> <a href="<?php echo WEB_URL; ?>cust_panel/mycar.php"> <i class="fa fa-car"></i> <span>My Car</span></a></li>
      <li class="treeview <?php if($page_name != '' && $page_name == 'estimate'){echo 'active';}?>"> <a href="<?php echo WEB_URL; ?>cust_panel/estimate.php"> <i class="fa fa-calculator"></i> <span>Estimate History</span></a></li>
      </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
<!-- =============================================== -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
