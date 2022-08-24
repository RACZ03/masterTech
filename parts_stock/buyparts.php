<?php 
include('../header.php');
$success = "none";
$id="";
$parts_id = '';
$sup_id = 0;
$maufacturer_id = 0;
$c_make = 0;
$c_model = 0;
$c_year = 0;
$title = 'Add New Parts';
//$button_text="Save Information";
$button_text="Guardar";
$successful_msg="Add Parts Successfully";
$form_url = WEB_URL . "parts_stock/buyparts.php";
$hdnid="0";
$image_cus = WEB_URL . 'img/no_image.jpg';
$img_track = '';
$add_date = date('d/m/Y');
$sellprice = '0.00';
$sku = '';
$quantity = '';
$buyprice = '';
$parts_names = '';
$parts_warranty = '';
$parts_model='';
$parts_condition = 'new';
$row_val = 0;
$mega_html = '';
$total_price = '0.00';
$given_price = '0.00';
$pending_amount = '0.00';
$pid = 0;
$existing_parts = array();
$invoice_id = substr(number_format(time() * rand(),0,'',''),0,6);


/*#############################################################*/
if(isset($_POST['parts_names'])){
	$image_url = uploadImage();
	if(empty($image_url)) {
		$image_url = $_POST['img_exist'];
	}
    // $new_array = [];
    // for ($x = 0; $x <= 2; $x++) {
    //     array_push($new_array, $_POST);
    // }
	$wms->saveUpdateBuyPartsInformation($link, $_POST, $image_url);
	if((int)$_POST['parts_id'] > 0){
		$url = WEB_URL.'parts_stock/buypartslist.php?m=up';
		header("Location: $url");
	} 
    else {
		//$url = WEB_URL.'parts_stock/buypartslist.php?m=add';
		$url = WEB_URL.'invoice/invoice_parts_purchase.php?invoice_id='.$_POST['invoice_id']; //invoice page
		header("Location: $url");
	}
	exit();
}

