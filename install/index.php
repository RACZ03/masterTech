<?php

define('DIR_APPLICATION', str_replace('\'', '/', realpath(dirname(__FILE__))) . '/');
define('DIR_SMS', str_replace('\'', '/', realpath(DIR_APPLICATION . '../')) . '/');

session_start();
$success_token = false;
$errror_token = false;
$base_url = home_base_url();
$msg = '';
$msg2 = '';
//step 2 reset data
$email = '';
$purchase_code = '';
//step 3 reset data
$root_path = '';
$host_name = '';
$db_user = '';
$db_password = '';
$db_name = '';
///////////////////////////////////////////////////////////////
/*if(!isset($_SESSION['step']) && file_exists("../config.php")){
	header("Location: ../index.php");
	die();
}*/
///////////////////////////////////////////////////////////////
if(!isset($_SESSION['step'])){
	$_SESSION['step'] = 1;
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if($_SESSION['step']===1){
		unset($_SESSION['step']);
		$_SESSION['step'] = 2;
	}else if($_SESSION['step']===2){
		if(!empty($_POST['txtDomainName']) && !empty($_POST['txtPurchaseCode'])){
			$response = callAPI('http://sakocart.com/api/index.php', array('apikey'=>'7770f593-1ce3-4a94-bfe4-05d55718d7cc','email' => trim($_POST['txtEmailAddress']),'domain' => trim($_POST['txtDomainName']),'pcode' =>trim($_POST['txtPurchaseCode']),'ip_address'=>$_SERVER['REMOTE_ADDR']));
			if($response['code'] == '200'){
				unset($_SESSION['response']);
				$_SESSION['response'] = $response;
				unset($_SESSION['step']);
				$_SESSION['step'] = 3;
				$_SESSION['domain'] = trim($_POST['txtDomainName']);
			} else {
				$msg = $response['msg'];
				$email = trim($_POST['txtEmailAddress']);
				$purchase_code = trim($_POST['txtPurchaseCode']);
				$base_url = trim($_POST['txtDomainName']);
			}
		} else {
			$msg = 'Domain and Purchase code Required.';
		}
	}else if($_SESSION['step']===3){
		if(!empty($_POST['txtDocRoot']) && !empty($_POST['txtHostName']) && !empty($_POST['txtDBUserName']) && !empty($_POST['txtDBName'])){
			$root_path = trim($_POST['txtDocRoot']);
			$host_name = trim($_POST['txtHostName']);
			$db_user = trim($_POST['txtDBUserName']);
			$db_password = trim($_POST['txtPassword']);
			$db_name = trim($_POST['txtDBName']);
			//////////////////////////////////////////////////////////////////////
			$link = @mysqli_connect($host_name, $db_user, $db_password, $db_name);
			if (!$link) {
				$msg2 = 'Connect Error: ' . mysqli_connect_error();
			} else {
				if(isset($_SESSION['response']) && !empty($_SESSION['response']['data'])){
					databaseOperation($host_name, $db_name, $db_user, $db_password, $_SESSION['response']['data']);
					$options = array('server'=>$_SESSION['domain'],'root'=>$root_path,'db_host'=>$host_name,'db_user'=>$db_user,'db_password'=>$db_password,'db_name'=>$db_name);
					write_config_files($options);
					unset($_SESSION['step']);
					$_SESSION['step'] = 4;
				} else {
					session_unset();
					session_destroy();
					header('Location: '.$_SERVER['REQUEST_URI']);
				}
			}
		} else {
			$msg2 = 'Database information cannot be empty';
		} 
	}
}
//
function callAPI($url, $data){
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response_json = curl_exec($ch);
	curl_close($ch);
	return json_decode($response_json, true);
}
//
function databaseOperation($mysql_host,$mysql_database,$mysql_user,$mysql_password,$query){
	$db = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);
	$stmt = $db->prepare($query);
	if ($stmt->execute())
		 return true;
	else 
		 return false;
}
//
function write_config_files($options) {
	$output  = '<?php' . "\n";
	$output .= 'define(\'CURRENCY\', \'$\');' . "\n";
	$output .= 'define(\'WEB_URL\', \'' . $options['server'] . '\');' . "\n";
	$output .= 'define(\'ROOT_PATH\', \'' . $options['root'] . '\');' . "\n\n\n";
	$output .= 'define(\'DB_HOSTNAME\', \'' . addslashes($options['db_host']) . '\');' . "\n";
	$output .= 'define(\'DB_USERNAME\', \'' . addslashes($options['db_user']) . '\');' . "\n";
	$output .= 'define(\'DB_PASSWORD\', \'' . addslashes($options['db_password']) . '\');' . "\n";
	$output .= 'define(\'DB_DATABASE\', \'' . addslashes($options['db_name']) . '\');' . "\n";
	$output .= '$link = new mysqli(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE);';
	$output .= '?>';

	$file = fopen($options['root'] . 'config.php', 'w');
	fwrite($file, $output);
	fclose($file);
}
//
function home_base_url(){   
	$base_url = (isset($_SERVER['HTTPS']) &&
	$_SERVER['HTTPS']!='off') ? 'https://' : 'http://';
	$tmpURL = dirname(__FILE__);
	$tmpURL = str_replace(chr(92),'/',$tmpURL);
	$tmpURL = str_replace($_SERVER['DOCUMENT_ROOT'],'',$tmpURL);
	$tmpURL = ltrim($tmpURL,'/');
	$tmpURL = rtrim($tmpURL, '/');
	$tmpURL = str_replace('install','',$tmpURL);
	$base_url .= $_SERVER['HTTP_HOST'].'/'.$tmpURL;
	return $base_url; 
}
//step
$step_1 = '';
$step_2 = '';
$step_3 = '';
if(isset($_SESSION['step']) && $_SESSION['step']==1){
	$step_1 = 'rms-current-step';
}
if(isset($_SESSION['step']) && $_SESSION['step']==2){
	$step_1 = 'completed-step';
	$step_2 = 'rms-current-step';
	$step_3 = '';
}
if(isset($_SESSION['step']) && $_SESSION['step']==3){
	$step_1 = 'completed-step';
	$step_2 = 'completed-step';
	$step_3 = 'rms-current-step';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>Garage or Workshop Management System Installation</title>
<link href="media/css.css" rel="stylesheet">
<link href="media/font-awesome/css/font-awesome.css" rel="stylesheet">
<link href="media/bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="media/bootstrap/css/bootstrap-grid.css" rel="stylesheet">
<link href="media/multistep.css" rel="stylesheet">
<link href="media/animate.css" rel="stylesheet">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<!--Multi Step Wizard Start-->
<div id="rms-wizard" class="rms-wizard defaultTheme">
  <!--Wizard Container-->
  <div class="rms-container">
    <!--Wizard Header-->
    <div class="rms-wizard-header">
      <h2 class="title"><i class="fa fa-wrench"></i> Garage or Workshop Management System <span>Installation Wizard</span></h2>
    </div>
    <!--Wizard Header Close-->
    <div class="rms-form-wizard">
      <!--Wizard Step Navigation Start-->
      <div class="rms-step-section compeletedStepClickable" data-step-counter="true" data-step-image="false">
        <?php if(isset($_SESSION['step']) && $_SESSION['step'] != 4) { ?>
        <ul class="rms-multistep-progressbar">
          <li class="rms-step <?php echo $step_1; ?>"> <span class="step-icon"><i class="fa fa-lock" aria-hidden="true"></i></span> <span class="step-title">Privacy Policy</span> <span class="step-info">Read our privacy policy and terms of service</span> </li>
          <li class="rms-step <?php echo $step_2; ?>"> <span class="step-icon"><i class="fa fa-user" aria-hidden="true"></i></span> <span class="step-title">Verify Purchase</span> <span class="step-info">Enter domain name and purchase code details</span> </li>
          <li class="rms-step <?php echo $step_3; ?>"> <span class="step-icon ml10"><i class="fa fa-credit-card" aria-hidden="true"></i></span> <span class="step-title">Database information</span> <span class="step-info">Enter database information details</span> </li>
        </ul>
        <?php } ?>
      </div>
      <!--Wizard Navigation Close-->
      <!--Wizard Content Section Start-->
      <div class="rms-content-section">
        <div class="rms-content-box <?php echo isset($_SESSION['step']) && $_SESSION['step']==1 ? 'rms-current-section' : ''; ?>">
          <div class="rms-content-area animated fadeIn">
            <div class="rms-content-title">
              <div class="leftside-title"><b> <i class="fa fa-address-card-o" aria-hidden="true"></i> Privacy</b> Policy</div>
              <div class="step-label">Step 1 - 3 </div>
            </div>
            <div class="rms-content-body">
              <div class="row">
                <div class="col-md-12">
                  <form method="post" enctype="multipart/form-data" onSubmit="return validate_me(1);" id="frm_step_1">
                    <div class="row">
                      <div class="col-md-12" style="color:#666;font-size:12px;height:200px;overflow:auto;">
                        <ul class="list-group">
                          <li class="list-group-item">You cannot install more than one domian based on <b>envato licence</b>.</li>
                          <li class="list-group-item">During installation time we will take your domian name, purchase code and email address. <b>Your information is Safe here &amp; never shared</b> anywhere and when we will update our application or need any support we will contact with your given email address.</li>
                          <li class="list-group-item">If you change domain future that time you can contact with us via our support email devsolver@gmail.com.</li>
                          <li class="list-group-item">If you install application into localhost then no problem after install you can copy your local copy to server so no need to install again for live server.</li>
                          <li class="list-group-item">If you need install support kindly contact with our support email devsolver@gmail.com anf that time you need you provide your hosting details also.</li>
                          <li class="list-group-item">After install done kindly change your all demo access information from your server otherwise we will not take any <b>liability</b> for any problem or hack.</li>
                          <li class="list-group-item">If envato support expired then you need to buy support again if you need future support.</li>
                          <li class="list-group-item">We will give you support if you find any application error.</li>
						              <li class="list-group-item">For extra work we will take customization cost from you.</li>
                          <li class="list-group-item">Take schedule backup your application and database otherwise we cannot take any liability if you loose them.</li>
                        </ul>
                        <div style="margin:7px;">
                          <input type="checkbox" id="chk_read" name="chk_read" />
                          &nbsp; I have read and accept the Terms of Service & Privacy Policy</div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="rms-content-box <?php echo isset($_SESSION['step']) && $_SESSION['step']==2 ? 'rms-current-section' : ''; ?>">
          <div class="rms-content-area">
            <?php if(!empty($msg)) { ?>
            <br/>
            <div class="alert alert-danger"><strong>Error!</strong> <?php echo $msg; ?></div>
            <?php } ?>
            <div class="rms-content-title">
              <div class="leftside-title"><b> <i class="fa fa-shopping-cart" aria-hidden="true"></i> Purchase</b> Information</div>
              <div class="step-label">Step 2 - 3 </div>
            </div>
            <div class="rms-content-body">
              <div class="row">
                <div class="col-md-8">
                  <form method="post" enctype="multipart/form-data" onSubmit="return validate_me(2);" id="frm_step_2">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="inpt-form-group">
                          <label for="password">Email Address: </label>
                          <div class="inpt-group">
                            <input name="txtEmailAddress" id="txtEmailAddress" class="inpt-control" placeholder="Email Address" value="<?php echo $email; ?>" type="email">
                            <div class="form-tooltip"> <span class="tooltip-title">Why do we need this info?</span>
                              <p class="tooptip-content">Future we will contact with you about update or any support.</p>
                              <span class="tooltip-info">Your information is Safe here &amp; never shared.</span> </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="inpt-form-group">
                          <label for="txtDomainName">Domain Name: </label>
                          <div class="inpt-group"></span>
                            <input name="txtDomainName" id="txtDomainName" class="inpt-control" placeholder="http://www.google.com" type="text" value="<?php echo $base_url; ?>" required>
                            <div class="form-tooltip"> <span class="tooltip-title">Why do we need this info?</span>
                              <p class="tooptip-content">We need your domain information because this product under license.</p>
                              <span class="tooltip-info">Your information is Safe here &amp; never shared.</span> </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="inpt-form-group">
                          <label for="password">Purchase Code: </label>
                          <div class="inpt-group">
                            <input name="txtPurchaseCode" id="txtPurchaseCode" class="inpt-control" placeholder="purchase code" type="text" value="<?php echo $purchase_code; ?>" required>
                            <div class="form-tooltip"> <span class="tooltip-title">Why do we need this info?</span>
                              <p class="tooptip-content">We need to verify your purchase code before use this application.</p>
                              <span class="tooltip-info">Your information is Safe here &amp; never shared.</span> </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="rms-content-box <?php echo isset($_SESSION['step']) && $_SESSION['step']==3 ? 'rms-current-section' : ''; ?>">
          <div class="rms-content-area">
            <?php if(!empty($msg2)) { ?>
            <br/>
            <div class="alert alert-danger"><strong>Error!</strong> <?php echo $msg2; ?></div>
            <?php } ?>
            <div class="rms-content-title">
              <div class="leftside-title"><b> <i class="fa fa-database" aria-hidden="true"></i> Database</b> Information</div>
              <div class="step-label">Step 3 - 3 </div>
            </div>
            <div class="rms-content-body">
              <div class="row">
                <div class="col-md-8">
                  <form method="post" enctype="multipart/form-data" onSubmit="return validate_me(3);" id="frm_step_3">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="inpt-form-group">
                          <label for="Email">Domain Name: </label>
                          <div class="inpt-group"> <span class="inpt-group-addon"></span>
                            <input name="domain" id="domain" class="inpt-control" readonly="true" value="<?php echo $_SESSION['domain']; ?>" type="text">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="inpt-form-group">
                          <label for="Email">Root Path: </label>
                          <div class="inpt-group"> <span class="inpt-group-addon"></span>
                            <input name="txtDocRoot" id="txtDocRoot" class="inpt-control" value="<?php echo !empty($root_path) ? $root_path : DIR_SMS; ?>" type="text">
                            <div class="form-tooltip"> <span class="tooltip-title">Why do we need this info?</span>
                              <p class="tooptip-content">We need to setup document root into our application if its correct then ok otherwise kindly make it perfect based on your cpanel document root path.</p>
                              <span class="tooltip-info">Your information is Safe here &amp; never shared.</span> </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="inpt-form-group">
                          <label for="Email">Host Name: </label>
                          <div class="inpt-group"> <span class="inpt-group-addon"></span>
                            <input name="txtHostName" id="txtHostName" class="inpt-control" value="<?php echo !empty($host_name) ? $host_name : $_SERVER['SERVER_NAME']; ?>" type="text">
                            <div class="form-tooltip"> <span class="tooltip-title">Why do we need this info?</span>
                              <p class="tooptip-content">We need to add host name for database setup if its correct then ok otherwise kindly make it perfect based on your cpanel database host name.</p>
                              <span class="tooltip-info">Your information is Safe here &amp; never shared.</span> </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="inpt-form-group">
                          <label for="Email">Database User: </label>
                          <div class="inpt-group"> <span class="inpt-group-addon"></span>
                            <input name="txtDBUserName" id="txtDBUserName" class="inpt-control" type="text" value="<?php echo $db_user; ?>">
                            <div class="form-tooltip"> <span class="tooltip-title">Why do we need this info?</span>
                              <p class="tooptip-content">We need to add database user for database setup.</p>
                              <span class="tooltip-info">Your information is Safe here &amp; never shared.</span> </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="inpt-form-group">
                          <label for="Email">Database Password: </label>
                          <div class="inpt-group"> <span class="inpt-group-addon"></span>
                            <input name="txtPassword" id="txtPassword" class="inpt-control" type="text" value="<?php echo $db_password; ?>">
                            <div class="form-tooltip"> <span class="tooltip-title">Why do we need this info?</span>
                              <p class="tooptip-content">We need to add database password for database setup..</p>
                              <span class="tooltip-info">Your information is Safe here &amp; never shared.</span> </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="inpt-form-group">
                          <label for="Email">Database Name: </label>
                          <div class="inpt-group"> <span class="inpt-group-addon"></span>
                            <input name="txtDBName" id="txtDBName" class="inpt-control" type="text" value="<?php echo $db_name; ?>">
                            <div class="form-tooltip"> <span class="tooltip-title">Why do we need this info?</span>
                              <p class="tooptip-content">We need to add database name for database setup..</p>
                              <span class="tooltip-info">Your information is Safe here &amp; never shared.</span> </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="rms-content-box <?php echo isset($_SESSION['step']) && $_SESSION['step']==4 ? 'rms-current-section' : ''; ?>">
          <div class="rms-content-area animated fadeIn">
            <div class="rms-content-title"></div>
            <div class="rms-content-body">
              <div class="row">
                <div class="col-md-12">
                  <div align="center"><img src="media/img/ok.png" style="width:15%;" /></div>
                  <div style="font-weight:bold;text-align:center;font-size:25px;">Thank You for installation</div>
                  <div style="text-align:left;">If you face any trouble kindly contact with our support email address <b>devsolver@gmail.com</b> don't forget to remove installation folder from root directory.</div>
				  
				  <div class="row" style="margin-top:50px;">
						<div class="col-md-6"><a target="_blank" href="<?php echo isset($_SESSION['domain']) ? $_SESSION['domain'] : '#'; ?>admin.php"><img style="width:100%;border:solid 1px #ccc;" src="media/img/admin.png" /></a></div>
				  		<div class="col-md-6"><a target="_blank" href="<?php echo isset($_SESSION['domain']) ? $_SESSION['domain'] : '#'; ?>"><img style="width:100%;border:solid 1px #ccc;" src="media/img/front.png" /></a></div>
				  </div>
				  <div style="clear:both;"></div>
                </div>
              </div>
              <br/>
              <br/>
              <div style="clear:both;"></div>
              <div class="row">
                <div class="col-md-12">
                  <div style="text-align:center;text-decoration:underline;font-size:20px;color:red;">Login Access</div>
                </div>
                <div class="col-md-12">
                  <table class="table" style="font-size:14px;width:99%;border:solid 2px #666;" align="center">
                    <tr>
                      <td style="color:green; background:#fff;">User Type</td>
                      <td style="color:green; background:#fff;">Username</td>
                      <td style="color:green; background:#fff;">Password</td>
                    </tr>
                    <tr style="background:#00a65a;color:#fff;">
                      <td>Admin</td>
                      <td>eliza@gmail.com</td>
                      <td>123456</td>
                    </tr>
                    <tr style="background:#00a65a;color:#fff;">
                      <td>Customer</td>
                      <td>johnsina@gmail.com</td>
                      <td>123456</td>
                    </tr>
                    <tr style="background:#00a65a;color:#fff;">
                      <td>Mechanics</td>
                      <td>henry@gmail.com</td>
                      <td>123456</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--Wizard Content Section Close-->
      <!--Wizard Footer section Start-->
      <div class="rms-footer-section">
        <div class="button-section">
          <?php if(isset($_SESSION['step']) && $_SESSION['step']==1) { ?>
          <span class="next"> <a href="javascript:void(0)" onClick="javascript:$('#frm_step_1').submit();" class="btn">Next Step <small>Verify Purchase</small> </a> </span>
          <?php } ?>
          <?php if(isset($_SESSION['step']) && $_SESSION['step']==2) { ?>
          <span class="next"> <a href="javascript:void(0)" onClick="javascript:$('#frm_step_2').submit();" class="btn">Next Step <small>Database Information</small> </a> </span>
          <?php } ?>
          <?php if(isset($_SESSION['step']) && $_SESSION['step']==3) { ?>
          <span class="next"> <a href="javascript:void(0)" onClick="javascript:$('#frm_step_3').submit();" class="btn">Next Step <small>Thank You</small> </a> </span>
          <?php } ?>
        </div>
        <hr/>
        <div align="center"><a target="_blank" href="http://sakosys.com" style="text-decoration:none;font-size:12px;">Copyright © 2014-2018 sakosys.com. All rights reserved. </a></div>
      </div>
      <!--Wizard Footer Close-->
    </div>
  </div>
  <br/>
  <br/>
  <!--Wizard Container Close-->
</div>
<!--Multi Step Wizard Close-->
<script src="media/jquery.min.js"></script>
<script src="media/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="media/script.js"></script>
</body>
</html>
