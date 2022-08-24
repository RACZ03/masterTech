<?php 
include('../header.php');
$success = "none";
$c_name = '';
$c_email = '';
$c_address = '';
$c_home_tel = '';
$c_work_tel = '';
$c_mobile = '';
$c_password = '';
$title = 'Add New Customer';
$button_text="Save Information";
$successful_msg="Add Customer Successfully";
$form_url = WEB_URL . "customer/addcustomer.php";
$id="";
$hdnid="0";
$image_cus = WEB_URL . 'img/no_image.jpg';
$img_track = '';
$wow = false;

/*#############################################################*/
if(isset($_POST['txtCName'])){
	if(!$wms->checkCustomerEmailAddress($link, $_POST['txtCEmail'], $_POST)) {
		$image_url = uploadImage();
		if(empty($image_url)) {
			$image_url = $_POST['img_exist'];
		}
		$wms->saveUpdateCustomerInformation($link, $_POST, $image_url);
		if((int)$_POST['customer_id'] > 0){
			$url = WEB_URL.'customer/customerlist.php?m=up';
			header("Location: $url");
		} else {		
			$url = WEB_URL.'customer/customerlist.php?m=add';
			header("Location: $url");
		}
		exit();
	} else {
		$wow = true;
	}
}

if(isset($_GET['id']) && $_GET['id'] != ''){
	$row = $wms->getCustomerInfoByCustomerId($link, $_GET['id']);
	if(!empty($row)) {
		$c_name = $row['c_name'];
		$c_email = $row['c_email'];
		$c_address = $row['c_address'];
		$c_home_tel = $row['c_home_tel'];
		$c_work_tel = $row['c_work_tel'];
		$c_mobile = $row['c_mobile'];
		$c_password = $row['c_password'];
		if($row['image'] != ''){
			$image_cus = WEB_URL . 'img/upload/' . $row['image'];
			$img_track = $row['image'];
		}
		$hdnid = $_GET['id'];
		$title = 'Update Customer';
		$button_text="Update Information";
		$successful_msg="Update Customer Successfully";
		$form_url = WEB_URL . "customer/addcustomer.php?id=".$_GET['id'];
	}
}

