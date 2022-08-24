<?php include('../cust_panel/header.php')?>
<?php
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0){
	$wms->deleteRepairCar($link, $_GET['id']); 
	$delinfo = 'block';
	$msg = "Deleted repair car information successfully";
}
if(isset($_GET['m']) && $_GET['m'] == 'add'){
	$addinfo = 'block';
	$msg = "Added repair car Information Successfully";
}
if(isset($_GET['m']) && $_GET['m'] == 'up'){
	$addinfo = 'block';
	$msg = "Updated repair car Information Successfully";
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-wrench"></i> My Repair Car</h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>cust_panel/cust_dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Repair Car List</li>
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
    <!-- <div align="right" style="margin-bottom:1%;"><a class="btn btn-warning" data-toggle="tooltip" href="<?php echo WEB_URL; ?>cust_panel/cust_dashboard.php" data-original-title="Dashboard"><i class="fa fa-dashboard"></i></a> </div> -->
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title"><i class="fa fa-list"></i> Repair Car List</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
			  <th>Repair ID</th>
              <th>Image</th>
			  <th>Car Name</th>
			  <th>Customer Name</th>
              <th>Make</th>
              <th>Model</th>
              <th>Year</th>
              <th>Chasis No</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
        <?php
				$result = $wms->getCustomerRepairCarList($link, $_SESSION['objCust']['user_id']);
				foreach($result as $row) {
					$image = WEB_URL . 'img/no_image.jpg';
					$image_customer = WEB_URL . 'img/no_image.jpg';
						
					if(file_exists(ROOT_PATH . '/img/upload/' . $row['car_image']) && $row['car_image'] != ''){
						$image = WEB_URL . 'img/upload/' . $row['car_image']; //car image
					}
					if(file_exists(ROOT_PATH . '/img/upload/' . $row['customer_image']) && $row['customer_image'] != ''){
						$image_customer = WEB_URL . 'img/upload/' . $row['customer_image']; //customer iamge
					}
				
				?>
            <tr>
			<td><span class="label label-success"><?php echo $row['repair_car_id']; ?></span></td>
            <td><img class="photo_img_round" style="width:50px;height:50px;" src="<?php echo $image;  ?>" /></td>
			<td><?php echo $row['car_name']; ?></td>
			<td><?php echo $row['c_name']; ?></td>
            <td><span class="label label-info"><?php echo $row['make_name']; ?></span></td>
            <td><span class="label label-warning"><?php echo $row['model_name']; ?></span></td>
            <td><span class="label label-default"><?php echo $row['year_name']; ?></span></td>
            <td><span class="label label-danger"><?php echo $row['chasis_no']; ?></span></td>
            <td><a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onClick="$('#nurse_view_<?php echo $row['car_id']; ?>').modal('show');" data-original-title="View"><i class="fa fa-eye"></i></a></a> <a class="btn btn-warning" data-toggle="tooltip" href="<?php echo WEB_URL;?>cust_panel/estimate.php?rid=<?php echo $row['repair_car_id']; ?>" data-original-title="Estimate History"><i class="fa fa-calculator"></i></a>
            <div id="nurse_view_<?php echo $row['car_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header orange_header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                    <h3 class="modal-title">Repair Car Details</h3>
                  </div>
                  <div class="modal-body model_view" align="center">&nbsp;
                    <div><img class="photo_img_round" style="width:100px;height:100px;" src="<?php echo $image;  ?>" /></div>
                    <div class="model_title"><?php echo $row['car_name']; ?></div>
					<div style="color:#fff;font-size:15px;font-weight:bold;">Invoice No: <?php echo $row['repair_car_id']; ?></div>
                  </div>
				  <div class="modal-body">
                    <h3 style="text-decoration:underline;">Car Details Infromation</h3>
                    <div class="row">
                      <div class="col-xs-12">
                        <b>Car Name :</b> <?php echo $row['car_name']; ?><br/>
						<b>Car Repair ID :</b> <?php echo $row['repair_car_id']; ?><br/>
                        <b>Car Make :</b> <?php echo $row['make_name']; ?><br/>
                        <b>Car Model :</b> <?php echo $row['model_name']; ?><br/>
                        <b>Year :</b> <?php echo $row['year_name']; ?><br/>
                        <b>Chasis No :</b> <?php echo $row['chasis_no']; ?><br/>
						<b>Registration No :</b> <?php echo $row['car_reg_no']; ?><br/>
						<b>VIN No :</b> <?php echo $row['VIN']; ?><br/>
						<b>Note :</b> <?php echo $row['note']; ?><br/>
						<b>Added Date :</b> <?php echo date('d/m/Y h:m:s', strtotime($row['added_date'])); ?>
                      </div>
                    </div>
                  </div>				  
                </div>
                <!-- /.modal-content -->
				<div class="modal-content">
                  <div class="modal-header orange_header">
                    <h3 class="modal-title">Customer Details</h3>
                  </div>
                  <div class="modal-body model_view" align="center">&nbsp;
                    <div><img class="photo_img_round" style="width:100px;height:100px;" src="<?php echo $image_customer;  ?>" /></div>
                    <div class="model_title"><?php echo $row['c_name']; ?></div>
                  </div>
				  <div class="modal-body">
                    <h3 style="text-decoration:underline;">Customer Details Infromation</h3>
                    <div class="row">
                      <div class="col-xs-12"> 
					    <b>Customer Name :</b> <?php echo $row['c_name']; ?><br/>
						<b>Customer Email :</b> <?php echo $row['c_email']; ?><br/>
						<b>Customer Telephone :</b> <?php echo $row['c_mobile']; ?><br/>
  
                      </div>
                    </div>
                  </div>				  
                </div>
				<!-- /.modal-content -->
              </div>
            </div>
            </td>
            </tr>
            <?php } mysqli_close($link);$link=NULL; ?>
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
  	var iAnswer = confirm("Are you sure you want to delete this Car ?");
	if(iAnswer){
		window.location = '<?php echo WEB_URL; ?>repaircar/carlist.php?id=' + Id;
	}
  }
  
  $( document ).ready(function() {
	setTimeout(function() {
		  $("#me").hide(300);
		  $("#you").hide(300);
	}, 3000);
});
</script>
<?php include('../cust_panel/footer.php'); ?>
