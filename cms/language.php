<?php 
include('../header.php');
$success = "none";
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
$social_url = '';
$title = 'Add New Customer';
$button_text="Save Information";
$successful_msg="Add Customer Successfully";
$form_url = WEB_URL . "setting/social.php";
$id="";
$hdnid="0";

if(isset($_POST['social_url'])){
	if(isset($_POST['hdn']) && $_POST['hdn'] == '0'){
	$sql = "INSERT INTO tbl_social(social_url) values('$_POST[social_url]')";		
	mysqli_query($link,$sql);
	mysqli_close($link);
	$url = WEB_URL . 'setting/social.php?m=add';
	header("Location: $url");
	
}
else{	
	$sql = "UPDATE `tbl_social` SET `social_url`='".$_POST['social_url']."' WHERE social_id='".$_GET['id']."'";
	mysqli_query($link,$sql);
	$url = WEB_URL . 'setting/social.php?m=up';
	header("Location: $url");
}

$success = "block";
}
/*=======================Delete=======================*/

if(isset($_GET['del_id']) && $_GET['del_id'] != '' && $_GET['del_id'] > 0){
	$sqlx= "DELETE FROM `tbl_social` WHERE social_id = ".$_GET['del_id'];
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
	$result = mysqli_query($link,"SELECT * FROM tbl_social where social_id = '" . $_GET['id'] . "'");
	while($row = mysqli_fetch_array($result)){
		
		$social_url = $row['social_url'];
		$hdnid = $_GET['id'];
		$title = 'Update Customer';
		$button_text="Update";
		$successful_msg="Update Customer Successfully";
		$form_url = WEB_URL . "setting/social.php?id=".$_GET['id'];
	}
	
	//mysqli_close($link);

}
	
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1> Add Language </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Language</li>
    <li class="active">Add Language</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">

	<div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Package Language</a></li>
              <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Blog Language</a></li>
              <li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">News Language</a></li>              
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <form onSubmit="return validateMe();" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">
					<div class="box-body">
					  <div class="form-group">
						<label for="social_url"><span style="color:red;">*</span>Social Link :</label>
						<input type="text" name="social_url" value="<?php echo $social_url;?>" id="social_url" class="form-control" />
					  </div>
					  <div class="form-group pull-right">
						<input type="submit" name="submit" class="btn btn-primary" value="<?php echo $button_text; ?>"/>
					  </div>
					</div>
					<input type="hidden" value="<?php echo $hdnid; ?>" name="hdn"/>
				  </form>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                					<div class="box-body">
					  <div class="form-group">
						<label for="social_url"><span style="color:red;">*</span>Social Link :</label>
						<input type="text" name="social_url" value="<?php echo $social_url;?>" id="social_url" class="form-control" />
					  </div>
					  <div class="form-group">
						<label for="social_url"><span style="color:red;">*</span>Social Link :</label>
						<input type="text" name="social_url" value="<?php echo $social_url;?>" id="social_url" class="form-control" />
					  </div>
					  <div class="form-group pull-right">
						<input type="submit" name="submit" class="btn btn-primary" value="<?php echo $button_text; ?>"/>
					  </div>
					</div>
					<input type="hidden" value="<?php echo $hdnid; ?>" name="hdn"/>
				  </form>

              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
                					<div class="box-body">
					  <div class="form-group">
						<label for="social_url"><span style="color:red;">*</span>Social Link :</label>
						<input type="text" name="social_url" value="<?php echo $social_url;?>" id="social_url" class="form-control" />
					  </div>
					  <div class="form-group">
						<label for="social_url"><span style="color:red;">*</span>Social Link :</label>
						<input type="text" name="social_url" value="<?php echo $social_url;?>" id="social_url" class="form-control" />
					  </div>
					  <div class="form-group">
						<label for="social_url"><span style="color:red;">*</span>Social Link :</label>
						<input type="text" name="social_url" value="<?php echo $social_url;?>" id="social_url" class="form-control" />
					  </div>
					  <div class="form-group pull-right">
						<input type="submit" name="submit" class="btn btn-primary" value="<?php echo $button_text; ?>"/>
					  </div>
					</div>
					<input type="hidden" value="<?php echo $hdnid; ?>" name="hdn"/>
				  </form>

              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
		
  <div class="col-md-12">
	<div class="box box-info">
      <div class="box-header">
        <h3 class="box-title">Language List</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th>Social Link</th>          
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
        <?php
				$result = mysqli_query($link,"SELECT * FROM tbl_social order by social_id");
				while($row = mysqli_fetch_array($result)){
					
				
				?>
            <tr>
            <td><?php echo $row['social_url']; ?></td>            
            <td>
           <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL;?>setting/social.php?id=<?php echo $row['social_id']; ?>" data-original-title="Edit"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger" data-toggle="tooltip" onClick="deleteCustomer(<?php echo $row['social_id']; ?>);" href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>            
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
		window.location = '<?php echo WEB_URL; ?>setting/social.php?del_id=' + Id;
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
	if($("#social_url").val() == ''){
		alert("Social Link is Required !!!");
		$("#social_url").focus();
		return false;
	}	
	else{
		return true;
	}
}
</script>

<?php include('../footer.php'); ?>