//for image upload
function uploadImage(){
	if((!empty($_FILES["uploaded_file"])) && ($_FILES['uploaded_file']['error'] == 0)) {
	  $filename = basename($_FILES['uploaded_file']['name']);
	  $ext = substr($filename, strrpos($filename, '.') + 1);
	  if(($ext == "jpg" && $_FILES["uploaded_file"]["type"] == 'image/jpeg') || ($ext == "png" && $_FILES["uploaded_file"]["type"] == 'image/png') || ($ext == "gif" && $_FILES["uploaded_file"]["type"] == 'image/gif')){   
	  	$temp = explode(".",$_FILES["uploaded_file"]["name"]);
	  	$newfilename = NewGuid() . '.' .end($temp);
		move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], ROOT_PATH . '/img/upload/' . $newfilename);
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
	
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-users"></i> Customer </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?php echo WEB_URL?>customer.customerlist.php">Customer</a></li>
    <li class="active">Add/Update Customer</li>
  </ol>
</section>
<!-- Main content -->
<form onSubmit="return validateMe();" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">
  <section class="content">
    <!-- Full Width boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12">
      <?php if($wow) { ?>
	  <div id="me" class="alert alert-warning alert-dismissable">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-ban"></i> Warning!</h4>
      Email already exist choose another one. </div>
	  <?php } ?>
		<!--<div class="box box-success" id="box_model">
          <div class="box-body">
            <div class="form-group col-md-12" style="padding-top:10px;">
              <div class="pull-right">
                <button type="submit" class="btn btn-success btnsp"><i class="fa fa-save fa-2x"></i><br/>
                <?php //echo $button_text; ?></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-warning btnsp" data-toggle="tooltip" href="<?php //echo WEB_URL; ?>customer/customerlist.php" data-original-title="Back"><i class="fa fa-reply  fa-2x"></i><br/>
                Back</a> </div>
            </div>
          </div>
        </div>-->
		
	   <div align="right" style="margin-bottom:1%;"> <button class="btn btn-success" type="submit" data-toggle="tooltip" href="javascript:;" data-original-title="<?php echo $button_text; ?>"><i class="fa fa-save"></i></button> &nbsp;<a class="btn btn-warning" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>customer/customerlist.php" data-original-title="Back"><i class="fa fa-reply"></i></a> </div>
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-plus"></i> Customer Form</h3>
          </div>
          <div class="box-body">
            <div class="form-group">
              <label for="txtCName"><span style="color:red;">*</span> Nombre :</label>
              <input type="text" name="txtCName" value="<?php echo $c_name;?>" id="txtCName" class="form-control" />
            </div>
            <div class="form-group">
              <label for="txtCEmail"><span style="color:red;">*</span> E-Mail :</label>
              <input type="text" name="txtCEmail" value="<?php echo $c_email;?>" id="txtCEmail" class="form-control" />
            </div>
            <div class="form-group">
              <label for="txtCAddress"><span style="color:red;">*</span> Dirección :</label>
              <textarea name="txtCAddress" id="txtCAddress" class="form-control"><?php echo $c_address;?></textarea>
            </div>
            <div class="form-group">
              <label for="txtCHomeTel"><span style="color:red;">*</span> Teléfono de casa :</label>
              <input type="text" name="txtCHomeTel" value="<?php echo $c_home_tel;?>" id="txtCHomeTel" class="form-control" />
            </div>
		  <div class="form-group">
              <label for="txtCMobile"><span style="color:red;">*</span> Teléfono móvil :</label>
              <input type="text" name="txtCMobile" value="<?php echo $c_mobile;?>" id="txtCMobile" class="form-control" />
            </div>
		  <div class="form-group">
              <label for="txtCWorkTel"><span style="color:red;">*</span> Teléfono :</label>
              <input type="text" name="txtCWorkTel" value="<?php echo $c_work_tel;?>" id="txtCWorkTel" class="form-control" />
            </div>
            <div class="form-group">
              <label for="txtCPassword"><span style="color:red;">*</span> Contraseña :</label>
              <input type="text" name="txtCPassword" value="<?php echo $c_password;?>" id="txtCPassword" class="form-control" />
            </div>
            <div class="form-group">
              <label for="Prsnttxtarea">Priview :</label>
              <img class="form-control" src="<?php echo $image_cus; ?>" style="height:100px;width:100px;" id="output"/>
              <input type="hidden" name="img_exist" value="<?php echo $img_track; ?>" />
            </div>
            <div class="form-group"> <span class="btn btn-file btn btn-primary">Upload Image
              <input type="file" name="uploaded_file" onchange="loadFile(event)" />
              </span> </div>
          </div>
          <input type="hidden" value="<?php echo $hdnid; ?>" name="customer_id"/>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
    </div>
  </section>
</form>
<!-- /.row -->
<script type="text/javascript">
function validateMe(){
	if($("#txtCName").val() == ''){
		alert("Customer Name is Required !!!");
		$("#txtCName").focus();
		return false;
	}
	else if($("#txtCEmail").val() == ''){
		alert("Email is Required !!!");
		$("#txtCEmail").focus();
		return false;
	}
	else if($("#txtCAddress").val() == ''){
		alert("Address is Required !!!");
		$("#txtCAddress").focus();
		return false;
	}
	else if($("#txtCHomeTel").val() == ''){
		alert("Home Tel Number is Required !!!");
		$("#txtCHomeTel").focus();
		return false;
	}
	else if($("#txtCMobile").val() == ''){
		alert("Mobile Tel Number is Required !!!");
		$("#txtCMobile").focus();
		return false;
	}
	else if($("#txtCWorkTel").val() == ''){
		alert("Work Tel Number is Required !!!");
		$("#txtCWorkTel").focus();
		return false;
	}
	else if($("#txtCPassword").val() == ''){
		alert("Password is Required !!!");
		$("#txtCPassword").focus();
		return false;
	}
	else{
		return true;
	}
}
</script>
<script type="text/javascript">
  $( document ).ready(function() {
	setTimeout(function() {
		  $("#me").hide(300);
		  $("#you").hide(300);
	}, 3000);
});
</script>
<?php include('../footer.php'); ?>
