<?php 
include('../header.php');
$success 			= "none";
$menu_name 			= '';
$menu_sort_order 	= '0';
$menu_status 		= '';
$parent_menu 		= '';
$menu_url_slug = '';
$page_id 			= 0;
$fixed_page_url 	= '';

$title 				= 'Add New Menu';
$button_text		= "Save Information";
$hdnid				= "0";

if(isset($_POST['txtMenuname'])){
	$wms->saveUpdateMenuInformation($link,$_POST);
	if((int)$_POST['menu_id'] > 0){
		$url = WEB_URL.'cms/menulist.php?m=up';
		header("Location: $url");
	} else {		
		$url = WEB_URL.'cms/menulist.php?m=add';
		header("Location: $url");
	}
	exit();
}

if(isset($_GET['id']) && $_GET['id'] != ''){
	$row = $wms->getMenuInfoByMenuId($link, $_GET['id']);
	if(!empty($row)) {
		$menu_name 				= $row['menu_name'];
		$menu_sort_order 		= $row['menu_sort_order'];
		$menu_status 			= $row['menu_status'];
		$menu_url_slug			= $row['url_slug'];
		$hdnid 					= $_GET['id'];
		$page_id 				= $row['cms_page'];
		$fixed_page_url			= $row['fixed_page_url'];
		$title 					= 'Update Menu';
		$button_text			="Update Infomation";
		$parent_menu 			= $row['parent_id'];
	}

}	
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1> Add New Menu </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Menu</li>
    <li class="active">Add Menu</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" onclick="javascript:$('#frm_menu').submit();" data-toggle="tooltip" href="javascript:;" data-original-title="<?php echo $button_text; ?>"><i class="fa fa-save"></i></a> &nbsp;<a class="btn btn-warning" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>cms/menulist.php" data-original-title="Back"><i class="fa fa-reply"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title">Menu Entry Form</h3>
      </div>
      <form onSubmit="return validateMe();" id="frm_menu" method="post" enctype="multipart/form-data">
        <div class="box-body">
          <div class="form-group">
            <label for="txtParent">Parent Menu :</label>
            <select class="form-control" name="txtParent" id="txtParent">
              <option value=''>--Select Menu--</option>
              <?php
				$result = $wms->getParentMenuList($link);
				foreach($result as $row) {
					if($parent_menu > 0 && $parent_menu == $row['menu_id']) {
						echo "<option selected value='".$row['menu_id']."'>".$row['menu_name']."</option>";
					} else {
						echo "<option value='".$row['menu_id']."'>".$row['menu_name']."</option>";
					}
					
				} ?>
            </select>
          </div>
          <div class="form-group">
            <label for="txtMenuname"><span style="color:red;">*</span> Menu Name :</label>
            <input type="text" name="txtMenuname" value="<?php echo $menu_name;?>" id="txtMenuname" class="form-control" />
          </div>
          <div class="form-group">
            <label for="txtParentUrlSlug">Parent Menu Url Slug : <i data-original-title="(must be unique and avoid special character @#$%^&amp;*()!, it will be be before child url example: service or product)" data-toggle="tooltip" style="color:red;" class="fa fa-question-circle"></i></label>
            <input type="text" name="txtParentUrlSlug" value="<?php echo $menu_url_slug;?>" id="txtParentUrlSlug" class="form-control" />
          </div>
		  <div class="form-group">
            <label for="txtSortodder">Sort Order :</label>
            <input type="number" name="txtSortodder" value="<?php echo $menu_sort_order;?>" id="txtSortodder" class="form-control" />
          </div>
          <div class="form-group">
            <label>Link Menu with CMS Page : <i data-original-title="(N.B. Select CMS page link or Our page.)" data-toggle="tooltip" style="color:red;" class="fa fa-question-circle"></i></label>
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
		  <div class="form-group">
            <label>Our Pages : <i data-original-title="(N.B. Select CMS page link or Our page.)" data-toggle="tooltip" style="color:red;" class="fa fa-question-circle"></i></label>
            <div style="border:solid 1px #ccc; width:100%;height:100px;overflow:auto;padding:8px;">
             <div><input name="rbCMSPage" <?php if(is_string($fixed_page_url) && !empty($fixed_page_url) && $fixed_page_url=='home'){echo 'checked';}?> type="radio" value="home"><label class="label label-success">Home Page<label></div>
			 <div><input name="rbCMSPage" <?php if(is_string($fixed_page_url) && !empty($fixed_page_url) && $fixed_page_url=='car-collection'){echo 'checked';}?> type="radio" value="car-collection"><label class="label label-success">Car Page<label></div>
			 <div><input name="rbCMSPage" <?php if(is_string($fixed_page_url) && !empty($fixed_page_url) && $fixed_page_url=='auto-parts-collection'){echo 'checked';}?> type="radio" value="auto-parts-collection"><label class="label label-success">Parts Page<label></div>
			  <div><input name="rbCMSPage" <?php if(is_string($fixed_page_url) && !empty($fixed_page_url) && $fixed_page_url=='news-latest-collection'){echo 'checked';}?> type="radio" value="news-latest-collection"><label class="label label-success">News Collection<label></div>
			  <div><input name="rbCMSPage" <?php if(is_string($fixed_page_url) && !empty($fixed_page_url) && $fixed_page_url=='our-team'){echo 'checked';}?> type="radio" value="our-team"><label class="label label-success">Our Team<label></div>
			 <div><input name="rbCMSPage" <?php if(is_string($fixed_page_url) && !empty($fixed_page_url) && $fixed_page_url=='contact-us'){echo 'checked';}?> type="radio" value="contact-us"><label class="label label-success">Contact Us<label></div>
			 <div><input name="rbCMSPage" <?php if(is_string($fixed_page_url) && !empty($fixed_page_url) && $fixed_page_url=='faq'){echo 'checked';}?> type="radio" value="faq"><label class="label label-success">FAQ<label></div>
            </div>
          </div>
          <div class="form-group">
            <label for="txtStatus">Status :</label>
            <select class="form-control" name="txtStatus" id="txtStatus">
              <option <?php if($menu_status == 'enable'){echo 'selected'; }?> value='1'>Enable</option>
              <option <?php if($menu_status == 'disable'){echo 'selected'; }?> value='0'>Disable</option>
            </select>
          </div>
        </div>
        <input type="hidden" value="<?php echo $hdnid; ?>" name="menu_id"/>
      </form>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
<!-- /.row -->
<script type="text/javascript">
function validateMe(){
	if($("#txtMenuname").val() == ''){
		alert("Menu Name is Required !!!");
		$("#txtMenuname").focus();
		return false;
	}
	else{
		return true;
	}
}
</script>
<?php include('../footer.php'); ?>
