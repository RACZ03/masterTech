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
$button_text="Save Information";
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
$status = '1';
$mega_html = '';
$total_price = '0.00';
$given_price = '0.00';
$pending_amount = '0.00';

$invoice_id = substr(number_format(time() * rand(),0,'',''),0,6);


/*#############################################################*/
if(isset($_POST['parts_names'])){
	$image_url = uploadImage();
	if(empty($image_url)) {
		$image_url = $_POST['img_exist'];
	}
	$wms->updatePartsStockInformation($link, $_POST, $image_url);
	$url = WEB_URL.'parts_stock/partsstocklist.php?m=up';
	header("Location: $url");
	exit();
}

if(isset($_GET['id']) && $_GET['id'] != ''){
	$row = $wms->getPartsStockInfoByPartsId($link, $_GET['id']);
	if(!empty($row)) {
		$parts_id = $row['parts_id'];
		$parts_names= $row['parts_name'];
		$sup_id = $row['supplier_id'];
		$maufacturer_id = $row['manufacturer_id'];
		$quantity = $row['quantity'];
		$sku = $row['part_no'];
		$sellprice = $row['price'];
		$parts_condition = $row['condition'];
		$parts_warranty = $row['parts_warranty'];
		$status = $row['status'];
		if($row['parts_image'] != ''){
			$image_cus = WEB_URL . 'img/upload/' . $row['parts_image'];
			$img_track = $row['parts_image'];
		}
		$hdnid = $_GET['id'];
		$title = 'Update Parts';
		$button_text="Update Parts Stock";
		
		$queryx = $wms->getAllPartsFitDate($link, $_GET['id']);
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

?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-pencil"></i> Update Parts Stock </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?php echo WEB_URL?>parts_stock/buypartslist.php">Purchase Parts</a></li>
    <li class="active">Update Stock</li>
  </ol>
</section>
<!-- Main content -->
<form onSubmit="return validateMe();" id="from_add_buy_parts" method="post" enctype="multipart/form-data">
  <section class="content">
  <!-- Full Width boxes (Stat box) -->
  <div class="row">
    <div class="col-md-12">
      <div class="box box-success" id="box_model">
        <div class="box-body">
          <div class="form-group col-md-12" style="padding-top:10px;">
            <div class="pull-left"><a href="<?php echo WEB_URL;?>parts_stock/buyparts.php?pid=<?php echo $_GET['id']; ?>" class="btn btn-info btnsp"><i class="fa fa-cart-plus fa-2x"></i><br/>
              Purchase This Parts</a> </div>
            <div class="pull-right">
              <button type="button" onclick=$("#from_add_buy_parts").submit(); class="btn btn-success btnsp"><i class="fa fa-save fa-2x"></i><br/>
              <?php echo $button_text; ?></button>
                    <a class="btn btn-warning btnsp" data-toggle="tooltip" href="<?php echo WEB_URL; ?>parts_stock/partsstocklist.php" data-original-title="Back"><i class="fa fa-reply  fa-2x"></i><br/>
              Back</a> </div>
          </div>
        </div>
      </div>
      <div class="box box-success">
        <div class="box-header">
          <h3 class="box-title">Parts Information</h3>
        </div>
        <div class="box-body">
          <div class="form-group col-md-12">
            <label for="parts_names"><span style="color:red;">*</span> Parts Name :</label>
            <input type="text" name="parts_names" value="<?php echo $parts_names;?>" id="parts_names" class="form-control" />
          </div>
          <div class="form-group col-md-4">
            <label for="ddl_supplier"><span style="color:red;">*</span> Supplier :</label>
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
            <label for="ddl_load_manufracturer"><span style="color:red;">*</span> Manufacturer :</label>
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
            <label for="txtCondition"><span style="color:red;">*</span> Condition :</label>
            <select class="form-control" name="txtCondition">
              <option <?php if($parts_condition == 'new'){echo 'selected'; }?> value='new'>New</option>
              <option <?php if($parts_condition == 'old'){echo 'selected'; }?> value='old'>Old</option>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="parts_quantity"><span style="color:red;">*</span> Stock Quantity :</label>
            <input type="text" name="parts_quantity" value="<?php echo $quantity;?>" id="parts_quantity" class="form-control ppcal allownumberonly" />
          </div>
          <div class="form-group col-md-4">
            <label for="parts_sell_price"><span style="color:red;">*</span> Sell Price (Per pcs) :</label>
            <div class="input-group"> <span class="input-group-addon"><?php echo $currency;?></span>
              <input type="text" placeholder="Selling Price" value="<?php echo $sellprice; ?>" name="parts_sell_price" id="parts_sell_price" class="form-control allownumberonly" required/>
            </div>
          </div>
          <div class="form-group col-md-4">
            <label for="ddl_status">Parts Status :</label>
            <select class="form-control" name="ddl_status" id="ddl_status">
              <option <?php if($status=='1'){echo 'selected';}?> value='1'>Enable</option>
              <option <?php if($status=='0'){echo 'selected';}?> value='0'>Disable</option>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="parts_sku"><span style="color:red;">*</span> Part No / SKU :</label>
            <input type="text" name="parts_sku" value="<?php echo $sku;?>" id="parts_sku" class="form-control" />
          </div>
          <div class="form-group col-md-4">
            <label for="parts_warranty">Parts Warranty :</label>
            <input type="text" name="parts_warranty" placeholder="5 Years or 6 Months" value="<?php echo $parts_warranty;?>" id="parts_warranty" class="form-control" />
          </div>
          <div class="form-group col-md-12">
            <label for="Prsnttxtarea">Parts Image (500x500px) :</label>
            <img class="form-control" src="<?php echo $image_cus; ?>" style="height:250px;width:250px;" id="output"/>
            <input type="hidden" name="img_exist" value="<?php echo $img_track; ?>" />
          </div>
          <div class="form-group col-md-12"> <span class="btn btn-file btn btn-primary">Upload Image
            <input type="file" name="uploaded_file" onchange="loadFile(event)" />
            </span> </div>
        </div>
        <input type="hidden" value="<?php echo $hdnid; ?>" name="parts_id"/>
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
                  <td class="left"><button class="btn btn-primary" title="" data-toggle="tooltip" onclick="addPartsData();" type="button" data-original-title="Add Car Parts Information"><i class="fa fa-plus-circle"></i></button></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<!-- /.row -->
<script type="text/javascript">
var parts_rows = <?php echo $row_val; ?>;
function addPartsData() {
	html  = '<tbody id="parts-row' + parts_rows + '">';
	html += '  <tr>';
	
	html += '    <td class="left"><select class="form-control" id="make_' + parts_rows + '" name="partsfilter[' + parts_rows + '][make]" onchange="loadModelDatax(this,' + parts_rows + ');">';
	html += '      <option value="">--Select Make--</option>';
	<?php foreach($makes as $make) { ?>
	html += '      <option value="<?php echo $make['make_id']; ?>"><?php echo $make['make_name']; ?></option>';
	<?php }?>
	html += '    </select></td>';
	html += '    <td class="left"><select class="form-control" disabled="disabled" id="model_' + parts_rows + '" name="partsfilter[' + parts_rows + '][model]" onchange="loadYearDatax(this,' + parts_rows + ');">';
	html += '      <option value="">--Select Model--</option>';
	html += '    </select></td>';
	html += '    <td class="left"><select class="form-control" id="year_' + parts_rows + '" disabled="disabled" name="partsfilter[' + parts_rows + '][year]">';
	html += '      <option value="">--Select Year--</option>';
	html += '    </select></td>';
	html += '    <td class="left"><button class="btn btn-danger" title="Remove" data-toggle="tooltip" onclick="$(\'#parts-row' + parts_rows + '\').remove();" type="button"><i class="fa fa-minus-circle"></i></button></td>';
	html += '  </tr>';	
	html += '</tbody>';
	
	$('#partsdata tfoot').before(html);
	
	parts_rows++;
}

function loadModelDatax(obj,row){
	if(obj.value != ''){
		var post_url = "<?php echo WEB_URL; ?>ajax/getstate.php";
		$.ajax({
			type: "POST",
			url: post_url,
			data: 'mid=' + obj.value + '&token=getmodel',
			success: function(response) {
				if(response != '-99'){
					$("#model_" + row).html(response);
					$("#model_" + row).prop('disabled', false);
				}
				else{
					alert('Wrong Request');
					$("#model_" + row).prop('disabled', true);
				}
			},
		});
	}
}
function loadYearDatax(obj,row){
	if(obj.value != ''){
		var post_url = "<?php echo WEB_URL; ?>ajax/getstate.php";
		$.ajax({
			type: "POST",
			url: post_url,
			data: 'moid=' + obj.value + '&mid=' + $("#make_" + row).val() + '&token=getyear',
			success: function(response) {
				if(response != '-99'){
					$("#year_" + row).html(response);
					$("#year_" + row).prop('disabled', false);
				}
				else{
					alert('Wrong Request');
					$("#year_" + row).prop('disabled', true);
				}
			},
		});
	}
}

function validateMe(){
	if($("#parts_names").val() == ''){
		alert("Parts Name is Required !!!");
		$("#parts_names").focus();
		return false;
	}
	 else if($("#ddl_supplier").val() == ''){
	 	alert("Parts Supplier is Required !!!");
	 	$("#ddl_supplier").focus();
	 	return false;
	 }
	 else if($("#ddl_load_manufracturer").val() == ''){
	 	alert("Manufacturer is Required !!!");
	 	$("#ddl_load_manufracturer").focus();
	 	return false;
	 }
	 else if($("#txtCondition").val() == ''){
	 	alert("Condition is Required !!!");
	 	$("#txtCondition").focus();
	 	return false;
	 }
	 else if($("#buy_prie").val() == ''){
	 	alert("Buy Price is Required !!!");
	 	$("#buy_prie").focus();
	 	return false;
	 }
	 else if($("#parts_quantity").val() == ''){
	 	alert("Quantity is Required !!!");
	 	$("#parts_quantity").focus();
	 	return false;
	 }
	 else if($("#parts_sell_price").val() == ''){
	 	alert("Parts Sell Price is Required !!!");
	 	$("#parts_sell_price").focus();
	 	return false;
	 }	  
	 else if($("#parts_sku").val() == ''){
	 	alert("Parts Model is Required !!!");
	 	$("#parts_sku").focus();
	 	return false;
	 }	 
	else{
		return true;
	}
}
</script>
<?php include('../footer.php'); ?>
