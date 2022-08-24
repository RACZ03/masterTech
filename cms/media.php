<?php 
include('../header.php');
$success = "none";
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
$media_title = '';
$media_link = '';
$title = 'Add New Customer';
$button_text="Save Information";
$successful_msg="Add Customer Successfully";
$form_url = WEB_URL . "cms/media.php";
$id="";
$hdnid="0";

if(isset($_POST['media_title'])){
	if(isset($_POST['hdn']) && $_POST['hdn'] == '0'){
	$sql = "INSERT INTO tbl_media(media_title,media_link) values('$_POST[media_title]','$_POST[media_link]')";		
	mysqli_query($link,$sql);
	mysqli_close($link);
	$url = WEB_URL . 'cms/media.php?m=add';
	header("Location: $url");
	
}
else{	
	$sql = "UPDATE `tbl_media` SET `media_title`='".$_POST['media_title']."',`media_link`='".$_POST['media_link']."' WHERE media_id='".$_GET['id']."'";
	mysqli_query($link,$sql);
	$url = WEB_URL . 'cms/media.php?m=up';
	header("Location: $url");
}

$success = "block";
}
/*=======================Delete=======================*/

if(isset($_GET['del_id']) && $_GET['del_id'] != '' && $_GET['del_id'] > 0){
	$sqlx= "DELETE FROM `tbl_media` WHERE media_id = ".$_GET['del_id'];
	mysqli_query($link,$sqlx); 
	$delinfo = 'block';
}
if(isset($_GET['m']) && $_GET['m'] == 'add'){
	$addinfo = 'block';
	$msg = "Added Customer Information Successfully";
}
if(isset($_GET['m']) && $_GET['m'] == 'up'){
	$addinfo = 'block';
	$msg = "Updated Customer Information Successfully";
}

/*==============================================*/



if(isset($_GET['id']) && $_GET['id'] != ''){
	$result = mysqli_query($link,"SELECT * FROM tbl_media where media_id = '" . $_GET['id'] . "'");
	while($row = mysqli_fetch_array($result)){
		
		$media_title = $row['media_title'];
		$media_link = $row['media_link'];
		$hdnid = $_GET['id'];
		$title = 'Update Customer';
		$button_text="Update";
		$successful_msg="Update Customer Successfully";
		$form_url = WEB_URL . "cms/media.php?id=".$_GET['id'];
	}
	
	//mysqli_close($link);

}
	
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1> Add Category </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Category</li>
    <li class="active">Add Category</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-primary" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>donationExpense/donationExpense.php" data-original-title="Back"><i class="fa fa-refresh"></i></a> </div>
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title">Add Category</h3>
      </div>
      <form onSubmit="return validateMe();" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">
        <div class="box-body">          
		  <div class="form-group col-md-12">
            <label for="media_title"><span style="color:red;">*</span>Media Name :</label>
            <div class="input-group col-md-12">
              <input type="text" placeholder="Enter Name" value="<?php echo $media_title; ?>" name="media_title" id="media_title" class="form-control" required/>
            </div>
          </div>
		  <div class="form-group col-md-12">
            <label for="media_link"><span style="color:red;">*</span>Media Link :</label>
            <div class="input-group col-md-12">
              <input type="text" placeholder="Enter Name" value="<?php echo $media_link; ?>" name="media_link" id="media_link" class="form-control" required/>
            </div>
          </div>
          <div class="form-group pull-right">
            <input type="submit" name="submit" class="btn btn-primary" value="<?php echo $button_text; ?>"/>
          </div>
        </div>
        <input type="hidden" value="<?php echo $hdnid; ?>" name="hdn"/>
      </form>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
	
	<div class="box box-info">
      <div class="box-header">
        <h3 class="box-title">Amount List</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th>Media Title</th>
			  <th>Media Link</th>			            
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
        <?php
				$result = mysqli_query($link,"SELECT * FROM tbl_media order by media_id");
				while($row = mysqli_fetch_array($result)){
				?>
            <tr>
            <td><?php echo $row['media_title']; ?></td>  
			<td><?php echo $row['media_link']; ?></td>            
            <td>
           <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL;?>cms/media.php?id=<?php echo $row['media_id']; ?>" data-original-title="Edit"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger" data-toggle="tooltip" onClick="deleteCustomer(<?php echo $row['media_id']; ?>);" href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>            
            </td>
            </tr>
            <?php } mysqli_close($link); ?>
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
function deleteCustomer(Id){
  	var iAnswer = confirm("Are you sure you want to delete this Item ?");
	if(iAnswer){
		window.location = '<?php echo WEB_URL; ?>cms/media.php?del_id=' + Id;
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
	if($("#txtCName").val() == ''){
		alert("Brand Name is Required !!!");
		$("#txtCName").focus();
		return false;
	}	
	else{
		return true;
	}
}
</script>

<?php include('../footer.php'); ?>
