<?php 
ob_start();
session_start();

include(__DIR__ . "/config.php");
include(__DIR__ . "/helper/common.php");
//
$wms = new wms_core();
$site_name = '';
$currency = '';
$email = '';
$address = '';
$logo = '';

$result_settings = $wms->getWebsiteSettingsInformation($link);
if(!empty($result_settings)) {
	$site_name = $result_settings['site_name'];
	$currency = $result_settings['currency'];
	$email = $result_settings['email'];
	$address = $result_settings['address'];
	if(!empty($result_settings['site_logo'])){
		$logo = WEB_URL.'img/'.$result_settings['site_logo'];
	}
}

$msg = '';
if(isset($_POST['username']) && $_POST['username'] != '' && isset($_POST['ddlLoginType']) && $_POST['ddlLoginType'] != ''){
	$obj_login = $wms->forgot_operation($link, $_POST);
	if(!empty($obj_login) && count($obj_login) > 0){
		$msg = 'Check your email address for access information.';
		$message = '<div>Username: '.$obj_login['name'].'</div>';
		$message .= '<div>Password: '.$obj_login['password'].'</div>';
		$subject = 'Forgot password information.';
		$wms->sendForgotPasswordEmail($link, $obj_login['email'], $subject, $message);
	} else {
		$msg = '!!! Email and login type required for retrieve information with valid email address.';
	}
}
?>
<!DOCTYPE html>
<html lang="en-IN">
<head>
<meta charset="utf-8">
<title><?php echo $site_name; ?></title>
<link href='http://fonts.googleapis.com/css?family=Ropa+Sans' rel='stylesheet'>
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel='stylesheet'>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body{background: #00c0e4 none repeat scroll 0 0;color: #333;font-family: 'Ropa Sans', sans-serif; color:#666}
.add1{margin:0 auto; width:720px}
li,ul,body{margin:0; padding:0; list-style:none}
#login-form{width:350px; background:#FFF; margin:0 auto; background:#f1f1f1;margin-top:100px;}
.form-header{display:table; clear:both}
.form-header li{display:inline-block; line-height:40px; width:246px; margin:0 2px; text-align:center; background:#e8e8e8}
.form-header li:nth-child(odd){width:50px; margin:0 0;}
.user-image{padding:20px 0; text-align:center}
.user{height:100px; width:100px; border-radius:50%; border:solid 8px #e1e1e1; line-height:100px; color:#e1e1e1; font-size:50px}
.form{padding:0 30px; padding-bottom:10px}
.login li{height:35px; line-height:35px; margin-bottom:15px}
.input,select{border:solid 1px #e8e8e8; outline:none; background:#f8f8f8; margin:0 auto; font-family: 'Ropa Sans', sans-serif; font-size:15px; display:block; height:35px; width:268px; padding:0 10px; border-radius:3px; transition:all .3s}
.input:focus:invalid{border-color:red}
select:focus:invalid{border-color:red}
.input:focus:valid{border-color:green}
#check{top:2px; position:relative}
.inline:after{display:table; content:''}
.remember{width:100%; float:left; position:relative;font-size:14px}
.remember a{text-decoration:none; color:#666}
.remember:nth-child(2){text-align:right}
.btn{border:none; outline:none; background:#00a65a; border-bottom:solid 4px #006666; font-family: 'Ropa Sans', sans-serif; margin:0 auto; display:block; height:40px; width:100%; padding:0 10px; border-radius:3px; font-size:16px; color:#FFF;}
.social-login{padding:10px 30px; background:#e8e8e8; text-align:center}
.social-login a{display:inline-block; height:35px; line-height:35px; width:35px; margin:0 3px; text-decoration:none; color:#FFFFFF;}
.form a i.fa{ line-height:35px}
.fb{background:#305891} .tw{background:#2ca8d2} .gp{background:#ce4d39} .in{background:#006699}
.form-footer{height:40px; line-height:40px; padding:3px 35px; text-align:right; font-size:14px}
.form-footer a{padding:6px; background:#006699; margin-left:4px; color:#FFFFFF; text-decoration:none}
.backto{background:#09C}
.backto>a{padding:16px; margin-bottom:10px; display:block; text-align:center; text-decoration:none; font-size:16px; color:#fff}
select{width:290px !important;}
@media only screen and (max-width: 450px) {
	#login-form{width:280px !important;margin-top:25px !important;}
	.input{width:200px !important}
	select{width:222px !important}
	
}
</style>
</head>
<body>
<div class="row">
  <div class="col-md-12">
    <?php if(!empty($msg)) { ?>
		<div style="margin:0 auto;padding:12px;width:80%;background:#E52740;color:#fff;font-size:22px;text-align:center"><?php echo $msg; ?></div>
	<?php } ?>
    <div id="login-form">
      <div>
        <ul class="form-header">
          <li style="background:#00a65a;"><a title="Go to Home Page" style="color:#fff;font-weight:bold;" href="<?php echo WEB_URL;?>"><i class="fa fa-home"></i></a></li>
        </ul>
      </div>
      <?php if(!empty($logo)) { ?>
      <div class="user-image"><img alt="<?php echo $site_name; ?>" src="<?php echo $logo; ?>"></div>
      <?php } ?>
      <form class="form" method="post" enctype="multipart/form-data">
        <ul class="login">
          <li>
            <input type="email" name="username" class="input" required placeholder="User Email">
          </li>
          <li class="inline">
            <select name="ddlLoginType" required>
              <option value="">--Select User Type--</option>
              <option value="admin">Admin</option>
              <option value="customer">Customer</option>
              <option value="mechanics">Mechanics</option>
            </select>
          </li>
          <li>
            <button class="btn" type="submit"><i class="fa fa-key" aria-hidden="true"></i> &nbsp;Submit</button>
          </li>
          <li>
            <button class="btn" type="button" onClick="javascript:window.location='<?php echo WEB_URL;?>admin.php'"><i class="fa fa-user" aria-hidden="true"></i> &nbsp;Back To Login</button>
          </li>
        </ul>
      </form>
    </div>
  </div>
</div>
</body>
</html>
<?php $link = $wms->close_db_connection($link); ?>
