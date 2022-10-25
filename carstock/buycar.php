<?php 
include_once('../header.php');
include('../helper/calculation.php');

/*variables*/
$delinfo = 'none';
$addinfo = 'none';
$msg = '';
$del_msg = '';
//$CTL = '';
$c_make = 0;
$car_color = '';
$car_door=0;
$c_model = 0;
$c_year = 0;
$savebtn='Save Information';
$owner_name='';
$owner_mobile='';
$owner_email='';
$owner_nid='';
$company_name ='';
$owner_address='';
$car_name = '';
$car_make='';
$car_model='';
$car_year='';
//$reg_number='';
$reg_date='';
$chasis_no='';
$engine_name='';
//$total_mileage='';
//$car_sit='';
$car_color='';
$car_door='';
$car_note='';
$buying_price='';
$buying_given_amount='';
$buying_note='';
$image_cus = WEB_URL . 'img/no_image.jpg';
$img_track = '';
$buy_date = '';
$hdnid = 0;
$asking_price = '';
$car_condition = 'new';

$wmscalc = new wms_calculation();

/************************ Insert Query ***************************/
if(isset($_POST['txtOwnerName'])){
	$image_url = uploadImage();
	if(empty($image_url)) {
		$image_url = $_POST['img_exist'];
	}
	$wms->saveUpdateBuyCarInformation($link, $_POST, $image_url);
	if((int)$_POST['buycar_id'] > 0){
		$url = WEB_URL.'carstock/buycarlist.php?m=up';
		header("Location: $url");
	} else {		
		$url = WEB_URL.'carstock/buycarlist.php?m=add';
		header("Location: $url");
	}
	exit();
}

