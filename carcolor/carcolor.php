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
$make_button_label = 'GUARDAR';
$model_button_label = 'GUARDAR';
$make_post_token = 0;
$model_post_token = 0;

if(isset($_POST['form_token'])){
	if($_POST['form_token'] == 'make') {
		$wms->saveUpdateCarColor($link, $_POST);
		if($_POST['submit_token'] == '0') {
			$addinfo = 'block';
			$msg = "Car Color Inserted Successfuly";
		} else {		
			$addinfo = 'block';
			$msg = "Updated Car Color Successfuly";
		}
	} else if($_POST['form_token'] == 'model') {
		$wms->saveUpdateCarDoor($link, $_POST);
		if($_POST['submit_token'] == '0') {
			$addinfo = 'block';
			$msg = "Car Door Inserted Successfuly";
		} else {		
			$addinfo = 'block';
			$msg = "Car Door Update Successfuly";
		}
	}	
}


/************************ Make edit and delete ***************************/
if(isset($_GET['mid']) && $_GET['mid'] != ''){
	$row = $wms->getCarColorDataByColorId($link, $_GET['mid']);
	if(!empty($row)) {
		$make_name = $row['color_name'];
	}
	$make_button_label = 'Update Color';
	$make_post_token = $_GET['mid'];
}

if(isset($_GET['mdelid']) && $_GET['mdelid'] != ''){
	$wms->deleteCarColorData($link, $_GET['mdelid']);
	$delinfo = 'block';
	$del_msg = "Car Color Deleted Successfuly";
}

/************************ Model edit and delete ***************************/
if(isset($_GET['moid']) && $_GET['moid'] != ''){
	$row = $wms->getCarDoorDataByDoorId($link, $_GET['moid']);
	if(!empty($row)) {
		$model_name = $row['door_name'];
	}
	$model_button_label = 'Update Door';
	$model_post_token = $_GET['moid'];
	//mysql_close($link);
}

if(isset($_GET['modelid']) && $_GET['modelid'] != ''){
	//delete make
	$wms->deleteCarDoorData($link, $_GET['modelid']);
	$delinfo = 'block';
	$del_msg = "Car Door Deleted Successfuly";
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa repair-car-heading"><span class="repair-setting-txt"> Características de las máquinas </span></i></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Car and Door</li>
    <li class="active">Agregar Características</li>
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
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>carcolor/carcolor.php" data-original-title="Refresh Page"><i class="fa fa-refresh"></i></a> </div>
    <div class="box box-success">
      <form method="post" enctype="multipart/form-data">
        <div class="box-body">
          <div class="box-header">
            <h3 class="box-title">Agregar Características</h3>
          </div>
          <div class="form-group col-md-10">
            <input type="text" placeholder="Color Name" value="<?php echo $make_name; ?>" name="txtColorname" id="txtColorname" class="form-control" required/>
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
					$result = $wms->getCarColorInformation($link);
					foreach($result as $row){ ?>
                <tr>
                  <td><?php echo $row['color_name']; ?></td>
                  <td><a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL;?>carcolor/carcolor.php?mid=<?php echo $row['color_id']; ?>" data-original-title="Add your Car"><i class="fa fa-edit"></i></a> <a class="btn btn-danger" data-toggle="tooltip" onClick=deleteMe("<?php echo WEB_URL;?>carcolor/carcolor.php?mdelid=<?php echo $row['color_id']; ?>"); href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a></td>
                </tr>
                <?php } ?>
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
            <h3 class="box-title">Agregar Juegos</h3>
          </div>
          <div class="form-group col-md-10">
            <input type="text" placeholder="Door Name" name="txtDoor" id="txtDoor" value="<?php echo $model_name; ?>" class="form-control" required/>
          </div>
          <div class="form-group col-md-2">
            <input type="submit" name="submit" class="btn btn-success" value="<?php echo $model_button_label; ?>"/>
          </div>
          <br>
          <br>
          <br>
          <br>
          <br>
          <br>
          <div>
            <table class="table sakotable table-bordered table-striped dt-responsive">
              <thead>
                <tr>
                  <th>Model Name</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
					$result = $wms->getCarDoorInformation($link);
					foreach($result as $row){ ?>
                <tr>
                  <td><?php echo $row['door_name']; ?></td>
                  <td><a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL;?>carcolor/carcolor.php?moid=<?php echo $row['door_id']; ?>#box_model" data-original-title="Add your Car"><i class="fa fa-edit"></i></a> <a class="btn btn-danger" data-toggle="tooltip" onClick=deleteMe("<?php echo WEB_URL;?>carcolor/carcolor.php?modelid=<?php echo $row['door_id']; ?>"); href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a></td>
                </tr>
                 <?php } $wms->close_db_connection($link); ?>
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
  </div>
</div>
<!-- /.row -->
<?php include('../footer.php'); ?>
