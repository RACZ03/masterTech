<?php 
include_once('../header.php');
include('../helper/calculation.php');

$invoice_id = substr(number_format(time() * rand(),0,'',''),0,6);

/*variables*/

$selling_date=date("d/m/Y");

$wmscalc = new wms_calculation();
/************************ Insert Query ***************************/
if(isset($_POST['invoice_id'])){
	$data = array(
		'invoice_id'		=> $_POST['invoice_id'],
		'total'				=> $_POST['hdntotal'],
		'discount'			=> $_POST['txtSellDiscount'],
		'paid_amount'		=> $_POST['txtSellPaidamount'],
		'due_amount'		=> $_POST['hdn_due_amount'],
		'grand_total'		=> $_POST['hdn_grand_total'],
		'customer_name'		=> $_POST['txtBuyerName'],
		'telephone'			=> $_POST['txtMobile'],
		'email'				=> $_POST['txtEmail'],
		'company_name'		=> $_POST['txtCompanyname'],
		'customer_address'	=> $_POST['txtprestAddress'],
		'delivery_address'	=> $_POST['txtpermanentAddress'],
		'note'				=> $_POST['txtSellnote'],
		'invoice_date'		=> $wms->datepickerDateToMySqlDate($_POST['txtSellDate'])
	);
	$wms->savePartsCheckoutData($link, $data);
	$url = WEB_URL.'invoice/invoice_parts_sell.php?invoice_id='.$_POST['invoice_id'];
	header("Location: $url");
}

?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa"></i> &nbsp; </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Sell Parts Setting</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <form onsubmit="return confirmSubmit();" id="frmcarstock" method="post" enctype="multipart/form-data">
      <div class="box box-success" id="box_model">
        <div class="box-body">
          <div class="col-md-12" style="padding-top:10px;">
            <div class="col-md-12">
            	<input type="text" placeholder="Sell Date" style="color:red;font-weight:bold;text-align:center;font-size:20px;border:none;border-bottom:solid 1px #ccc;" name="txtSellDate" id="txtSellDate" value="<?php echo $selling_date; ?>" class="form-control datepicker"/>
			</div>
			 <div class="text-center">
				<div style="font-size:25px;font-weight:bold;color:green;text-decoration:underline;">Ordenes de Venta: <?php echo $invoice_id; ?></div>
			</div>
			<?php if(isset($_SESSION['parts_cart']) && !empty($_SESSION['parts_cart'])) { ?>
			<div class="pull-right">
              <button type="submit" name="btnSaveInvoive" class="btn btn-info btnsp"><i class="fa fa-print fa-2x"></i><br>
              Procesar</button>
            </div>
			<?php }?>
          </div>
        </div>
      </div>
      <div class="box box-success" id="box_model">
        <div class="box-body">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-cart-plus"></i> Carrito de Compras</h3>
          </div>
          <div>
            <table id="partsdata" class="table">
              <thead>
                <tr>
                  <th>Imagen</th>
                  <th>Artículo</th>
                  <th>Garantia</th>
				  <th>Condición</th>
                  <th>Precio</th>
                  <th>Cantidad</th>
                  <th>Total</th>
                  <th>&nbsp;</th>
                </tr>
              </thead>
			        <tbody>
                <?php echo $wms->getShoppingCartDate($link); ?>
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
     
      <!-- /.box -->
	  <div class="box box-success">
        <div class="box-body">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-user"></i> Cliente</h3>
          </div>
          <!-- <div class="form-group col-md-6">
            <label for="txtBuyerName"><span style="color:red;">*</span> Nombre :</label>
            <input type="text" placeholder="Owner Name" name="txtBuyerName" id="txtBuyerName" class="form-control" required/>
          </div> -->
          <div class="form-group col-md-6">
            <label for="txtBuyerName"><span style="color:red;">*</span> Nombre :</label>
            <select class='form-control' id="txtBuyerName" name="txtBuyerName">
              <option value="">--Seleccione Nombre--</option>
              <?php
                $customer_list = $wms->getAllCustomerList($link);
                foreach($customer_list as $crow){
                  if($cus_id > 0 && $cus_id == $crow['customer_id']) {
                    echo '<option selected value="'.$crow['c_name'].'">'.$crow['c_name'].'</option>';
                  } else {
                    echo '<option value="'.$crow['c_name'].'">'.$crow['c_name'].'</option>';
                  }
                }
              ?>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="txtMobile"><span style="color:red;">*</span> Telefono :</label>
            <input type="text" placeholder="Owner Mobile" name="txtMobile" id="txtMobile" class="form-control" required/>
          </div>
          <div class="form-group col-md-6">
            <label for="txtEmail"><span style="color:red;">*</span> E-Mail :</label>
            <input type="text" placeholder="Owner Email" name="txtEmail" id="txtEmail" class="form-control" required/>
          </div>
          <div class="form-group col-md-6">
            <label for="txtCompanyname"> Nombre de empresa:</label>
            <input type="text" placeholder="Company Name" name="txtCompanyname" id="txtCompanyname" class="form-control"/>
          </div>
          <div class="form-group col-md-6">
            <label for="txtprestAddress"><span style="color:red;">*</span> Dirección :</label>
            <textarea type="text" placeholder="Owner Address" name="txtprestAddress" id="txtprestAddress" class="form-control" required></textarea>
          </div>
          <div class="form-group col-md-6">
            <label for="txtpermanentAddress"><span style="color:red;">*</span> Dirección de Entrega :</label>
            <textarea type="text" placeholder="Owner Address" name="txtpermanentAddress" id="txtpermanentAddress" class="form-control" required></textarea>
          </div>
		  <div class="form-group col-md-12">
            <label for="txtSellnote">Notas :</label>
            <textarea name="txtSellnote" placeholder="Note" id="txtSellnote" class="form-control"></textarea>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
	  <input type="hidden" value="<?php echo $invoice_id; ?>" name="invoice_id"/>
    </form>
  </div>
</div>
<!-- /.row -->
<script type="text/javascript">
function confirmSubmit() {
	if(confirm('Are you sure everything is ok and you want to save information and generate invoice ?')) {
		return true;
	} else {
		return false;
	}
}
</script>
<?php include('../footer.php'); ?>
