<?php 
include_once('../header.php');
include('../helper/calculation.php');


/*variables*/
$delinfo = 'none';
$addinfo = 'none';
$savebtn='Save Information';
$msg = '';
$del_msg = '';

$sold_id=0;

$results = array();

$wmscalc = new wms_calculation();

/************************ Insert Query ***************************/
if(isset($_POST['sold_id'])){
	$data = array(
		'sold_id'			=> $_POST['sold_id'],
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
	$wms->updatePartsCheckoutData($link, $data);
	$url = WEB_URL.'invoice/invoice_parts_sell.php?invoice_id='.$_POST['invoice_id'];
	if(isset($_POST['btnBackList'])) {
		$url = WEB_URL.'parts_stock/sellpartslist.php?m=up';
	}
	header("Location: $url");
}

if(isset($_GET['sold_id']) && $_GET['sold_id'] != ''){
	$sold_id = $_GET['sold_id'];
	$results = $wms->getSellPartsInformationBySoldId($link, $_GET['sold_id']);
} else {
	echo 'BAD GETWAY';
	die();
}

?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-car"></i> Sell New/Old Parts </h1>
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
    <div id="me" class="alert alert-danger alert-dismissable" style="display:<?php echo $delinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-ban"></i> Deleted!</h4>
      <?php echo $del_msg; ?> </div>
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> Success!</h4>
      <?php echo $msg; ?> </div>
    <form onsubmit="return confirmSubmit();" id="frmcarstock" action="<?php echo WEB_URL; ?>parts_stock/partsselledit.php?sold_id=<?php echo $sold_id; ?>" method="post" enctype="multipart/form-data">
      <div class="box box-success" id="box_model">
        <div class="box-body">
          <div class="col-md-12" style="padding-top:10px;">
            <div class="col-md-12">
            	<input type="text" placeholder="Buy Date" style="color:red;font-weight:bold;text-align:center;font-size:20px;border:none;border-bottom:solid 1px #ccc;" name="txtSellDate" id="txtSellDate" value="<?php echo !empty($results) ? $wms->mySqlToDatePicker($results['invoice']['invoice_date']) : ''; ?>" class="form-control datepicker"/>
			</div>
			 <div class="text-center">
				<div style="font-size:25px;font-weight:bold;color:green;text-decoration:underline;">INVOICE: <?php echo !empty($results) ? $results['invoice']['invoice_id'] : ''; ?></div>
			</div>
			<div class="pull-right">
              <button type="submit" name="btnSaveInvoive" class="btn btn-info btnsp"><i class="fa fa-print fa-2x"></i><br>
              Update &amp; Generate Invoice</button> &nbsp;&nbsp; <button type="submit" name="btnBackList" class="btn btn-success btnsp"><i class="fa fa-list fa-2x"></i><br>
              Update &amp; Back to List</button>
            </div>
          </div>
        </div>
      </div>
      <div class="box box-success" id="box_model">
        <div class="box-body">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-cart-plus"></i> Parts sell shopping cart</h3>
          </div>
          <div>
            <table class="table">
              <thead>
                <tr>
                  <th>Image</th>
                  <th>Name</th>
                  <th>Warranty</th>
				  <th>Condition</th>
                  <th>Price</th>
                  <th>Quantity</th>
                  <th>Total</th>
                  <th>&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <?php echo !empty($results['parts']) ? $wms->getShoppingCartHtmlBasedOnSoldId($link, $results['parts'], $results['invoice']['discount'], $results['invoice']['due_amount'], $results['invoice']['paid_amount'], $results['invoice']['grand_total'], $sold_id) : ''; ?>
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
            <h3 class="box-title"><i class="fa fa-user"></i> Buyer Information</h3>
          </div>
          <div class="form-group col-md-6">
            <label for="txtBuyerName"><span style="color:red;">*</span> Buyer Name :</label>
            <input type="text" placeholder="Owner Name" value="<?php echo !empty($results) ? $results['invoice']['customer_name'] : ''; ?>" name="txtBuyerName" id="txtBuyerName" class="form-control" required/>
          </div>
          <div class="form-group col-md-6">
            <label for="txtMobile"><span style="color:red;">*</span> Telephone :</label>
            <input type="text" placeholder="Owner Mobile" value="<?php echo !empty($results) ? $results['invoice']['telephone'] : ''; ?>" name="txtMobile" id="txtMobile" class="form-control" required/>
          </div>
          <div class="form-group col-md-6">
            <label for="txtEmail"><span style="color:red;">*</span> Email :</label>
            <input type="text" placeholder="Owner Email" value="<?php echo !empty($results) ? $results['invoice']['email'] : ''; ?>" name="txtEmail" id="txtEmail" class="form-control" required/>
          </div>
          <div class="form-group col-md-6">
            <label for="txtCompanyname"> Company Name:</label>
            <input type="text" placeholder="Company Name" value="<?php echo !empty($results) ? $results['invoice']['company_name'] : ''; ?>" name="txtCompanyname" id="txtCompanyname" class="form-control"/>
          </div>
          <div class="form-group col-md-6">
            <label for="txtprestAddress"><span style="color:red;">*</span> Customer Address :</label>
            <textarea type="text" placeholder="Owner Address" name="txtprestAddress" id="txtprestAddress" class="form-control" required><?php echo !empty($results) ? $results['invoice']['customer_address'] : ''; ?></textarea>
          </div>
          <div class="form-group col-md-6">
            <label for="txtpermanentAddress"><span style="color:red;">*</span> Delivery Address :</label>
            <textarea type="text" placeholder="Owner Address" name="txtpermanentAddress" id="txtpermanentAddress" class="form-control" required><?php echo !empty($results) ? $results['invoice']['delivery_address'] : ''; ?></textarea>
          </div>
		  <div class="form-group col-md-12">
            <label for="txtSellnote">Note :</label>
            <textarea name="txtSellnote" placeholder="Note" id="txtSellnote" class="form-control"><?php echo !empty($results) ? $results['invoice']['note'] : ''; ?></textarea>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
	  <input type="hidden" value="<?php echo $sold_id; ?>" name="sold_id"/>
	  <input type="hidden" value="<?php echo !empty($results) ? $results['invoice']['invoice_id'] : ''; ?>" name="invoice_id"/>
    </form>
  </div>
</div>
<!-- /.row -->
<script type="text/javascript">
function confirmSubmit() {
	if(confirm('Are you sure everything is ok and you want to update information?')) {
		return true;
	} else {
		return false;
	}
}
</script>
<?php include('../footer.php'); ?>
