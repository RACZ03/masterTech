<?php 
include_once('../header.php');
$result = $wms->getWebsiteSettingsInformation($link);
$currency = '';
if(!empty($result)) { $currency = $result['currency']; }
$c_make = 0;
$c_model = 0;
$c_year = 0;
$sql = '';
$part_no = '';
$condition = 'new';
$manufacturer = 0;
$token = false;

/************************ Insert Query ***************************/
if (!empty($_POST)) {
	//$sql = "SELECT *,s.s_name,m.make_name,mo.model_name,y.year_name,mu.manufacturer_name FROM tbl_parts_stock_manage ps left join tbl_make m on m.make_id = ps.make_id left join tbl_model mo on mo.model_id = ps.model_id left join tbl_year y on y.year_id = ps.year_id LEFT JOIN tbl_add_supplier s ON s.supplier_id = ps.supplier_id LEFT JOIN tbl_manufacturer mu ON mu.manufacturer_id = ps.manufacturer_id where ps.parts_condition = '".$_POST['txtCondition']."'";
	$sql = '';
	$filter = array(
		'makeid'		=> $_POST['ddlMake'],
		'modelid'		=> $_POST['ddlModel'],
		'yearid'		=> $_POST['ddlYear'],
		'condition'		=> $_POST['txtCondition'],
		'part_no'		=> $_POST['txtPartNo'],
		'manufacturer'	=> $_POST['txtManufacturer']
	);
	if(!empty($_POST['ddlMake'])){
		//$fit_parts = $wms->filterPartsBasedOnMakeModelYear($link, $filter);
		//if(!empty($fit_parts)){
		if(!empty($filter['makeid']) && !empty($filter['modelid']) && !empty($filter['yearid'])) {
			$sql = 'SELECT *, mu.manufacturer_name FROM tbl_parts_fit_data pfd INNER JOIN tbl_parts_stock_manage ps ON ps.parts_id = pfd.parts_id LEFT JOIN tbl_add_supplier s ON s.supplier_id = ps.supplier_id LEFT JOIN tbl_manufacturer mu ON mu.manufacturer_id = ps.manufacturer_id WHERE pfd.make_id = "'.(int)$filter['makeid'].'" AND pfd.model_id = "'.(int)$filter['modelid'].'" AND pfd.year_id = "'.(int)$filter['yearid'].'" AND ps.status = 1';
		} else if(!empty($filter['makeid']) && !empty($filter['modelid'])) {
			$sql = 'SELECT *, mu.manufacturer_name FROM tbl_parts_fit_data pfd INNER JOIN tbl_parts_stock_manage ps ON ps.parts_id = pfd.parts_id LEFT JOIN tbl_add_supplier s ON s.supplier_id = ps.supplier_id LEFT JOIN tbl_manufacturer mu ON mu.manufacturer_id = ps.manufacturer_id WHERE pfd.make_id = "'.(int)$filter['makeid'].'" AND pfd.model_id = "'.(int)$filter['modelid'].'" AND ps.status = 1';
		} else if(!empty($filter['makeid'])) {
			$sql = 'SELECT *, mu.manufacturer_name FROM tbl_parts_fit_data pfd INNER JOIN tbl_parts_stock_manage ps ON ps.parts_id = pfd.parts_id LEFT JOIN tbl_add_supplier s ON s.supplier_id = ps.supplier_id LEFT JOIN tbl_manufacturer mu ON mu.manufacturer_id = ps.manufacturer_id WHERE pfd.make_id = "'.(int)$filter['makeid'].'" AND ps.status = 1';
		}
		//}
	} else {
		$sql = "SELECT *, mu.manufacturer_name FROM tbl_parts_stock_manage ps LEFT JOIN tbl_add_supplier s ON s.supplier_id = ps.supplier_id LEFT JOIN tbl_manufacturer mu ON mu.manufacturer_id = ps.manufacturer_id where ps.status = 1";
	}
	
	if(!empty($_POST['txtCondition'])) {
		$sql .= " and ps.condition = '".$_POST['txtCondition']."'";
	}
	if(!empty($_POST['txtPartNo'])) {
		$sql .= " and ps.part_no = '".$_POST['txtPartNo']."'";
	}
	if(!empty($_POST['txtManufacturer'])) {
		$sql .= " and ps.manufacturer_id = ".(int)$_POST['txtManufacturer'];
	}
	
	$c_make = $_POST['ddlMake'];
	$c_model =  $_POST['ddlModel'];
	$c_year =  $_POST['ddlYear'];
	$condition = $_POST['txtCondition'];
	$part_no = $_POST['txtPartNo'];
	$manufacturer = $_POST['txtManufacturer'];
	
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
    <h1><i class="fa fa-briefcase repair-car-heading"> <span class="repair-car-txt">Vender nueva/Piezas antiguas
            </span></i></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Vender nueva/Piezas antiguas</li>
        <li class="active">Vender nueva/Piezas antiguas</li>
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
                            <h3 class="box-title"><i class="fa fa-search"></i> Encuentre sus piezas</h3>
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
                                  
                                  } 
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="ddl_model">Modelo :</label>
                            <select onchange="loadYearData(this.value);" class="form-control" name="ddlModel"
                                id="ddl_model">
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
                            <!-- <label for="txtCondition">Part No. :</label> -->
                            <label for="txtCondition">Número de pieza :</label>
                            <input type="text" class="form-control" value="<?php echo $part_no; ?>" name="txtPartNo"
                                id="txtPartNo" />
                        </div>
                        <div class="form-group col-md-4">
                            <label for="txtCondition">Condición :</label>
                            <select class="form-control" name="txtCondition" id="txtCondition">
                                <option <?php if($condition == 'new'){echo 'selected'; }?> value='new'>New</option>
                                <option <?php if($condition == 'old'){echo 'selected'; }?> value='old'>Old</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <!-- <label for="txtManufacturer">Manufacturer :</label> -->
                            <label for="txtManufacturer">Fabricante :</label>
                            <select class="form-control" name="txtManufacturer" id="txtManufacturer">
                                <option value=''>--Select--</option>
                                <?php
					$manufacturer_list = $wms->getAllManufacturerList($link);
					foreach($manufacturer_list as $row) {
						if($manufacturer > 0 && $manufacturer == $row['id']) {
							echo "<option selected value='".$row['id']."'>".$row['name']."</option>";
						} else {
							echo "<option value='".$row['id']."'>".$row['name']."</option>";
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
                        <h3 class="box-title"><i class="fa fa-list"></i> Lista de piezas disponibles</h3>
                    </div>
                    <?php 
			if(!empty($sql)) { $result = $wms->filterSellPartsList($link, $sql);
				foreach($result as $row) {
				$image = WEB_URL . 'img/no_image.jpg';
				if(file_exists(ROOT_PATH . '/img/upload/' . $row['parts_image']) && $row['parts_image'] != ''){
					$image = WEB_URL . 'img/upload/' . $row['parts_image']; //car image
				}
				$token = true;
			?>
                    <div style="width:98%;height:auto;border:solid 1px #ccc;padding:10px;margin:10px;">
                        <div class="col-md-2 text-left"><img class="img-thumbnail" style="width:150px;height:150px;"
                                src="<?php echo $image; ?>" /></div>
                        <div class="col-md-3 text-left">
                            <div>
                                <h4 style="font-weight:bold;"><?php echo $row['parts_name']; ?></h4>
                            </div>
                            <div><b>Quantity:</b> <span class="label label-danger"
                                    style="font-size:11px;"><?php echo $row['quantity']; ?></span></div>
                            <div><b>Part No:</b> <?php echo $row['part_no']; ?></div>
                            <div><b>Manufacturer:</b> <?php echo $row['manufacturer_name']; ?></div>
                            <div><b>Condition:</b> <span
                                    class="label label-info"><?php echo $row['condition']; ?></span></div>
                        </div>
                        <div class="col-md-3 text-left">
                            <div>
                                <h4 style="font-weight:bold;">&nbsp;</h4>
                            </div>
                            <div><b>Parts Status:</b>
                                <?php if($row['quantity'] > 0){echo '<span class="label label-success" style="font-size:11px;">In-Stock</span>';} else {echo '<span style="font-size:12px;" class="label label-danger">Out Of Stock</span>';} ?>
                            </div>
                            <div><b>Supplier Name:</b> <?php echo $row['s_name']; ?></div>
                            <div><b>Parts Warranty:</b> <span
                                    class="label label-warning"><?php echo !empty($row['parts_warranty']) ? $row['parts_warranty'] : 'No'; ?></span>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                            <div>
                                <h4 style="font-weight:bold;">&nbsp;</h4>
                            </div>
                            <div class="label label-warning" style="font-size:17px;"><i class="fa fa-tag"></i> Price:
                                <?php echo $currency; ?><?php echo $row['price']; ?></div>
                            <br />
                            <br />
                            <div>Qty:
                                <input id="qty_<?php echo $row['parts_id']; ?>" style="width:50px;height:35px" step="1"
                                    min="1" max="<?php echo $row['quantity']; ?>" value="1" type="number">
                                <!--<a href="<?php //echo WEB_URL; ?>parts_stock/partssellform.php?partsid=<?php //echo $row['parts_id']; ?>" style="font-weight:bold;font-size:17px;" class="btn btn-success"><i class="fa fa-shopping-cart"></i> </a>-->
                                <button type="button"
                                    onclick="addPartsToCart(<?php echo $row['parts_id']; ?>,'<?php echo $row['parts_name']; ?>','<?php echo $row['price']; ?>','<?php echo !empty($row['parts_warranty']) ? $row['parts_warranty'] : 'N/A'; ?>','<?php echo $row['condition']; ?>');"
                                    style="font-weight:bold;font-size:17px;" class="btn btn-success"><i
                                        class="fa fa-shopping-cart"></i></button>
                            </div>
                            <div id="load_<?php echo $row['parts_id']; ?>" style="display:none;"><img
                                    src="<?php echo WEB_URL;?>img/ajax-loader.gif" /></div>
                        </div>
                        <div style="clear:both;"></div>
                    </div>
                    <?php } } ?>
                    <?php if(!$token && !empty($_POST)) { ?>
                    <div align="center">No parts found based on your selected query.</div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


    <div id="minicart" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header orange_header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                            aria-hidden="true"><i class="fa fa-close"></i></span></button>
                    <h3 class="modal-title"><i class="fa fa-check-square-o"></i> <b>Success</b></h3>
                </div>
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i
                            class="fa fa-close"></i></span></button>
                <div class="modal-body"> <b>El Artículo se agrego al Carrito correctamente</b> <br />
                    <div class="pull-right btn-top">
                        <a class="btn btn-success" data-dismiss="modal">¿Quieres añadir más artículos?</a>
                        <a class="btn btn-success top-pull"
                            href="<?php echo WEB_URL;?>/parts_stock/partssellform.php">Proceder al Checkout</a>
                    </div>
                    <div style="clear:both;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('../footer.php'); ?>