<?php 
include_once('../header.php');

$c_make = 0;
$c_model = 0;
$c_year = 0;
$sql = '';
$condition = 'new';
$car_door = 0;
$car_color = 0;
$token = false;

/************************ Insert Query ***************************/
if (!empty($_POST)) {
	
	$sql = "SELECT *,cc.color_name,cd.door_name,m.make_name,mo.model_name,y.year_name FROM tbl_buycar bc left join tbl_carcolor cc on cc.color_id = bc.car_color left join tbl_cardoor cd on cd.door_id = bc.car_door left join tbl_make m on m.make_id = bc.make_id left join tbl_model mo on mo.model_id = bc.model_id left join tbl_year y on y.year_id = bc.year_id where bc.car_condition = '".$_POST['txtCondition']."'";
	if(!empty($_POST['ddlMake'])) {
		$sql .= " and bc.make_id = ".(int)$_POST['ddlMake'];
	}
	if(!empty($_POST['ddlMake']) && !empty($_POST['ddlModel'])) {
		$sql .= " and bc.model_id = ".(int)$_POST['ddlModel'];
	}
	if(!empty($_POST['ddlMake']) && !empty($_POST['ddlModel']) && !empty($_POST['ddlYear'])) {
		$sql .= " and bc.year_id = ".(int)$_POST['ddlYear'];
	}
	if(!empty($_POST['txtCardoor'])) {
		$sql .= " and bc.car_door = ".(int)$_POST['txtCardoor'];
	}
	if(!empty($_POST['txtCarcolor'])) {
		$sql .= " and bc.car_color = ".(int)$_POST['txtCarcolor'];
	}
	
	$c_make = $_POST['ddlMake'];
	$c_model =  $_POST['ddlModel'];
	$c_year =  $_POST['ddlYear'];
	$condition = $_POST['txtCondition'];
	$car_door = $_POST['txtCardoor'];
	$car_color = $_POST['txtCarcolor'];
	
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-briefcase repair-car-heading"> <span class="repair-car-txt">Búsqueda avanzada </span></i></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Car Setting</li>
    <li class="active">Add Car Setting</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <form id="frmcarstock" method="post" enctype="multipart/form-data">
      <div class="box box-success" id="box_model">
        <div class="box-body">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-search"></i> motor de búsqueda</h3>
          </div>
          <div class="form-group col-md-4">
            <label for="ddlMake">Marca :</label>
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
          <div class="form-group col-md-4">
            <label for="ddl_model">Modelo :</label>
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
          <div class="form-group col-md-4">
            <label for="ddlYear">Juegos :</label>
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
          <div class="form-group col-md-4">
            <label for="txtCondition">Condición :</label>
            <select class="form-control" name="txtCondition" id="txtCondition">
              <option <?php if($condition == 'new'){echo 'selected'; }?> value='new'>New</option>
              <option <?php if($condition == 'old'){echo 'selected'; }?> value='old'>Old</option>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="txtCardoor">Cant. de juegos :</label>
            <select class="form-control" name="txtCardoor" id="txtCardoor">
              <option value=''>--Select Door--</option>
              <?php
					$doors = $wms->getCarDoorInformation($link);
					foreach($doors as $door) {
						if($car_door > 0 && $car_door == $door['door_id']) {
							echo "<option selected value='".$door['door_id']."'>".$door['door_name']."</option>";
						} else {
							echo "<option value='".$door['door_id']."'>".$door['door_name']."</option>";
						}
					
					} ?>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="txtCarcolor">Características :</label>
            <select class="form-control" name="txtCarcolor" id="txtCarcolor">
              <option value=''>--Select Color--</option>
              <?php
					$colors = $wms->getCarColorInformation($link);
					foreach($colors as $color) {
						if($car_color > 0 && $car_color == $color['color_id']) {
							echo "<option selected value='".$color['color_id']."'>".$color['color_name']."</option>";
						} else {
							echo "<option value='".$color['color_id']."'>".$color['color_name']."</option>";
						}
					
					} ?>
            </select>
          </div>
          <div class="form-group col-md-12">
            <button type="submit" class="btn btn-success btn-large btn-block"><b>SEARCH</b></button>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </form>
    <div class="box box-success" id="box_model">
      <div class="box-body">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-list"></i> Available Car List</h3>
        </div>
        <?php if(!empty($sql)) { $results = $wms->getMultipleRowData($link, $sql);
				foreach($results as $row) {
				$image = WEB_URL . 'img/no_image.jpg';
				if(file_exists(ROOT_PATH . '/img/upload/' . $row['car_image']) && $row['car_image'] != ''){
					$image = WEB_URL . 'img/upload/' . $row['car_image']; //car image
				}
				$token = true;
				?>
        <div style="width:98%;height:auto;border:solid 1px #ccc;padding:10px;margin:10px;">
          <div class="col-md-2 text-center"><img class="img-thumbnail" style="width:150px;height:150px;" src="<?php echo $image; ?>" /></div>
          <div class="col-md-4 text-left">
            <div>
              <h4 style="font-weight:bold;"><?php echo $row['car_name']; ?></h4>
            </div>
            <div><b>Make:</b> <?php echo $row['make_name']; ?>; <b>Model:</b> <?php echo $row['model_name']; ?>; <b>Year:</b> <?php echo $row['model_name']; ?></div>
            <div><b>Color:</b> <?php echo $row['color_name']; ?></div>
            <div><b>Door:</b> <?php echo $row['door_name']; ?></div>
            <div><b>Condition:</b> <?php echo $row['car_condition']; ?></div>
            <div><b>Car Status:</b> <span style="font-size:12px;" class="label label-<?php if($row['car_status'] == '0'){echo 'success';} else {echo 'danger';} ?>">
              <?php if($row['car_status'] == '0'){echo 'Available';} else {echo 'Sold';} ?>
              </span></div>
          </div>
          <div class="col-md-3 text-left">
            <div>
              <h4 style="font-weight:bold;">&nbsp;</h4>
            </div>
            <div><b>Chasis No:</b> <?php echo $row['car_chasis_no']; ?></div>
            <div><b>Engine & CC:</b> <?php echo $row['car_engine_name']; ?></div>
            <div><b>Total Mileage:</b> <?php echo $row['car_totalmileage']; ?></div>
            <div><b>Buy Date:</b> <?php echo $row['buy_date']; ?></div>
          </div>
          <div class="col-md-3 text-right">
            <div>
              <h4 style="font-weight:bold;">&nbsp;</h4>
            </div>
            <div class="label label-danger" style="font-size:17px;">Sell Price: <?php echo $currency; ?><?php echo $row['asking_price']; ?></div>
            <br/>
            <br/>
            <?php if($row['car_status'] == '0') { ?>
            <div><a href="<?php echo WEB_URL; ?>carstock/carsellform.php?carid=<?php echo $row['buycar_id']; ?>" style="font-weight:bold;font-size:17px;" class="btn btn-warning"><i class="fa fa-shopping-cart"></i> Sell Now</a></div>
            <?php } ?>
          </div>
          <div style="clear:both;"></div>
        </div>
        <?php } } ?>
        <?php if(!$token && !empty($_POST)) { ?>
        <div align="center">No car found based on your selected query.</div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<?php include('../footer.php'); ?>
