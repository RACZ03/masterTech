<?php 
include('../workshop_admin_panel/header.php');
$success = "none";
$cus_id = 0;
$mechanics_id = 0;
$m_name= '';
$car_names= '';
$c_name = '';
$c_make = 0;
$c_model = 0;
$c_year = 0;
$c_chasis_no = '';
$vin = '';
$c_registration = '';
$c_note = '';
$c_add_date = '';
$title = 'Crear Orden';
// $button_text="Save Information";
$button_text="GUARDAR";
$successful_msg="Add Repair Car Successfully";
$form_url = WEB_URL . "workshop_admin_panel/addcar.php";
$id="";
$hdnid="0";
$image_cus = WEB_URL . 'img/no_image.jpg';
$img_track = '';

$invoice_id = substr(number_format(time() * rand(),0,'',''),0,6);

/*added my*/
$cus_id = 0;
if(isset($_GET['cid']) && (int)$_GET['cid'] > 0) {
	$cus_id = $_GET['cid'];
}


if(isset($_POST['car_names'])){
	$image_url = uploadImage();
	if(empty($image_url)) {
		$image_url = $_POST['img_exist'];
	}
	$wms->saveUpdateRepairCarInformation($link, $_POST, $image_url);
	if((int)$_POST['repair_car'] > 0){
		$url = WEB_URL.'workshop_admin_panel/carlist.php?m=up';
		header("Location: $url");
	} else {		
		$url = WEB_URL.'workshop_admin_panel/carlist.php?m=add';
		header("Location: $url");
	}
	exit();
}

if(isset($_GET['id']) && $_GET['id'] != ''){
	$row = $wms->getRepairCarInfoByRepairCarId($link, $_GET['id']);
	if(!empty($row)) {
		$cus_id = $row['customer_id'];
		$mechanics_id = $row['mechanics_id'];
		$car_names= $row['car_name'];
		$c_make = $row['car_make'];
		$c_model = $row['car_model'];
		$c_year = $row['year'];
		$c_chasis_no = $row['chasis_no'];
		$c_registration = $row['car_reg_no'];
		$vin = $row['VIN'];
		$c_note = $row['note'];
		$c_add_date = $row['added_date'];
		if($row['image'] != ''){
			$image_cus = WEB_URL . 'img/upload/' . $row['image'];
			$img_track = $row['image'];
		}
		$invoice_id = $row['repair_car_id'];
		$hdnid = $_GET['id'];
		$title = 'Update Car Information';
		$button_text="Update Information";
		//$successful_msg="Update car Successfully";
		$form_url = WEB_URL . "workshop_admin_panel/addcar.php?id=".$_GET['id'];
	}
	
	//mysql_close($link);

}

//for image upload
function uploadImage(){
	if((!empty($_FILES["uploaded_file"])) && ($_FILES['uploaded_file']['error'] == 0)) {
	  $filename = basename($_FILES['uploaded_file']['name']);
	  $ext = substr($filename, strrpos($filename, '.') + 1);
	  if(($ext == "jpg" && $_FILES["uploaded_file"]["type"] == 'image/jpeg') || ($ext == "png" && $_FILES["uploaded_file"]["type"] == 'image/png') || ($ext == "gif" && $_FILES["uploaded_file"]["type"] == 'image/gif')){   
	  	$temp = explode(".",$_FILES["uploaded_file"]["name"]);
	  	$newfilename = NewGuid() . '.' .end($temp);
		move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], ROOT_PATH . '/img/upload/' . $newfilename);
		return $newfilename;
	  }
	  else{
	  	return '';
	  }
	}
	return '';
}
function NewGuid() { 
    $s = strtoupper(md5(uniqid(rand(),true))); 
    $guidText = 
        substr($s,0,8) . '-' . 
        substr($s,8,4) . '-' . 
        substr($s,12,4). '-' . 
        substr($s,16,4). '-' . 
        substr($s,20); 
    return $guidText;
}

$msg = '';
$addinfo = 'none';
if(isset($_GET['m']) && $_GET['m'] == 'add'){
	$addinfo = 'block';
	$msg = "Added repair car Information Successfully";
}
	
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-wrench repair-car-heading"><span class="repair-car-txt"> Reparación de máquina </span></i></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?php echo WEB_URL?>workshop_admin_panel/carlist.php"> Repair Slot Machine</a></li>
    <li class="active">Add Repair Slot Machine</li>
  </ol>
