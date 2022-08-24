<?php 
include('../header.php');
$success = "none";
$title = 'Add Mechanics Salary';
$button_text="Save Information";
$successful_msg="Add Salary Successfully";
$form_url = WEB_URL . "mechanics/addsalary.php";
$mechanics_list = '';
// $fix_salary = '';
// $total_time = '';
// $total = '';
// $paid = '';
// $due = '';
// $salary_date = date('d/m/Y');
$hdnid=0;
$month_id = '';
$year_id = '';

if(isset($_POST['ddlMechanicslist'])){
	$wms->saveUpdateMechanicSaleryInformation($link, $_POST);
	if((int)$_POST['salery_id'] > 0){
		$url = WEB_URL.'mechanics/mechanicsalarylist.php?m=up';
		header("Location: $url");
	} else {		
		$url = WEB_URL.'mechanics/mechanicsalarylist.php?m=add';
		header("Location: $url");
	}
	exit();
}

if(isset($_GET['id']) && $_GET['id'] != ''){
	$row = $wms->getMechanicSlaeryInfoBySaleryId($link, $_GET['id']);
	if(!empty($row)){
		$mcns_id = $row['mechanics_id'];
		$mechanics_list = $row['m_name'];
		// $fix_salary = $row['fix_salary'];
		// $total_time = $row['total_time'];
		// $total = $row['total'];
		// $paid = $row['paid_amount'];
		// $due = $row['due_amount'];
		// $salary_date = $wms->mySqlToDatePicker($row['sl_date']);
		$hdnid = $_GET['id'];
		$year_id = $row['year_id'];
		$month_id = $row['month_id'];
		$title = 'Update mechanics Salary';
		$button_text="Update";
		$successful_msg="Update mechanics salary Successfully";
		$form_url = WEB_URL . "mechanics/addsalary.php?id=".$_GET['id'];
	}
}
	
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-money"></i> Mechanics Salary </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Mechanics Salary</li>
    <li class="active">Add Mechanics Salary</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<form onSubmit="return validateMe();" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">
  <div class="row">
    <div class="col-md-12">
      <div align="right" style="margin-bottom:1%;">
        <button class="btn btn-success" type="submit" data-toggle="tooltip" href="javascript:;" data-original-title="<?php echo $button_text; ?>"><i class="fa fa-save"></i></button>
          <a class="btn btn-warning" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>mechanics/mechanicsalarylist.php" data-original-title="Back"><i class="fa fa-reply"></i></a> </div>
      <div class="box box-success">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-plus"></i> <?php echo $title; ?></h3>
        </div>
        <div class="box-body">
          <div class="form-group col-md-12">
            <label for="ddlMechanicslist"><span style="color:red;">*</span> Select Mechanics :</label>
            <select onchange="loadSalaryPayment(this.value);" class='form-control' id="ddlMechanicslist" name="ddlMechanicslist">
              <option value="">--Select Mechanics--</option>
              <?php
					$mechanics_list = $wms->getAllMechanicListSortByName($link);
					foreach($mechanics_list as $row) {
						if($mcns_id > 0 && $mcns_id == $row['mechanics_id']) {
							echo '<option selected value="'.$row['mechanics_id'].'">'.$row['m_name'].'</option>';
						} else {
							echo '<option value="'.$row['mechanics_id'].'">'.$row['m_name'].'</option>';
						}
					}
				?>
            </select>
          </div>
		  <div class="form-group col-md-6">
            <label for="ddlMonth"><span style="color:red;">*</span> Select Month :</label>
            <select onchange="loadWorkedHour();" class="form-control" name="ddlMonth" id="ddlMonth" required>
              <option value=''>--Select Month--</option>
              <option <?php if($month_id=='01'){echo 'selected';}?> value='01'>January</option>
              <option <?php if($month_id=='02'){echo 'selected';}?> value='02'>February</option>
              <option <?php if($month_id=='03'){echo 'selected';}?> value='03'>March</option>
              <option <?php if($month_id=='04'){echo 'selected';}?> value='04'>April</option>
              <option <?php if($month_id=='05'){echo 'selected';}?> value='05'>May</option>
              <option <?php if($month_id=='06'){echo 'selected';}?> value='06'>June</option>
              <option <?php if($month_id=='07'){echo 'selected';}?> value='07'>July</option>
              <option <?php if($month_id=='08'){echo 'selected';}?> value='08'>August</option>
              <option <?php if($month_id=='09'){echo 'selected';}?> value='09'>September</option>
              <option <?php if($month_id=='10'){echo 'selected';}?> value='10'>October</option>
              <option <?php if($month_id=='11'){echo 'selected';}?> value='11'>November</option>
              <option <?php if($month_id=='12'){echo 'selected';}?> value='12'>December</option>
            </select>
          </div>
		  <div class="form-group col-md-6">
            <label for="ddlYear"><span style="color:red;">*</span> Select Year :</label>
            <select onchange="loadWorkedHour();" class="form-control" name="ddlYear" id="ddlYear">
              <option value=''>--Select Year--</option>
              <?php 
			  	for($i=2000;$i<=date('Y');$i++){
			  		if($year_id==$i) {
						echo '<option selected value="'.$i.'">'.$i.'</option>';
					} else {
						echo '<option value="'.$i.'">'.$i.'</option>';
					}
			    }
				?>
            </select>
          </div>
          <!-- <div class="form-group col-md-6">
            <label for="txtFixsalary"><span style="color:red;">*</span> Hour Rate :</label>
            <div class="input-group"> <span class="input-group-addon"><?php echo $currency; ?></span>
              <input type="text" name="txtFixsalary" style="color:red;font-weight:bold;" placeholder="0.00" value="<?php echo $fix_salary;?>" id="buy_prie" class="form-control ppcal allownumberonly" />
            </div>
          </div>
          <div class="form-group col-md-6">
            <label for="txtTotaltime"><span style="color:red;">*</span> Total Hour In Month :</label>
            <input type="text" name="txtTotaltime" style="color:red;font-weight:bold;" placeholder="0.00"value="<?php echo $total_time;?>" id="parts_quantity" class="form-control ppcal allownumberonly" />
          </div>
          <div class="form-group col-md-6">
            <label for="total_amount"><span style="color:red;">*</span> Total Amount :</label>
            <div class="input-group"> <span class="input-group-addon"><?php echo $currency; ?></span>
              <input type="text" readonly="" placeholder="0.00" style="color:red;font-weight:bold;" name="txtTotal" value="<?php echo $total;?>" id="total_amount" class="form-control ppcal allownumberonly" />
            </div>
          </div>
          <div class="form-group col-md-6">
            <label for="total_amount"><span style="color:red;">*</span> Paid Amount :</label>
            <div class="input-group"> <span class="input-group-addon"><?php echo $currency; ?></span>
              <input placeholder="0.00" name="given_amount" value="<?php echo $paid; ?>" id="given_amount" class="form-control allownumberonly ppcal" type="text">
            </div>
          </div>
          <div class="form-group col-md-6">
            <label for="total_amount"><span style="color:red;">*</span> Due Amount :</label>
            <div class="input-group"> <span class="input-group-addon"><?php echo $currency; ?></span>
              <input readonly="" style="color:red;font-weight:bold;" placeholder="0.00"  value="<?php echo $due; ?>" name="pending_amount" id="pending_amount" class="form-control allownumberonly" required type="text">
            </div>
          </div>
          <div class="form-group col-md-6">
            <label for="txtSalarydate">Salary Date (dd/mm/yyyy) :</label>
            <input type="text" placeholder="Salery Date" name="txtSalarydate" id="txtSalarydate" value="<?php echo $salary_date; ?>" class="form-control datepicker" required/>
          </div> -->
        </div>
        <input type="hidden" value="<?php echo $hdnid; ?>" name="salery_id"/>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
  </div>
</form>
<!-- /.row -->
<script type="text/javascript">
function validateMe(){
	if($("#ddlMechanicslist").val() == ''){
		alert("Select Mechanic !!!");
		$("#ddlMechanicslist").focus();
		return false;
	}
	else if($("#txtFixsalary").val() == ''){
		alert("Salery required !!!");
		$("#txtFixsalary").focus();
		return false;
	}
	else if($("#ddlMonth").val() == ''){
		alert("Salery month required !!!");
		$("#ddlMonth").focus();
		return false;
	}
	else if($("#txtTotaltime").val() == ''){
		alert("Total time required !!!");
		$("#txtTotaltime").focus();
		return false;
	}
	else if($("#txtTotal").val() == ''){
		alert("Total amount required!!!");
		$("#txtTotal").focus();
		return false;
	}
	else{
		return true;
	}
}
</script>
<?php include('../footer.php'); ?>
