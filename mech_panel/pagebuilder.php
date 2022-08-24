<?php 
include('../mech_panel/header.php');
$success = "none";
$page_title = '';
$seo_url = '';
$page_details = '';
$hide_top_header = '0';
$status = '1';
$name = '';
$msg = '';

if(isset($_POST['txtPageTitle'])){
	$wms->saveUpdateMechanicsPageInformation($link, $_POST);
	$url = WEB_URL.'mech_panel/pagebuilder.php?m=save';
	header("Location: $url");
	exit();
}

if(isset($_GET['m']) && !empty($_GET['m'])) {
	$msg = 'Update information successfully';
}

if(!empty($_SESSION['objMech']['user_id'])){
	//load mechanics info
	$details = $wms->getMechanicsInfoByMechanicsId($link, $_SESSION['objMech']['user_id']);
	if(!empty($details)){
		$page_title = $details['m_name'];
		$seo_url = $wms->generateSeoUrl(trim($details['m_name']));
	}
	$row = $wms->getMechanicsPageInfoByMechanicsId($link, $_SESSION['objMech']['user_id']);
	if(!empty($row)){
		$page_title = $row['page_title'];
		$seo_url = $row['seo_url'];
		$page_details=$row['page_details'];
		$hide_top_header = $row['hide_top_header'];
		$status=$row['status'];
		$button_text="Update";
	}
	$hdnid = $_SESSION['objMech']['user_id'];
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1> Mechanics Profile Page </h1>
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
  
  <?php if(!empty($msg)) { ?>
  <div id="you" class="alert alert-success alert-dismissable">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      <?php echo $msg; ?> </div>
  <?php } ?>
  
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" onclick="javascript:$('#frm_mac').submit();" data-toggle="tooltip" href="javascript:;" data-original-title="Update"><i class="fa fa-save"></i></a> <a class="btn btn-warning" data-toggle="tooltip" href="<?php echo WEB_URL; ?>mech_panel/mech_dashboard.php" data-original-title="Dashboard"><i class="fa fa-dashboard"></i></a></div>
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
        </div>
        <input type="hidden" value="1" name="status"/>
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
<?php include('../mech_panel/footer.php'); ?>
