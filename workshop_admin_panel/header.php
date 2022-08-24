<?php
ob_start();
session_start();

include("../config.php");
include("../helper/common.php");

if(!isset($_SESSION['objWork']) || empty($_SESSION['objWork'])){
	header("Location: ../logout.php");
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
/*if(!empty($_SESSION['objLogin']['image'])){
	$user_image = WEB_URL.'img/'.$_SESSION['objLogin']['image'];
}*/
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
     <link href="<?php echo WEB_URL; ?>assets/css/sakocheckbox.css" rel="stylesheet" type="text/css" />
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
     <!-- <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script> -->
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
                    <span class="logo-lg"><img style="width:150px;" src="<?php echo $website_logo; ?>"></span>
               </a>
               <!-- Header Navbar: style can be found in header.less -->
               <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"> <span
                              class="sr-only">Toggle navigation</span> </a>
                    <div class="navbar-custom-menu">
                         <ul class="nav navbar-nav">
                              <li class="dropdown messages-menu hidden-xs"> <a href="<?php echo WEB_URL; ?>"
                                        target="_blank"> <i style="font-size:18px" class="fa fa-home"></i></a></li>
                              <li class="dropdown messages-menu"> 
                                   <!-- <a title="Sample Estimate"
                                        href="<?php echo WEB_URL; ?>estimate/sample_estimate.php"> <i
                                             class="fa fa-calculator"></i><span class="label label-danger">E</span></a> -->
                                   <a title="Sample Estimate" href="<?php echo WEB_URL; ?>dashboard.php#"> <i
                                        class="fa fa-calculator"></i><span class="label label-danger">E</span></a>
                              </li>
                              <li class="dropdown messages-menu"> <a href="#" class="dropdown-toggle"
                                        data-toggle="dropdown" aria-expanded="false"> <!--<img height="13" width="13" src="img/clients.png"class="slot-machine">--><i class="fa fa-car"></i> <span
                                             class="label label-danger"><?php echo !empty($car_request_list) ? count($car_request_list) : '0'; ?></span>
                                   </a>
                                   <ul class="dropdown-menu">
                                        <li class="header" style="text-align:center;font-weight:bold;">You have
                                             <?php echo !empty($car_request_list) ? count($car_request_list) : '0'; ?>
                                             car request waiting.</li>
                                        <li>
                                             <!-- inner menu: contains the actual data -->
                                             <div class="slimScrollDiv"
                                                  style="position: relative; overflow: auto; width: auto; height: 200px;">
                                                  <ul class="menu" style="overflow: auto; width: 100%; height: 200px;">
                                                       <?php foreach($car_request_list as $c_list) { ?>
                                                       <li>
                                                            <a
                                                                 href="<?php echo WEB_URL; ?>car_request/carrequest.php?rid=<?php echo $c_list['car_request_id']; ?>">
                                                                 <div class="pull-left"><i
                                                                           class="fa fa-phone fa-2x"></i></div>
                                                                 <h4> <?php echo $c_list['name']; ?> <small><i
                                                                                class="fa fa-clock-o"></i>
                                                                           <b><?php echo $wms->mySqlToDatePicker($c_list['requested_date']);?></b></small>
                                                                 </h4>
                                                                 <p><?php echo $c_list['email']; ?></p>
                                                            </a>
                                                       </li>
                                                       <?php } ?>
                                                  </ul>
                                                  <div class="slimScrollBar"
                                                       style="background: rgb(0, 0, 0) none repeat scroll 0% 0%; width: 3px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 131.148px;">
                                                  </div>
                                                  <div class="slimScrollRail"
                                                       style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51) none repeat scroll 0% 0%; opacity: 0.2; z-index: 90; right: 1px;">
                                                  </div>
                                             </div>
                                        </li>
                                        <li class="footer"><a
                                                  href="<?php echo WEB_URL; ?>car_request/carrequest.php"><span
                                                       style="font-size:12px;" class="label label-success">Check
                                                       List</span></a></li>
                                   </ul>
                              </li>

                              <li class="dropdown messages-menu"> <a href="#" class="dropdown-toggle"
                                        data-toggle="dropdown" aria-expanded="false"> <i class="fa fa-phone"></i> <span
                                             class="label label-warning"><?php echo !empty($apointment_list) ? count($apointment_list) : '0'; ?></span>
                                   </a>
                                   <ul class="dropdown-menu">
                                        <li class="header" style="text-align:center;font-weight:bold;">You have
                                             <?php echo !empty($apointment_list) ? count($apointment_list) : '0'; ?>
                                             apointment request waiting.</li>
                                        <li>
                                             <!-- inner menu: contains the actual data -->
                                             <div class="slimScrollDiv"
                                                  style="position: relative; overflow: auto; width: auto; height: 200px;">
                                                  <ul class="menu" style="overflow: auto; width: 100%; height: 200px;">
                                                       <?php foreach($apointment_list as $a_list) { ?>
                                                       <li>
                                                            <a
                                                                 href="<?php echo WEB_URL; ?>apointment/apointment.php?aid=<?php echo $a_list['apointment_id']; ?>">
                                                                 <div class="pull-left"><i
                                                                           class="fa fa-phone fa-2x"></i></div>
                                                                 <h4> <?php echo $a_list['name']; ?> <small><i
                                                                                class="fa fa-clock-o"></i>
                                                                           <b><?php echo $wms->mySqlToDatePicker($a_list['added_date']);?></b></small>
                                                                 </h4>
                                                                 <p><?php echo $a_list['email']; ?></p>
                                                            </a>
                                                       </li>
                                                       <?php } ?>
                                                  </ul>
                                                  <div class="slimScrollBar"
                                                       style="background: rgb(0, 0, 0) none repeat scroll 0% 0%; width: 3px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 131.148px;">
                                                  </div>
                                                  <div class="slimScrollRail"
                                                       style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51) none repeat scroll 0% 0%; opacity: 0.2; z-index: 90; right: 1px;">
                                                  </div>
                                             </div>
                                        </li>
                                        <li class="footer"><a
                                                  href="<?php echo WEB_URL; ?>apointment/apointment.php"><span
                                                       style="font-size:12px;" class="label label-success">Check
                                                       List</span></a></li>
                                   </ul>
                              </li>

                              <!-- Messages: style can be found in dropdown.less-->
                              <li class="dropdown messages-menu"> <a href="#" class="dropdown-toggle"
                                        data-toggle="dropdown" aria-expanded="false"> <i class="fa fa-envelope-o"></i>
                                        <span
                                             class="label label-info"><?php echo !empty($contact_us_list) ? count($contact_us_list) : '0'; ?></span>
                                   </a>
                                   <ul class="dropdown-menu">
                                        <li class="header">You have
                                             <?php echo !empty($contact_us_list) ? count($contact_us_list) : '0'; ?>
                                             messages</li>
                                        <li>
                                             <!-- inner menu: contains the actual data -->
                                             <div class="slimScrollDiv"
                                                  style="position:relative;overflow:auto;width:auto;min-height: 100px;">
                                                  <ul class="menu" style="overflow:auto;width: 100%;min-height:100px;">
                                                       <?php foreach($contact_us_list as $c_list) { ?>
                                                       <li>
                                                            <a
                                                                 href="<?php echo WEB_URL; ?>contact_list/contact_list.php?cid=<?php echo $c_list['contact_id']; ?>">
                                                                 <div class="pull-left"><i
                                                                           class="fa fa-envelope-o fa-2x"></i></div>
                                                                 <h4> <?php echo $c_list['name']; ?> <small><i
                                                                                class="fa fa-clock-o"></i>
                                                                           <b><?php echo $wms->mySqlToDatePicker($c_list['added_date']);?></b></small>
                                                                 </h4>
                                                                 <p><?php echo $c_list['email']; ?></p>
                                                            </a>
                                                       </li>
                                                       <?php } ?>
                                                  </ul>
                                                  <div class="slimScrollBar"
                                                       style="background: rgb(0, 0, 0) none repeat scroll 0% 0%; width: 3px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 131.148px;">
                                                  </div>
                                                  <div class="slimScrollRail"
                                                       style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51) none repeat scroll 0% 0%; opacity: 0.2; z-index: 90; right: 1px;">
                                                  </div>
                                             </div>
                                        </li>
                                        <li class="footer"><a
                                                  href="<?php echo WEB_URL; ?>contact_list/contact_list.php"><span
                                                       style="font-size:12px;" class="label label-success">Check
                                                       List</span></a></li>
                                   </ul>
                              </li>
                              <!-- Tasks: style can be found in dropdown.less -->
                              <li class="dropdown tasks-menu"> <a href="#" class="dropdown-toggle"
                                        data-toggle="dropdown"> <i class="fa fa-shopping-cart"></i> <span
                                             class="label label-danger parts_cart"><?php echo $cart_total; ?></span>
                                   </a>
                                   <ul class="dropdown-menu">
                                        <li class="header text-center"><b>You have added <span
                                                       class="parts_cart"><?php echo $cart_total; ?></span> parts</b>
                                        </li>
                                        <li class="footer"> <a
                                                  href="<?php echo WEB_URL;?>/parts_stock/partssellform.php"><span
                                                       class="btn btn-danger btn-sm"><i class="fa fa-shopping-cart"></i>
                                                       CHECKOUT</span></a> </li>
                                   </ul>
                              </li>
                              <!-- User Account: style can be found in dropdown.less -->
                              <li class="dropdown user user-menu"> <a href="#" class="dropdown-toggle"
                                        data-toggle="dropdown"> <img src="<?php echo $user_image; ?>" class="user-image"
                                             alt="User Image"> <span
                                             class="hidden-xs"><?php echo !empty($_SESSION['objLogin']['name']) ? $_SESSION['objLogin']['name'] : ''; ?></span>
                                   </a>
                                   <ul class="dropdown-menu">
                                        <!-- User image -->
                                        <li class="user-header"> <img src="<?php echo $user_image; ?>"
                                                  class="img-circle" alt="User Image">
                                             <p style="text-transform:capitalize;"> <b
                                                       style="color:#fff;font-weight:normal !important;"><?php echo !empty($_SESSION['objLogin']['name']) ? $_SESSION['objLogin']['name'] : ''; ?></b>
                                                  <small
                                                       style="color:#fff;font-weight:bold;"><?php echo !empty($_SESSION['login_type']) ? $_SESSION['login_type'] : ''; ?></small>
                                             </p>
                                        </li>
                                        <!-- Menu Footer-->
                                        <li class="user-footer">
                                             <div class="pull-left"> <a href="<?php echo WEB_URL; ?>user/profile.php"
                                                       class="btn btn-success btn-flat"><b>Profile</b></a> </div>
                                             <div class="pull-right"> <a href="<?php echo WEB_URL; ?>logout.php"
                                                       class="btn btn-danger btn-flat"><b>Sign out</b></a> </div>
                                        </li>
                                   </ul>
                              </li>
                              <!-- Control Sidebar Toggle Button -->
                              <!-- <li class="hidden-xs"> <a href="<?php echo WEB_URL;?>/setting/setting.php"><i
                                             class="fa fa-gears"></i></a> </li> -->
                              <li class="hidden-xs"> <a href="<?php echo WEB_URL;?>dashboard.php#"><i
                              class="fa fa-gears"></i></a> </li>               
                         </ul>
                    </div>
               </nav>
          </header>
          <!-- =============================================== -->
          <!-- Left side column. contains the sidebar -->
          <aside class="main-sidebar">
               <div class="user-panel admin_header_border header_admin_border">
                    <div class="pull-left image"> <img src="<?php echo $user_image; ?>" class="img-circle"
                              alt="User Image"> </div>
                    <div class="pull-left info">
                         <p><?php echo !empty($_SESSION['objLogin']['name']) ? $_SESSION['objLogin']['name'] : ''; ?>
                         </p>
                         <a href="<?php echo WEB_URL; ?>user/profile.php"><i
                                   class="fa fa-circle text-success text-profile"></i> Online</a>
                    </div>
               </div>
               <!-- sidebar: style can be found in sidebar.less -->
               <!-- old sidebar -->
               <!-- <section class="sidebar">
                    <ul class="sidebar-menu">
                         <li class="header">MAIN NAVIGATION</li>
                         <li class="tm10 <?php if($page_name != '' && $page_name == 'dashboard'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>dashboard.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a> </li>
                         <li class="treeview <?php if($page_name != '' && $page_name == 'customerlist' || $page_name == 'addcustomer'){echo 'active';}?>"> <a href="<?php echo WEB_URL; ?>customer/customerlist.php"> <i class="fa fa-user"></i> <span>Customer</span></a></li>
                         <li class="treeview <?php if($page_name != '' && $page_name == 'addcar' || $page_name == 'carlist'){echo 'active';}?>"> <a href="<?php echo WEB_URL; ?>repaircar/carlist.php"> <i class="fa fa-wrench"></i> <span>Repair Car</span> </a> </li>
                         <li class="treeview <?php if($page_name != '' && $page_name == 'findcar' || $page_name == 'estimate_form'){echo 'active';}?>"> <a href="<?php echo WEB_URL; ?>estimate/findcar.php"> <i class="fa fa-calculator"></i> <span>Create Estimate</span></a></li>
                         <li class="treeview <?php if($page_name != '' && $page_name == 'deliverylist' || $page_name == 'finddeliverycar'){echo 'active';}?>"> <a href="<?php echo WEB_URL; ?>delivery/finddeliverycar.php"> <i class="fa fa-cart-plus"></i> <span>Delivery Car</span> <i class="fa fa-angle-left pull-right"></i> </a>
                         <ul class="treeview-menu">
                              <li class="<?php if($page_name != '' && $page_name == 'finddeliverycar'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>delivery/finddeliverycar.php"><i class="fa fa-arrow-circle-right"></i>Car Delivery</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'deliverylist'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>delivery/deliverylist.php"><i class="fa fa-arrow-circle-right"></i>Delivery List</a></li>
                         </ul>
                         </li>
                         <li class="treeview <?php if($page_name != '' && $page_name == 'buypartslist' || $page_name == 'sellpartslist' || $page_name == 'partsstocklist' || $page_name == 'sellparts'){echo 'active';}?>"> <a href="#"> <i class="fa fa-truck"></i> <span>Parts Stock</span> <i class="fa fa-angle-left pull-right"></i> </a>
                         <ul class="treeview-menu">
                              <li class="<?php if($page_name != '' && $page_name == 'buypartslist'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>parts_stock/buypartslist.php"><i class="fa fa-arrow-circle-right"></i>Purchase Parts</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'partsstocklist'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>parts_stock/partsstocklist.php"><i class="fa fa-arrow-circle-right"></i>Parts Stock</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'sellpartslist' || $page_name == 'sellparts'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>parts_stock/sellparts.php"><i class="fa fa-arrow-circle-right"></i>Sell Parts<i class="fa fa-angle-left pull-right"></i></a>
                              <ul class="treeview-menu">
                              <li class="<?php if($page_name != '' && $page_name == 'sellpartslist'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>parts_stock/sellpartslist.php"><i class="fa fa-arrow-circle-right"></i>Sell Parts List</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'sellparts'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>parts_stock/sellparts.php"><i class="fa fa-arrow-circle-right"></i>Find Sell Parts</a></li>
                              </ul>
                              </li>
                         </ul>
                         </li>
                         <li class="treeview <?php if($page_name != '' && $page_name == 'buycarlist' || $page_name == 'buycar' || $page_name == 'sellcarlist' || $page_name == 'sellcar' || $page_name == 'carsellform'){echo 'active';}?>"> <a href="#"> <i class="fa fa-car"></i> <span>Car Stock</span> <i class="fa fa-angle-left pull-right"></i> </a>
                         <ul class="treeview-menu">
                              <li class="<?php if($page_name != '' && $page_name == 'buycarlist' || $page_name == 'buycar'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>carstock/buycarlist.php"><i class="fa fa-arrow-circle-right"></i>Buy Car</a></li>
                              <li class="<?php if($page_name == 'sellcarlist' || $page_name == 'sellcar' || $page_name == 'carsellform'){echo 'active';}?>"><a href="#"><i class="fa fa-arrow-circle-right"></i>Sell Car<i class="fa fa-angle-left pull-right"></i></a>
                              <ul class="treeview-menu">
                              <li class="<?php if($page_name != '' && $page_name == 'sellcarlist'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>carstock/sellcarlist.php"><i class="fa fa-arrow-circle-right"></i>Sell Car List</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'sellcar' || $page_name == 'carsellform'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>carstock/sellcar.php"><i class="fa fa-arrow-circle-right"></i>Find Sell Car</a></li>
                              </ul>
                              </li>
                         </ul>
                         </li>
                         <li class="treeview <?php if($page_name != '' && $page_name == 'addsupplier' || $page_name == 'supplierlist'){echo 'active';}?>"> <a href="<?php echo WEB_URL; ?>supplier/supplierlist.php"> <i class="fa fa-user"></i> <span>Suppliers</span></a>
                         </li>
                         <li class="treeview <?php if($page_name != '' && $page_name == 'mechanicsalarylist' || $page_name == 'mechanicslist' || $page_name == 'addmechanics'){echo 'active';}?>"> <a href="#"> <i class="fa fa-users"></i> <span>Mechanics</span> <i class="fa fa-angle-left pull-right"></i> </a>
                         <ul class="treeview-menu">
                              <li class="<?php if($page_name != '' && $page_name == 'mechanicslist' || $page_name == 'addmechanics'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>mechanics/mechanicslist.php"> <i class="fa fa-arrow-circle-right"></i> Mechanics List</a></li>
                         </ul>
                         <ul class="treeview-menu">
                              <li class="<?php if($page_name != '' && $page_name == 'mechanicsalarylist'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>mechanics/mechanicsalarylist.php"> <i class="fa fa-arrow-circle-right"></i> Mechanics Salary</a></li>
                         </ul>
                         </li>
                         <ul class="treeview-menu">
                              <li class="<?php if($page_name != '' && $page_name == 'addinvoice'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>invoice/addinvoice.php"><i class="fa fa-arrow-circle-right"></i> Add Invoice</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'invoicelist'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>invoice/invoicelist.php"><i class="fa fa-arrow-circle-right"></i> Invoice List</a></li>
                         </ul>
                         </li>
                         <li class="treeview <?php if($page_name != '' && $page_name == 'addnotification' || $page_name == 'notificationlist'){echo 'active';}?>"> <a href="#"> <i class="fa fa-comment"></i> <span>Reminders</span> <i class="fa fa-angle-left pull-right"></i> </a>
                         <ul class="treeview-menu">
                              <li class="<?php if($page_name != '' && $page_name == 'addnotification'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>notification/addnotification.php"><i class="fa fa-arrow-circle-right"></i> Send Reminder</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'notificationlist'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>notification/notificationlist.php"><i class="fa fa-arrow-circle-right"></i> Reminder List</a></li>
                         </ul>
                         </li>
                         <li class="<?php if($page_name != '' && $page_name == 'apointment'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>apointment/apointment.php"><i class="fa fa-phone"></i> <span>Apointment</span></a> </li>
                         <li class="<?php if($page_name != '' && $page_name == 'carrequest'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>car_request/carrequest.php"><i class="fa fa-car"></i> <span>Car Request</span></a> </li>
                         <li class="<?php if($page_name != '' && $page_name == 'contact_list'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>contact_list/contact_list.php"><i class="fa fa-envelope-o"></i> <span>Contact</span></a> </li>
                         <li class="treeview <?php if($page_name != '' && $page_name == 'reprepairdelivery' || $page_name == 'rptcarbuy' || $page_name == 'rptcarsell' || $page_name == 'rptpartsbuy' || $page_name == 'rptpartssell' || $page_name == 'rptmsalary'){echo 'active';}?>"> <a href="#"> <i class="fa fa-bar-chart-o"></i> <span>Report</span> <i class="fa fa-angle-left pull-right"></i> </a>
                         <ul class="treeview-menu">
                              <li class="<?php if($page_name != '' && $page_name == 'reprepairdelivery'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>report/reprepairdelivery.php"><i class="fa fa-arrow-circle-right"></i> Car Repair Report</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'rptcarbuy'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>report/rptcarbuy.php"><i class="fa fa-arrow-circle-right"></i> Car Buy Report</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'rptcarsell'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>report/rptcarsell.php"><i class="fa fa-arrow-circle-right"></i> Car Sell Report</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'rptpartsbuy'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>report/rptpartsbuy.php"><i class="fa fa-arrow-circle-right"></i> Parts Purchase Report</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'rptpartssell'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>report/rptpartssell.php"><i class="fa fa-arrow-circle-right"></i> Parts Sell Report</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'rptmsalary'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>report/rptmsalary.php"><i class="fa fa-arrow-circle-right"></i> Mechanics Salary</a></li>
                         </ul>
                         </li>
                         <li class="treeview <?php if($page_name != '' && $page_name == 'menulist' || $page_name == 'addmenu' || $page_name == 'servicelist' || $page_name == 'addservice' || $page_name == 'commentslist' || $page_name == 'addcomments' || $page_name == 'sliderlist' || $page_name == 'addslider' || $page_name == 'cmslist' || $page_name == 'addcms' || $page_name == 'teamview' || $page_name == 'gallerylist' || $page_name == 'addgallery' || $page_name == 'widgetlist' || $page_name == 'wprocess' || $page_name == 'author' || $page_name == 'category' || $page_name == 'bloglist' || $page_name == 'blog' || $page_name == 'faq' || $page_name == 'newscommentslist'){echo 'active';}?>"> <a href="#"> <i class="fa fa-file-text-o"></i> <span>Catalog</span> <i class="fa fa-angle-left pull-right"></i> </a>
                         <ul class="treeview-menu">
                              <li class="<?php if($page_name != '' && $page_name == 'cmslist' || $page_name == 'addcms'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>cms/cmslist.php"><i class="fa fa-file-o"></i> CMS</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'menulist' || $page_name == 'addmenu'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>cms/menulist.php"><i class="fa fa-list"></i> Menu</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'faq'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>cms/faq.php"><i class="fa fa-question-circle-o"></i> FAQ</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'commentslist' || $page_name == 'addcomment'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>cms/commentslist.php"><i class="fa fa-comments-o"></i> Testimonials</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'author' || $page_name == 'category' || $page_name == 'newscommentslist' || $page_name == 'bloglist' || $page_name == 'blog'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>cms/menulist.php"><i class="fa fa-newspaper-o"></i> News<i class="fa fa-angle-left pull-right"></i></a>
                              <ul class="treeview-menu">
                              <li class="<?php if($page_name != '' && $page_name == 'author'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>cms/author.php"><i class="fa fa-arrow-circle-right"></i> Author</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'category'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>cms/category.php"><i class="fa fa-arrow-circle-right"></i> Category</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'bloglist' || $page_name == 'blog'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>cms/bloglist.php"><i class="fa fa-arrow-circle-right"></i> News</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'newscommentslist' || $page_name == 'blog'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>cms/newscommentslist.php"><i class="fa fa-commenting-o" aria-hidden="true"></i> Comments</a></li>
                              </ul>
                              </li>
                              <li class="<?php if($page_name != '' && $page_name == 'servicelist' || $page_name == 'wprocess' || $page_name == 'addservice' || $page_name == 'sliderlist' || $page_name == 'addslider' || $page_name == 'teamview' || $page_name == 'gallerylist' || $page_name == 'addgallery'){echo 'active';}?>"><a href="#"><i class="fa fa-cubes"></i> Modules<i class="fa fa-angle-left pull-right"></i></a>
                              <ul class="treeview-menu">
                              <li class="<?php if($page_name != '' && $page_name == 'sliderlist' || $page_name == 'addslider'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>cms/sliderlist.php"><i class="fa fa-arrow-circle-right"></i> Slider</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'servicelist' || $page_name == 'addservice'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>cms/servicelist.php"><i class="fa fa-arrow-circle-right"></i> Service</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'teamview'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>cms/teamview.php"><i class="fa fa-arrow-circle-right"></i> Team Widget</a></li>
                                   <li class="<?php if($page_name != '' && $page_name == 'wprocess'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>cms/wprocess.php"><i class="fa fa-arrow-circle-right"></i> Work Process Widget</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'gallerylist' || $page_name == 'addgallery'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>cms/gallerylist.php"><i class="fa fa-arrow-circle-right"></i> Gallery Widget</a></li>
                              </ul>
                              </li>
                         </ul>
                         </li>
                         <li class="treeview <?php if($page_name != '' && $page_name == 'setting' || $page_name == 'carcolor' || $page_name == 'parts_manufacturer' || $page_name == 'carsetting'){echo 'active';}?>"> <a href="#"> <i class="fa fa-gear"></i> <span>Setting</span> <i class="fa fa-angle-left pull-right"></i> </a>
                         <ul class="treeview-menu">
                              <li class="<?php if($page_name != '' && $page_name == 'carsetting'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>setting/carsetting.php"><i class="fa fa-arrow-circle-right"></i>Car Setting</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'parts_manufacturer'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>parts_manufacturer/parts_manufacturer.php"><i class="fa fa-arrow-circle-right"></i>Parts Manufacturer</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'carcolor'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>carcolor/carcolor.php"><i class="fa fa-arrow-circle-right"></i>Car Color & Door</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'setting'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>setting/setting.php"><i class="fa fa-arrow-circle-right"></i>System Setup</a></li>
                         </ul>
                         </li>
                    </ul>
               </section> -->
               <section class="sidebar">
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                         <li class="header">MAIN NAVIGATION</li>
                         <li class="tm10 <?php if($page_name != '' && $page_name == 'dashboard'){echo 'active';}?>"><a
                                   href="<?php echo WEB_URL; ?>workshop_admin_panel/dashboard.php"><i class="fa fa-dashboard"></i>
                                   <span>Dashboard</span></a> </li>
                         <li
                              class="treeview <?php if($page_name != '' && $page_name == 'customerlist' || $page_name == 'addcustomer'){echo 'active';}?>">
                              <a href="<?php echo WEB_URL; ?>workshop_admin_panel/customerlist.php"> <i class="fa fa-users"></i>
                                   <span>Clientes</span></a>
                         </li>
                         <li
                              class="treeview <?php if($page_name != '' && $page_name == 'addcar' || $page_name == 'carlist'){echo 'active';}?>">
                              <a href="<?php echo WEB_URL; ?>workshop_admin_panel/carlist.php"> <i class="fa fa-wrench"></i>
                                   <span>Retiro de máquina</span> </a>
                         </li>
                         <li class="treeview <?php if($page_name != '' && $page_name == 'findcar' || $page_name == 'estimate_form'){echo 'active';}?>"> 
                         <a href="<?php echo WEB_URL; ?>workshop_admin_panel/findcar.php"> <i class="fa fa-calculator"></i> 
                         <span>Cotizar Reparacion</span></a></li>
                         
                         <li class="treeview <?php if($page_name != '' && $page_name == 'deliverylist' || $page_name == 'finddeliverycar'){echo 'active';}?>"> <a href="<?php echo WEB_URL; ?>workshop_admin_panel/finddeliverycar.php"> <i class="fa fa-truck"></i> <span>Entrega de máquina</span> <i class="fa fa-angle-left pull-right"></i> </a>
                              <ul class="treeview-menu">
                                   <li class="<?php if($page_name != '' && $page_name == 'finddeliverycar'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>workshop_admin_panel/finddeliverycar.php"><i class="fa fa-arrow-circle-right"></i>Entregar y Facturar</a></li>
                                   <li class="<?php if($page_name != '' && $page_name == 'deliverylist'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>workshop_admin_panel/deliverylist.php"><i class="fa fa-arrow-circle-right"></i>Lista de Entregas</a></li>
                              </ul>
                         </li>
                         <li
                              class="treeview <?php if($page_name != '' && $page_name == 'buypartslist' || $page_name == 'sellpartslist' || $page_name == 'partsstocklist' || $page_name == 'sellparts'){echo 'active';}?>">
                              <a href="#"> <i class="fa fa-gear"></i> <span>Stock de Repuestos</span> <i
                                        class="fa fa-angle-left pull-right"></i> </a>
                              <ul class="treeview-menu">
                                   <li
                                        class="<?php if($page_name != '' && $page_name == 'buypartslist'){echo 'active';}?>">
                                        <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                  class="fa fa-arrow-circle-right"></i>Purchase Parts</a>
                                   </li>
                                   <li
                                        class="<?php if($page_name != '' && $page_name == 'partsstocklist'){echo 'active';}?>">
                                        <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                  class="fa fa-arrow-circle-right"></i>Parts Stock</a>
                                   </li>
                                   <li
                                        class="<?php if($page_name != '' && $page_name == 'sellpartslist' || $page_name == 'sellparts'){echo 'active';}?>">
                                        <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                  class="fa fa-arrow-circle-right"></i>Sell Parts<i
                                                  class="fa fa-angle-left pull-right"></i></a>
                                        <ul class="treeview-menu">
                                             <li
                                                  class="<?php if($page_name != '' && $page_name == 'sellpartslist'){echo 'active';}?>">
                                                  <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                            class="fa fa-arrow-circle-right"></i>Sell Parts List</a>
                                             </li>
                                             <li
                                                  class="<?php if($page_name != '' && $page_name == 'sellparts'){echo 'active';}?>">
                                                  <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                            class="fa fa-arrow-circle-right"></i>Find Sell Parts</a>
                                             </li>
                                        </ul>
                                   </li>
                              </ul>
                         </li>
                         <li
                              class="treeview <?php if($page_name != '' && $page_name == 'buycarlist' || $page_name == 'buycar' || $page_name == 'sellcarlist' || $page_name == 'sellcar' || $page_name == 'carsellform'){echo 'active';}?>">
                              <!-- <a href="#"> <img height="13" width="13" src="img/slot-machine.png" class="slot-machine"> <span class="stock-align">Stock de maquinas</span> <i
                                        class="fa fa-angle-left pull-right"></i> </a> -->
                              <a href="#"> <i class="fa fa-briefcase"></i> <span>Stock de maquinas</span> <i class="fa fa-angle-left pull-right"></i> </a>          
                              <ul class="treeview-menu">
                                   <li
                                        class="<?php if($page_name != '' && $page_name == 'buycarlist' || $page_name == 'buycar'){echo 'active';}?>">
                                        <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                  class="fa fa-arrow-circle-right"></i>Buy Car</a>
                                   </li>
                                   <li
                                        class="<?php if($page_name == 'sellcarlist' || $page_name == 'sellcar' || $page_name == 'carsellform'){echo 'active';}?>">
                                        <a href="#"><i class="fa fa-arrow-circle-right"></i>Sell Car<i
                                                  class="fa fa-angle-left pull-right"></i></a>
                                        <ul class="treeview-menu">
                                             <li
                                                  class="<?php if($page_name != '' && $page_name == 'sellcarlist'){echo 'active';}?>">
                                                  <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                            class="fa fa-arrow-circle-right"></i>Sell Car List</a>
                                             </li>
                                             <li
                                                  class="<?php if($page_name != '' && $page_name == 'sellcar' || $page_name == 'carsellform'){echo 'active';}?>">
                                                  <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                            class="fa fa-arrow-circle-right"></i>Find Sell Car</a>
                                             </li>
                                        </ul>
                                   </li>
                              </ul>
                         </li>
                         <li
                              class="treeview <?php if($page_name != '' && $page_name == 'addsupplier' || $page_name == 'supplierlist'){echo 'active';}?>">
                              <a href="<?php echo WEB_URL; ?>dashboard.php#"> <i class="fa fa-user"></i>
                                   <span>Proveedores</span></a>

                         </li>
                         <li class="treeview <?php if($page_name != '' && $page_name == 'mechanicsalarylist' || $page_name == 'mechanicslist' || $page_name == 'addmechanics'){echo 'active';}?>"> <a href="#"> <i class="fa fa-users"></i> <span>Técnicos</span> <i class="fa fa-angle-left pull-right"></i> </a>
                         <ul class="treeview-menu">
                              <li class="<?php if($page_name != '' && $page_name == 'mechanicslist' || $page_name == 'addmechanics'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>workshop_admin_panel/mechanicslist.php"> <i class="fa fa-arrow-circle-right"></i> Mechanics List</a></li>
                         </ul>
                         <!-- <ul class="treeview-menu">
                              <li class="<?php //if($page_name != '' && $page_name == 'mechanicsalarylist'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>mechanics/mechanicsalarylist.php"> <i class="fa fa-arrow-circle-right"></i> Mechanics Salary</a></li>
                         </ul> -->
                         </li>
                         <ul class="treeview-menu">
                              <li class="<?php if($page_name != '' && $page_name == 'addinvoice'){echo 'active';}?>"><a
                                        href="<?php echo WEB_URL; ?>invoice/addinvoice.php"><i
                                             class="fa fa-arrow-circle-right"></i> Add Invoice</a></li>
                              <li class="<?php if($page_name != '' && $page_name == 'invoicelist'){echo 'active';}?>"><a
                                        href="<?php echo WEB_URL; ?>invoice/invoicelist.php"><i
                                             class="fa fa-arrow-circle-right"></i> Invoice List</a></li>
                         </ul>
                         </li>
                         <li
                              class="treeview <?php if($page_name != '' && $page_name == 'addnotification' || $page_name == 'notificationlist'){echo 'active';}?>">
                              <a href="#"> <i class="fa fa-bell-o"></i> <span>Recordatorios</span> <i
                                        class="fa fa-angle-left pull-right"></i> </a>
                              <ul class="treeview-menu">
                                   <li
                                        class="<?php if($page_name != '' && $page_name == 'addnotification'){echo 'active';}?>">
                                        <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                  class="fa fa-arrow-circle-right"></i> Send Reminder</a>
                                   </li>
                                   <li
                                        class="<?php if($page_name != '' && $page_name == 'notificationlist'){echo 'active';}?>">
                                        <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                  class="fa fa-arrow-circle-right"></i> Reminder List</a>
                                   </li>
                              </ul>
                         </li>
                         <li class="<?php if($page_name != '' && $page_name == 'apointment'){echo 'active';}?>"><a
                                   href="<?php echo WEB_URL; ?>dashboard.php#"><i class="fa fa-calendar"></i>
                                   <span>Agenda</span></a> </li>
                         <li class="<?php if($page_name != '' && $page_name == 'carrequest'){echo 'active';}?>">
                         <!-- <a href="<?php echo WEB_URL; ?>dashboard.php#"><img height="13" width="13" src="img/slot-machine.png" class="slot-machine">
                                   <span class="stock-align">Solicitud de servicio</span></a>  -->
                                   <a href="<?php echo WEB_URL; ?>dashboard.php#"><i class="fa fa-briefcase"></i>
                                   <span>Solicitud de servicio</span></a>
                         </li>
                         <li class="<?php if($page_name != '' && $page_name == 'contact_list'){echo 'active';}?>"><a
                                   href="<?php echo WEB_URL; ?>dashboard.php#"><i class="fa fa-envelope-o"></i>
                                   <span>Hubspot</span></a> </li>
                         <li
                              class="treeview <?php if($page_name != '' && $page_name == 'reprepairdelivery' || $page_name == 'rptcarbuy' || $page_name == 'rptcarsell' || $page_name == 'rptpartsbuy' || $page_name == 'rptpartssell' || $page_name == 'rptmsalary'){echo 'active';}?>">
                              <a href="#"> <i class="fa fa-file-text-o"></i> <span>Reportes</span> <i
                                        class="fa fa-angle-left pull-right"></i> </a>
                              <ul class="treeview-menu">
                                   <li
                                        class="<?php if($page_name != '' && $page_name == 'reprepairdelivery'){echo 'active';}?>">
                                        <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                  class="fa fa-arrow-circle-right"></i> Car Repair Report</a>
                                   </li>
                                   <li
                                        class="<?php if($page_name != '' && $page_name == 'rptcarbuy'){echo 'active';}?>">
                                        <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                  class="fa fa-arrow-circle-right"></i> Car Buy Report</a>
                                   </li>
                                   <li
                                        class="<?php if($page_name != '' && $page_name == 'rptcarsell'){echo 'active';}?>">
                                        <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                  class="fa fa-arrow-circle-right"></i> Car Sell Report</a>
                                   </li>
                                   <li
                                        class="<?php if($page_name != '' && $page_name == 'rptpartsbuy'){echo 'active';}?>">
                                        <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                  class="fa fa-arrow-circle-right"></i> Parts Purchase Report</a>
                                   </li>
                                   <li
                                        class="<?php if($page_name != '' && $page_name == 'rptpartssell'){echo 'active';}?>">
                                        <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                  class="fa fa-arrow-circle-right"></i> Parts Sell Report</a>
                                   </li>
                                   <li
                                        class="<?php if($page_name != '' && $page_name == 'rptmsalary'){echo 'active';}?>">
                                        <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                  class="fa fa-arrow-circle-right"></i> Mechanics Salary</a>
                                   </li>
                              </ul>
                         </li>
                         <li
                              class="treeview <?php if($page_name != '' && $page_name == 'menulist' || $page_name == 'addmenu' || $page_name == 'servicelist' || $page_name == 'addservice' || $page_name == 'commentslist' || $page_name == 'addcomments' || $page_name == 'sliderlist' || $page_name == 'addslider' || $page_name == 'cmslist' || $page_name == 'addcms' || $page_name == 'teamview' || $page_name == 'gallerylist' || $page_name == 'addgallery' || $page_name == 'widgetlist' || $page_name == 'wprocess' || $page_name == 'author' || $page_name == 'category' || $page_name == 'bloglist' || $page_name == 'blog' || $page_name == 'faq' || $page_name == 'newscommentslist'){echo 'active';}?>">
                              <a href="#"> <i class="fa fa-globe"></i> <span>CMS Frontend</span> <i
                                        class="fa fa-angle-left pull-right"></i> </a>
                              <ul class="treeview-menu">
                                   <li
                                        class="<?php if($page_name != '' && $page_name == 'cmslist' || $page_name == 'addcms'){echo 'active';}?>">
                                        <a href="<?php echo WEB_URL; ?>dashboard.php#"><i class="fa fa-file-o"></i>
                                             CMS</a>
                                   </li>
                                   <li
                                        class="<?php if($page_name != '' && $page_name == 'menulist' || $page_name == 'addmenu'){echo 'active';}?>">
                                        <a href="<?php echo WEB_URL; ?>dashboard.php#"><i class="fa fa-list"></i>
                                             Menu</a>
                                   </li>
                                   <li class="<?php if($page_name != '' && $page_name == 'faq'){echo 'active';}?>"><a
                                             href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                  class="fa fa-question-circle-o"></i> FAQ</a></li>
                                   <li
                                        class="<?php if($page_name != '' && $page_name == 'commentslist' || $page_name == 'addcomment'){echo 'active';}?>">
                                        <a href="<?php echo WEB_URL; ?>dashboard.php#"><i class="fa fa-comments-o"></i>
                                             Testimonials</a>
                                   </li>
                                   <li
                                        class="<?php if($page_name != '' && $page_name == 'author' || $page_name == 'category' || $page_name == 'newscommentslist' || $page_name == 'bloglist' || $page_name == 'blog'){echo 'active';}?>">
                                        <a href="<?php echo WEB_URL; ?>dashboard.php#"><i class="fa fa-newspaper-o"></i>
                                             News<i class="fa fa-angle-left pull-right"></i></a>
                                        <ul class="treeview-menu">
                                             <li
                                                  class="<?php if($page_name != '' && $page_name == 'author'){echo 'active';}?>">
                                                  <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                            class="fa fa-arrow-circle-right"></i> Author</a>
                                             </li>
                                             <li
                                                  class="<?php if($page_name != '' && $page_name == 'category'){echo 'active';}?>">
                                                  <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                            class="fa fa-arrow-circle-right"></i> Category</a>
                                             </li>
                                             <li
                                                  class="<?php if($page_name != '' && $page_name == 'bloglist' || $page_name == 'blog'){echo 'active';}?>">
                                                  <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                            class="fa fa-arrow-circle-right"></i> News</a>
                                             </li>
                                             <li
                                                  class="<?php if($page_name != '' && $page_name == 'newscommentslist' || $page_name == 'blog'){echo 'active';}?>">
                                                  <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                            class="fa fa-commenting-o" aria-hidden="true"></i>
                                                       Comments</a>
                                             </li>
                                        </ul>
                                   </li>
                                   <li
                                        class="<?php if($page_name != '' && $page_name == 'servicelist' || $page_name == 'wprocess' || $page_name == 'addservice' || $page_name == 'sliderlist' || $page_name == 'addslider' || $page_name == 'teamview' || $page_name == 'gallerylist' || $page_name == 'addgallery'){echo 'active';}?>">
                                        <a href="#"><i class="fa fa-cubes"></i> Modules<i
                                                  class="fa fa-angle-left pull-right"></i></a>
                                        <ul class="treeview-menu">
                                             <li
                                                  class="<?php if($page_name != '' && $page_name == 'sliderlist' || $page_name == 'addslider'){echo 'active';}?>">
                                                  <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                            class="fa fa-arrow-circle-right"></i> Slider</a>
                                             </li>
                                             <li
                                                  class="<?php if($page_name != '' && $page_name == 'servicelist' || $page_name == 'addservice'){echo 'active';}?>">
                                                  <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                            class="fa fa-arrow-circle-right"></i> Service</a>
                                             </li>
                                             <li
                                                  class="<?php if($page_name != '' && $page_name == 'teamview'){echo 'active';}?>">
                                                  <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                            class="fa fa-arrow-circle-right"></i> Team Widget</a>
                                             </li>
                                             <li
                                                  class="<?php if($page_name != '' && $page_name == 'wprocess'){echo 'active';}?>">
                                                  <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                            class="fa fa-arrow-circle-right"></i> Work Process
                                                       Widget</a>
                                             </li>
                                             <li
                                                  class="<?php if($page_name != '' && $page_name == 'gallerylist' || $page_name == 'addgallery'){echo 'active';}?>">
                                                  <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                            class="fa fa-arrow-circle-right"></i> Gallery Widget</a>
                                             </li>
                                        </ul>
                                   </li>
                              </ul>
                         </li>
                         <li
                              class="treeview <?php if($page_name != '' && $page_name == 'setting' || $page_name == 'carcolor' || $page_name == 'parts_manufacturer' || $page_name == 'carsetting'){echo 'active';}?>">
                              <a href="#"> <i class="fa fa-cogs"></i> <span>Ajustes</span> <i
                                        class="fa fa-angle-left pull-right"></i> </a>
                              <ul class="treeview-menu">
                                   <li
                                        class="<?php if($page_name != '' && $page_name == 'carsetting'){echo 'active';}?>">
                                        <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                  class="fa fa-arrow-circle-right"></i>Car Setting</a>
                                   </li>
                                   <li
                                        class="<?php if($page_name != '' && $page_name == 'parts_manufacturer'){echo 'active';}?>">
                                        <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                  class="fa fa-arrow-circle-right"></i>Parts Manufacturer</a>
                                   </li>
                                   <li class="<?php if($page_name != '' && $page_name == 'carcolor'){echo 'active';}?>">
                                        <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                  class="fa fa-arrow-circle-right"></i>Car Color & Door</a>
                                   </li>
                                   <li class="<?php if($page_name != '' && $page_name == 'setting'){echo 'active';}?>">
                                        <a href="<?php echo WEB_URL; ?>dashboard.php#"><i
                                                  class="fa fa-arrow-circle-right"></i>System Setup</a>
                                   </li>
                              </ul>
                         </li>
                    </ul>
               </section>
               <!-- /.sidebar -->
          </aside>
          <!-- =============================================== -->
          <!-- Content Wrapper. Contains page content -->
          <div class="content-wrapper">