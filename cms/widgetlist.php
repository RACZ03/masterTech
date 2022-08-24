<?php include('../header.php')?>
<?php
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0){
	$wms->deleteCMS($link, $_GET['id']);
	$delinfo = 'block';
}
if(isset($_GET['m']) && $_GET['m'] == 'add'){
	$addinfo = 'block';
	$msg = "Added CMS Information Successfully";
}
if(isset($_GET['m']) && $_GET['m'] == 'up'){
	$addinfo = 'block';
	$msg = "Updated CMS Information Successfully";
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1> <i class="fa fa-th-large"></i> Widget </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Widgets</li>
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
    <div align="right" style="margin-bottom:1%;"><a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="Dashboard"><i class="fa fa-dashboard"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title">Widget List</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th>Widget Name</th>
              <th>Show Pages</th>
              <th>Sort Order</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
			$results = $wms->getAllWidgetList($link);
			foreach($results as $result) { ?>
			<tr>
				<td><?php echo $result['name']; ?></td>
				<td></td>
				<td></td>
				<td><a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL;?>cms/editwidget.php?id=<?php echo $result['widget_id']; ?>" data-original-title="Edit Widget"><i class="fa fa-edit"></i></a></td>
			</tr>
			<?php } ?>
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
  	var iAnswer = confirm("Are you sure you want to delete this Customer ?");
	if(iAnswer){
		window.location = '<?php echo WEB_URL; ?>cms/cmslist.php?id=' + Id;
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
