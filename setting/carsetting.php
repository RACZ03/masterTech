<?php 
include('../header.php');

/*variables*/
$delinfo = 'none';
$addinfo = 'none';
$msg = '';
$del_msg = '';
$make_name = '';
$model_name = '';
$make_id = 0;
$make_id_year = 0;
$model_id = 0;
$year_name = '';
$make_button_label = 'Save Make';
$model_button_label = 'Guardar marca';
$year_button_label ='Guardar año';
$make_post_token = 0;
$model_post_token = 0;
$year_post_token =0;
//
if(isset($_POST['form_token'])){
	if($_POST['form_token'] == 'make') {
		$wms->saveUpdateMakeSetup($link, $_POST);
		if($_POST['submit_token'] == '0') {
			$addinfo = 'block';
			$msg = "Make Inserted Successfuly";
		} else {		
			$addinfo = 'block';
			$msg = "Make Updated Successfuly";
		}
	}
	else if($_POST['form_token'] == 'model') {
		$wms->saveUpdateModelSetup($link, $_POST);
		if($_POST['submit_token'] == '0') {
			$addinfo = 'block';
			$msg = "Model Inserted Successfuly";
		} else {		
			$addinfo = 'block';
			$msg = "Model Updated Successfuly";
		}
	}		
	else if($_POST['form_token'] == 'year') {
		$wms->saveUpdateYearSetup($link, $_POST);
		if($_POST['submit_token'] == '0') {
			$addinfo = 'block';
			$msg = "Year Inserted Successfuly";
		} else {		
			$addinfo = 'block';
			$msg = "Year Updated Successfuly";
		}
   }
}


/************************ Make edit and delete ***************************/
if(isset($_GET['mid']) && $_GET['mid'] != ''){
	$row = $wms->getCarMakeDataByMakeId($link, $_GET['mid']);
	if(!empty($row)) {
		$make_name = $row['make_name'];
	}
	$make_button_label = 'Update Make';
	$make_post_token = $_GET['mid'];
}
if(isset($_GET['mdelid']) && $_GET['mdelid'] != ''){
	$wms->deleteMakeData($link, $_GET['mdelid']);
	$delinfo = 'block';
	$del_msg = "Make Delete Successfuly";
}

/************************ Model edit and delete ***************************/
if(isset($_GET['moid']) && $_GET['moid'] != ''){
	//view
	$row = $wms->getCarModelDataByModelId($link, $_GET['moid']);
	if(!empty($row)) {
		$make_id = $row['make_id'];
		$model_name = $row['model_name'];
	}
	$model_button_label = 'Update Model';
	$model_post_token = $_GET['moid'];
	//mysql_close($link);
}

if(isset($_GET['modelid']) && $_GET['modelid'] != ''){
	$wms->deleteModelData($link, $_GET['modelid']);
	$delinfo = 'block';
	$del_msg = "Model Delete Successfuly";
}

/************************ Year edit and delete ***************************/
if(isset($_GET['yid']) && $_GET['yid'] != ''){
	$row = $wms->getCarYearDataByYearId($link, $_GET['yid']);
	if(!empty($row)) {
		$make_id_year = $row['make_id'];
		$model_id = $row['model_id'];
		$year_name = $row['year_name'];
	}
	$year_button_label = 'Update Year';
	$year_post_token = $_GET['yid'];
}

if(isset($_GET['ydelid']) && $_GET['ydelid'] != ''){
	$wms->deleteYearData($link, $_GET['ydelid']);
	$delinfo = 'block';
	$del_msg = "Year Delete Successfuly";
}
/**************************************************************/

