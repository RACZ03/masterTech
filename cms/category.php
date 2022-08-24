<?php 
include('../header.php');
$success = "none";
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
$category_name = '';
$title = 'Add Category';
$button_text="Save Information";
$id="";
$category_seo_url = '';
$hdnid="0";


if(isset($_POST['category_name'])){
	$wms->saveUpdateCategoryInformation($link,$_POST);
	if((int)$_POST['category_id'] > 0){
		$url = WEB_URL . 'cms/category.php?m=up';
		header("Location: $url");
	} else {		
		$url = WEB_URL . 'cms/category.php?m=add';
		header("Location: $url");
	}
	exit();
}

/*=======================Delete=======================*/

if(isset($_GET['del_id']) && $_GET['del_id'] != '' && $_GET['del_id'] > 0){
	$wms->deleteCategory($link, $_GET['del_id']);
	$delinfo = 'block';
}
if(isset($_GET['m']) && $_GET['m'] == 'add'){
	$addinfo = 'block';
	$msg = "Added Category Successfully";
}
if(isset($_GET['m']) && $_GET['m'] == 'up'){
	$addinfo = 'block';
	$msg = "Updated Category Successfully";
}

/*==============================================*/



if(isset($_GET['id']) && $_GET['id'] != ''){
	$data = $wms->getCategoryDataByCategoryId($link, $_GET['id']);
	if(!empty($data)) {
		$category_name = $data['category_name'];
		$category_seo_url = $data['seo_url'];
		$hdnid = $_GET['id'];
		$title = 'Update Category';
		$button_text="Update";
	}
}
	
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1> Category </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Category</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <div id="me" class="alert alert-danger alert-dismissable" style="display:<?php echo $delinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-ban"></i> Deleted!</h4>
      Deleted Author Successfully. </div>
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      <?php echo $msg; ?> </div>
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" onclick="javascript:$('#frm_category').submit();" data-toggle="tooltip" href="javascript:;" data-original-title="<?php echo $button_text; ?>"><i class="fa fa-save"></i></a></div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title col-md-12"><?php echo $title; ?></h3>
      </div>
      <form onSubmit="return validateMe();" id="frm_category" method="post" enctype="multipart/form-data">
        <div class="box-body">
          <div class="form-group col-md-12">
            <label for="category_name"><span style="color:red;">*</span>Category Name :</label>
            <div class="input-group col-md-12">
              <input type="text" placeholder="Enter Name" value="<?php echo $category_name; ?>" name="category_name" id="category_name" class="form-control" required/>
            </div>
          </div>
          <div class="form-group col-md-12">
            <label for="blog_seo_url">Seo URL : <i  data-original-title="(must be unique and avoid special character @#$%^&*()!,. if blank then system will generate automatically example: john-henry)" data-toggle="tooltip" style="color:red;" class="fa fa-question-circle"></i></label>
            <input type="text" name="category_seo_url" value="<?php echo $category_seo_url;?>" id="category_seo_url" class="form-control" />
          </div>
        </div>
        <input type="hidden" value="<?php echo $hdnid; ?>" name="category_id"/>
      </form>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title">Category List</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th>Category</th>
			  <th>Seo Url</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
			$results = $wms->getCategoryData($link);
			foreach($results as $row){
			?>
            <tr>
              <td><?php echo $row['category_name']; ?></td>
			  <td><?php echo $row['seo_url']; ?></td>
              <td><a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL;?>cms/category.php?id=<?php echo $row['category_id']; ?>" data-original-title="Edit"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger" data-toggle="tooltip" onClick="deleteCategory(<?php echo $row['category_id']; ?>);" href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a> </td>
            </tr>
           <?php } $wms->close_db_connection($link); ?>
          </tbody>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
<!-- /.row -->
<script type="text/javascript">
function deleteCategory(Id){
  	var iAnswer = confirm("Are you sure you want to delete this Item ?");
	if(iAnswer){
		window.location = '<?php echo WEB_URL; ?>cms/category.php?del_id=' + Id;
	}
  }
  
  $( document ).ready(function() {
	setTimeout(function() {
		  $("#me").hide(300);
		  $("#you").hide(300);
	}, 3000);
});
</script>
<script type="text/javascript">
function validateMe(){
	if($("#category_name").val() == ''){
		alert("Category Name Required !!!");
		$("#category_name").focus();
		return false;
	}	
	else{
		return true;
	}
}
</script>
<?php include('../footer.php'); ?>
