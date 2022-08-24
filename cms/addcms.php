<?php 
include('../header.php');
$success = "none";
$cms_page_title = '';
$cms_seo_url = '';
$cms_status = '';
$cms_content = '';

$title = 'Add New CMS';
$button_text="Save Information";
$successful_msg="Add CMS Successfully";
$id="";
$hdnid="0";

if(isset($_POST['txtPtitle'])){
	$wms->saveUpdateCMSInformation($link,$_POST);
	if((int)$_POST['cms_id'] > 0){
		$url = WEB_URL.'cms/cmslist.php?m=up';
		header("Location: $url");
	} else {		
		$url = WEB_URL.'cms/cmslist.php?m=add';
		header("Location: $url");
	}
	exit();
}

if(isset($_GET['id']) && $_GET['id'] != ''){
	$row = $wms->getCMSDetailsByCMSId($link,$_GET['id']);
	if(!empty($row)) {
		$cms_page_title = $row['page_title'];
		$cms_seo_url = $row['seo_url'];
		$cms_status = $row['cms_status'];
		$cms_content = $row['page_details'];
		$hdnid = $_GET['id'];
		$title = 'Update CMS';
		$button_text="Update";
	}
}
?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> <?php echo $title; ?> </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">CMS</li>
    <li class="active"><?php echo $title; ?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" onclick="javascript:$('#frm_cms').submit();" data-toggle="tooltip" href="javascript:;" data-original-title="<?php echo $button_text; ?>"><i class="fa fa-save"></i></a> &nbsp;<a class="btn btn-warning" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>cms/cmslist.php" data-original-title="Back"><i class="fa fa-reply"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title">CMS Form</h3>
      </div>
      <form onSubmit="return validateMe();" id="frm_cms" method="post" enctype="multipart/form-data">
        <div class="box-body">
          <div class="form-group">
            <label for="txtPtitle"><span style="color:red;">*</span> Page Title :</label>
            <input type="text" name="txtPtitle" value="<?php echo $cms_page_title;?>" id="txtPtitle" class="form-control" />
          </div>
          <div class="form-group">
            <label for="txtSeo"> SEO Url : <i  data-original-title="(must be unique and avoid special character @#$%^&*()!,. if blank then system will generate automatically example: john-henry)" data-toggle="tooltip" style="color:red;" class="fa fa-question-circle"></i></label>
            <input type="text" name="txtSeo" value="<?php echo $cms_seo_url;?>" id="txtSeo" class="form-control" />
          </div>
          <div class="form-group">
            <label for="txtStatus"><span style="color:red;">*</span> Status :</label>
            <select class="form-control" name="txtStatus" id="txtStatus">
              <option <?php if($cms_status == '1'){echo 'selected'; }?> value='1'>Enable</option>
              <option <?php if($cms_status == '0'){echo 'selected'; }?> value='0'>Disable</option>
            </select>
          </div>
          <div class="form-group">
            <label for="txtCmcontent"><span style="color:red;">*</span> CMS Content :</label>
            <textarea name="txtCmcontent" id="txtCmcontent" class="form-control summernote"><?php echo $cms_content;?></textarea>
          </div>
        </div>
        <input type="hidden" value="<?php echo $hdnid; ?>" name="cms_id"/>
      </form>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
<!-- /.row -->
<script type="text/javascript">
function validateMe(){
	if($("#txtPtitle").val() == '') {
		alert("Page Title is Required !!!");
		$("#txtPtitle").focus();
		return false;
	} else if($("#txtStatus").val() == '') {
		alert("Status is Required !!!");
		$("#txtStatus").focus();
		return false;
	} else if($("#txtCmcontent").val() == '') {
		alert("CMS Content is Required !!!");
		$("#txtCmcontent").focus();
		return false;
	} else {
		return true;
	}
}
</script>
<?php include('../footer.php'); ?>
