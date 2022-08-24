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
	'condition'	=> 'both',
	'payment'	=> ''
);
if(!empty($_POST)) {
	$filter = array(
		'date'		=> '',
		'month'		=> '',
		'year'		=> '',
		'condition'	=> $_POST['ddlCondition'],
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
	$results = $wms->getAllPurchasedPartsReportList($link, $filter);
	$token = 1;
}
$wmscalc = new wms_calculation();
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><i class="fa fa-line-chart"></i> Parts Purchased Reports </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Parts Purchased Reports</li>
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
          <div class="form-group  col-md-12" style="color:red; font-weight:bold;">** Select Buy date or month and year together or month or year.</div>
          <div class="form-group col-md-12">
            <label for="txtBuyDate">Parts Buy Date :</label>
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
            <label for="ddlCondition">Parts Condition :</label>
            <select class="form-control" name="ddlCondition" id="ddlCondition">
              <option <?php echo $filter['condition'] == 'both' ? 'selected' : ''; ?> value='both'>Both</option>
              <option <?php echo $filter['condition'] == 'new' ? 'selected' : ''; ?> value='new'>New</option>
              <option <?php echo $filter['condition'] == 'old' ? 'selected' : ''; ?> value='old'>Old</option>
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
  	$buy += (float)$row['total_amount'];
	$due += (float)$row['pending_amount'];
	$paid += (float)$row['given_amount'];
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
        <h3 class="box-title">Car Buy List</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th>Date</th>
              <th>Invoice</th>
              <th>Condition</th>
              <th>Price</th>
			  <th>Quantity</th>
			  <th>Total</th>
			  <th>Paid</th>
			  <th>Due</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
				foreach($results as $row) {				
					$image = WEB_URL . 'img/no_image.jpg';
					if(file_exists(ROOT_PATH . '/img/upload/' . $row['parts_image']) && $row['parts_image'] != ''){
						$image = WEB_URL . 'img/upload/' . $row['parts_image']; //car image
					}
				?>
            <tr>
              <td><span class="label label-default"><?php echo $wms->mySqlToDatePicker($row['parts_added_date']); ?></span></td>
              <td><span class="label label-success"><?php echo $row['invoice_id']; ?></span></td>
              <td><span class="label label-primary"><?php echo $row['parts_condition']; ?></span></td>
              <td><span class="label label-success"><?php echo $currency.$row['parts_buy_price']; ?></span></td>
              <td><span class="label label-info"><?php echo $row['parts_quantity']; ?></span></td>
			  <td><span class="label label-warning"><?php echo $currency.$row['total_amount']; ?></span></td>
			  <td><span class="label label-info"><?php echo $currency.$row['given_amount']; ?></span></td>
			  <td><span class="label label-danger"><?php echo $currency.$row['pending_amount']; ?></span></td>
              <td><a class="btn btn-info" data-toggle="tooltip" href="<?php echo WEB_URL;?>invoice/invoice_parts_purchase.php?invoice_id=<?php echo $row['invoice_id']; ?>" data-original-title="Invoice"><i class="fa fa-file-text-o"></i></a> <a class="btn btn-warning" data-toggle="tooltip" href="javascript:;" onClick="$('#nurse_view_<?php echo $row['invoice_id']; ?>').modal('show');" data-original-title="View"><i class="fa fa-eye"></i></a> <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL;?>parts_stock/buyparts.php?invoice_id=<?php echo $row['invoice_id']; ?>" data-original-title="Edit"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger" data-toggle="tooltip" onClick="deleteCustomer(<?php echo $row['invoice_id']; ?>);" href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
                <div id="nurse_view_<?php echo $row['invoice_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header orange_header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                        <h3 class="modal-title">Parts Details</h3>
                      </div>
                      <div class="modal-body model_view" align="center">&nbsp;
                        <div><img class="photo_img_round" style="width:100px;height:100px;" src="<?php echo $image;  ?>" /></div>
                        <div class="model_title"><?php echo $row['parts_name']; ?></div>
                      </div>
                      <div class="modal-body">
                        <h3 style="text-decoration:underline;">Parts Purchase Infromation</h3>
                        <div class="row">
                          <div class="col-xs-12"> <b>Parts Name :</b> <?php echo $row['parts_name']; ?><br/>
                            <b>Supplier Name :</b> <?php echo $row['s_name']; ?><br/>
                            <b>Manufacturer Name :</b> <span class="label label-success"><?php echo $row['manufacturer_name']; ?></span><br/>
                            <b>Buy Price :</b> <?php echo $currency.$row['parts_buy_price']; ?><br/>
                            <b>Quantity :</b> <span class="label label-success"><?php echo $row['parts_quantity']; ?></span><br/>
                            <b>Part no :</b> <?php echo $row['parts_sku']; ?><br/>
                            <b>Condition :</b> <span class="label label-danger"><?php echo $row['parts_condition']; ?></span><br/>
                            <b>Added Date :</b> <?php echo $wms->mySqlToDatePicker($row['parts_added_date']); ?> </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div></td>
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
