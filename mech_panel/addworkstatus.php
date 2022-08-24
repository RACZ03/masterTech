<?php 
include('../mech_panel/header.php');
$success = "none";
$w_date = date('d/m/Y');
$w_hour = '';
$w_details = '';


$title = 'Add Work Status';
$button_text="Save Information";
$hdnid="0";

/*#############################################################*/
if(isset($_POST['txtTotalHour'])){
	$wms->saveUpdateMechanicsWorkStatus($link, $_POST);
	if((int)$_POST['work_id'] > 0){
		$url = WEB_URL.'mech_panel/workstatus.php?m=up';
		header("Location: $url");
	} else {		
		$url = WEB_URL.'mech_panel/workstatus.php?m=add';
		header("Location: $url");
	}
	exit();
}

if(isset($_GET['wid']) && $_GET['wid'] != ''){
	$row = $wms->getWorkStatusInfoById($link, $_GET['wid']);
	if(!empty($row)) {
		$w_date = $wms->mySqlToDatePicker($row['work_date']);
		$w_hour = $row['total_hour'];
		$w_details = $row['work_details'];
		
		$hdnid = $_GET['wid'];
		$title = 'Update Work Status';
		$button_text="Update Information";
	}
}
	
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-wrench"></i> <?php echo $title; ?> </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?php echo WEB_URL?>customer.customerlist.php">Customer</a></li>
    <li class="active">Add/Update Work Status</li>
  </ol>
</section>
<!-- Main content -->
<form method="post" enctype="multipart/form-data">
  <section class="content">
    <!-- Full Width boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12">
		<div align="right" style="margin-bottom:1%;"> <button class="btn btn-success" type="submit" data-toggle="tooltip" href="javascript:;" data-original-title="<?php echo $button_text; ?>"><i class="fa fa-save"></i></button> &nbsp;<a class="btn btn-warning" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>mech_panel/workstatus.php" data-original-title="Back"><i class="fa fa-reply"></i></a> </div>
		
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-plus"></i> Work Status Form</h3>
          </div>
          <div class="box-body">
            <div class="form-group">
              <label for="txtWorkDate"><span style="color:red;">*</span> Work Date :</label>
              <input type="text" name="txtWorkDate" value="<?php echo $w_date;?>" id="txtWorkDate" required class="form-control datepicker" />
            </div>
            <div class="form-group">
              <label for="txtTotalHour"><span style="color:red;">*</span> Total Hour :</label>
              <input type="text" name="txtTotalHour" value="<?php echo $w_hour;?>" id="txtTotalHour" required class="form-control allownumberonly" />
            </div>
            <div class="form-group">
              <label for="txtWorkDetails"><span style="color:red;">*</span> Work Details :</label>
              <textarea name="txtWorkDetails" id="txtWorkDetails" class="form-control" required><?php echo $w_details; ?></textarea>
            </div>
            
          </div>
          <input type="hidden" value="<?php echo $hdnid; ?>" name="work_id"/>
		  <input type="hidden" value="<?php echo $_SESSION['objMech']['user_id']; ?>" name="mechanic_id"/>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
    </div>
  </section>
</form>
<?php include('../mech_panel/footer.php'); ?>
