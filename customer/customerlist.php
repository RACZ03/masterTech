<?php 
include('../header.php');
define('ROOT_PATH', '../');
?>
<?php
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0){
	$wms->deleteCustomer($link, $_GET['id']);
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
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-users"></i> Customer </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Customer List</li>
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
      Deleted Customer Information Successfully. </div>
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      <?php echo $msg; ?> </div>
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL; ?>customer/addcustomer.php" data-original-title="Add Customer"><i class="fa fa-plus"></i></a> <a class="btn btn-warning" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="Dashboard"><i class="fa fa-dashboard"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title"><i class="fa fa-list"></i> Customer List</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <!-- <th>Image</th>
              <th>Name</th>
              <th>Email</th>
              <th>Home Tel</th>
              <th>Work Tel</th>
              <th>Mobile Tel</th>
              <th>Action</th> -->
              <th>Imagen</th>
              <th>Nombre</th>
              <th>E-Mail</th>
              <th>Teléfono de casa</th>
              <th>Teléfono</th>
              <th>Teléfono móvil</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php
				$result = $wms->getAllCustomerList($link);
				foreach($result as $row) {
					$image = WEB_URL . 'img/no_image.jpg';	
					if(file_exists(ROOT_PATH . '/img/upload/' . $row['image']) && $row['image'] != ''){
						$image = WEB_URL . 'img/upload/' . $row['image'];
					}
				
				?>
            <tr>
              <td><img class="photo_img_round" style="width:50px;height:50px;" src="<?php echo $image;  ?>" /></td>
              <td><?php echo $row['c_name']; ?></td>
              <td><?php echo $row['c_email']; ?></td>
              <td><?php echo $row['c_home_tel']; ?></td>
              <td><?php echo $row['c_work_tel']; ?></td>
              <td><?php echo $row['c_mobile']; ?></td>
              <td>
                <a class="btn btn-info" data-toggle="tooltip" href="<?php echo WEB_URL;?>repaircar/addcar.php?cid=<?php echo $row['customer_id']; ?>" 
                   data-original-title="Add machinery" title="Add machinery">
                   <i class="fa fa-calculator"></i>
                </a> 
                <a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onClick="$('#nurse_view_<?php echo $row['customer_id']; ?>').modal('show');" 
                  data-original-title="View">
                  <i class="fa fa-eye"></i>
                </a> 
                <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL;?>customer/addcustomer.php?id=<?php echo $row['customer_id']; ?>" 
                  data-original-title="Edit">
                  <i class="fa fa-pencil"></i>
                </a> 
                <a class="btn btn-danger" data-toggle="tooltip" onClick="deleteCustomer(<?php echo $row['customer_id']; ?>);" href="javascript:;" data-original-title="Delete">
                  <i class="fa fa-trash-o"></i>
                </a>
                <div id="nurse_view_<?php echo $row['customer_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <!-- Modal Customers Details -->
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header orange_header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                        <h3 class="modal-title">Customer Details</h3>
                      </div>
                      <div class="modal-body model_view" align="center">&nbsp;
                        <div><img class="photo_img_round" style="width:100px;height:100px;" src="<?php echo $image;  ?>" /></div>
                        <div class="model_title"><?php echo $row['c_name']; ?></div>
                      </div>
                      <div class="modal-body">
                        <h3 style="text-decoration:underline;">Details Infromation</h3>
                        <div class="row">
                          <div class="col-xs-12"> <b>Name :</b> <?php echo $row['c_name']; ?><br/>
                            <b>Email :</b> <?php echo $row['c_email']; ?><br/>
                            <b>Address :</b> <?php echo $row['c_address']; ?><br/>
                            <b>Home Tel :</b> <?php echo $row['c_home_tel']; ?><br/>
                            <b>Work Tel :</b> <?php echo $row['c_work_tel']; ?><br/>
                            <b>Mobile Tel :</b> <?php echo $row['c_mobile']; ?> </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- /.modal-content -->
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
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
  function deleteCustomer(Id){
    Swal.fire({
      title: '¿Está seguro de que desea eliminar este cliente?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, eliminar!'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location = '<?php echo WEB_URL; ?>customer/customerlist.php?id=' + Id;
      }
    });
  }
  
  $( document ).ready(function() {
	setTimeout(function() {
		  $("#me").hide(300);
		  $("#you").hide(300);
	}, 3000);
});
</script>
<?php include('../footer.php'); ?>