if(isset($_GET['id']) && $_GET['id'] != ''){
	$row = $wms->getBuyCarInfoById($link, $_GET['id']);
	if(!empty($row)) {
		$owner_name=$row['owner_name'];
		$owner_mobile=$row['owner_mobile'];
		$owner_email = $row['owner_email'];
		$owner_nid=$row['nid'];		
		$company_name =$row['company_name'];
		//$CTL=$row['ctl'];	
		$owner_address=$row['owner_address'];
		$car_name = $row['car_name'];		
		$c_make = $row['make_id'];
		$c_model = $row['model_id'];
		$c_year = $row['year_id'];		
		//$reg_number=$row['car_reg_no'];
		$reg_date=$row['car_reg_date'];
		$chasis_no=$row['car_chasis_no'];
		$engine_name=$row['car_engine_name'];
		$total_mileage=$row['car_totalmileage'];
		$car_color = $row['car_color'];
		$car_door = $row['car_door'];
		$car_note=$row['car_note'];
		$buying_price=$row['buy_price'];
		$asking_price = $row['asking_price'];
		$buying_given_amount=$row['buy_given_amount'];
		$buying_note=$row['buy_note'];
		$buy_date = $wms->mySqlToDatePicker($row['buy_date']);
		$car_sit = $row['car_sit'];
		if($row['car_image'] != ''){
			$image_cus = WEB_URL . 'img/upload/' . $row['car_image'];
			$img_track = $row['car_image'];
		}
		$hdnid = $_GET['id'];
		$title = 'Update Customer';
		$button_text="Update";
		$successful_msg="Update Customer Successfully";
		$form_url = WEB_URL . "carstock/buycarlist.php?id=".$_GET['id'];
		$car_condition = $row['car_condition'];
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

?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-briefcase repair-car-heading"><span class="repair-car-txt"> Recibir máquina </span></i></h1>
  <!-- <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Car Setting</li>
    <li class="active">Add Car Setting</li>
  </ol> -->
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <div id="me" class="alert alert-danger alert-dismissable" style="display:<?php echo $delinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-ban"></i> Deleted!</h4>
      <?php echo $del_msg; ?> </div>
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      <?php echo $msg; ?> 
	</div>
    <div align="right" style="margin-bottom:1%;"> 
		<a onclick="$('#frmcarstock').submit();" class="btn btn-success" title="" data-toggle="tooltip" data-original-title="Save Information">
			<i class="fa fa-save"></i>
		</a> 
		<a class="btn btn-warning" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>carstock/buycarlist.php" data-original-title="Back">
			<i class="fa fa-reply"></i>
		</a> 
	</div>
    <form id="frmcarstock" onsubmit="return validateMe();" method="post" enctype="multipart/form-data">
      <div class="box box-success">
        <div class="box-body">
          <div class="box-header">
            <h3 class="box-title"> Datos del 3PS</h3>
          </div>
          <div class="form-group col-md-4">
            <label for="txtOwnerName"><span style="color:red;">*</span> Nombre del 3PV :</label>
            <input type="text" placeholder="Nombre del 3PV" value="<?php echo $owner_name; ?>" name="txtOwnerName" id="txtOwnerName" class="form-control" required/>
          </div>
          <div class="form-group col-md-4">
            <label for="txtMobile"><span style="color:red;">*</span> Telefono :</label>
            <input type="text" placeholder="Telefono" value="<?php echo $owner_mobile; ?>" name="txtMobile" id="txtMobile" class="form-control" required/>
          </div>
          <div class="form-group col-md-4">
            <label for="txtEmail"><span style="color:red;">*</span> Email :</label>
            <input type="text" placeholder="Email" value="<?php echo $owner_email; ?>" name="txtEmail" id="txtEmail" class="form-control" required/>
          </div>
          <div class="form-group col-md-12">
            <label for="txtNid"> Cedula/RUC :</label>
            <input type="text" placeholder="Cedula/RUC" value="<?php echo $owner_nid; ?>" name="txtNid" id="txtNid" class="form-control" required/>
          </div>
		  <div class="form-group col-md-6">
            <label for="txtCompanyname"> Company Name:</label>
            <input type="text" placeholder="Company Name" value="<?php echo $company_name; ?>" name="txtCompanyname" id="txtCompanyname" class="form-control" required/>
          </div>
		  <!-- <div class="form-group col-md-6">
            <label for="txtCTL"> Company Trade License:</label>
            <input type="text" placeholder="Trade License" value="<?php //echo $CTL; ?>" name="txtCTL" id="txtCTL" class="form-control" required/>
          </div> -->
          <div class="form-group col-md-12">
            <label for="txtAddress"><span style="color:red;">*</span> Dirección :</label>
            <textarea type="text" placeholder="Dirección" name="txtAddress" id="txtAddress" class="form-control" required><?php echo $owner_address; ?></textarea>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
      <div class="box box-success" id="box_model">
        <div class="box-body">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-briefcase"></i> Información de la máquina</h3>
          </div>
          <div class="form-group col-md-3">
            <label for="txtCarname"><span style="color:red;">*</span> Nombre de la máquina :</label>
            <input type="text" placeholder="Nombre de la máquina" name="txtCarname" id="txtCarname" value="<?php echo $car_name; ?>" class="form-control" required/>
          </div>
          <div class="form-group col-md-3">
            <label for="txtCondition"><span style="color:red;">*</span> Condición :</label>
            <select class="form-control" name="txtCondition" id="txtCondition">
              <option <?php if($car_condition == 'new'){echo 'selected'; }?> value='new'>NEW</option>
              <option <?php if($car_condition == 'old'){echo 'selected'; }?> value='old'>REFURB</option>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="txtCarcolor"><span style="color:red;">*</span> Características de la máquinas :</label>
            <select class="form-control" name="txtCarcolor" id="txtCarcolor">
              <option value=''>--Select Color--</option>
              <?php
					$result = $wms->getCarColorInformation($link);
					foreach($result as $row){
						if($car_color > 0 && $car_color == $row['color_id']) {
							echo "<option selected value='".$row['color_id']."'>".$row['color_name']."</option>";
						} else {
							echo "<option value='".$row['color_id']."'>".$row['color_name']."</option>";
						}
					
					} ?>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="txtCardoor"><span style="color:red;">*</span> Juegos :</label>
            <select class="form-control" name="txtCardoor" id="txtCardoor">
              <option value=''>--Select Door--</option>
              <?php
					$result = $wms->getCarDoorInformation($link);
					foreach($result as $row){
						if($car_door > 0 && $car_door == $row['door_id']) {
							echo "<option selected value='".$row['door_id']."'>".$row['door_name']."</option>";
						} else {
							echo "<option value='".$row['door_id']."'>".$row['door_name']."</option>";
						}
					
					} ?>
            </select>
          </div>
          <div class="form-group col-md-3">
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
          <div class="form-group col-md-3">
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
          <div class="form-group col-md-3">
            <label for="ddlYear"><span style="color:red;">*</span> Nombre del Juego :</label>
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
          <!-- <div class="form-group col-md-3">
            <label for="txtRegnumber"><span style="color:red;">*</span> Registration Number :</label>
            <input type="text" placeholder="Car Registration Number" name="txtRemysqli_query($con,"INSERT INTO tbl_buycar(owner_name,owner_mobile,owner_email,nid,company_name,ctl, owner_address,car_name,car_condition,car_color,car_door,make_id,model_id,year_id,car_reg_no,car_reg_date,car_chasis_no,car_engine_name,car_totalmileage,car_sit,car_note,car_image,buy_price,asking_price,buy_given_amount,buy_note,buy_date) values('$data[txtOwnerName]','$data[txtMobile]','$data[txtEmail]','$data[txtNid]','$data[txtCompanyname]','$data[txtCTL]','$data[txtAddress]','$data[txtCarname]','$data[txtCondition]','$data[txtCarcolor]','$data[txtCardoor]','$data[ddlMake]','$data[ddlModel]','$data[ddlYear]','$data[txtRegnumber]','$data[txtRegDate]','$data[txtChasisnumber]','$data[txtEnginename]','$data[txtTotalmileasge]','$data[txtCarSeat]','$data[txtNote]','$image_url','$data[txtBuyPrice]','$data[txtAskingPrice]','$data[txtGivamount]','$data[txtBuynote]','".$this->datepickerDateToMySqlDate($data['txtBuyDate'])."')");gnumber" id="txtRegnumber" value="<?php //echo $reg_number; ?>" class="form-control"/>
          </div> -->
		  <div class="form-group col-md-3">
            <label for="txtRegDate"><span style="color:red;">*</span> Fecha de Ingreso (d/m/a) :</label>
            <input type="text" placeholder="Fecha de Ingreso" name="txtRegDate" id="txtRegDate" value="<?php echo $reg_date; ?>" class="form-control datepicker"/>
          </div>
          <div class="form-group col-md-3">
            <label for="txtChasisnumber"><span style="color:red;">*</span> <span class="year-txt-bg"> Serie # :</label>
            <input type="text" placeholder="Serie" name="txtChasisnumber" id="txtChasisnumber" value="<?php echo $chasis_no; ?>" class="form-control"/>
          </div>
          <div class="form-group col-md-3">
            <label for="txtEnginename"><span style="color:red;">*</span> Placa :</label>
            <input type="text" placeholder="Name" name="txtEnginename" id="txtEnginename" value="<?php echo $engine_name; ?>" class="form-control"/>
          </div>
          <!-- <div class="form-group col-md-3">
            <label for="txtTotalmileasge"><span style="color:red;">*</span> Total Mileage (km) :</label>
            <input type="text" placeholder="Car Total Mileage" name="txtTotalmileasge" id="txtTotalmileasge" value="<?php //echo $total_mileage; ?>" class="form-control"/>
          </div>
		  <div class="form-group col-md-12">
            <label for="txtCarSeat">Car Seat :</label>
            <input type="text" placeholder="Car Total Seat" name="txtCarSeat" id="txtTotatxtCarSeatlmileasge" value="<?php echo $car_sit; ?>" class="form-control"/>
          </div> -->
          <div class="form-group col-md-12">
            <label for="txtNote">Note :</label>
            <textarea name="txtNote" placeholder="Note" id="txtNote" class="form-control"><?php echo $car_note;?></textarea>
          </div>
          <div class="form-group col-md-12">
            <label for="Prsnttxtarea">Priview (600px X 375px):</label>
            <img class="form-control" src="<?php echo $image_cus; ?>" style="height:125px;width:125px;" id="output"/>
            <input type="hidden" name="img_exist" value="<?php echo $img_track; ?>" />
          </div>
          <div class="form-group col-md-12"> <span class="btn btn-file btn btn-primary">Upload Car Image
            <input type="file" name="uploaded_file" onchange="loadFile(event)" />
            </span> </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
      <div class="box box-success" id="box_year">
        <div class="box-body">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-info"></i> Información de compra y venta</h3>
          </div>
          <div class="form-group col-md-4">
            <label for="txtBuyPrice"><span style="color:red;">*</span> Costo total de la máquina <span class="sm-text">(incluye Tarifa de Servicios MT)</span> :</label>
            <div class="input-group"> <span class="input-group-addon">$</span>
              <input type="text" placeholder="Costo total de la máquina" value="<?php echo $buying_price; ?>" name="txtBuyPrice" id="txtBuyPrice" class="form-control allownumberonly"/>
            </div>
          </div>
          <div class="form-group col-md-4">
            <label for="txtGivamount"><span style="color:red;">*</span> Tarifa de Servicios MasterTech :</label>
            <div class="input-group"> <span class="input-group-addon">$</span>
              <input type="text" placeholder="Tarifa de Servicios MasterTech" value="<?php echo $buying_given_amount; ?>" name="txtGivamount" id="txtGivamount" class="form-control allownumberonly"/>
            </div>
          </div>
          <div class="form-group col-md-4">
            <label for="txtDue">Saldo Adeudado al 3PS:</label>
            <div class="input-group"> <span class="input-group-addon">$</span>
              <input type="text" disabled="disabled" placeholder="Saldo Adeudado al 3PS" value="<?php echo $wmscalc->getResultFromTwoValues($buying_price,$buying_given_amount,'-'); ?>" name="txtDue" id="txtDue" class="form-control"/>
            </div>
          </div>
		  <div class="form-group col-md-4">
            <label for="txtBuyDate">Fecha de Compra:</label>
            <input type="text" placeholder="Fecha de Compra" name="txtBuyDate" id="txtBuyDate" value="<?php echo $buy_date; ?>" class="form-control datepicker"/>
          </div>
		  <div class="form-group col-md-4">
            <label for="txtAskingPrice"><span style="color:red;">*</span> Precio Sugerido de Venta de la máquina :</label>
            <input type="text" placeholder="Precio Sugerido de Venta de la máquina" name="txtAskingPrice" id="txtAskingPrice" value="<?php echo $asking_price; ?>" class="form-control"/>
          </div>
          <div class="form-group col-md-12">
            <label for="txtBuynote">Note :</label>
            <textarea name="txtBuynote" placeholder="Note" id="txtBuynote" class="form-control"><?php echo $buying_note;?></textarea>
          </div>
         <input type="hidden" value="<?php echo $hdnid; ?>" name="buycar_id"/>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </form>
  </div>
</div>
<!-- /.row -->
<script type="text/javascript">
function validateMe(){
	if($("#txtOwnerName").val() == ''){
		alert("Name is Required !!!");
		$("#txtOwnerName").focus();
		return false;
	}
	else if($("#txtMobile").val() == ''){
		alert("Mobile is Required !!!");
		$("#txtMobile").focus();
		return false;
	}
	else if($("#txtEmail").val() == ''){
		alert("Email is Required !!!");
		$("#txtEmail").focus();
		return false;
	}
	else if($("#txtAddress").val() == ''){
		alert("Address is Required !!!");
		$("#txtAddress").focus();
		return false;
	}
	else if($("#txtCarname").val() == ''){
		alert("Car Name is Required !!!");
		$("#txtCarname").focus();
		return false;
	}
	else if($("#txtCondition").val() == ''){
		alert("Condition is Required !!!");
		$("#txtCondition").focus();
		return false;
	}
	else if($("#txtCarcolor").val() == ''){
		alert("Color is Required !!!");
		$("#txtCarcolor").focus();
		return false;
	}	
	else if($("#txtCardoor").val() == ''){
		alert("Door is Required !!!");
		$("#txtCardoor").focus();
		return false;
	}
	else if($("#ddlMake").val() == ''){
		alert("Make is Required !!!");
		$("#ddlMake").focus();
		return false;
	}
	else if($("#ddl_model").val() == ''){
		alert("Model is Required !!!");
		$("#ddl_model").focus();
		return false;
	}
	else if($("#ddlYear").val() == ''){
		alert("Year is Required !!!");
		$("#ddlYear").focus();
		return false;
	}
	// else if($("#txtRegnumber").val() == ''){
	// 	alert("Registration is Required !!!");
	// 	$("#txtRegnumber").focus();
	// 	return false;
	// }
	else if($("#txtRegDate").val() == ''){
		alert("Registration date Required !!!");
		$("#txtRegDate").focus();
		return false;
	}
	else if($("#txtChasisnumber").val() == ''){
		alert("Chasis is Required !!!");
		$("#txtChasisnumber").focus();
		return false;
	}
	else if($("#txtEnginename").val() == ''){
		alert("Engine Name is Required !!!");
		$("#txtEnginename").focus();
		return false;
	}
	// else if($("#txtTotalmileasge").val() == ''){
	// 	alert("Total Mileasge is Required !!!");
	// 	$("#txtTotalmileasge").focus();
	// 	return false;
	// }
	else if($("#txtBuyPrice").val() == ''){
		alert("Buy Price is Required !!!");
		$("#txtBuyPrice").focus();
		return false;
	}
	else if($("#txtGivamount").val() == ''){
		alert("Give amount is Required !!!");
		$("#txtGivamount").focus();
		return false;
	}
	else if($("#txtAskingPrice").val() == ''){
		alert("Asking Priceis Required !!!");
		$("#txtAskingPrice").focus();
		return false;
	}	
	else{
		return true;
	}
}
</script>
<?php include('../footer.php'); ?>
