<?php 
include('../header.php');
$success = "none";
$page_title = '';
$seo_url = '';
$page_details = '';
$hide_top_header = '0';
$status = '1';
$name = '';
$button_text="Save Information";

if(isset($_POST['txtPageTitle'])){
	$wms->saveUpdateMechanicsPageInformation($link, $_POST);
	$url = WEB_URL.'workshop_admin_panel/mechanicslist.php?m=page';
	header("Location: $url");
	exit();
}

if(isset($_GET['id']) && $_GET['id'] != ''){
	//load mechanics info
	$details = $wms->getMechanicsInfoByMechanicsId($link, $_GET['id']);
	if(!empty($details)){
		$page_title = $details['m_name'];
		$seo_url = $wms->generateSeoUrl(trim($details['m_name']));
	}
	$row = $wms->getMechanicsPageInfoByMechanicsId($link, $_GET['id']);
	if(!empty($row)){
		$page_title = $row['page_title'];
		$seo_url = $row['seo_url'];
		$page_details=$row['page_details'];
		$hide_top_header = $row['hide_top_header'];
		$status=$row['status'];
		$button_text="Update Information";
	}
	$hdnid = $_GET['id'];
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1> Mechanics Page Builder </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Mechanics</li>
    <li class="active">Page Builder</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" onclick="javascript:$('#frm_mac').submit();" data-toggle="tooltip" href="javascript:;" data-original-title="<?php echo $button_text; ?>"><i class="fa fa-save"></i></a> &nbsp;<a class="btn btn-warning" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>workshop_admin_panel/mechanicslist.php" data-original-title="Back"><i class="fa fa-reply"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title col-md-12">Page Information</h3>
      </div>
      <form id="frm_mac" onSubmit="return validateMe();" method="post" enctype="multipart/form-data">
        <div class="box-body">
          <div class="form-group  col-md-12">
            <label for="txtPageTitle"> Page Title :</label>
            <input type="text" name="txtPageTitle" value="<?php echo $page_title;?>" id="txtPageTitle" class="form-control" />
          </div>
          <div class="form-group  col-md-12">
            <label for="txtSeoUrl"> SEO URL : <i  data-original-title="(must be unique and avoid special character @#$%^&*()!,. if blank then system will generate automatically example: john-henry)" data-toggle="tooltip" style="color:red;" class="fa fa-question-circle"></i> </label>
            <input type="text" name="txtSeoUrl" value="<?php echo $seo_url;?>" id="txtSeoUrl" class="form-control" />
          </div>
          <div class="form-group col-md-12">
            <label for="page_description">Page Description:</label>
            <textarea name="page_description" id="page_description" class="form-control summernote"><?php echo $page_details;?></textarea>
          </div>
          <div class="form-group  col-md-12">
            <label for="chkPageTitle"> Hide Page Title :</label>
			<div class="checkbox"><label style="font-size: 1.7em"><input <?php if($hide_top_header == '1'){echo 'checked';}?> type="checkbox" name="chkPageTitle" id="chkPageTitle" /><span class="cr"><i class="cr-icon fa fa-check"></i></span></label></div>
          </div>
          <div class="form-group col-md-12">
            <label for="status">Status :</label>
            <select class="form-control" name="status" id="status">
              <option <?php if($status == '1'){echo 'selected'; }?> value='1'>Active</option>
              <option <?php if($status == '0'){echo 'selected'; }?> value='0'>In-Active</option>
            </select>
          </div>
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
	if($("#txtPageTitle").val() == ''){
		alert("Page Title Required !!!");
		$("#txtPageTitle").focus();
		return false;
	} else {
		return true;
	}
}
</script>
<?php include('../footer.php'); ?>
