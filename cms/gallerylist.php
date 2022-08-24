<?php include('../header.php')?>
<?php
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
if(isset($_GET['delid']) && $_GET['delid'] != '' && $_GET['delid'] > 0){
	$wms->deleteGallery($link, $_GET['delid']); 
	$delinfo = 'block';
}
if(isset($_GET['m']) && $_GET['m'] == 'add'){
	$addinfo = 'block';
	$msg = "Added Gallery Successfully";
}
if(isset($_GET['m']) && $_GET['m'] == 'up'){
	$addinfo = 'block';
	$msg = "Updated Gallery Successfully";
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-image"></i> Gallery </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Gallery List</li>
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
      Deleted Gallery Successfully. </div>
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      <?php echo $msg; ?> </div>
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL; ?>cms/addgallery.php" data-original-title="Add Slider"><i class="fa fa-plus"></i></a> <a class="btn btn-warning" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="Dashboard"><i class="fa fa-dashboard"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title">Gallery List</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th>Gallery Category</th>
              <th>Sort Order</th>
			  <th>Status</th>
			  <th>Action</th>
            </tr>
          </thead>
          <tbody>
        <?php
			$results = $wms->getAllGalleryInformation($link);
			foreach($results as $row) { ?>
            <tr>
            <td><?php echo $row['gallery_name']; ?></td>
			<td><?php echo $row['sort_order']; ?></td>
			<td><?php if($row['status'] == 1){echo "Enable";} else {echo "Disable";} ?></td>		
            <td>
            <a class="btn btn-warning" data-toggle="tooltip" href="javascript:;" onClick="$('#nurse_view_<?php echo $row['gallery_id']; ?>').modal('show');" data-original-title="View"><i class="fa fa-eye"></i></a> <a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL;?>cms/addgallery.php?id=<?php echo $row['gallery_id']; ?>" data-original-title="Edit"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger" data-toggle="tooltip" onClick="deleteGallery(<?php echo $row['gallery_id']; ?>);" href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>  
			<div id="nurse_view_<?php echo $row['gallery_id']; ?>" class="modal fade clearfix" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header orange_header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                        <h3 class="modal-title"><?php echo $row['gallery_name']; ?> Images</h3>
                      </div>
                      <div class="modal-body">
                        <?php foreach($row['images'] as $image) { ?>
					    	<div class="col-md-4"><div><img class="img-thumbnail" style="width:150px;height:150px;" src="<?php echo $image['image_url']; ?>"></div>
							<div align="center"><span class="label label-info"><b><?php echo $image['text']; ?></span></b></div><br/>
							</div>
						<?php } ?>
						<div style="clear:both;"></div>
                      </div>
                    </div>
                    <!-- /.modal-content -->
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
	function deleteGallery(Id){
		var iAnswer = confirm("Are you sure you want to delete this gallery ?");
		if(iAnswer){
			window.location = '<?php echo WEB_URL; ?>cms/gallerylist.php?delid=' + Id;
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
