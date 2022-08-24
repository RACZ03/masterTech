<?php 
include('../header.php');
$success = "none";
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
$c_name = '';
$c_email = '';
$c_address = '';
$c_home_tel = '';
$c_work_tel = '';
$c_mobile = '';
$c_password = '';
$title = 'Add New Manufacturer';
$button_text="Save Information";
$successful_msg="Add Manufacturer Successfully";
$form_url = WEB_URL . "parts_manufacturer/parts_manufacturer.php";
$id="";
$hdnid="0";
$image_cus = WEB_URL . 'img/no_image.jpg';
$img_track = '';


if(isset($_POST['txtCName'])){
	$image_url = uploadImage();
	if(empty($image_url)) {
		$image_url = $_POST['img_exist'];
	}
	$wms->saveUpdateManufacturerInformation($link, $_POST, $image_url);
	if((int)$_POST['manufacturer_id'] > 0){
		$url = WEB_URL . 'parts_manufacturer/parts_manufacturer.php?m=up';
		header("Location: $url");
	} else {		
		$url = WEB_URL . 'parts_manufacturer/parts_manufacturer.php?m=add';
		header("Location: $url");
	}
	exit();
}

/*=======================Delete=======================*/

if(isset($_GET['did']) && (int)$_GET['did'] > 0){
	$wms->deleteManufacturer($link, $_GET['did']);
	$delinfo = 'block';
}
if(isset($_GET['m']) && $_GET['m'] == 'add'){
	$addinfo = 'block';
	$msg = "Added Manufacturer Information Successfully";
}
if(isset($_GET['m']) && $_GET['m'] == 'up'){
	$addinfo = 'block';
	$msg = "Updated Manufacturer Information Successfully";
}

/*==============================================*/



if(isset($_GET['id']) && $_GET['id'] != ''){
	$row = $wms->getManufacturerInfoByManufacturerId($link, $_GET['id']);
	if(!empty($row)) {
		$c_name = $row['manufacturer_name'];		
		if($row['manufacturer_image'] != ''){
			$image_cus = WEB_URL . 'img/upload/' . $row['manufacturer_image'];
			$img_track = $row['manufacturer_image'];
		}
		$hdnid = $_GET['id'];
		$title = 'Update Customer';
		$button_text="Update";
		$successful_msg="Update Customer Successfully";
		$form_url = WEB_URL . "parts_manufacturer/parts_manufacturer.php?id=".$_GET['id'];
	}
	
	//mysql_close($link);

}

//for image upload
function uploadImage(){
	if((!empty($_FILES["uploaded_file"])) && ($_FILES['uploaded_file']['error'] == 0)) {
	  $filename = basename($_FILES['uploaded_file']['name']);
	  $ext = substr($filename, strrpos($filename, '.') + 1);
	  if(($ext == "jpg" && $_FILES["uploaded_file"]["type"] == 'image/jpeg') || ($ext == "png" && $_FILES["uploaded_file"]["type"] == 'image/png') || ($ext == "gif" && $_FILES["uploaded_file"]["type"] == 'image/gif')){   
	  	$temp = explode(".",$_FILES["uploaded_file"]["name"]);
	  	$newfilename = NewGuid() . '.' .end($temp);
		move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], ROOT_PATH . '/img/upload/' . $newfilename);
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
<section class="content-header">
  <h1> Add Manufacturer </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Manufacturer</li>
    <li class="active">Add Manufacturer</li>
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
      Deleted Manufacturer Information Successfully. </div>
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      <?php echo $msg; ?> </div>
	  
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>parts_manufacturer/parts_manufacturer.php" data-original-title="Back"><i class="fa fa-refresh"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title">Add Manufacturer</h3>
      </div>
      <form action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">
        <div class="box-body">
          <div class="form-group">
            <label for="txtCName"><span style="color:red;">*</span> Manufacturer Name :</label>
            <input type="text" required name="txtCName" value="<?php echo $c_name;?>" id="txtCName" class="form-control" />
          </div>
          <div class="form-group">
            <label for="Prsnttxtarea">Priview :</label>
            <img class="form-control" src="<?php echo $image_cus; ?>" style="height:100px;width:100px;" id="output"/>
            <input type="hidden" name="img_exist" value="<?php echo $img_track; ?>" />
          </div>
          <div class="form-group"> <span class="btn btn-file btn btn-success">Upload Image
            <input type="file" name="uploaded_file" onchange="loadFile(event)" />
            </span> </div>
          <div class="form-group pull-right">
            <input type="submit" name="submit" class="btn btn-success" value="<?php echo $button_text; ?>"/>
          </div>
        </div>
        <input type="hidden" value="<?php echo $hdnid; ?>" name="manufacturer_id"/>
      </form>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title">Manufacturer List</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th>Image</th>
              <th>Name</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
				$results = $wms->getAllManufacturerList($link);
				foreach($results as $row){ ?>
            <tr>
              <td><img class="img_size" src="<?php echo $row['image'];  ?>" /></td>
              <td><?php echo $row['name']; ?></td>
              <td><a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onClick="$('#nurse_view_<?php echo $row['id']; ?>').modal('show');" data-original-title="View"><i class="fa fa-eye"></i></a> <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL;?>parts_manufacturer/parts_manufacturer.php?id=<?php echo $row['id']; ?>" data-original-title="Edit"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger" data-toggle="tooltip" onClick="deleteCustomer(<?php echo $row['id']; ?>);" href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
                <div id="nurse_view_<?php echo $row['id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header orange_header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                        <h3 class="modal-title">Manufacturer Details</h3>
                      </div>
                      <div class="modal-body model_view" align="center">&nbsp;
                        <div><img class="photo_img_round" style="width:100px;height:100px;" src="<?php echo $row['image'];  ?>" /></div>
                        <div class="model_title"><?php echo $row['name']; ?></div>
                      </div>
                      <div class="modal-body">
                        <h3 style="text-decoration:underline;">Details Infromation</h3>
                        <div class="row">
                          <div class="col-xs-12"> <b>Name :</b> <?php echo $row['name']; ?><br/>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div></td>
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
function deleteCustomer(Id){
  	var iAnswer = confirm("Are you sure you want to delete this Item ?");
	if(iAnswer){
		window.location = '<?php echo WEB_URL; ?>parts_manufacturer/parts_manufacturer.php?did=' + Id;
	}
  }
  
  $( document ).ready(function() {
	setTimeout(function() {
		  $("#me").hide(300);
		  $("#you").hide(300);
	}, 3000);
});
</script>
<?php include('../footer.php'); ?>
