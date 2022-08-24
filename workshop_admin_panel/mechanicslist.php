<?php include('../workshop_admin_panel/header.php')?>
<?php
$delinfo = 'none';
$addinfo = 'none';
$msg = "";

if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0){
	$wms->deleteMechanics($link, $_GET['id']);
	$delinfo = 'block';
}
if(isset($_GET['m']) && $_GET['m'] == 'add'){
	$addinfo = 'block';
	$msg = "Added Mechanics Successfully";
}
if(isset($_GET['m']) && $_GET['m'] == 'up'){
	$addinfo = 'block';
	$msg = "Updated Mechanics Successfully";
}
if(isset($_GET['m']) && $_GET['m'] == 'page'){
	$addinfo = 'block';
	$msg = "Save Mechanics Page Information Successfully";
}

?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1> Mechanics List </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Mechanics List</li>
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
      Deleted mechanics Information Successfully. </div>
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      <?php echo $msg; ?> </div>
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL; ?>workshop_admin_panel/addmechanics.php" data-original-title="Add Mechanics"><i class="fa fa-plus"></i></a> <a class="btn btn-warning" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="Dashboard"><i class="fa fa-dashboard"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title">Mechanics List</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th>Image</th>
              <th>Name</th>
              <!-- <th>Cost</th> -->
              <th>Phone Number</th>
              <th>Designation</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
			$results = $wms->getAllMechanicsList($link);
			foreach($results as $row) {
				$image = WEB_URL . 'img/no_image.jpg';	
				if(file_exists(ROOT_PATH . '/img/employee/' . $row['m_image']) && $row['m_image'] != ''){
					$image = WEB_URL . 'img/employee/' . $row['m_image'];
				}
			
			?>
            <tr>
              <td><img class="photo_img_round" style="width:50px;height:50px;" src="<?php echo $image;  ?>" /></td>
              <td><?php echo $row['m_name']; ?></td>
              <!--<td><label class="label label-success"><?php echo $currency; ?><?php echo $row['m_cost']; ?></label></td>-->
              <td><?php echo $row['m_phone_number']; ?></td>
              <td><label class="label label-primary"><?php echo $row['title']; ?></label></td>
              <td><?php if($row['status'] == 1){echo "<label class='label label-success'>Active</label>";} else {echo "<label class='label label-danger'>In-Active</label>";} ?></td>
              <td><a class="btn btn-warning" data-toggle="tooltip" href="<?php echo WEB_URL;?>workshop_admin_panel/pagebuilder.php?id=<?php echo $row['mechanics_id']; ?>" data-original-title="Mechanics Page"><i class="fa fa-file-text"></i></a> <a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onClick="$('#nurse_view_<?php echo $row['mechanics_id']; ?>').modal('show');" data-original-title="View"><i class="fa fa-eye"></i></a> <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL;?>workshop_admin_panel/addmechanics.php?id=<?php echo $row['mechanics_id']; ?>" data-original-title="Edit"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger" data-toggle="tooltip" onClick="deletemechanics(<?php echo $row['mechanics_id']; ?>);" href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
                <div id="nurse_view_<?php echo $row['mechanics_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header orange_header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                        <h3 class="modal-title">Mechanics Details</h3>
                      </div>
                      <div class="modal-body model_view" align="center">&nbsp;
                        <div><img class="photo_img_round" style="width:280px;height:350px;" src="<?php echo $image;  ?>" /></div>
                        <div class="model_title"><?php echo $row['m_name']; ?></div>
                      </div>
                      <div class="modal-body">
                        <h3 style="text-decoration:underline;">Details Infromation</h3>
                        <div class="row">
                          <div class="col-xs-12"> <b>Name :</b> <?php echo $row['m_name']; ?><br/>
                            <!-- <b>Cost Per Month :</b>
                            <label class="label label-info"><?php echo $currency. $row['m_cost']; ?></label>
                            <br/> -->
                            <b>Phone Number :</b> <?php echo $row['m_phone_number']; ?><br/>
                            <b>Password :</b>
                            <label class="label label-warning"><?php echo $row['m_password']; ?></label>
                            <br/>
                            <b>Email :</b> <?php echo $row['m_email']; ?><br/>
							              <b>Joining Date :</b> <label class="label label-info"><?php echo $wms->mySqlToDatePicker($row['joining_date']); ?></label><br/>
                            <b>Present Address :</b> <span style="white-space: pre-wrap;"><?php echo $row['m_present_address']; ?></span><br/>
                            <b>Permanent Address :</b> <span style="white-space: pre-wrap;"><?php echo $row['m_permanent_address']; ?></span><br/>
                            <b>Designation :</b>
                            <label class="label label-primary"><?php echo $row['title']; ?></label>
                            <br/>
                            <b>Notes :</b> <?php echo $row['m_notes']; ?><br/>
                            <b>Status :</b>
                            <label class="label label-danger">
                            <?php if($row['status'] == 1){echo "Active";} else {echo "In-Active";} ?>
                            </label>
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
function deletemechanics(Id){
  	var iAnswer = confirm("Are you sure you want to delete this mechanics ?");
	if(iAnswer){
		window.location = '<?php echo WEB_URL; ?>workshop_admin_panel/mechanicslist.php?id=' + Id;
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
