<?php include('../header.php')?>
<?php
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0){
	$wms->deleteMechanicSalery($link, $_GET['id']);
	$delinfo = 'block';
}
//	add success
if(isset($_GET['m']) && $_GET['m'] == 'add'){
	$addinfo = 'block';
	$msg = "Added Mechanics Salary Information Successfully";
}
//	update success
if(isset($_GET['m']) && $_GET['m'] == 'up'){
	$addinfo = 'block';
	$msg = "Updated mechanics Salary Information Successfully";
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-user"></i> Mechanics Salary </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Mechanics Salary List</li>
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
      Deleted mechanics salery information successfully. </div>
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      <?php echo $msg; ?> </div>
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL; ?>mechanics/addsalary.php" data-original-title="Add Salary"><i class="fa fa-plus"></i></a> <a class="btn btn-warning" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="Dashboard"><i class="fa fa-dashboard"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title"><i class="fa fa-list"></i> Mechanics Salary List</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
				<th>Name</th>
				<!-- <th>Hour</th>
				<th>Total</th> -->
				<th>Month</th>
				<th>Year</th>
				<!-- <th>Salary Date</th> -->
				<th>Action</th>
            </tr>
          </thead>
          <tbody>
        <?php
				$result = $wms->getAllMechnahicSaleryList($link);
				foreach($result as $row) {
					$image = WEB_URL . 'img/no_image.jpg';	
					if(file_exists(ROOT_PATH . '/img/employee/' . $row['m_image']) && $row['m_image'] != ''){
						$image = WEB_URL . 'img/employee/' . $row['m_image'];
					}
				
				?>
            <tr>
            <td><?php echo $row['m_name']; ?></td>
			<!-- <td><span class="label label-success"><?php echo $row['total_time']; ?></span></td>
			<td><span class="label label-primary"><?php echo $currency.$row['total']; ?></span></td> -->
			<td><span class="label label-danger"><?php echo $wms->getMonthValueToMonthName($row['month_id']); ?></span></td>
			<td><span class="label label-info"><?php echo $row['year_id']; ?></span></td>
			<!-- <td><span class="label label-warning"><?php echo $wms->mySqlToDatePicker($row['sl_date']); ?></span></td>-->
            <td>
            <a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onClick="$('#nurse_view_<?php echo $row['m_salary_id']; ?>').modal('show');" data-original-title="View"><i class="fa fa-eye"></i></a> <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL;?>mechanics/addsalary.php?id=<?php echo $row['m_salary_id']; ?>" data-original-title="Edit"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger" data-toggle="tooltip" onClick="deletemechanics(<?php echo $row['m_salary_id']; ?>);" href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
            <div id="nurse_view_<?php echo $row['m_salary_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header orange_header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                    <h3 class="modal-title">Mechanics Details</h3>
                  </div>
                  <div class="modal-body model_view" align="center">&nbsp;
                    <div><img class="photo_img_round" style="width:200px;height:200px;" src="<?php echo $image;  ?>" /></div>
                    <div class="model_title"><?php echo $row['m_name']; ?></div>
                  </div>
				  <div class="modal-body">
                    <h3 style="text-decoration:underline;">Details Infromation</h3>
                    <div class="row">
                      <div class="col-xs-12"> 
					    <b>Name :</b> <?php echo $row['m_name']; ?><br/>
						<!-- <b>Salary :</b> <?php echo $row['fix_salary']; ?><br/>
						<b>Time :</b> <span class="label label-info"><?php echo $row['total_time']; ?></span><br/>
						<b>Total :</b> <?php echo $currency.$row['total']; ?><br/>
						<b>Paid :</b> <span class="label label-success"><?php echo $currency.$row['paid_amount']; ?></span><br/>
						<b>Due :</b> <?php echo $currency.$row['due_amount']; ?><br/> -->
						<b>Paid Month :</b> <span class="label label-info"><?php echo $wms->getMonthValueToMonthName($row['month_id']); ?></span><br/>
                        <b>Phone Number :</b> <?php echo $row['m_phone_number']; ?><br/>
						<b>Paid Year :</b> <span class="label label-danger"><?php echo $row['year_id']; ?></span><br/>
                        <b>Email :</b> <?php echo $row['m_email']; ?><br/>
						<!--<b>Salery Date :</b> <span class="label label-warning"><?php echo $wms->mySqlToDatePicker($row['sl_date']); ?></span>-->
                      </div>
                    </div>
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
function deletemechanics(Id){
  	var iAnswer = confirm("Are you sure you want to delete this salery history ?");
	if(iAnswer){
		window.location = '<?php echo WEB_URL; ?>mechanics/mechanicsalarylist.php?id=' + Id;
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