</section>
<!-- Main content -->
<form onSubmit="return validateMe();" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">
  <section class="content">
    <!-- Full Width boxes (Stat box) -->
    <div class="row">
      <div class="col-md-12">
        <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      <?php echo $msg; ?> </div>
	  
		<div class="box box-success" id="bid=36ox_model">
          <div class="box-body">
            <div class="form-group col-md-12" style="padding-top:10px;">
              <div class="pull-left id-txt"> 
                <label class="label label-danger label-color" style="font-size:15px;"><i class="fa fa-car car-align"></i> ID de la Orden-<?php echo $invoice_id; ?></label>
              </div>
			  <div class="pull-right left-pull">
			<!-- <button type="submit" class="btn btn-success btnsp"><i class="fa fa-save fa-2x"></i><br/>
                <?php echo $button_text; ?></button>&emsp; -->
			 <button type="submit" class="btn btn-success btnsp"><span class="print-txt"><?php echo $button_text; ?></span>&emsp;<i class="fa fa-save fa-2x"></i></button>
				 <?php if(isset($_GET['id']) && $_GET['id'] != ''){ ?>
				<button type="button" onclick="javascript:window.print();" class="btn btn-danger btnsp"><i class="fa fa-print fa-2x"></i><br/>
                Print</button>&emsp;
				<?php } ?>
                <!-- <a class="btn btn-warning btnsp" data-toggle="tooltip" href="<?php echo WEB_URL; ?>repaircar/carlist.php" data-original-title="Back"><i class="fa fa-reply  fa-2x"></i><br/>
                Back</a>  -->
			 <a class="btn btnsp btn-color" data-toggle="tooltip" href="<?php echo WEB_URL; ?>workshop_admin_panel/carlist.php" data-original-title="Back"><i class="fa fa-reply  fa-2x"></i><br/>
                Regresar</a> 
			</div>
            </div>
          </div>
        </div>
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title box-txt"><i class="circle fa fa-plus"></i> <?php echo $title; ?></h3>
          </div>
          <div class="box-body">
            <div class="form-group">
              <label for="ddlCustomerList"><span style="color:red;">*</span> Seleccionar Cliente :</label>
              <select class='form-control' id="ddlCustomerList" name="ddlCustomerList">
                <option value="">--Select Customer--</option>
                <?php
					$customer_list = $wms->getAllCustomerList($link);
					foreach($customer_list as $crow){
						if($cus_id > 0 && $cus_id == $crow['customer_id']) {
							echo '<option selected value="'.$crow['customer_id'].'">'.$crow['c_name'].'</option>';
						} else {
							echo '<option value="'.$crow['customer_id'].'">'.$crow['c_name'].'</option>';
						}
					}
				?>
              </select>
            </div>
		  <div class="form-group">
              <label for="ddlMechanicList"><span style="color:red;">*</span> Seleccionar Técnicos :</label>
              <select class='form-control' id="ddlMechanicList" name="ddlMechanicList">
                <option value="">--Select Technician--</option>
                <?php
				$technician_list = $wms->getAllMechanicsList($link);
				foreach($technician_list as $row){
					if($mechanics_id > 0 && $mechanics_id == $row['mechanics_id']) {
						echo '<option selected value="'.$row['mechanics_id'].'">'.$row['m_name'].'</option>';
					} else {
						echo '<option value="'.$row['mechanics_id'].'">'.$row['m_name'].'</option>';
					}
				}
				?>
              </select>
            </div>
            <div class="form-group">
              <label for="car_names"><span style="color:red;">*</span> Seleccionar máquina :</label>
              <input type="text" name="car_names" value="<?php echo $car_names;?>" id="car_names" class="form-control" />
            </div>
            <div class="form-group">
              <label for="ddlMake"><span style="color:red;">*</span> Marca :</label>
              <select class="form-control" onchange="loadYear(this.value);" name="ddlMake" id="ddlMake">
                <option value=''>--Select Make--</option>
                <?php
					$result = $wms->get_all_make_list($link);
					foreach($result as $row){
						if($c_make > 0 && $c_make == $row['make_id']) {
							echo "<option selected value='".$row['make_id']."'>".$row['make_name']."</option>";
						} else {
							echo "<option value='".$row['make_id']."'>".$row['make_name']."</option>";
						}
					
					} ?>
              </select>
            </div>
            <div class="form-group">
              <label for="ddl_model"><span style="color:red;">*</span> Modelo :</label>
              <select onchange="loadYearData(this.value);" class="form-control" name="ddlModel" id="ddl_model">
                <option value="">--Select Model--</option>
                <?php
					if($c_make > 0) {
						$result_model = $wms->getModelListByMakeId($link, $c_make);
						foreach($result_model as $row_model) {
							if($c_model > 0 && $c_model == $row_model['model_id']) {
								echo "<option selected value='".$row_model['model_id']."'>".$row_model['model_name']."</option>";
							} else {
								echo "<option value='".$row_model['model_id']."'>".$row_model['model_name']."</option>";
							}
						
						}
					} ?>
              </select>
            </div>
            <div class="form-group">
              <label for="ddlYear"><span style="color:red;">*</span> <span class="year-txt-bg">Serie # :</span></label>
              <select class="form-control" name="ddlYear" id="ddlYear">
                <option value="">--Select Year--</option>
                <?php
					if($c_make > 0 && $c_model > 0) {
						$result_year = $wms->getYearlListByMakeIdAndModelId($link, $c_make, $c_model);
						foreach($result_year as $row_year){
							if($c_year > 0 && $c_year == $row_year['year_id']) {
								echo "<option selected value='".$row_year['year_id']."'>".$row_year['year_name']."</option>";
							} else {
								echo "<option value='".$row_year['year_id']."'>".$row_year['year_name']."</option>";
							}
						
						}
					} ?>
              </select>
            </div>
            <div class="form-group">
              <label for="car_chasis_no"><span style="color:red;">*</span> Placa :</label>
              <input type="text" name="car_chasis_no" value="<?php echo $c_chasis_no;?>" id="car_chasis_no" class="form-control" />
            </div>
            <div class="form-group">
              <label for="registration"><span style="color:red;">*</span> Registration No :</label>
              <input type="text" name="registration" value="<?php echo $c_registration;?>" id="registration" class="form-control" />
            </div>
            <div class="form-group">
              <label for="vin"><span style="color:red;">*</span> VIN# :</label>
              <input type="text" name="vin" value="<?php echo $vin;?>" id="vin" class="form-control" />
            </div>
            <div class="form-group">
              <label for="car_note">Note :</label>
              <textarea type="text" name="car_note" value="" id="car_note" class="form-control"><?php echo $c_note;?></textarea>
            </div>
            <div class="form-group">
              <label for="add_date">Added Date :</label>
              <input type="text" name="add_date" value="<?php echo $c_add_date;?>" id="add_date" class="form-control datepicker" />
            </div>
            <!--<div class="form-group">
              <label for="c_delivery_date">Delivery Date :</label>
              <input type="text" name="c_delivery_date" value="<?php //echo $c_deliver_date;?>" id="c_delivery_date" class="form-control datepicker" />
            </div>-->
            <div class="form-group">
              <label for="Prsnttxtarea">Priview :</label>
              <img class="form-control" src="<?php echo $image_cus; ?>" style="height:100px;width:100px;" id="output"/>
              <input type="hidden" name="img_exist" value="<?php echo $img_track; ?>" />
            </div>
            <div class="form-group"> <span class="btn btn-file btn btn-primary">Upload Image
              <input type="file" name="uploaded_file" onchange="loadFile(event)" />
              </span> </div>
          </div>
          <input type="hidden" value="<?php echo $hdnid; ?>" name="repair_car"/>
		  <input type="hidden" name="hfInvoiceId" value="<?php echo $invoice_id; ?>" />
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
    </div>
    <!-- /.row -->
  </section>
