<?php
include_once('../header.php');
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0){
	$wms->deleteSupplierInformation($link, $_GET['id']);
	$delinfo = 'block';
}
//	add success
if(isset($_SESSION['token']) && $_SESSION['token'] == 'add'){
	$addinfo = 'block';
	$msg = "Added Supplier Information Successfully";
	unset($_SESSION['token']);
}
//	update success
if(isset($_SESSION['token']) && $_SESSION['token'] == 'up'){
	$addinfo = 'block';
	$msg = "Updated Supplier Information Successfully";
	unset($_SESSION['token']);
}
if(isset($_GET['m']) && $_GET['m'] == 'add'){
	$_SESSION['token'] = 'add';
	$url = WEB_URL . 'supplier/supplierlist.php';
	header("Location: $url");
}
if(isset($_GET['m']) && $_GET['m'] == 'up'){
	$_SESSION['token'] = 'up';
	$url = WEB_URL . 'supplier/supplierlist.php';
	header("Location: $url");
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1> Supplier List </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Supplier List</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-xs-12">
    <div id="me" class="alert alert-danger alert-dismissable" style="display:<?php echo $delinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-ban"></i> Deleted!</h4>
      Deleted Supplier Information Successfully. </div>
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      <?php echo $msg; ?> </div>
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL; ?>supplier/addsupplier.php" data-original-title="Add Supplier"><i class="fa fa-plus"></i></a> <a class="btn btn-warning" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="Dashboard"><i class="fa fa-dashboard"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title">Supplier List</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th>Image</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone Number</th>
              <th>Fax</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
				$results = $wms->getAllSupplierData($link);
				foreach($results as $row){
				$image = WEB_URL . 'img/no_image.jpg';	
				if(file_exists(ROOT_PATH . '/img/upload/' . $row['image']) && $row['image'] != ''){
					$image = WEB_URL . 'img/upload/' . $row['image'];
				}
				$manufacturerInfo = $wms->getManufacturersForSupplier($link, $row['supplier_id']);
			?>
            <tr>
              <td><img class="photo_img_round img_size" src="<?php echo $image;  ?>" /></td>
              <td><?php echo $row['s_name']; ?></td>
              <td><?php echo $row['s_email']; ?></td>
              <td><?php echo $row['phone_number']; ?></td>
              <td><?php echo $row['fax_number']; ?></td>
              <td><a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onClick="$('#nurse_view_<?php echo $row['supplier_id']; ?>').modal('show');" data-original-title="View"><i class="fa fa-eye"></i></a> <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL;?>supplier/addsupplier.php?id=<?php echo $row['supplier_id']; ?>" data-original-title="Edit"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger" data-toggle="tooltip" onClick="deleteSupplier(<?php echo $row['supplier_id']; ?>);" href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
                <div id="nurse_view_<?php echo $row['supplier_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header orange_header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                        <h3 class="modal-title">Supplier Details</h3>
                      </div>
                      <div class="modal-body model_view" align="center">&nbsp;
                        <div><img class="photo_img_round" style="width:100px;height:100px;" src="<?php echo $image;  ?>" /></div>
                        <div class="model_title"><?php echo $row['s_name']; ?></div>
                      </div>
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-xs-6">
                            <h3 style="text-decoration:underline;">Details Infromation</h3>
                            <b>Name :</b> <?php echo $row['s_name']; ?><br/>
                            <b>Email :</b> <?php echo $row['s_email']; ?><br/>
                            <b>Address :</b> <?php echo $row['s_address']; ?><br/>
                            <b>Country :</b> <?php echo $row['country_name']; ?><br/>
                            <b>State :</b> <?php echo $row['state_name']; ?><br/>
                            <b>Phone Number :</b> <?php echo $row['phone_number']; ?><br/>
                            <b>Fax :</b> <?php echo $row['fax_number']; ?><br/>
                            <b>Post Code :</b> <?php echo $row['post_code']; ?><br/>
                            <b>Website :</b> <?php echo $row['website_url']; ?> </div>
                          <div class="col-xs-6">
                            <h3 style="text-decoration:underline;">Manufacturer Infromation</h3>
                            <?php foreach($manufacturerInfo as $manufacturer) { ?>
                            <div class="chkBoxStyle">
                              <label><?php echo $manufacturer['name']; ?></label>
                              &nbsp;&nbsp;<img style="float:right;" class="img_small" src="<?php echo $manufacturer['image']; ?>" /></div>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.modal-content -->
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
  <!-- /.col -->
</div>
<!-- /.row -->
<script type="text/javascript">
function deleteSupplier(Id){
  	var iAnswer = confirm("Are you sure you want to delete this Supplier ?");
	if(iAnswer){
		window.location = '<?php echo WEB_URL; ?>supplier/supplierlist.php?id=' + Id;
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
