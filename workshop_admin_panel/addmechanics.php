<?php 
include('../workshop_admin_panel/header.php');
$success = "none";
$s_name = '';
//$cost_per_month = '';
$phone_number='';
$s_password = '';
$s_email='';
$s_present_address='';
$s_permanent_address='';
$s_notes='';
$mech_status = '1';
$designation_id = '';
$joining_date = date('d/m/Y');
$title = 'Add New mechanics';
$button_text="Save Information";
$successful_msg="Add Mechanics Successfully";
$form_url = WEB_URL . "workshop_admin_panel/addmechanics.php";
$id="";
$hdnid="0";
$image_sup = WEB_URL . 'img/no_image.jpg';  
$img_track = '';
$wow = false;

$designation = $wms->getMechanicsDesignation($link);

if(isset($_POST['txtSName'])){
	if(!$wms->checkMechanicsEmailAddress($link, $_POST['txtSEmail'], $_POST)) {
		$image_url = uploadImage();
		if(empty($image_url)) {
			$image_url = $_POST['img_exist'];
		}
		$wms->saveUpdateMechanicsInformation($link, $_POST, $image_url);
		if((int)$_POST['mechanics_id'] > 0){
			$url = WEB_URL.'workshop_admin_panel/mechanicslist.php?m=up';
			header("Location: $url");
		} else {		
			$url = WEB_URL.'workshop_admin_panel/mechanicslist.php?m=add';
			header("Location: $url");
		}
		exit();
	} else {
		$wow = true;
	}
}

