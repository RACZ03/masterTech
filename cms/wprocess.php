<?php include('../header.php')?>
<?php
$msg = "";
$image_cus = WEB_URL . 'img/no_image.jpg';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$wms->deleteWorkProcessImage(ROOT_PATH.'img/work_process/process.png');
	if(empty($_POST['chkDelete'])){
		$image_url = uploadImage();
	}
	$msg = "Operation done successfully";
}

//for image upload
function uploadImage(){
	if((!empty($_FILES["uploaded_file"])) && ($_FILES['uploaded_file']['error'] == 0)) {
	  $filename = basename($_FILES['uploaded_file']['name']);
	  $ext = substr($filename, strrpos($filename, '.') + 1);
	  if(($ext == "png" && $_FILES["uploaded_file"]["type"] == 'image/png')){   
	  	$temp = explode(".",$_FILES["uploaded_file"]["name"]);
	  	$newfilename = 'process.' .end($temp);
		move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], ROOT_PATH . '/img/work_process/' . $newfilename);
		return $newfilename;
	  }
	  else{
	  	return '-1';
	  }
	}
	return '';
}

if(file_exists(ROOT_PATH.'img/work_process/process.png')) {
	$image_cus = WEB_URL.'img/work_process/process.png';
}

?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1> Work Process </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li>Module</li>
    <li class="active">Work Process</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-xs-12">
    <?php if(!empty($msg)) { ?>
	<div id="you" class="alert alert-success alert-dismissable">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      <?php echo $msg; ?> </div>
	  <?php } ?>
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onclick="javascript:$('#frm_team').submit();" data-original-title="Add Slider"><i class="fa fa-save"></i></a> <a class="btn btn-warning" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="Dashboard"><i class="fa fa-dashboard"></i></a> </div>
    <div class="box box-success">
      <!-- /.box-header -->
      <div class="box-body">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-plus"></i> Add/Update Work Process Image</h3>
        </div>
        <form id="frm_team" method="post" enctype="multipart/form-data">
          <div class="col-md-12">
            <div class="form-group">
				<input name="chkDelete" type="checkbox" /> <b>Delete Image</b> (if delete image or no image then frontend work process section will hide automatically)
			</div>
			<div class="form-group">
              <label for="Prsnttxtarea">Image (1455 × 359 px png image):</label>
              <img class="form-control" src="<?php echo $image_cus; ?>" style="height:170px;width:727px;" id="output"/> </div>
            <div class="form-group"> <span class="btn btn-file btn btn-primary">Upload Image
              <input type="file" name="uploaded_file" onchange="loadFile(event)" />
              </span> </div>
          </div>
        </form>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->
<?php include('../footer.php'); ?>