if(isset($_GET['invoice_id']) && $_GET['invoice_id'] != ''){
	$row = $wms->getBuyPartsListByPartsId($link, $_GET['invoice_id']);
	if(!empty($row)) {
		$invoice_id = $row['invoice_id'];
		$parts_id = $row['parts_id'];
		$parts_names= $row['parts_name'];
		$sup_id = $row['supplier_id'];
		$maufacturer_id = $row['manufacturer_id'];
		$buyprice = $row['parts_buy_price'];
		$quantity = $row['parts_quantity'];
		$sku = $row['parts_sku'];
		$sellprice = $row['price'];
		$add_date = $wms->mySqlToDatePicker($row['parts_added_date']);
		$parts_condition = $row['parts_condition'];
		$parts_warranty = $row['parts_warranty'];
		if($row['parts_image'] != ''){
			$image_cus = WEB_URL . 'img/upload/' . $row['parts_image'];
			$img_track = $row['parts_image'];
		}
		$total_price = $row['total_amount'];
		$given_price = $row['given_amount'];
		$pending_amount = $row['pending_amount'];
		$hdnid = $row['parts_id'];
		$title = 'Update Parts';
		$button_text="Update";
		
		$queryx = $wms->getAllPartsFitDate($link, $row['parts_id']);
		if(!empty($queryx)){
			$i = 0;
			foreach($queryx as $frow){
				$make_html = $wms->getmakeHtml($frow['make_id'],$i,$link);
				$model_html = $wms->getmodelHtml($frow['make_id'],$frow['model_id'],$i,$link);
				$year_html = $wms->getyearHtml($frow['make_id'],$frow['model_id'],$frow['year_id'],$i,$link);
				
				$mega_html .= "<tbody id='parts-row".$i."'><tr><td class='left'>".$make_html."</td><td class='left'>".$model_html."</td><td class='left'>".$year_html."</td><td class='left'><button class='btn btn-danger' title='Remove' data-toggle='tooltip' onclick=$('#parts-row".$i."').remove(); type='button'><i class='fa fa-minus-circle'></i></button> </td></tr></tbody>";
				$i++;
			}
			$row_val = $i;
		}
		//$successful_msg="Update Parts Successfully";
		//$form_url = WEB_URL . "parts_stock/parts_stock_list.php?id=".$_GET['id'];
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

//fit work here
$makes = $wms->get_all_make_list($link);

//exisitng work
$parts = $wms->partsStockList($link);
if(isset($_GET['pid']) && $_GET['pid'] != ''){
	$pid = $_GET['pid'];
	$existing_parts = $wms->getPartsStockInfoByPartsId($link, $_GET['pid']);
	if(!empty($existing_parts)) {
		$parts_names= $existing_parts['parts_name'];
		$sup_id = $existing_parts['supplier_id'];
		$maufacturer_id = $existing_parts['manufacturer_id'];
		$parts_condition = $existing_parts['condition'];
		$parts_warranty = $existing_parts['parts_warranty'];
		$sku = $existing_parts['part_no'];
		if($existing_parts['parts_image'] != ''){
			$image_cus = WEB_URL . 'img/upload/' . $existing_parts['parts_image'];
			$img_track = $existing_parts['parts_image'];
		}
		$sellprice = $existing_parts['price'];
		
		$queryx = $wms->getAllPartsFitDate($link, $_GET['pid']);
		if(!empty($queryx)){
			$i = 0;
			foreach($queryx as $frow){
				$make_html = $wms->getmakeHtml($frow['make_id'],$i,$link);
				$model_html = $wms->getmodelHtml($frow['make_id'],$frow['model_id'],$i,$link);
				$year_html = $wms->getyearHtml($frow['make_id'],$frow['model_id'],$frow['year_id'],$i,$link);
				$mega_html .= "<tbody id='parts-row".$i."'>
				<tr>
				<td class='left'>".$make_html."</td>
				<td class='left'>".$model_html."</td>
				<td class='left'>".$year_html."</td>
				<td class='left'>
				<button class='btn btn-danger' title='Remove' data-toggle='tooltip' onclick=$('#parts-row".$i."').remove(); type='button'><i class='fa fa-minus-circle'></i></button> </td></tr></tbody>";
				$i++;
			}
			$row_val = $i;
		}
	}
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
    <h1><i class="fa fa-shopping-cart repair-car-heading"><span class="repair-car-txt">Comprar Stock</span></i></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo WEB_URL?>parts_stock/buypartslist.php">Purchase Parts</a></li>
        <li class="active">Add Purchase Parts</li>
    </ol>
</section>
<!-- Main content -->
<form onSubmit="return validateMe();" id="from_add_buy_parts" action="<?php echo $form_url; ?>" method="post"
    enctype="multipart/form-data">
    <section class="content">
        <!-- Full Width boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success" id="box_model">
                    <div class="box-body">
                        <div class="form-group col-md-12" style="padding-top:10px;">
                            <div class="pull-left id-txt">
                                <label class="label label-danger label-color" style="font-size:15px;">Orden de Compra -
                                    <?php echo $invoice_id; ?></label>
                            </div>
                            <div class="pull-right">
                                <!-- <button type="button" onclick=$("#from_add_buy_parts").submit(); class="btn btn-success btnsp"><i class="fa fa-save fa-2x"></i><br/><span class="print-txt"><?php //echo $button_text; ?></button> -->
                                <button type="button" onclick=$("#from_add_buy_parts").submit();
                                    class="btn btn-success btnsp btn-size"><span
                                        class="print-txt"><?php echo $button_text; ?></span>&emsp;<i
                                        class="fa fa-save fa-2x"></i></button>
                                      <a class="btn btn-warning btnsp" data-toggle="tooltip"
                                    href="<?php echo WEB_URL; ?>parts_stock/buypartslist.php"
                                    data-original-title="Back"><i class="fa fa-reply  fa-2x"></i><br />
                                    Volver</a>
                            </div>
                        </div>
                        <?php if($hdnid=='0') { ?>
                        <div class="clearfix" style="color:red;font-weight:bold;text-align:center;">** Al guardar la
                            información, automáticamente irá a ala ORDEN DE COMPRA.</div>
                        <?php } ?>
                    </div>
                </div>
                <div class="box box-success" id="box_model">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-cart-plus"></i>&nbsp;&nbsp;¿Es esta una compra para un
                            artículo existente?</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group col-md-12" style="padding-top:10px;">
                            <label for="ddl_e_parts"> Seleccione el elemento de la lista :</label>
                            <select onchange="loadExistingParts(this.value);"
                                class="form-control js-example-basic-single" name="ddl_e_parts" id="ddl_e_parts">
                                <option value="">--Select Parts--</option>
                                <?php foreach($parts as $p) { ?>
                                <option <?php if($pid==$p['parts_id']){echo 'selected';}?>
                                    value="<?php echo $p['parts_id']; ?>"><?php echo $p['parts_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php if(isset($existing_parts) && !empty($existing_parts)) { ?>
                        <div class="form-group col-md-12" align="center">
                            <img class="form-control" src="<?php echo $image_cus; ?>"
                                style="height:100px;width:100px;" />
                        </div>
                        <div class="form-group col-md-12" align="center">
                            <label>Available Stock : <span
                                    class="label label-danger"><?php echo $existing_parts['quantity']; ?></span></label>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">Detalles de la Compra</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group col-md-12">
                            <label for="parts_names"><span style="color:red;">*</span> Artículo :</label>
                            <input type="text" name="parts_names" value="<?php echo $parts_names;?>" id="parts_names"
                                class="form-control" />
                        </div>
                        <div class="form-group col-md-4">
                            <label for="ddl_supplier"><span style="color:red;">*</span> Proveedor :</label>
                            <select class='form-control' id="ddl_supplier" name="ddl_supplier">
                                <option value="">--Select Suppliers--</option>
                                <?php
									$supplier_list = $wms->getAllSupplierList($link);
									foreach($supplier_list as $row){
										if($sup_id > 0 && $sup_id == $row['supplier_id']) {
											echo '<option selected value="'.$row['supplier_id'].'">'.$row['s_name'].'</option>';
										} else {
											echo '<option value="'.$row['supplier_id'].'">'.$row['s_name'].'</option>';
										}
									}
								?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="ddl_load_manufracturer"><span style="color:red;">*</span> Marca :</label>
                            <select class='form-control' id="ddl_load_manufracturer" name="ddl_load_manufracturer">
                                <option value="">--Select Manufacturer--</option>
                                <?php
                                    $manufacturer_list = $wms->getAllManufacturerList($link);
                                    foreach($manufacturer_list as $row) {
                                        if($maufacturer_id > 0 && $maufacturer_id == $row['id']) {
                                            echo '<option selected value="'.$row['id'].'">'.$row['name'].'</option>';
                                        } else {
                                            echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="txtCondition"><span style="color:red;">*</span> Condición :</label>
                            <select class="form-control" name="txtCondition" id="txtCondition">
                                <option <?php if($parts_condition == 'new'){echo 'selected'; }?> value='new'>New
                                </option>
                                <option <?php if($parts_condition == 'old'){echo 'selected'; }?> value='old'>Old
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="buy_prie"><span style="color:red;">*</span> Precio de Compra :</label>
                            <div class="input-group"> <span class="input-group-addon"><?php echo $currency;?></span>
                                <input type="text" placeholder="Buying Price" value="<?php echo $buyprice; ?>"
                                    name="buy_prie" id="buy_prie" class="form-control allownumberonly ppcal" required />
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="parts_quantity"><span style="color:red;">*</span> Cantidad :</label>
                            <input type="text" name="parts_quantity" value="<?php echo $quantity;?>" id="parts_quantity"
                                class="form-control ppcal allownumberonly" />
                        </div>
                        <div class="form-group col-md-4">
                            <label for="parts_sell_price"><span style="color:red;">*</span> Precio de Venta :</label>
                            <div class="input-group"> <span class="input-group-addon"><?php echo $currency;?></span>
                                <input type="text" placeholder="Selling Price" value="<?php echo $sellprice; ?>"
                                    name="parts_sell_price" id="parts_sell_price" class="form-control allownumberonly"
                                    required />
                            </div>
                        </div>
                        <!--<div class="form-group col-md-4">
                            <label for="ddl_status"><span style="color:red;">*</span> Status :</label>
                            <select class="form-control" name="ddl_status" id="ddl_status">
                                <option value='1'>Enable</option>
                                <option value='0'>Disable</option>
                            </select>
                        </div>-->
                        <div class="form-group col-md-4">
                            <label for="parts_sku"><span style="color:red;">*</span> ID del Artículo :</label>
                            <input type="text" name="parts_sku" value="<?php echo $sku;?>" id="parts_sku"
                                class="form-control" />
                        </div>
                        <div class="form-group col-md-4">
                            <label for="parts_add_date">Fecha de Compra :</label>
                            <input type="text" name="parts_add_date" value="<?php echo $add_date;?>" id="parts_add_date"
                                class="form-control datepicker" />
                        </div>
                        <div class="form-group col-md-4">
                            <label for="parts_warranty">Garantía :</label>
                            <input type="text" name="parts_warranty" placeholder="5 Years or 6 Months"
                                value="<?php echo $parts_warranty;?>" id="parts_warranty" class="form-control" />
                        </div>
                        <div class="form-group col-md-4">
                            <label for="total_amount"><span style="color:red;">*</span> Valor de la Compra :</label>
                            <div class="input-group"> <span class="input-group-addon"><?php echo $currency;?></span>
                                <input type="text" readonly="" style="color:red;font-weight:bold;"
                                    placeholder="Total Price" value="<?php echo $total_price; ?>" name="total_amount"
                                    id="total_amount" class="form-control allownumberonly" required />
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="total_amount"><span style="color:red;">*</span>Tarifa de Servicios MT <span
                                    class="small-text">(0.00 en adelante)</span> :</label>
                            <div class="input-group"> <span class="input-group-addon"><?php echo $currency;?></span>
                                <input type="text" placeholder="Given Amount" value="<?php echo $given_price; ?>"
                                    name="given_amount" id="given_amount" class="form-control allownumberonly ppcal"
                                    required />
                            </div>
                            <label><span class="small-text">(Si corresponde, establezca las tarifas de servicio de
                                    MasterTech aqui) :</span></label>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="total_amount"><span style="color:red;">*</span> Saldo a favor del Proveedor
                                :</label>
                            <div class="input-group"> <span class="input-group-addon"><?php echo $currency;?></span>
                                <input type="text" readonly="" style="color:red;font-weight:bold;"
                                    placeholder="Pending Amount" value="<?php echo $pending_amount; ?>"
                                    name="pending_amount" id="pending_amount" class="form-control allownumberonly"
                                    required />
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="Prsnttxtarea">Parts Image (500x500px) :</label>
                            <img class="form-control" src="<?php echo $image_cus; ?>" style="height:250px;width:250px;"
                                id="output" />
                            <input type="hidden" name="img_exist" id="img_exist" value="<?php echo $img_track; ?>" />
                        </div>
                        <div class="form-group col-md-12"> <span class="btn btn-file btn btn-primary">Upload Image
                                <input type="file" name="uploaded_file" onchange="loadFile(event)" />
                            </span> </div>
                    </div>
                    <input type="hidden" value="<?php echo $quantity; ?>" name="old_qty" id="old_qty"/>
                    <input type="hidden" value="<?php echo $hdnid; ?>" name="parts_id" id="parts_id" />
                    <input type="hidden" value="<?php echo $invoice_id; ?>" name="invoice_id" id="invoice_id" />
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                <div class="box box-success" id="box_model">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-list"></i> Parts Fits With...</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group col-md-12" style="padding-top:10px;">
                            <table id="partsdata" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <td class="left"><u><b>Make</b></u></td>
                                        <td class="left"><u><b>Model</b></u></td>
                                        <td class="left"><u><b>Year</b></u></td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <?php if($mega_html != '') { ?>
                                <?php echo $mega_html; ?>
                                <?php } ?>
                                <tfoot>
                                    <tr>
                                        <td colspan="4"></td>
                                        <td class="left"><button class="btn btn-primary" title="" data-toggle="tooltip"
                                                onclick="addPartsData();" type="button"
                                                data-original-title="Add Car Parts Information"><i
                                                    class="fa fa-plus-circle"></i></button></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <input id="submit" class="submit" onclick="formsubmit()" type="button" value="Submit"> -->
</form>
<!-- /.row -->
<script type="text/javascript">
var parts_rows = <?php echo $row_val; ?>;

function addPartsData() {
    html = '<tbody id="parts-row' + parts_rows + '">';
    html += '  <tr>';
    html += '    <td class="left"><select class="form-control" id="make_' + parts_rows + '" name="partsfilter[' +
        parts_rows + '][make]" onchange="loadModelDatax(this,' + parts_rows + ');">';
    html += '      <option value="">--Select Make--</option>';
    <?php foreach($makes as $make) { ?>
    html += '      <option value="<?php echo $make['make_id']; ?>"><?php echo $make['make_name']; ?></option>';
    <?php }?>
    html += '    </select></td>';
    html += '    <td class="left"><select class="form-control" disabled="disabled" id="model_' + parts_rows +
        '" name="partsfilter[' + parts_rows + '][model]" onchange="loadYearDatax(this,' + parts_rows + ');">';
    html += '      <option value="">--Select Model--</option>';
    html += '    </select></td>';
    html += '    <td class="left"><select class="form-control" id="year_' + parts_rows +
        '" disabled="disabled" name="partsfilter[' + parts_rows + '][year]">';
    html += '      <option value="">--Select Year--</option>';
    html += '    </select></td>';
    html +=
        '    <td class="left"><button class="btn btn-danger" title="Remove" data-toggle="tooltip" onclick="$(\'#parts-row' +
        parts_rows + '\').remove();" type="button"><i class="fa fa-minus-circle"></i></button></td>';
    html += '  </tr>';
    html += '</tbody>';

    $('#partsdata tfoot').before(html);

    parts_rows++;
}

function loadModelDatax(obj, row) {
    if (obj.value != '') {
        var post_url = "<?php echo WEB_URL; ?>ajax/getstate.php";
        $.ajax({
            type: "POST",
            url: post_url,
            data: 'mid=' + obj.value + '&token=getmodel',
            success: function(response) {
                if (response != '-99') {
                    $("#model_" + row).html(response);
                    $("#model_" + row).prop('disabled', false);
                } else {
                    alert('Wrong Request');
                    $("#model_" + row).prop('disabled', true);
                }
            },
        });
    }
}

function loadYearDatax(obj, row) {
    if (obj.value != '') {
        var post_url = "<?php echo WEB_URL; ?>ajax/getstate.php";
        $.ajax({
            type: "POST",
            url: post_url,
            data: 'moid=' + obj.value + '&mid=' + $("#make_" + row).val() + '&token=getyear',
            success: function(response) {
                if (response != '-99') {
                    $("#year_" + row).html(response);
                    $("#year_" + row).prop('disabled', false);
                } else {
                    alert('Wrong Request');
                    $("#year_" + row).prop('disabled', true);
                }
            },
        });
    }
}

function validateMe() {
    var invoice_id = document.getElementById('invoice_id').value;
    if ($("#parts_names").val() == '') {
        alert("Parts Name is Required !!!");
        $("#parts_names").focus();
        return false;
    } else if ($("#ddlMake").val() == '') {
        alert("Parts Make is Required !!!");
        $("#ddlMake").focus();
        return false;
    } else if ($("#ddl_model").val() == '') {
        alert("Parts Model is Required !!!");
        $("#ddl_model").focus();
        return false;
    } else if ($("#ddlYear").val() == '') {
        alert("Parts Year is Required !!!");
        $("#ddlYear").focus();
        return false;
    } else if ($("#ddl_supplier").val() == '') {
        alert("Parts Supplier is Required !!!");
        $("#ddl_supplier").focus();
        return false;
    } else if ($("#ddl_load_manufracturer").val() == '') {
        alert("Manufacturer is Required !!!");
        $("#ddl_load_manufracturer").focus();
        return false;
    } else if ($("#txtCondition").val() == '') {
        alert("Condition is Required !!!");
        $("#txtCondition").focus();
        return false;
    } else if ($("#buy_prie").val() == '') {
        alert("Buy Price Date is Required !!!");
        $("#buy_prie").focus();
        return false;
    } else if ($("#sell_price").val() == '') {
        alert("Delivery Date is Required !!!");
        $("#sell_price").focus();
        return false;
    } else if ($("#parts_quantity").val() == '') {
        alert("Quantity is Required !!!");
        $("#parts_quantity").focus();
        return false;
    } else if ($("#parts_sell_price").val() == '') {
        alert("Parts Sell Price is Required !!!");
        $("#parts_sell_price").focus();
        return false;
    } else if ($("#ddl_status").val() == '') {
        alert("Parts Status is Required !!!");
        $("#ddl_status").focus();
        return false;
    } else if ($("#parts_sku").val() == '') {
        alert("Parts Model is Required !!!");
        $("#parts_sku").focus();
        return false;
    }
    else {
        var agree = confirm("Do you want to add more product?");
        if (agree) {
            var ddl_e_parts = document.getElementById('ddl_e_parts').value;
            var parts_names = document.getElementById('parts_names').value;
            var ddl_supplier = document.getElementById('ddl_supplier').value;
            var ddl_load_manufracturer = document.getElementById('ddl_load_manufracturer').value;
            var txtCondition = document.getElementById('txtCondition').value;
            var buy_prie = document.getElementById('buy_prie').value;
            var parts_quantity = document.getElementById('parts_quantity').value;
            var parts_sell_price = document.getElementById('parts_sell_price').value;
            var parts_sku = document.getElementById('parts_sku').value;
            var parts_add_date = document.getElementById('parts_add_date').value;
            var parts_warranty = document.getElementById('parts_warranty').value;
            var total_amount = document.getElementById('total_amount').value;
            var given_amount = document.getElementById('given_amount').value;
            var pending_amount = document.getElementById('pending_amount').value;
            var img_exist = document.getElementById('img_exist').value;
            var old_qty = document.getElementById('old_qty').value; 
            var parts_id = document.getElementById('parts_id').value;
            var invoice_id = document.getElementById('invoice_id').value;
            var formdata = 'ddl_e_parts=' + ddl_e_parts + '&parts_names=' + parts_names + '&ddl_supplier=' +
                ddl_supplier + '&ddl_load_manufracturer=' + ddl_load_manufracturer + '&txtCondition=' + txtCondition + '&buy_prie=' + buy_prie +
                '&parts_quantity=' + parts_quantity + '&parts_sell_price=' + parts_sell_price + '&parts_sku=' + parts_sku + '&parts_add_date=' + parts_add_date + '&parts_warranty=' + parts_warranty + '&total_amount=' + total_amount + '&given_amount=' + given_amount + '&pending_amount=' + pending_amount + '&img_exist='+ img_exist + '&old_qty=' + old_qty + '&parts_id=' + parts_id + '&invoice_id=' + invoice_id ;
            $.ajax({
                type: "POST",
                url: "storebuyparts.php",
                data: formdata,
                cache: false,
            });
            return false;
        }
        // else if(!agree) {
        //     var ddl_e_parts = document.getElementById('ddl_e_parts').value;
        //     var parts_names = document.getElementById('parts_names').value;
        //     var ddl_supplier = document.getElementById('ddl_supplier').value;
        //     var ddl_load_manufracturer = document.getElementById('ddl_load_manufracturer').value;
        //     var txtCondition = document.getElementById('txtCondition').value;
        //     var buy_prie = document.getElementById('buy_prie').value;
        //     var parts_quantity = document.getElementById('parts_quantity').value;
        //     var parts_sell_price = document.getElementById('parts_sell_price').value;
        //     var parts_sku = document.getElementById('parts_sku').value;
        //     var parts_add_date = document.getElementById('parts_add_date').value;
        //     var parts_warranty = document.getElementById('parts_warranty').value;
        //     var total_amount = document.getElementById('total_amount').value;
        //     var given_amount = document.getElementById('given_amount').value;
        //     var pending_amount = document.getElementById('pending_amount').value;
        //     var img_exist = document.getElementById('img_exist').value;
        //     var old_qty = document.getElementById('old_qty').value; 
        //     var parts_id = document.getElementById('parts_id').value;
        //     var invoice_id = document.getElementById('invoice_id').value;
        //     var formdata = 'ddl_e_parts=' + ddl_e_parts + '&parts_names=' + parts_names + '&ddl_supplier=' +
        //         ddl_supplier + '&ddl_load_manufracturer=' + ddl_load_manufracturer + '&txtCondition=' + txtCondition + '&buy_prie=' + buy_prie +
        //         '&parts_quantity=' + parts_quantity + '&parts_sell_price=' + parts_sell_price + '&parts_sku=' + parts_sku + '&parts_add_date=' + parts_add_date + '&parts_warranty=' + parts_warranty + '&total_amount=' + total_amount + '&given_amount=' + given_amount + '&pending_amount=' + pending_amount + '&img_exist='+ img_exist + '&old_qty=' + old_qty + '&parts_id=' + parts_id + '&invoice_id=' + invoice_id ;
        //     $.ajax({
        //         type: "POST",
        //         url: "storebuyparts.php",
        //         data: formdata,
        //         cache: false,
        //     });
        //     return true;
        // } 
        else {
            return true;
        }
    }
}

function loadExistingParts(parts_id) {
    if (parts_id != '') {
        window.location.href = "<?php echo WEB_URL;?>parts_stock/buyparts.php?pid=" + parts_id;
    }
}
</script>
<?php include('../footer.php'); ?>