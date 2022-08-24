<?php include('../header.php')?>
<?php
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
if(isset($_GET['delid']) && $_GET['delid'] != '' && $_GET['delid'] > 0){
	$wms->deleteCarRequest($link, $_GET['delid']); 
	$delinfo = 'block';
}
if(isset($_GET['callid']) && $_GET['callid'] != '' && $_GET['callid'] > 0){
	$wms->setCarRequestStatus($link, $_GET['callid']); 
	$addinfo = 'block';
	$msg = "Contact with customer successfully";
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1> <i class="fa fa-car"></i> Customer Car Request </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Apointment List</li>
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
      Apointment Request Deleted Successfully. </div>
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      <?php echo $msg; ?> </div>
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL; ?>car_request/carrequest.php" data-original-title="Reload Page"><i class="fa fa-refresh"></i></a> <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="Dashboard"><i class="fa fa-dashboard"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title">Requested List</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
			  <th>Name</th>
              <th>Email</th>
			  <th>Telephone</th>
			  <th>My Price</th>
			  <th>Status</th>
              <th>Requested Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
			 $image = '';
			 $rid = 0;
			 if(isset($_GET['rid']) && (int)$_GET['rid'] > 0) {
			 	$rid = $_GET['rid'];
			 }
			 $results = $wms->get_all_car_request_list($link, $rid);
			 foreach($results as $row) {
			 $image = WEB_URL . 'img/no_image.jpg';	
			 if(file_exists(ROOT_PATH . '/img/upload/' . $row['car_image']) && $row['car_image'] != ''){
				$image = WEB_URL . 'img/upload/' . $row['car_image'];
			 }
			 ?>
            <tr>
			  <td><?php echo $row['name']; ?></td>
			  <td><?php echo $row['email']; ?></td>
			  <td><?php echo $row['phone']; ?></td>
			  <td><?php echo $currency.$row['price']; ?></td>
              <td><?php if($row['status']==1) echo "<span class='label label-success'>Success</span>"; else echo "<span class='label label-danger'>Waiting</span>"; ?></td>
              <td><b><?php echo $wms->mySqlToDatePicker($row['requested_date']);?></b></td>
              <td><a class="btn btn-warning" data-toggle="tooltip" href="javascript:;" onClick="$('#nurse_view_<?php echo $row['car_request_id']; ?>').modal('show');" data-original-title="Message"><i class="fa fa-envelope-o"></i></a> <a class="btn btn-success" data-toggle="tooltip" onClick="talkdone(<?php echo $row['car_request_id']; ?>);" data-original-title="Contact Done ?"><i class="fa fa-phone"></i></a> <a class="btn btn-danger" data-toggle="tooltip" onClick="deletemechanics(<?php echo $row['car_request_id']; ?>);" href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
			  <div id="nurse_view_<?php echo $row['car_request_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header orange_header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                        <h3 class="modal-title">Request Details</h3>
                      </div>
                      <div class="modal-body model_view" align="center">&nbsp;
                        <div><img class="photo_img_round" style="width:100px;height:100px;" src="<?php echo $image;  ?>" /></div>
                        <div class="model_title"><?php echo $row['car_name']; ?></div>
                      </div>
					  
                      <div class="modal-body">
                        <h3 class="modal-title"><u>Message</u></h3>
						<div class="row">
						  <div class="col-xs-12"><?php echo $row['details']; ?>
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
function deletemechanics(Id){
  	var iAnswer = confirm("Are you sure you want to delete this request ?");
	if(iAnswer){
		window.location = '<?php echo WEB_URL; ?>car_request/carrequest.php?delid=' + Id;
	}
}
function talkdone(Id){
  	var iAnswer = confirm("Are you sure you want to set status done ?");
	if(iAnswer){
		window.location = '<?php echo WEB_URL; ?>car_request/carrequest.php?callid=' + Id;
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
