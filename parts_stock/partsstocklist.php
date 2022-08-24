<?php include('../header.php');?>
<?php
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0){
	$wms->deleteStockPartsInformation($link, $_GET['id']);
	$delinfo = 'block';
	$msg = "Deleted Parts information from stock successfully";
}
if(isset($_GET['m']) && $_GET['m'] == 'up'){
	$addinfo = 'block';
	$msg = "Updated Parts stock Information Successfully";
}

?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-shopping-cart"></i> Parts Stock </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Parts Stock</li>
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
      <?php echo $msg; ?> </div>
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      <?php echo $msg; ?> </div>
    <div align="right" style="margin-bottom:1%;"><a class="btn btn-warning" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="Dashboard"><i class="fa fa-dashboard"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title">Parts Stock List</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
			  <th>Image</th>
			  <th>Name</th>
			  <th>Condition</th>
			  <th>Quantity</th>
			  <th>Sell Price</th>
			  <th>Warranty</th>
			  <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
        <?php
				$result = $wms->partsStockList($link);
				foreach($result as $row) {				
					$image = WEB_URL . 'img/no_image.jpg';
					if(file_exists(ROOT_PATH . '/img/upload/' . $row['parts_image']) && $row['parts_image'] != ''){
						$image = WEB_URL . 'img/upload/' . $row['parts_image']; //car image
					}
				?>
            <tr>
            <td><img class="img_size" style="width:50px;height:50px;border:solid 1px #ccc;border-radius:5px;" src="<?php echo $image;  ?>" /></td>
			<td><?php echo $row['parts_name']; ?></td>
			<td><span class="label label-danger"><?php echo $row['condition']; ?></span></td>
			<td><span class="label label-success"><?php echo $row['quantity']; ?></span></td>
			<td><span class="label label-default"><?php echo $currency.$row['price']; ?></span></td>
			<td><?php echo !empty($row['parts_warranty']) ? '<span class="label label-primary">'.$row['parts_warranty'].'</span>' : ''; ?></td>
			<td><?php if($row['status'] == '1'){echo '<span class="label label-success">Enable</span>';} else {echo '<span class="label label-danger">Disable</span>';} ?></td>
            <td>
              <a class="btn btn-info" data-toggle="tooltip" href="<?php echo WEB_URL;?>parts_stock/buyparts.php?pid=<?php echo $row['parts_id']; ?>" data-original-title="Purchase or Load Parts Quantity"><i class="fa fa-cart-plus"></i></a> <a class="btn btn-warning" data-toggle="tooltip" href="javascript:;" onClick="$('#nurse_view_<?php echo $row['parts_id']; ?>').modal('show');" data-original-title="View"><i class="fa fa-eye"></i></a>  <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL;?>parts_stock/editpartsstock.php?id=<?php echo $row['parts_id']; ?>" data-original-title="Edit"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger" data-toggle="tooltip" onClick="deleteCustomer(<?php echo $row['parts_id']; ?>);" href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
            <div id="nurse_view_<?php echo $row['parts_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header orange_header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                    <h3 class="modal-title">Parts Details</h3>
                  </div>
                  <div class="modal-body model_view" align="center">&nbsp;
                    <div><img class="photo_img_round" style="width:100px;height:100px;" src="<?php echo $image;  ?>" /></div>
                    <div class="model_title"><?php echo $row['parts_name']; ?></div>
                  </div>
				  <div class="modal-body">
                    <h3 style="text-decoration:underline;">Parts Details Infromation</h3>
                    <div class="row">
                      <div class="col-xs-12">
                        <b>Parts Name :</b> <?php echo $row['parts_name']; ?><br/>
						<b>Supplier Name :</b> <?php echo $row['s_name']; ?><br/>
						<b>Manufacturer :</b> <span class="label label-info"><?php echo $row['manufacturer_name']; ?></span><br/>
						<b>Quantity :</b> <span class="label label-danger"><?php echo $row['quantity']; ?></span><br/>
						<b>Model no :</b> <?php echo $row['part_no']; ?><br/>
						<b>Condition :</b> <span class="label label-success"><?php echo $row['condition']; ?></span>
                      </div>
                    </div>
                  </div>				  
                </div>
              </div>
            </div>
            </td>
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
function deleteCustomer(Id){
  var iAnswer = confirm("Are you sure you want to delete this Item ?");
	if(iAnswer){
		window.location = '<?php echo WEB_URL; ?>parts_stock/partsstocklist.php?id=' + Id;
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
