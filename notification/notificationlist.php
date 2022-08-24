<?php
include_once('../header.php');
$results = array();
$delinfo = 'none';

if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0){
	$wms->deleteNotificationEmailAlert($link, $_GET['id']);
	$delinfo = 'block';
}
//load list
$results = $wms->getCustomerNotificationEmails($link);
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-list"></i> Email Notification List </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Email Notification List</li>
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
      Deleted customer notification successfully. </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title">Email Notification List</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th>Customer Name</th>
              <th>Car</th>
              <th>Progress</th>
              <th>Notify Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
				if(!empty($results)) {
				foreach($results as $result) {
				?>
            <tr>
              <td><?php echo $result['c_name']; ?></td>
              <td><?php echo $result['car_name']; ?></td>
              <td><label class="label label-success"><?php echo $result['progress']; ?>%</label></td>
              <td><?php echo date("m/d/Y g:i A", strtotime($result['notify_date'])); ?></td>
              <td><a class="btn btn-danger" data-toggle="tooltip" onClick="deleteNotifyEmail(<?php echo $result['n_id']; ?>);" href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a> </td>
            </tr>
            <?php } } $wms->close_db_connection($link); ?>
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
function deleteNotifyEmail(Id){
  	var iAnswer = confirm("Are you sure you want to delete this notification ?");
	if(iAnswer){
		window.location = '<?php echo WEB_URL; ?>notification/notificationlist.php?id=' + Id;
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
