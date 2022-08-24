<?php 
include('../header.php');
$service_title 			= '';
$service_sort_desc 		= '';
$sort_order 			= '0';
$page_id				= '0';
$service_status 		= '1';
$title 					= 'Add New Service';
$button_text			= 'Save Information';
$form_url 				=  WEB_URL . 'cms/donationpackage.php';
$hdnid					= '0';
$image_sup 				= WEB_URL . 'img/no_image.jpg';  
$img_track 				= '';

if(isset($_POST['service_title'])){
	$image_url = uploadImage();
	if(empty($image_url)) {
		$image_url = $_POST['img_exist'];
	}
	$wms->saveUpdateServiceInformation($link,$_POST,$image_url);
	if((int)$_POST['service_id'] > 0){
		$url = WEB_URL.'cms/servicelist.php?m=up';
		header("Location: $url");
	} else {		
		$url = WEB_URL.'cms/servicelist.php?m=add';
		header("Location: $url");
	}
	exit();
}

if(isset($_GET['id']) && $_GET['id'] != ''){
	$row = $wms->getServiceInfoByServiceId($link, $_GET['id']);
	if(!empty($row)){
		$service_title 			= $row['service_name'];
		$service_sort_desc 		= $row['short_description'];
		$sort_order 			= $row['sort_order'];	
		$page_id				= $row['page_id'];
		$service_status 		= $row['status'];	
		if($row['image_url'] != ''){
			$image_sup = WEB_URL . 'img/service/' . $row['image_url'];
			$img_track = $row['image_url'];
		}
		$hdnid = $_GET['id'];
		$title = 'Update Service';
		$button_text="Update Information";	
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
		move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], ROOT_PATH . '/img/service/' . $newfilename);
		return $newfilename;
	  } else {
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
  <h1> <?php echo $title; ?> </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Service</li>
    <li class="active">Add new service</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" onclick="javascript:$('#frm_service').submit();" data-toggle="tooltip" href="javascript:;" data-original-title="<?php echo $button_text; ?>"><i class="fa fa-save"></i></a> &nbsp;<a class="btn btn-warning" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>cms/servicelist.php" data-original-title="Back"><i class="fa fa-reply"></i></a> </div>
    <div class="box box-success">
      <form onSubmit="return validateMe();" id="frm_service" method="post" enctype="multipart/form-data">
        <div class="box-body">
          <div class="form-group col-md-12">
            <label for="service_title"><span style="color:red;">*</span> Service Title :</label>
            <input type="text" name="service_title" value="<?php echo $service_title;?>" id="service_title" class="form-control" />
          </div>
          <div class="form-group col-md-12">
            <label for="service_sort_desc"><span style="color:red;">*</span> Service Short Description:</label>
            <textarea name="service_sort_desc" id="service_sort_desc" class="form-control"><?php echo $service_sort_desc;?></textarea>
          </div>
		  <div class="form-group col-md-12">
            <label>Link Menu with CMS Page : </label>
            <div style="border:solid 1px #ccc; width:100%;height:100px;overflow:auto;padding:8px;">
              <?php
				$result_cms = $wms->getCMSPageList($link);
				foreach($result_cms as $row_cms) {
					if(is_numeric($page_id) && (int)$page_id > 0 && (int)$page_id == (int)$row_cms['cms_id']) {
						echo '<div><input name="rbCMSPage" checked type="radio" value="'.$row_cms['cms_id'].'"><label class="label label-success">'.$row_cms['page_title'].'<label></div>';
					} else {
						echo '<div><input name="rbCMSPage" type="radio" value="'.$row_cms['cms_id'].'"><label class="label label-success">'.$row_cms['page_title'].'</label></div>';
					}
				
				} ?>
            </div>
          </div>
		  <div class="form-group col-md-12">
            <label for="sort_order"><span style="color:red;">*</span> Sort Order :</label>
            <input type="text" name="sort_order" value="<?php echo $sort_order;?>" id="sort_order" class="form-control" />
          </div>
          <div class="form-group col-md-12">
            <label for="status">Status :</label>
            <select class="form-control" name="status" id="status">
              <option <?php if($service_status == '1'){echo 'selected'; }?> value='1'>Enable</option>
              <option <?php if($service_status == '0'){echo 'selected'; }?> value='0'>Disable</option>
            </select>
          </div>
          <div class="form-group col-md-12">
            <label for="Prsnttxtarea">Service Image (70x70px) :</label>
            <img class="form-control" src="<?php echo $image_sup; ?>" style="height:70px;width:70px;padding:3px !important;" id="output"/>
            <input type="hidden" name="img_exist" value="<?php echo $img_track; ?>" />
          </div>
          <div class="form-group col-md-12"> <span class="btn btn-file btn btn-success">Upload
            <input type="file" name="uploaded_file" id="uploaded_file" onchange="loadFile(event)" />
            </span> </div>
        </div>
        <input type="hidden" value="<?php echo $hdnid; ?>" name="service_id"/>
      </form>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
<!-- /.row -->
<script type="text/javascript">
function validateMe(){
	if($("#service_title").val() == ''){
		alert("Service title required !!!");
		$("#service_title").focus();
		return false;
	}else if($("#service_sort_desc").val() == ''){
		alert("Short description required !!!");
		$("#service_sort_desc").focus();
		return false;
	}else{
		return true;
	}
}
</script>
<?php include('../footer.php'); ?>
