<?php 
include_once('../cust_panel/header.php');

if(isset($_POST['txtUserName'])){
	$image_url = uploadImage();
	if(empty($image_url)) {
		$image_url = $_POST['img_exist'];
	}
	$wms->updateCustomerUserProfile($link, $_POST, $image_url);
	$url = WEB_URL.'logout.php';
	header("Location: $url");
	exit();
}

//for image upload
function uploadImage(){
	if((!empty($_FILES["uploaded_file"])) && ($_FILES['uploaded_file']['error'] == 0)) {
	  $filename = basename($_FILES['uploaded_file']['name']);
	  $ext = substr($filename, strrpos($filename, '.') + 1);
	  if(($ext == "jpg" && $_FILES["uploaded_file"]["type"] == 'image/jpeg') || ($ext == "png" && $_FILES["uploaded_file"]["type"] == 'image/png') || ($ext == "gif" && $_FILES["uploaded_file"]["type"] == 'image/gif')){   
	  	$temp = explode(".",$_FILES["uploaded_file"]["name"]);
	  	$newfilename = NewGuid() . '.' .end($temp);
		move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], ROOT_PATH . '/img/employee/' . $newfilename);
		return $newfilename;
	  }
	  else{
	  	return '';
	  }
	}
	return '';
}
function NewGuid() { 
    $s = strtoupper(md5(uniqid(rand(),true))); 
    $guidText = 
        substr($s,0,8) . '-' . 
        substr($s,8,4) . '-' . 
        substr($s,12,4). '-' . 
        substr($s,16,4). '-' . 
        substr($s,20); 
    return $guidText;
}

$image = WEB_URL . 'img/no_image.jpg';
if(!empty($_SESSION['objCust']['image'])){
	$image = WEB_URL.'img/upload/'.$_SESSION['objCust']['image'];
	$img_track = $_SESSION['objCust']['image'];
}

	
?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><i class="fa fa-user"></i> User Profile </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Profile</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
  
  <div align="right" style="margin-bottom:1%;"> <button  onclick="$('#from_admin_profile').submit();" class="btn btn-success" type="submit" data-toggle="tooltip" href="javascript:;" data-original-title="Update Profile"><i class="fa fa-save"></i></button></div>
  
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title"><i class="fa fa-edit"></i> Update User Profile</h3>
		<br/><br/><h5 class="label label-danger"><i class="fa fa-gear"></i> After change your profile system will logout automatically.</h5>
      </div>
      <form onsubmit="return validateMe();" id="from_admin_profile" method="post" enctype="multipart/form-data">
        <div class="box-body">
          <div class="form-group">
            <label for="txtUserName"><span style="color:red;">*</span> Name:</label>
            <input type="text" name="txtUserName" value="<?php echo !empty($_SESSION['objCust']['name']) ? $_SESSION['objCust']['name'] : ''; ?>" id="txtUserName" class="form-control" required />
          </div>
          <div class="form-group">
            <label for="txtEmail"><span style="color:red;">*</span>Email :</label>
            <input type="text" name="txtEmail" value="<?php echo !empty($_SESSION['objCust']['email']) ? $_SESSION['objCust']['email'] : ''; ?>" id="txtEmail" class="form-control" required />
          </div>
          <div class="form-group">
            <label for="txtPassword"><span style="color:red;">*</span>Password :</label>
            <input type="text" name="txtPassword" value="<?php echo !empty($_SESSION['objCust']['password']) ? $_SESSION['objCust']['password'] : ''; ?>" id="txtPassword" class="form-control" required />
          </div>
          <div class="form-group">
            <label for="Prsnttxtarea">Preview :</label>
            <img class="form-control" src="<?php echo $image; ?>" style="height:100px;width:100px;" id="output"/>
            <input type="hidden" name="img_exist" value="<?php echo $img_track; ?>" />
          </div>
          <div class="form-group"> <span class="btn btn-file btn btn-success">Upload Image
            <input type="file" name="uploaded_file" onchange="loadFile(event)" />
            </span> </div>
        </div>
		<input type="hidden" name="hdnUserId" value="<?php echo !empty($_SESSION['objCust']['user_id']) ? $_SESSION['objCust']['user_id'] : '0'; ?>">
      </form>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
<!-- /.row -->
<script type="text/javascript">
function validateMe() {
	if($("#txtUserName").val() == ''){
		alert("User Name is Required !!!");
		$("#txtUserName").focus();
		return false;
	} else if($("#txtEmail").val() == ''){
		alert("Valid Email Required !!!");
		$("#txtEmail").focus();
		return false;
	} else if($("#txtPassword").val() == ''){
		alert("Password Required !!!");
		$("#txtPassword").focus();
		return false;
	} else {
		return true;
	}
}
</script>
<?php include('../cust_panel/footer.php'); ?>
