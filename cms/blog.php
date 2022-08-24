<?php 
include('../header.php');
$success = "none";
$cat_menu	 			= "";
$blog_title 			= "";
$blog_date 				= date("d/m/Y");
$blog_author			= "";
$blog_details 			= "";
$blog_status 			= "";
$allow_comment 			= 0;
$show_home 				= 0;
$cat_menu				= "";
$title 					= 'Add New ';
$button_text			="Save Information";
$successful_msg			="Add Successfully";
$hdnid					="0";
$image_sup 				= WEB_URL . 'img/no_image.jpg';
$image_blog_sup 		= WEB_URL . 'img/no_image.jpg';  
$img_track 				= '';
$img_track_thumb 		= '';
$blog_short_details 	= '';
$blog_seo_url			= '';
$blog_time				= '';



if(isset($_POST['blog_title'])){
	$image_url = uploadImage();
	if(empty($image_url)) {
		$image_url = $_POST['img_exist_1'];
	}
	$image_url_2 = uploadImage2();
	if(empty($image_url_2)) {
		$image_url_2 = $_POST['img_exist_2'];
	}
	
	if(empty($image_url)) {$image_url = $_POST['img_exist'];}
	$wms->saveUpdateNewsInformation($link, $_POST, $image_url, $image_url_2);
	if((int)$_POST['blog_id'] > 0){
		$url = WEB_URL.'cms/bloglist.php?m=up';
		header("Location: $url");
	} else {		
		$url = WEB_URL.'cms/bloglist.php?m=add';
		header("Location: $url");
	}
	exit();
}


