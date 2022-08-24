<?php
ob_start();
session_start();
if(!file_exists("config.php")){
	header("Location: install/index.php");
	die();
}

include("./config.php");
include("./helper/common.php");
include("./language/lang_en.php");

$wms = new wms_core();
$menu_array = array();
$menu_array = $wms->getFrontentMenuList($link);

$site_name = '';
$currency = '';
$email = '';
$address = '';
$footer_box_1 = '';
$footer_box_2 = '';
$footer_box_3 = '';
$footer_box_4 = '';
$footer_box_5 = '';
$header_box_1 = '';
$header_box_2 = '';
$contact_us_text_1 = '';
global $api_key;
global $map_address;

$settings = $wms->getWebsiteSettingsInformation($link);
if(!empty($settings)) {
	$site_name = $settings['site_name'];
	$currency = $settings['currency'];
	$email = $settings['email'];
	$address = $settings['address'];
	$footer_box_1 = $settings['footer_box_1'];
	$footer_box_2 = $settings['footer_box_2'];
	$footer_box_3 = $settings['footer_box_3'];
	$footer_box_4 = $settings['footer_box_4'];
	$footer_box_5 = $settings['footer_box_5'];
	$header_box_1 = $settings['header_box_1'];
	$header_box_2 = $settings['header_box_2'];
	$contact_us_text_1 = $settings['contact_us_text_1'];
	$api_key = $settings['gogole_api_key'];
	$map_address = $settings['map_address'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title><?php echo $site_name; ?></title>
<meta name="description" content="Workshop">
<meta name="keywords" content="workshop,garage">
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
<!-- google fonts -->
<link href='http://fonts.googleapis.com/css?family=Lato:400,300italic,300,700%7CPlayfair+Display:400,700italic%7CRoboto:300%7CMontserrat:400,700%7COpen+Sans:400,300%7CLibre+Baskerville:400,400italic' rel='stylesheet' type='text/css'>
<!-- Bootstrap -->
<link href="<?php echo WEB_URL; ?>assets/frontend/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo WEB_URL; ?>assets/frontend/css/bootstrap.css" rel="stylesheet">
<link href="<?php echo WEB_URL; ?>assets/frontend/css/bootstrap-theme.css" rel="stylesheet">
<link href="<?php echo WEB_URL; ?>assets/frontend/css/font-awesome.css" rel="stylesheet">
<link href="<?php echo WEB_URL; ?>assets/frontend/css/global.css" rel="stylesheet">
<link href="<?php echo WEB_URL; ?>assets/frontend/css/styleone.css" rel="stylesheet">
<link href="<?php echo WEB_URL; ?>assets/frontend/css/style.css" rel="stylesheet">
<link href="<?php echo WEB_URL; ?>assets/frontend/css/responsive.css" rel="stylesheet">
<link href="<?php echo WEB_URL; ?>assets/frontend/css/skin.css" rel="stylesheet">
<link href="<?php echo WEB_URL; ?>assets/frontend/css/flaticon.css" rel="stylesheet">
<link href="<?php echo WEB_URL; ?>assets/frontend/css/isotop.css" rel="stylesheet">
<link href="<?php echo WEB_URL; ?>assets/frontend/css/magnific-popup.css" rel="stylesheet">
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
</head>
<body>
<div id="wrapper">
<!--Header Section Start Here -->
<header id="header">
  <div class="container">
    <div class="row primary-header"> <a href="<?php echo WEB_URL; ?>" class="col-xs-12 col-sm-4 brand" title="<?php echo $site_name; ?>"><img src="<?php echo WEB_URL; ?>img/logo.png" alt="Charity"></a>
      <div class="col-xs-12 col-sm-8 cp-top-right"> <a data-toggle="modal" data-target=".join-now-form" class="btn btn-default btn-estimate"><?php echo $_lang['__get_apointment']; ?></a>
        <div class="upper-column info-box">
          <?php echo $header_box_1; ?>
        </div>
        <div class="upper-column info-box">
          <?php echo $header_box_2; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="navbar navbar-default" role="navigation">
    <div class="container">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      </div>
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <nav>
          <ul class="nav navbar-nav">
            <?php
					$i = 0;
					foreach($menu_array as $menu) {
						$href = '#';
						$url_slug = $menu['url_slug'];
						if(!empty($menu['href'])){
							$href = WEB_URL.$menu['href'];
						}
						if(!empty($menu['child_menu']) && count($menu['child_menu']) > 0) {
							echo '<li> <a href="'.$href.'" data-toggle="dropdown" class="submenu-icon">'.$menu['menu_name'].' <i class="fa fa-caret-down" aria-hidden="true"></i></a>';
						} else {
							echo '<li> <a href="'.$href.'">'.$menu['menu_name'].'</a>';
						}
						?>
            			<?php if(!empty($menu['child_menu'])) { ?>
            			<div  class="dropdown-menu">
              			<ul>
                			<?php
								foreach($menu['child_menu'] as $child) {
									$href = '#';
									if(!empty($child['href'])){
										if($url_slug != '') {
											$href = WEB_URL.$url_slug.'/'.$child['href'];
										} else {
											$href = WEB_URL.$child['href'];
										}
									}
									echo '<li> <a href="'.$href.'">'.$child['menu_name'].'</a></li>';
								} 
							?>
              			</ul>
            		</div>
           		 <?php } ?>
            </li>
            	<?php
					$i++; 
				} 
			  ?>
          </ul>
        </nav>
        <div class="navbar-form navbar-right search-form" style="display:none;">
          <ul>
            <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-shopping-cart"></i> <span class="badge">3</span> </a>
              <ul class="dropdown-menu cart-list animated" style="display: none; opacity: 1;">
                <li> <a href="#" class="photo"><img src="<?php echo WEB_URL; ?>images/shop/s1.jpg" class="cart-thumb" alt="s1.jpg"></a>
                  <h6><a href="#">Delica omtantur </a></h6>
                  <p>2x - <span class="price">$99.99</span></p>
                </li>
                <li> <a href="#" class="photo"><img src="<?php echo WEB_URL; ?>images/shop/s2.jpg" class="cart-thumb" alt="s2.jpg"></a>
                  <h6><a href="#">Omnes ocurreret</a></h6>
                  <p>1x - <span class="price">$33.33</span></p>
                </li>
                <li> <a href="#" class="photo"><img src="<?php echo WEB_URL; ?>images/shop/s3.jpg" class="cart-thumb" alt="s3.jpg"></a>
                  <h6><a href="#">Agam facilisis</a></h6>
                  <p>2x - <span class="price">$99.99</span></p>
                </li>
                <li class="total"> <span class="pull-right"><strong>Total</strong>: $0.00</span> <a href="#" class="btn btn-default btn-cart">Cart</a> </li>
              </ul>
            </li>
            <li class="search"><a href="#"><i class="fa fa-search"></i></a></li>
          </ul>
        </div>
        <!--<form class="navbar-form navbar-right search-form" role="search">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search Here">
          </div>
          <button type="submit"> <i class="fa fa-search"></i> </button>
        </form>-->
      </div>
      <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
  </div>
</header>
<!-- Header Section End Here -->
<!-- Site Content -->
<div id="main">
