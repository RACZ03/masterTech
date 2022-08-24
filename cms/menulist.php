<?php include('../header.php')?>
<?php
$delinfo = 'none';
$addinfo = 'none';
$status = '';
$msg = "";
if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0){
	$wms->deleteMenu($link, $_GET['id']);
	$delinfo = 'block';
}
if(isset($_GET['m']) && $_GET['m'] == 'add'){
	$addinfo = 'block';
	$msg = "Added Menu Successfully";
}
if(isset($_GET['m']) && $_GET['m'] == 'up'){
	$addinfo = 'block';
	$msg = "Updated Menu Successfully";
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1> Menu List </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Menu List</li>
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
      Deleted Menu Information Successfully. </div>
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      <?php echo $msg; ?> </div>
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL; ?>cms/addmenu.php" data-original-title="Add Menu"><i class="fa fa-plus"></i></a> <a class="btn btn-warning" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="Dashboard"><i class="fa fa-dashboard"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title">Menu List</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th>Page Title</th>
			  <th>Parent Slug</th>
              <th>Parent</th>
              <th>Target Page</th>
			  <th>Status</th>
              <th>Sort Id</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
			$results = $wms->getMenuList($link);
			foreach($results as $row){
			?>
            <tr>
              <td><?php echo $row['menu_name']; ?></td>
			  <td><?php echo $row['url_slug']; ?></td>
              <td><?php if(!empty($row['p_menu'])){echo $row['p_menu'];} ?></td>
              <td><?php echo !empty($row['page_title']) ? $row['page_title'] : str_replace('.php','',$row['fixed_page_url']); ?></td>
              <td><?php if($row['menu_status'] == 1){echo "Enable";} else {echo "Disable";} ?></td>
              <td><?php echo $row['menu_sort_order']; ?></td>
              <td><a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL;?>cms/addmenu.php?id=<?php echo $row['menu_id']; ?>" data-original-title="Edit"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger" data-toggle="tooltip" onClick="deleteCustomer(<?php echo $row['menu_id']; ?>);" href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a> </td>
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
  	var iAnswer = confirm("Are you sure you want to delete this Menu Item ?");
	if(iAnswer){
		window.location = '<?php echo WEB_URL; ?>cms/menulist.php?id=' + Id;
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