</form>
<script type="text/javascript">
function validateMe(){
	if($("#ddlCustomerList").val() == ''){
		alert("Customer Name is Required !!!");
		$("#ddlCustomerList").focus();
		return false;
	}
	else if($("#ddlMechanicList").val() == ''){
		alert("Technician Name is Required !!!");
		$("#ddlMechanicList").focus();
		return false;
	}
	 else if($("#car_names").val() == ''){
	 	alert("Car Name is Required !!!");
	 	$("#car_names").focus();
	 	return false;
	 }
	 else if($("#ddlMake").val() == ''){
	 	alert("Car Make is Required !!!");
	 	$("#ddlMake").focus();
	 	return false;
	 }
	 else if($("#ddl_model").val() == ''){
	 	alert("Car Model is Required !!!");
	 	$("#ddl_model").focus();
	 	return false;
	 }
	 else if($("#ddlYear").val() == ''){
	 	alert("Car Year is Required !!!");
	 	$("#ddlYear").focus();
	 	return false;
	 }
	 else if($("#car_chasis_no").val() == ''){
	 	alert("Car Chasis no is Required !!!");
	 	$("#car_chasis_no").focus();
	 	return false;
	 }
	
	 else if($("#registration").val() == ''){
	 	alert("Registration is Required !!!");
	 	$("#registration").focus();
	 	return false;
	 }	 
	else{
		return true;
	}
}
</script>
<?php include('../footer.php'); ?>