if(isset($_GET['id']) && $_GET['id'] != ''){
	$row = $wms->getNewsDataByNewsId($link, $_GET['id']);
	if(!empty($row)){
		$cat_menu	 			= $row['blog_cat'];
		$blog_date				= $wms->mySqlToDatePicker($row['blog_date_time']);
		$blog_time				= $row['blog_time'];
		$blog_title 			= $row['blog_title'];
		$blog_author 			= $row['blog_author'];
		$allow_comment 			= $row['allow_comment'];
		$show_home 				= $row['show_home'];
		$blog_details 			= $row['blog_details'];		
		$blog_short_details 	= $row['short_desc'];
		$blog_status 			= $row['blog_status'];	
		if($row['blog_image'] != ''){
			$image_sup = WEB_URL . 'img/blog/' . $row['blog_image'];
			$img_track = $row['blog_image'];
		}
		if($row['thumb_image'] != ''){
			$image_blog_sup = WEB_URL.'img/blog/'.$row['thumb_image'];
			$img_track_thumb = $row['thumb_image'];
		}
		$blog_seo_url			= $row['blog_seo_url'];
		$hdnid 					= $_GET['id'];
		$title 					= 'Update Blog';
		$button_text			="Update";
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
		move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], ROOT_PATH . '/img/blog/' . $newfilename);
		return $newfilename;
	  }
	  else{
	  	return '';
	  }
	}
	return '';
}
//for image upload
function uploadImage2(){
	if((!empty($_FILES["uploaded_file_2"])) && ($_FILES['uploaded_file_2']['error'] == 0)) {
	  $filename = basename($_FILES['uploaded_file_2']['name']);
	  $ext = substr($filename, strrpos($filename, '.') + 1);
	  if(($ext == "jpg" && $_FILES["uploaded_file_2"]["type"] == 'image/jpeg') || ($ext == "png" && $_FILES["uploaded_file_2"]["type"] == 'image/png') || ($ext == "gif" && $_FILES["uploaded_file_2"]["type"] == 'image/gif')){   
	  	$temp = explode(".",$_FILES["uploaded_file_2"]["name"]);
	  	$newfilename = NewGuid() . '.' .end($temp);
		move_uploaded_file($_FILES["uploaded_file_2"]["tmp_name"], ROOT_PATH . '/img/blog/' . $newfilename);
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
  <h1> News </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">News</li>
    <li class="active">Add News</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" onclick="javascript:$('#frm_blog').submit();" data-toggle="tooltip" href="javascript:;" data-original-title="<?php echo $button_text; ?>"><i class="fa fa-save"></i></a> &nbsp;<a class="btn btn-warning" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>cms/bloglist.php" data-original-title="Back"><i class="fa fa-reply"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title">News Entry Form</h3>
      </div>
      <form onSubmit="return validateMe();" id="frm_blog" method="post" enctype="multipart/form-data">
        <div class="box-body">
          <div class="form-group col-md-6">
            <label for="blog_date"><span style="color:red;">*</span> News Date :</label>
            <input type="text" name="blog_date" value="<?php echo $blog_date;?>" id="blog_date" class="form-control datepicker" />
          </div>
		  <div class="form-group col-md-6">
            <label for="blog_date"><span style="color:red;">*</span> News Time :</label>
            <input type="text" name="blog_time" value="<?php echo $blog_time;?>" id="blog_time" placeholder="12:50 AM" class="form-control" />
          </div>
          <div class="form-group col-md-6">
            <label for="blog_cat">News Category :</label>
            <select class="form-control" name="blog_cat" id="blog_cat">
              <option value=''>--Select Category--</option>
              <?php
				$results = $wms->getCategoryData($link);
				foreach($results as $row){
					if($cat_menu > 0 && $cat_menu == $row['category_id']) {
						echo "<option selected value='".$row['category_id']."'>".$row['category_name']."</option>";
					} else {
						echo "<option value='".$row['category_id']."'>".$row['category_name']."</option>";
					}
				
				} ?>
            </select>
          </div>
		  <div class="form-group col-md-6">
            <label for="blog_author">Blog Author :</label>
            <select class="form-control" name="blog_author" id="blog_author">
              <option value=''>--Select Author--</option>
              <?php
				$a_results = $wms->getAuthorData($link);
				foreach($a_results as $row){
					if($blog_author > 0 && $blog_author == $row['author_id']) {
						echo "<option selected value='".$row['author_id']."'>".$row['author_name']."</option>";
					} else {
						echo "<option value='".$row['author_id']."'>".$row['author_name']."</option>";
					}
				
				} ?>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="blog_title"><span style="color:red;">*</span> News Title :</label>
            <input type="text" name="blog_title" value="<?php echo $blog_title;?>" id="blog_title" class="form-control" />
          </div>
          <div class="form-group col-md-6">
            <label for="blog_seo_url">Seo URL : <i  data-original-title="(must be unique and avoid special character @#$%^&*()!,. if blank then system will generate automatically example: john-henry)" data-toggle="tooltip" style="color:red;" class="fa fa-question-circle"></i></label>
            <input type="text" name="blog_seo_url" value="<?php echo $blog_seo_url;?>" id="blog_seo_url" class="form-control" />
          </div>
          <div class="form-group col-md-12">
            <label for="blog_details"><span style="color:red;">*</span> Details:</label>
            <textarea name="blog_details" id="blog_details" class="form-control summernote"><?php echo $blog_details;?></textarea>
          </div>
          <div class="form-group col-md-12">
            <label for="blog_short_details"><span style="color:red;">*</span> Short Description (Max 143 Char.):</label>
            <textarea name="blog_short_details" id="blog_short_details" class="form-control"><?php echo $blog_short_details;?></textarea>
          </div>
          <div class="form-group col-md-12">
            <label>Blog Details Image (770x440 PX) :</label>
            <img class="form-control" src="<?php echo $image_sup; ?>" style="height:220px;width:385px;" id="output"/>
            <input type="hidden" name="img_exist_1" value="<?php echo $img_track; ?>" />
          </div>
          <div class="form-group col-md-12"> <span class="btn btn-file btn btn-primary">Upload Image
            <input type="file" name="uploaded_file" id="uploaded_file" onchange="loadFile(event)" />
            </span> </div>
          <div class="form-group col-md-12">
            <label>Blog Thumb Image (740x448 px) :</label>
            <img class="form-control" src="<?php echo $image_blog_sup; ?>" style="height:224px;width:370px;" id="output2"/>
            <input type="hidden" name="img_exist_2" value="<?php echo $img_track_thumb; ?>" />
          </div>
          <div class="form-group col-md-12"> <span class="btn btn-file btn btn-primary">Upload Image
            <input type="file" name="uploaded_file_2" id="uploaded_file_2" onchange="previewImage(event,'output2')" />
            </span> </div>
          <div class="form-group col-md-12">
            <label for="allow_comment">Allow Comments :</label>
			<div class="checkbox"><label style="font-size: 1.7em"><input <?php if($allow_comment == '1'){echo 'checked';} ?> type="checkbox" name="allow_comment" id="allow_comment" /><span class="cr"><i class="cr-icon fa fa-check"></i></span></label></div>
          </div>
          <div class="form-group col-md-12">
            <label for="show_home">Show Homepage ? :</label>
			<div class="checkbox"><label style="font-size: 1.7em"><input <?php if($show_home == '1'){echo 'checked';} ?> type="checkbox" name="show_home" id="show_home" /><span class="cr"><i class="cr-icon fa fa-check"></i></span></label></div>
          </div>
          <div class="form-group col-md-12" >
            <label for="blog_status">Blog Status :</label>
            <select class="form-control" name="blog_status" id="blog_status">
              <option <?php if($blog_status == 1){echo 'selected'; }?> value='1'>Enable</option>
              <option <?php if($blog_status == 0){echo 'selected'; }?> value='0'>Disable</option>
            </select>
          </div>
        </div>
        <input type="hidden" value="<?php echo $hdnid; ?>" name="blog_id"/>
      </form>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
<!-- /.row -->
<script type="text/javascript">
function validateMe(){
	if($("#blog_title").val() == ''){
		alert("Blog Title is Required !!!");
		$("#blog_title").focus();
		return false;
	}
	else if($("#blog_time").val() == ''){
		alert("Blog Time is Required !!!");
		$("#blog_time").focus();
		return false;
	}
	else if($("#allow_comment").val() == ''){
		alert("Comment  is Required !!!");
		$("#allow_comment").focus();
		return false;
	}
	else if($("#blog_status").val() == ''){
		alert("Status is Required !!!");
		$("#blog_status").focus();
		return false;
	}	
	else if($("#blog_details").val() == ''){
		alert("Details is Required !!!");
		$("#blog_details").focus();
		return false;
	}		
	else{
		return true;
	}
}
</script>
<?php include('../footer.php'); ?>
