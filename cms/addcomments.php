<?php 
include('../header.php');
$success = "none";
$cmments_text = "";
$author = "";
$profession = "";
$status = "1";
$approve = "0";
$title = 'Add New Testimonials';
$button_text="Save Information";
$hdnid="0";
$image_sup = WEB_URL . 'img/no_image.jpg';  
$img_track = '';

if(isset($_POST['txtComments'])){
	$image_url = uploadImage();
	if(empty($image_url)) {
		$image_url = $_POST['img_exist'];
	}
	$wms->saveUpdateCommentsInformation($link, $_POST, $image_url);
	if((int)$_POST['comments_id'] > 0){
		$url = WEB_URL.'cms/commentslist.php?m=up';
		header("Location: $url");
	} else {		
		$url = WEB_URL.'cms/commentslist.php?m=add';
		header("Location: $url");
	}
	exit();
}

if(isset($_GET['id']) && $_GET['id'] != ''){
	$row = $wms->getCommentsInfoByCommentsId($link, $_GET['id']);
	if(!empty($row)){
		$cmments_text = $row['comments'];
		$author = $row['author'];
		$profession = $row['profession'];
		$approve = $row['approve'];
		if($row['image_url'] != ''){
			$image_sup = WEB_URL.'img/comments/'.$row['image_url'];
			$img_track = $row['image_url'];
		}
		$status = $row['status'];
		$hdnid = $_GET['id'];
		$title = 'Update Comments';
		$button_text="Update Comments";
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
		move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], ROOT_PATH . '/img/comments/' . $newfilename);
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
  <h1><i class="fa fa-comments-o"></i> Customer Testimonials </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Comments</li>
    <li class="active">Add Testimonials</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" onclick="javascript:$('#frm_comments').submit();" data-toggle="tooltip" href="javascript:;" data-original-title="<?php echo $button_text; ?>"><i class="fa fa-save"></i></a> &nbsp;<a class="btn btn-warning" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>cms/commentslist.php" data-original-title="Back"><i class="fa fa-reply"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title col-md-12"><?php echo $title; ?></h3>
      </div>
      <form id="frm_comments" onSubmit="return validateMe();" method="post" enctype="multipart/form-data">
        <div class="box-body">
          <div class="form-group col-md-12">
            <label for="txtComments"><span style="color:red;">*</span> Testimonials Text:</label>
            <textarea name="txtComments" id="txtComments" class="form-control" style="height:150px;"><?php echo $cmments_text;?></textarea>
          </div>
		  <div class="form-group col-md-12">
            <label for="txtAuthorName"><span style="color:red;">*</span> Author Name :</label>
            <input type="text" name="txtAuthorName" value="<?php echo $author;?>" id="txtAuthorName" class="form-control" />
          </div>
		  <div class="form-group col-md-12">
            <label for="txtAuthorProfession"><span style="color:red;">*</span> Author Profession :</label>
            <input type="text" name="txtAuthorProfession" value="<?php echo $profession;?>" id="txtAuthorProfession" class="form-control" />
          </div>
          <div class="form-group col-md-12">
            <label for="Prsnttxtarea"><span style="color:red;">*</span> Image :</label>
            <img class="form-control" src="<?php echo $image_sup; ?>" style="height:128px;width:128px;" id="output"/>
            <input type="hidden" name="img_exist" value="<?php echo $img_track; ?>" />
          </div>
          <div class="form-group col-md-12"> <span class="btn btn-file btn btn-success">Upload Image
            <input type="file" name="uploaded_file" id="uploaded_file" onchange="loadFile(event)" />
            </span> </div>
          <div class="form-group col-md-12">
            <label for="chkApprove">Approve :</label>
			<div class="checkbox"><label style="font-size: 1.7em"><input <?php if($approve == '1'){echo 'checked';} ?> type="checkbox" name="chkApprove" id="chkApprove" /><span class="cr"><i class="cr-icon fa fa-check"></i></span></label></div>
          </div>
		  <div class="form-group col-md-12">
            <label for="status">Status :</label>
            <select class="form-control" name="status" id="status">
              <option <?php if($status == '1'){echo 'selected'; }?> value='1'>Enable</option>
              <option <?php if($status == '0'){echo 'selected'; }?> value='0'>Disable</option>
            </select>
          </div>
        </div>
        <input type="hidden" value="<?php echo $hdnid; ?>" name="comments_id"/>
      </form>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
<!-- /.row -->
<script type="text/javascript">
function validateMe(){
	if($("#txtComments").val() == ''){
		alert("Comments text required !!!");
		$("#txtComments").focus();
		return false;
	}
	else if($("#txtAuthorName").val() == ''){
		alert("Author name required !!!");
		$("#txtAuthorName").focus();
		return false;
	}
	else if($("#txtAuthorProfession").val() == ''){
		alert("Author profession required !!!");
		$("#txtAuthorProfession").focus();
		return false;
	}
	else{
		return true;
	}
}
</script>
<?php include('../footer.php'); ?>
