<?php 
include('../header.php');
$success = "none";
$slider_text = "";
$slider_url = "";
$html_text = "";
$sort_id = "0";
$slider_status = "1";
$title = 'Add New Slider';
$button_text="Save Information";
$id="";
$hdnid="0";
$image_sup = WEB_URL . 'img/no_image.jpg';  
$img_track = '';

if(isset($_POST['txtStext'])){
	$image_url = uploadImage();
	if(empty($image_url)) {
		$image_url = $_POST['img_exist'];
	}
	$wms->saveUpdateSliderInformation($link, $_POST, $image_url);
	if((int)$_POST['slider_id'] > 0){
		$url = WEB_URL.'cms/sliderlist.php?m=up';
		header("Location: $url");
	} else {		
		$url = WEB_URL.'cms/sliderlist.php?m=add';
		header("Location: $url");
	}
	exit();
}

if(isset($_GET['id']) && $_GET['id'] != ''){
	$row = $wms->getSliderInfoBySliderId($link, $_GET['id']);
	if(!empty($row)){
		$slider_text = $row['slider_text'];
		$slider_url = $row['slider_url'];
		$html_text = $row['html_text'];
		$sort_id = $row['sort_id'];		
		if($row['slider_image'] != ''){
			$image_sup = WEB_URL.'img/slider/'.$row['slider_image'];
			$img_track = $row['slider_image'];
		}
		$hdnid = $_GET['id'];
		$title = 'Update Slider';
		$button_text="Update Slider";
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
		move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], ROOT_PATH . '/img/slider/' . $newfilename);
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
  <h1> Home Slider </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Slider</li>
    <li class="active">Add Slider</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" onclick="javascript:$('#frm_slider').submit();" data-toggle="tooltip" href="javascript:;" data-original-title="<?php echo $button_text; ?>"><i class="fa fa-save"></i></a> &nbsp;<a class="btn btn-warning" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>cms/sliderlist.php" data-original-title="Back"><i class="fa fa-reply"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title col-md-12"><?php echo $title; ?></h3>
      </div>
      <form id="frm_slider" onSubmit="return validateMe();" method="post" enctype="multipart/form-data">
        <div class="box-body">
          <div class="form-group col-md-12">
            <label for="txtStext"><span style="color:red;">*</span> Slider Alter Text :</label>
            <input type="text" name="txtStext" value="<?php echo $slider_text;?>" id="txtStext" class="form-control" />
          </div>
          <div class="form-group col-md-12">
            <label for="txtSurl">Url :</label>
            <input type="text" name="txtSurl" value="<?php echo $slider_url;?>" id="txtSurl" class="form-control" />
          </div>
          <div class="form-group col-md-12">
            <label for="html_text">Slider Text:</label>
            <textarea name="html_text" id="html_text" class="form-control summernote" style="height:300px;"><?php echo $html_text;?></textarea>
          </div>
          <div class="form-group col-md-12">
            <label for="Prsnttxtarea"><span style="color:red;">*</span> Image :</label>
            <img class="form-control" src="<?php echo $image_sup; ?>" style="height:156px;width:380px;" id="output"/>
            <input type="hidden" name="img_exist" value="<?php echo $img_track; ?>" />
          </div>
          <div class="form-group col-md-12"> <span class="btn btn-file btn btn-success">Upload Image
            <input type="file" name="uploaded_file" id="uploaded_file" onchange="loadFile(event)" />
            </span> </div>
          <div class="form-group col-md-12">
            <label for="txtSid">Sort Order :</label>
            <input type="number" name="txtSid" value="<?php echo $sort_id;?>" id="txtSid" class="form-control" />
          </div>
		  <div class="form-group col-md-12">
            <label for="status">Status :</label>
            <select class="form-control" name="status" id="status">
              <option <?php if($slider_status == '1'){echo 'selected'; }?> value='1'>Enable</option>
              <option <?php if($slider_status == '0'){echo 'selected'; }?> value='0'>Disable</option>
            </select>
          </div>
        </div>
        <input type="hidden" value="<?php echo $hdnid; ?>" name="slider_id"/>
      </form>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
<!-- /.row -->
<script type="text/javascript">
function validateMe(){
	if($("#txtStext").val() == ''){
		alert("Slider Alter Text is Required !!!");
		$("#txtStext").focus();
		return false;
	}	
	else{
		return true;
	}
}
</script>
<?php include('../footer.php'); ?>
