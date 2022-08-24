<?php 
include_once('../header.php');
include_once('../helper/common.php');
include('../helper/calculation.php');
$delivery_list = array();
$token = 0;
$filter = array(
	'date'		=> '',
	'month'		=> '',
	'year'		=> '',
	'payment'	=> '',
	'invoice_no' => ''
);
if(!empty($_POST)) {
	$filter = array(
		'date'		=> '',
		'month'		=> '',
		'year'		=> '',
		'payment'	=> $_POST['ddlPayment']
	);
	if(!empty($_POST['txtBuyDate'])) {
		$filter['date'] = $_POST['txtBuyDate'];
	}
	if(!empty($_POST['ddlMonth'])) {
		$filter['month'] = $_POST['ddlMonth'];
	}
	if(!empty($_POST['ddlYear'])) {
		$filter['year'] = $_POST['ddlYear'];
	}
	if(!empty($_POST['txtInvoiceNo'])) {
		$filter['invoice_no'] = $_POST['txtInvoiceNo'];
	}
	$results = $wms->getAllSellPartsReportList($link, $filter);
	$token = 1;
}
$wmscalc = new wms_calculation();
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-line-chart"></i> Parts Sell Reports </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Parts Sell Reports</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-xs-12">
    <form id="frmcarstock" method="post" enctype="multipart/form-data">
      <div class="box box-success" id="box_model">
        <div class="box-body">
          <div class="box-header">
            <h3 class="box-title"><i class="fa fa-search"></i> Find Parts</h3>
          </div>
          <div class="form-group  col-md-12" style="color:red; font-weight:bold;">** Select Sell date or month and year together or month or year.</div>
          <div class="form-group col-md-12">
            <label for="txtBuyDate">Parts Sell Date :</label>
            <input type="text" name="txtBuyDate" value="<?php echo !empty($filter['date']) ? $filter['date'] : ''; ?>" id="txtBuyDate" class="form-control datepicker" />
          </div>
          <div class="form-group col-md-6">
            <label for="ddlMonth">Select Month :</label>
            <select class="form-control" name="ddlMonth" id="ddlMonth">
              <option <?php echo $filter['month'] == '' ? 'selected' : ''; ?> value=''>--Select Month--</option>
              <option <?php echo $filter['month'] == '1' ? 'selected' : ''; ?> value='1'>January</option>
              <option <?php echo $filter['month'] == '2' ? 'selected' : ''; ?> value='2'>February</option>
              <option <?php echo $filter['month'] == '3' ? 'selected' : ''; ?> value='3'>March</option>
              <option <?php echo $filter['month'] == '4' ? 'selected' : ''; ?> value='4'>April</option>
              <option <?php echo $filter['month'] == '5' ? 'selected' : ''; ?> value='5'>May</option>
              <option <?php echo $filter['month'] == '6' ? 'selected' : ''; ?> value='6'>June</option>
              <option <?php echo $filter['month'] == '7' ? 'selected' : ''; ?> value='7'>July</option>
              <option <?php echo $filter['month'] == '8' ? 'selected' : ''; ?> value='8'>August</option>
              <option <?php echo $filter['month'] == '9' ? 'selected' : ''; ?> value='9'>September</option>
              <option <?php echo $filter['month'] == '10' ? 'selected' : ''; ?> value='10'>October</option>
              <option <?php echo $filter['month'] == '11' ? 'selected' : ''; ?> value='11'>November</option>
              <option <?php echo $filter['month'] == '12' ? 'selected' : ''; ?> value='12'>December</option>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="ddlYear">Select Year :</label>
            <select class="form-control" name="ddlYear" id="ddlYear">
              <option <?php echo $filter['year'] == '' ? 'selected' : ''; ?> value=''>--Select Year--</option>
              <?php for($i=2000;$i<=date('Y');$i++){
			  	if($filter['year'] == $i) {
					echo '<option selected value="'.$i.'">'.$i.'</option>';
				} else {
					echo '<option value="'.$i.'">'.$i.'</option>';
				}
			    }?>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="ddlPayment">Payment :</label>
            <select class="form-control" name="ddlPayment" id="ddlPayment">
              <option <?php echo $filter['payment'] == '' ? 'selected' : ''; ?> value=''>--select--</option>
              <option <?php echo $filter['payment'] == 'due' ? 'selected' : ''; ?> value='due'>Due</option>
              <option <?php echo $filter['payment'] == 'paid' ? 'selected' : ''; ?> value='paid'>Paid</option>
            </select>
          </div>
		  <div class="form-group col-md-6">
            <label for="txtBuyDate">Invoice No :</label>
            <input type="text" name="txtInvoiceNo" value="<?php echo !empty($filter['invoice_no']) ? $filter['invoice_no'] : ''; ?>" id="txtInvoiceNo" class="form-control" />
          </div>
          <div class="form-group col-md-12">
            <button type="submit" class="btn btn-success btn-large btn-block"><b><i class="fa fa-filter"></i> SEARCH</b></button>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </form>
  </div>
  <?php if(!empty($results)) { ?>
  <?php
  $buy = 0.00;
  $due = 0.00;
  $paid = 0.00;
  foreach($results as $row) {
  	$buy += (float)$row['total'];
	$due += (float)$row['due_amount'];
	$paid += (float)$row['paid_amount'];
  }
  ?>
  <div class="col-xs-12 state-overview">
    <div class="box box-success">
      <div class="box-body">
        <div class="col-lg-4 col-sm-4">
          <section class="panel">
            <div class="symbol terques"> <i class="fa fa-line-chart" data-original-title="" title=""></i> </div>
            <div class="value">
              <h1 class=" count2"><?php echo $currency.number_format($buy, 2);?></h1>
              <p>Total Price</p>
            </div>
          </section>
        </div>
        <div class="col-lg-4 col-sm-4">
          <section class="panel">
            <div class="symbol red"> <i class="fa fa-bar-chart-o" data-original-title="" title=""></i> </div>
            <div class="value">
              <h1 class=" count2"><?php echo $currency.number_format($due, 2);?></h1>
              <p>Due Amount</p>
            </div>
          </section>
        </div>
        <div class="col-lg-4 col-sm-4">
          <section class="panel">
            <div class="symbol purple"> <i class="fa fa-area-chart" data-original-title="" title=""></i> </div>
            <div class="value">
              <h1 class=" count2"><?php echo $currency.number_format($paid, 2);?></h1>
              <p>Paid Amount</p>
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xs-12">
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title">Parts Sell List</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th>Invoice No</th>
			  <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Quantity</th>
			  <th>Paid</th>
			  <th>Due</th>
			  <th>Total</th>
              <th>Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($results as $result){ ?>
            <tr>
              <td><span class="label label-success"><?php echo $result['invoice_id']; ?></span></td>
              <td><?php echo $result['customer_name']; ?></td>
              <td><?php echo $result['email']; ?></td>
              <td><?php echo $result['telephone']; ?></td>
			  <td><span class="label label-danger"><?php echo $result['total_parts']; ?></span></td>
			  <td><span class="label label-danger"><?php echo $currency. $result['paid_amount']; ?></span></td>
			  <td><span class="label label-danger"><?php echo $currency. $result['due_amount']; ?></span></td>
			  <td><span class="label label-info"><?php echo $currency. $result['grand_total']; ?></span></td>
			  <td><span class="label label-primary"><?php echo $wms->mySqlToDatePicker($result['invoice_date']); ?></span></td>                     
              <td><a class="btn btn-info" data-toggle="tooltip" href="<?php echo WEB_URL;?>invoice/invoice_parts_sell.php?invoice_id=<?php echo $result['invoice_id']; ?>" data-original-title="Invoice"><i class="fa fa-file-text-o"></i></a> <a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL; ?>parts_stock/partsselledit.php?sold_id=<?php echo $result['sold_id']; ?>" data-original-title="Edit"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger" data-toggle="tooltip" onClick="deleteSoldItem(<?php echo $result['sold_id']; ?>);" href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>
           <?php } $wms->close_db_connection($link); ?>
          </tbody>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <?php } ?>
  <?php if(empty($results) && $token=='1') {?>
  <div class="col-xs-12">
    <div class="box box-success">
      <!-- /.box-header -->
      <div class="box-body empty_record">No record found.</div>
    </div>
  </div>
  <?php } ?>
  <!-- /.col -->
</div>
<!-- /.row -->
<?php include('../footer.php'); ?>