if(isset($_GET['id']) && $_GET['id'] != ''){
	$row = $wms->getMechanicsInfoByMechanicsId($link, $_GET['id']);
	if(!empty($row)){
		$s_name = $row['m_name'];
		//$cost_per_month = $row['m_cost'];
		$phone_number=$row['m_phone_number'];
		$s_password = $row['m_password'];
		$s_email=$row['m_email'];
		$s_present_address=$row['m_present_address'];
		$s_permanent_address=$row['m_permanent_address'];
		$s_notes=$row['m_notes'];
		$designation_id=$row['designation_id'];
		$joining_date=$wms->mySqlToDatePicker($row['joining_date']);
		if($row['m_image'] != ''){
			$image_sup = WEB_URL . 'img/employee/' . $row['m_image'];
			$img_track = $row['m_image'];
		}
		$mech_status = $row['status'];
		$hdnid = $_GET['id'];
		$title = 'Update mechanics';
		$button_text="Update mechanics";
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
	
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1> Mechanics </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Mechanics</li>
    <li class="active">Add Mechanics</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <?php if($wow) { ?>
	  <div id="me" class="alert alert-warning alert-dismissable" style="display:<?php echo $delinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-ban"></i> Warning!</h4>
      Email already exist choose another one. </div>
	  <?php } ?>
	<div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" onclick="savemechanics();" data-toggle="tooltip" href="javascript:;" data-original-title="<?php echo $button_text; ?>"><i class="fa fa-save"></i></a> &nbsp;<a class="btn btn-warning" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>workshop_admin_panel/mechanicslist.php" data-original-title="Back"><i class="fa fa-reply"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title col-md-12">Add New Mechanics</h3>
      </div>
      <form id="frm_mac" onSubmit="return validateMe();" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">
        <div class="box-body">
          <div class="form-group col-md-4">
            <label for="txtSName"><span style="color:red;">*</span> Full Name :</label>
            <input type="text" name="txtSName" value="<?php echo $s_name;?>" id="txtSName" class="form-control" />
          </div>
		  <!-- <div class="form-group col-md-3">
            <label for="target_amount">Salary Per Month/Hour :</label>
            <div class="input-group"> <span class="input-group-addon"><?php echo $currency; ?></span>
              <input type="text" placeholder="Salery amount" value="<?php echo $cost_per_month; ?>" name="cost_per_month" id="cost_per_month" class="form-control allownumberonly" />
            </div>
          </div> -->
          <div class="form-group  col-md-4">
            <label for="txtPhonenumber"><span style="color:red;">*</span> Phone number:</label>
            <input type="text" name="txtPhonenumber" value="<?php echo $phone_number;?>" id="txtPhonenumber" class="form-control" />
          </div>
          <div class="form-group  col-md-4">
            <label for="txtSPassword"><span style="color:red;">*</span> Password:</label>
            <input type="password" name="txtSPassword" value="<?php echo $s_password;?>" id="txtSPassword" class="form-control" />
          </div>
          <div class="form-group  col-md-6">
            <label for="txtSEmail"><span style="color:red;">*</span> Email Address:</label>
            <input type="text" name="txtSEmail" value="<?php echo $s_email;?>" id="txtSEmail" class="form-control" />
          </div>
          <div class="form-group  col-md-6">
            <label for="txtNCard"><span style="color:red;">*</span> NID (National ID Card):</label>
            <input type="text" name="txtNCard" value="<?php echo $s_email;?>" id="txtNCard" class="form-control" />
          </div>
          <div class="form-group col-md-6">
            <label for="present_address"><span style="color:red;">*</span> Present Address:</label>
            <textarea name="present_address" id="present_address" class="form-control"><?php echo $s_present_address;?></textarea>
          </div>
          <div class="form-group col-md-6">
            <label for="permanent_address">Permanent Address :</label>
            <textarea name="permanent_address" id="permanent_address" class="form-control"><?php echo $s_permanent_address;?></textarea>
          </div>
          <div class="form-group col-md-6">
            <label for="designation">Designation :</label>
            <select class="form-control" name="designation" id="designation">
              <option value="">--Select Designation--</option>
			  <?php foreach($designation as $deg) { ?>
			  <option <?php if($designation_id == $deg['designation_id']){echo 'selected';}?> value="<?php echo $deg['designation_id']; ?>"><?php echo $deg['title']; ?></option>
			  <?php }?>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="status">Status :</label>
            <select class="form-control" name="status" id="status">
              <option <?php if($mech_status == '1'){echo 'selected'; }?> value='1'>Active</option>
              <option <?php if($mech_status == '0'){echo 'selected'; }?> value='0'>In-Active</option>
            </select>
          </div>
		  <div class="form-group  col-md-12">
            <label for="txtJoiningDate"> Joining Date: </label>
            <input type="text" name="txtJoiningDate" value="<?php echo $joining_date;?>" id="txtJoiningDate" class="form-control datepicker" />
          </div>
          <div class="form-group col-md-12">
            <label for="notes"> Notes :</label>
            <textarea name="notes" id="notes" class="form-control"><?php echo $s_notes;?></textarea>
          </div>
          <div class="form-group col-md-12">
            <label for="Prsnttxtarea">Image (280x350px) :</label>
            <img class="form-control" src="<?php echo $image_sup; ?>" style="height:175px;width:140px;" id="output"/>
            <input type="hidden" name="img_exist" value="<?php echo $img_track; ?>" />
          </div>
          <div class="form-group col-md-12"> <span class="btn btn-file btn btn-success">Upload Image
            <input type="file" name="uploaded_file" onchange="loadFile(event)" />
            </span> </div>
        </div>
        <input type="hidden" value="<?php echo $hdnid; ?>" name="mechanics_id"/>
      </form>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
<!-- /.row -->
<script type="text/javascript">
function validateMe(){
  return true;
}
</script>
<script type="text/javascript">
function savemechanics(){
	if($("#txtSName").val() == ''){
		alert("Mechanics Name is Required !!!");
		$("#txtSName").focus();
		return false;
	}
	else if($("#txtPhonenumber").val() == ''){
		alert("Phone Number is Required !!!");
		$("#txtPhonenumber").focus();
		return false;
	}
	else if($("#txtSPassword").val() == ''){
		alert("Password is Required !!!");
		$("#txtSPassword").focus();
		return false;
	}
	else if($("#txtSEmail").val() == ''){
		alert("Email is Required !!!");
		$("#txtSEmail").focus();
		return false;
	}
	else if($("#txtNCard").val() == ''){
		alert("National ID Card Required !!!");
		$("#txtNCard").focus();
		return false;
	}
	else if($("#present_address").val() == ''){
		alert("Present Address is Required !!!");
		$("#present_address").focus();
		return false;
	}
	else if($("#designation").val() == ''){
		alert("Designation Required !!!");
		$("#designation").focus();
		return false;
	}
	else{
		var iAnswer = confirm("Are you sure you want to save this mechanics ?");
		if(iAnswer){
			javascript:$('#frm_mac').submit();
		}
	}
}
$( document ).ready(function() {
	setTimeout(function() {
		  $("#me").hide(300);
		  $("#you").hide(300);
	}, 3000);
});
</script>
<?php include('../footer.php'); ?>