?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa repair-car-heading"> <span class="repair-setting-txt">Configuración de marca/modelo/año</span></i></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Car Setting</li>
    <li class="active">Make/Model/Year</li>
  </ol>
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
      <?php echo $msg; ?> </div>
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>setting/carsetting.php" data-original-title="Refresh Page"><i class="fa fa-refresh"></i></a> </div>
    <div class="box box-success">
      <form method="post" enctype="multipart/form-data">
        <div class="box-body">
          <div class="box-header">
            <h3 class="box-title">Add Make</h3>
            <!-- <h3 class="box-title"><i class="fa repair-car-heading"><span class="repair-setting-txt">Agregar Hacer</span></i></h3> -->
          </div>
          <div class="form-group col-md-10">
            <input type="text" placeholder="Make Name" value="<?php echo $make_name; ?>" name="txtMakeName" id="txtMakeName" class="form-control" required/>
          </div>
          <div class="form-group col-md-2">
            <input type="submit" name="submit" class="btn btn-success" value="<?php echo $make_button_label; ?>"/>
          </div>
          <br>
          <br>
          <br>
          <br>
          <div>
            <table class="table sakotable table-bordered table-striped dt-responsive">
              <thead>
                <tr>
                  <th>Make</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
					$result = $wms->get_all_make_list($link);
					foreach($result as $row) {?>
                <tr>
                  <td><?php echo $row['make_name']; ?></td>
                  <td><a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL;?>setting/carsetting.php?mid=<?php echo $row['make_id']; ?>" data-original-title="Add your Car"><i class="fa fa-edit"></i></a> <a class="btn btn-danger" data-toggle="tooltip" onClick=deleteMe("<?php echo WEB_URL;?>setting/carsetting.php?mdelid=<?php echo $row['make_id']; ?>"); href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a></td>
                </tr>
                <?php } //mysql_close($link); ?>
              </tbody>
            </table>
          </div>
        </div>
        <input type="hidden" value="make" name="form_token"/>
        <input type="hidden" value="<?php echo $make_post_token; ?>" name="submit_token"/>
      </form>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
    <div class="box box-success" id="box_model">
      <form method="post" enctype="multipart/form-data">
        <div class="box-body">
          <div class="box-header">
            <h3 class="box-title"><i class="fa repair-car-heading"><span class="repair-setting-txt">Agregar Modelo</span></i></h3>
          </div>
		  <div class="form-group col-md-4">
            <select class="form-control" name="ddlMake" required>
              <option value=''>--Select Make--</option>
              <?php
					$result = $wms->get_all_make_list($link);
					foreach($result as $row) { 
						if($make_id > 0 && $make_id == $row['make_id']) {
							echo "<option selected value='".$row['make_id']."'>".$row['make_name']."</option>";
						} else {
							echo "<option value='".$row['make_id']."'>".$row['make_name']."</option>";
						}
					
					} ?>
            </select>
          </div>
          <div class="form-group col-md-4">
            <input type="text" placeholder="Model Name" name="txtModelName" id="txtModelName" value="<?php echo $model_name; ?>" class="form-control" required/>
          </div>
          <div class="form-group col-md-4">
            <input type="submit" name="submit" class="btn btn-success" value="<?php echo $model_button_label; ?>"/>
          </div>
          <br>
          <br>
		  <br>
          <br>
          <div>
            <table class="table sakotable table-bordered table-striped dt-responsive">
              <thead>
                <tr>
                  <th>Marca</th>
                  <th>Modelo</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody>
                <?php
					$result = $wms->get_all_model_list($link);
					foreach($result as $row) { ?>
                <tr>
                  <td><?php echo $row['make_name']; ?></td>
                  <td><?php echo $row['model_name']; ?></td>
                  <td><a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL;?>setting/carsetting.php?moid=<?php echo $row['model_id']; ?>#box_model" data-original-title="Add your Car"><i class="fa fa-edit"></i></a> <a class="btn btn-danger" data-toggle="tooltip" onClick=deleteMe("<?php echo WEB_URL;?>setting/carsetting.php?modelid=<?php echo $row['model_id']; ?>"); href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a></td>
                </tr>
                <?php } //mysql_close($link); ?>
              </tbody>
            </table>
          </div>
        </div>
        <input type="hidden" value="model" name="form_token"/>
        <input type="hidden" value="<?php echo $model_post_token; ?>" name="submit_token"/>
      </form>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
    <div class="box box-success" id="box_year">
      <form method="post" enctype="multipart/form-data">
        <div class="box-body">
          <div class="box-header">
            <h3 class="box-title"><i class="fa repair-car-heading"><span class="repair-setting-txt">Agregar Año</span></i></h3>
          </div>
          <div class="form-group col-md-3">
            <select onchange="loadYear(this.value);" class="form-control" name="ddlMake" required>
              <option value="">--Select Make--</option>
              <?php
					$result = $wms->get_all_make_list($link);
					foreach($result as $row) { 
						if($make_id_year > 0 && $make_id_year == $row['make_id']) {
							echo "<option selected value='".$row['make_id']."'>".$row['make_name']."</option>";
						} else {
							echo "<option value='".$row['make_id']."'>".$row['make_name']."</option>";
						}
					
					} ?>
            </select>
          </div>
          <div class="form-group col-md-3">
            <select class="form-control" name="ddlModel" id="ddl_model" required>
              <option value="">--Select Model--</option>
              <?php
				if($make_id_year > 0 && $model_id > 0) {
					$result = $wms->get_all_model_list($link);
					foreach($result as $row) { 
						if($model_id > 0 && $model_id == $row['model_id']) {
							echo "<option selected value='".$row['model_id']."'>".$row['model_name']."</option>";
						} else {
							echo "<option value='".$row['model_id']."'>".$row['model_name']."</option>";
						}
					
					}
				}
				?>
            </select>
          </div>
          <div class="form-group col-md-3">
            <input type="text" placeholder="Year Name" value="<?php echo $year_name; ?>" name="txtYear" id="txtYear" class="form-control" required/>
          </div>
          <div class="form-group col-md-3">
            <input type="submit" name="submit" class="btn btn-success" value="<?php echo $year_button_label; ?>" />
            <input type="hidden" value="<?php echo $year_post_token; ?>" name="submit_token"/>
          </div>
          <br>
          <br>
		  <br>
          <br>
          <div>
            <table class="table sakotable table-bordered table-striped dt-responsive">
              <thead>
                <tr>
                  <th>Marca</th>
                  <th>Modelo</th>
                  <th>Año</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody>
                <?php
					$result = $wms->get_all_year_list($link);
					foreach($result as $row) { ?>
                <tr>
                  <td><?php echo $row['make_name']; ?></td>
                  <td><?php echo $row['model_name']; ?></td>
                  <td><?php echo $row['year_name']; ?></td>
                  <td><a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL;?>setting/carsetting.php?yid=<?php echo $row['year_id']; ?>#box_year" data-original-title="Add your Car"><i class="fa fa-edit"></i></a> <a class="btn btn-danger" data-toggle="tooltip" onClick=deleteMe("<?php echo WEB_URL;?>setting/carsetting.php?ydelid=<?php echo $row['year_id']; ?>"); href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a></td>
                </tr>
                 <?php } $wms->close_db_connection($link); ?>
              </tbody>
            </table>
          </div>
        </div>
        <input type="hidden" value="year" name="form_token"/>
      </form>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
<!-- /.row -->
<?php include('../footer.php'); ?>
